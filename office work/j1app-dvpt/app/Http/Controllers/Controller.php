<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\MailerTrait;
use App\Traits\ImageTrait;
use App\Traits\DocumentTrait;
use App\Models\User;
use App\Models\J1Status;
use App\Models\ApplicationStatus AS AppStatus;
use App\Models\Document;
use App\Models\DocumentTypes;
use App\Models\AgencyContract;
use App\Models\Token;
use App\Models\EmailNotification AS EN; 
use App\Models\UserLog;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, MailerTrait, ImageTrait, DocumentTrait;
    
    protected $countries;
    protected $timezones;
    protected $programs;
    protected $airports;
    
    public function __construct()
    {
        $this->countries = get_countries();
        $this->timezones = get_timezones();
        $this->airports =  get_airport_list();
        $this->states = get_states();
    }
    
     /**
     * function to send invitation sendInvitation($data)
     *
     * @return array
     */
    public function sendInvitation($user_data){
        
        if(!empty($user_data)){
        
            $agencycontract = AgencyContract::create(['agency_id' => $user_data['agency_id'],
                                                    'contract_type' =>$user_data['agency_type'],
                                                    'user_id' =>$user_data['user_id'],
                                                    'portfolio_id' =>$user_data['portfolio_id'],
                                                    'request_by_id'=>$user_data['request_by_id'],
                                                    'email'=>$user_data['email'],
                                                    'request_status'=>1,
                                                    'request_by'=>1,
                                                    'is_expired'=>0]);
            
            if(!empty($user->portfolio->id)){
                $log_fields['portfolio_id'] = $user->portfolio->id;
            }

            $contract_id = $agencycontract->id;

            $token = new Token;
            $token = $token->generateToken(['contract_id'=>$contract_id],2,48);

            $invitation_url = config('app.url')."invitation/{$token}";
            $invitation_link = "<a style='background: #41b3f9;border: 1px solid #41b3f9;border-radius: 3px;color: #fff;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: 400;line-height:1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;text-decoration: none;' href='$invitation_url' class='btn btn-block btn-info'>Accept Invitation</a>";
            
            $mail_format =  (array) EN::getMailTextByKey("send_invitation_to_user");
            $subject      = $mail_format['subject'];
            $message_text = $mail_format['text'];
            $message_text = str_replace("{{url}}", $invitation_link, $message_text);
            
            $receiver = array();
            $receiver['toEmail']    = $user_data['email'];
            $data = ['message_text' => $message_text];

            $sent = $this->sendUIEmailNotification((object) $receiver, $subject, $data);
            return true;
        }
        else
        {
            return false;
        }
        
    }
    
    /**
     * @param object $user Instance of Model >> User
     * @param string $status key string of status
     * @param bool $add_log 1 = add log entry, 0 = ignore to add log
     * @param bool $force_update 1 = update status force fully, 0 = do not update status force fully
     * **/
    public function changeUserStatus(User $user,$status,$add_log = 1,$force_update = 0)
    {
        if (!empty($user) && !empty($status)) {
            $auto_admin = config('common.auto_admin');
            $app_interface = config('common.app_interface');
        
            $is_eligible_user_status = $this->isEligibleUserstatus($user, $status, $force_update);
            if($is_eligible_user_status !== false){
                
                $old_status_id = $user->j1_status_id;
                $porfolio = $user->portfolio;

                $user->j1_status_id = $is_eligible_user_status;
                $user->save();
                
                if($add_log == 1){
                    UserLog::log($user, $is_eligible_user_status);
                }

                $appStatus = new AppStatus;
                $appStatus->setCurrentStep($user, $is_eligible_user_status);
            }
            
            return true;
        }
        else
            return false;
    }
    
    /**
     * function isEligibleUserstatus
     * @param object $user Instance of Model >> User
     * @param string $status key string of status
     * @param int $force_update
     * **/
    public function isEligibleUserstatus(User $user, $status, $force_update)
    {
        if(!empty($user) && !empty($status))
        {
            $new_status_id = "";
            $status_data = J1Status::getId($status);
            if(!empty($status_data) && $status_data->category != 6){
                $new_status_id = $status_data->id;
            }
            
            if(!empty($new_status_id)){
                $current_status = $user->j1_status_id;
                if(($current_status < $new_status_id) || $force_update == 1){
                    return $new_status_id;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Function uploadDocument
     * @param int       $user           Instance of Model >> User
     * @param mixed     $file           file object or file path
     * @param string    $doc_type_key   document type key string "document_types >> doc_key"
     * @param int       $document_status 0 = pending, 1 = approved, 2 = rejected
     * @param string    $action_perform option like "user" and "admin"
     * @param int       $upload_type    option like 1 = uploaded file, 2 = move file location
     * @param bool      $is_return      If true return resultant data or return boolean
     * **/
    function uploadDocument(User $user, $file, $doc_type_key, $document_status = 0, $upload_type = 1, $is_return = FALSE)
    {
        if(!empty($user)) {
            $auto_admin = config('common.auto_admin');
            $app_interface = config('common.app_interface');
            
            $uploaded_by_admin = $uploaded_by_user = "";
            $user_id = $user->id;
            $portfolio = $user->portfolio;
            $j1_interview = $portfolio->j1Interview;
            $userGeneral = $portfolio->userGeneral;
            $current_status = $user->j1_status_id;
            $current_program_id = $portfolio->program_id;
            $user_name = trim($user->first_name.' '.$user->last_name);
            $email_address = $user->email_address;
            
            $session_user = auth()->user();
            $session_user_id = $session_user->id;
            
            $action_perform_id = (!is_null($session_user_id) && !empty($session_user_id))?$session_user_id:$auto_admin;
            $approve_by_admin = $auto_admin;
            
            if($app_interface == "user"){
                $uploaded_by_user = $action_perform_id;
            }
            else{
                $uploaded_by_admin = $approve_by_admin = $action_perform_id;
            }
            
            $document_type = DocumentTypes::where('doc_key',$doc_type_key)->select('id','name','doc_key')->first();
            
            if (!empty($document_type->doc_key)) {
                
                $columns = [
                    'd.id',
                    'd.document_filename',
                    'd.document_status',
                ];
                $document = $this->doc->getDocumentByType($user, $doc_type_key);
                
                if(!empty($document)) {
                    if($document->document_status == 1) {
                        return ['type' => "warning", 'message' => "{$document_type->name} is already approved.", 'document_data' => $document];
                    }
                    elseif($document->document_status == 0) {
                        return ['type' => "warning", 'message' => "{$document_type->name} is already uploaded and approval in progress.", 'document_data' => $document];
                    }
                }
                
                $file_dir = config('common.user-documents').DS.$user_id.DS;
                $pre_fix = $user_name;
                $file_label = trim($document_type->doc_key);
                $rand_num = rand(101, 999).time();
                $doc_file_name = str_slug("{$pre_fix}_{$file_label}_{$rand_num}","_");

                $new_file_name = doc_name_word_to_upper($doc_file_name);
                
                $output_file_name = false;
                $new_file_path = "";
                if($upload_type == 1){
                    $output_file_name = $this->uploadFile($file,$file_dir,$new_file_name);
                    $new_file_path = storage_path($file_dir).$output_file_name;
                }
                else if($upload_type == 2){
                    $output_file_name = $this->moveFile($file,$file_dir,$new_file_name);        
                }

                if($output_file_name !== false){

                    $new_j1_status = "";
                    
                    switch ($doc_type_key) {
                        case "resume":
                            $new_j1_status = (!empty($document_status) && $document_status == 1) ? "resume-approved" : "resume-upload";
                        break;
                    
                        case "j1-interview-finished":
                            $j1_agreement = $this->doc->getDocumentByType($user, 'j1_agreement');
                            if(!empty($j1_agreement)) {
                                if(!empty($j1_interview->reg_fee_status)){
                                    if($j1_interview->reg_fee_status == 1){
                                        $new_j1_status = "j1-agreement-signed";
                                    }
                                    else if($j1_interview->reg_fee_status == 2){
                                        $new_j1_status = "registration-fee-completed";
                                    }
                                }
                            }
                        break;
                        
                        case "j1_agreement": 
                            if(!empty($document_status) && $document_status == 1 && !empty($j1_interview->reg_fee_status)){
                                if($j1_interview->reg_fee_status == 1){
                                    $new_j1_status = "j1-agreement-signed";
                                }
                                else if($j1_interview->reg_fee_status == 2){
                                    $new_j1_status = "registration-fee-completed";
                                }
                            }
                            
                        break;
                    
                        case "passport_photo":
                            if(!empty($new_file_path) && 1==2){
                                $this->createThumbImage($new_file_path,$file_dir);
                            }
                        break;
                        
                        case "ds7002_template":
                            $new_j1_status = "ds7002-created";
                        break;
                    
                        case "training_plan_signed":
                            $new_j1_status = "ds7002-signed";
                        break;
                    
                        default:
                            
                        break;
                    }
                    
                    $documents = new Document;
                    $documents->user_id = $user_id;
                    $documents->portfolio_id = $portfolio->id;
                    $documents->document_type = $document_type->id;
                    $documents->document_filename = $output_file_name;
                    $documents->document_status = 0;
                    if(!empty($document_status) && $document_status == 1)
                    {
                        $documents->document_status = 1;
                        $documents->action_by_id = $approve_by_admin;
                    }
                    $documents->uploaded_by_admin = $uploaded_by_admin;
                    $documents->uploaded_by_user = $uploaded_by_user;
                    $documents->save();

                    if(!empty($new_j1_status) && !empty($documents->id)){
                        $this->changeUserStatus($user, $new_j1_status);
                    }
                    
                    $url = $this->getUILoginLink($user_id);
                    /*$url = "";*/
                    $mail_format = (array) EN::getMailTextByKey("document_uploaded");
                    $subject = $mail_format['subject'];

                    $message_text = $mail_format['text'];
                    $message_text = str_replace("{{first_name}}", $user->first_name, $message_text);
                    $message_text = str_replace("{{last_name}}", $user->last_name, $message_text);
                    $message_text = str_replace("{{document_label}}", $document_type->name, $message_text);
                    $message_text = str_replace("{{url}}", $url, $message_text);

                    $recipient = array();
                    $recipient['toEmail'] = $user->email_address;
                    $recipient['toName'] = $user_name;
                    $recipient['ccEmail'] = (!empty($mail_format['send_cc']))?$mail_format['send_cc']:"";

                    $data = ['message_text' => $message_text];

                    $ok = $this->sendUIEmailNotification((object) $recipient, $subject, $data);
                    
                    $return_data = [
                        'type' => "success",
                        'message' => "Your {$document_type->name} uploaded successfully",
                        'file_name' => $output_file_name,
                        'document_label' => $document_type->name,
                    ];
                    
                    return ($is_return == true) ? $return_data : true;
                }
                return false;
            }
            return false;
        }
        return false;
    }
    
    function getUILoginLink($user_id = "")
    {
        if(!empty($user_id)) {
            $user = User::find($user_id);
            
            if(!empty($user))
            {
                $token = new Token;
                $access_token = $token->generateToken(['user_id' => $user_id]);
                
                $url = config('app.url');
                $url = $url."/elogin/{$access_token}";
                return $url;
                
            }
        }
        return route('login');
    }
}
