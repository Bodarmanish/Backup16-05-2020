<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentRequirement;
use App\Models\User;
use Illuminate\Support\Facades\Input;
use App\Models\DocumentTypes;
use Illuminate\Support\Facades\Validator;
use Response;
use Storage;

class DocumentsController extends Controller
{ 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 
        parent::__construct();
        $this->doc = new Document;
        $this->doc_req = new DocumentRequirement;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user_id = user_token();
        $doc_id = $request->doc_id;
        
        if(!empty($user_id) && !empty($doc_id)){
            $user = User::where('id',$user_id)->first();
            if($this->doc->deleteDocumentById($user,$doc_id)){
                return redirect(route('document.list'))->with("success","Document deleted successfully.");
            }
        }
        
        return redirect(route('document.list'))->with("error","Failed to delete document requirement.");
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function documentRejectReason(Request $request)
    {
        if($request->action == "reject_document_reason_form" && !empty($request->doc_id)){
            $data = [
                'action' => 'reject_document_reason_form',
                'doc_id' => $request->doc_id,
                'active_step_key' => $request->active_step_key,
                'active_stage_key' => $request->active_stage_key,
                'section_id' => $request->section_id,
            ];
            
            $HTML = view('admin.ajax')->with($data)->render();
            $response = ["type" => "success", "message" => "", "data" => $HTML];
        }
        else if($request->action == "document_reject_reason"){
             
            $document = Document::where('id',$request->doc_id)->first();
            $document->document_reject_reason = $request->reject_message;
            $document->save();
            
            $response = ["type" => "success", "message" => "Document status updated successfully", "data" => NULL];
        }
        else{
            $response = ["type" => "success", "message" => "", "data" => ""];
        }
        return Response::json($response);
    }
    
    /**
    * Handle document action.
    * Action Type = 0 = Pending to approve or reject document
    * Action Type = 1 = Approve document
    * Action Type = 2 = Reject document
    * 
    */
    public function documentAction(Request $request)
    {
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
        $auto_admin = config('common.auto_admin');
        $user = User::where('id',$user_id)->first(); 
        $userGeneral = $user->portfolio->userGeneral;
        $j1_interview = $user->interview;
        $portfolio = $user->portfolio;
        $document = Document::where('id',$request->doc_id)->first();
        $doc_type_key = $document->documentType->doc_key;
        
        $session_user = auth()->user();
        $session_user_id = $session_user->id;
        $action_perform_id = (!is_null($session_user_id) && !empty($session_user_id))?$session_user_id:$auto_admin;
        
        if($request->action_type=='delete'){
            $this->doc->deleteDocumentById($user,$request->doc_id);
            $response = ['type' => "success", 'message' => "Document deleted successfully."];
        }
        elseif($request->action_type=='approve'){
            $document->document_status = 1;
            $document->action_by_id = $action_perform_id;
            $document->save();
            $new_j1_status = ''; 
            switch ($doc_type_key) {
                case "resume":
                    if(!empty($userGeneral->skype_id)){
                        $new_j1_status = "skype-added";
                    }
                    else{
                        $new_j1_status = "resume-approved";
                    } 
                break;
                
                case "j1_agreement": 
                    if(!empty($document->document_status) && $document->document_status == 1 && !empty($j1_interview->reg_fee_status)){
                        if($j1_interview->reg_fee_status == 1){
                            $new_j1_status = "j1-agreement-signed";
                        }
                        else if($j1_interview->reg_fee_status == 2){
                            $new_j1_status = "registration-fee-completed";
                        }
                    }
                break;
                
                default:

                break;
            } 
            
            $placement_agency = $portfolio->placementAgency;
            $sponsor_agency = $portfolio->sponsorAgency;

            if(!empty($placement_agency)){
                $req_placement_doc = $this->doc_req->getRequiredDocBySec($user,1,$placement_agency->id);
                if($req_placement_doc['approved_req_count'] == $req_placement_doc['req_count']){
                    $new_j1_status = 'supporting-document-collected';
                }
            }
            if(!empty($sponsor_agency)){
                $req_sponsor_doc = $this->doc_req->getRequiredDocBySec($user,2,$sponsor_agency->id);
                if($req_sponsor_doc['approved_req_count'] == $req_sponsor_doc['req_count']){
                    $new_j1_status = 'post-placement-doc-collected';
                }
            }

            $this->changeUserStatus($user, $new_j1_status);
            $response = ['type' => "success", 'message' => "Document has been approved successfully."];
        }
        elseif($request->action_type=='reject'){
            $document->document_status = 2;
            $document->action_by_id = $action_perform_id;
            $document->save();
            
            $response = ['type' => "success", 'message' => "Document has been rejected successfully."];
        }
        else{
            $response = ['type' => "error", 'message' => "Please pass action to do process."];
        }
        
        return Response::json($response);
    }
    
    public function userDocument(Request $request)
    {
        if(!empty($request->user_id)){ 
            $user_id = user_token();
            
            $user = User::where('id',$user_id)->first();
            $portfolio = $user->portfolio;
            
            $section_id = 1;
            if(!empty($request->section_id)){
                $section_id = $request->section_id;
            } 
            
            $section_list = config('common.document_section');
            $admin = auth()->user(); 
            
            if($admin->role_name == 'agency-admin'){
                if($admin->agency_type==2){
                    unset($section_list[2]);
                    unset($section_list[3]);
                }
                elseif($admin->agency_type == 3){
                    unset($section_list[1]);
                    $section_id = 2;
                }
            }
            
            $data = [ 
                'document_list' => $this->getDocumentList($user_id, $section_id),
                'section_list' => $section_list,
                'default_section_id' => $section_id,
            ];
            
            return view("admin.user-documents")->with($data)->render(); 
        }
    }
    
    public function getDocumentList($user_id, $section_id)
    {
        if(!empty($user_id)){
            $user = User::where('id',$user_id)->first();
            $user_document = [];
            
            $portfolio = $user->portfolio;
            $admin = auth()->user();
            
            $agency_id = "";
            if($section_id == 1 && !empty($portfolio->placement_agency_id)){
                $agency_id = $portfolio->placement_agency_id;
            }
            elseif(($section_id == 2 || $section_id == 3) && !empty($portfolio->sponsor_agency_id)){
                $agency_id = $portfolio->sponsor_agency_id;
            }
             
            $basic_document = $this->doc_req->getDocumentByDocSection($user,$section_id,$agency_id,false);
            $basic_document = $basic_document['document_requirements'];
            $user_document = collect($user_document)->merge($basic_document); 
            
            $data = [
                'user_document' => $user_document,
                'section_id' => $section_id,
            ];
            
            return view('admin.user-document-list')->with($data)->render(); 
        }
    }
    
    public function userDocumentList(Request $request)
    {
        if(!empty($request->user_id)){
            $user_id = user_token();
            $section_id = 1;
            if(!empty($request->section_id)){
                $section_id = $request->section_id;
            }
            $data = $this->getDocumentList($user_id, $section_id);
            
            return ['type' => "success", 'message' => "", 'data' => $data];
        }
    }
    
    public function uploadSupportingDocument(Request $request)
    {
        $user_id = user_token();
        $user = User::where('id',$user_id)->first();
        $portfolio = $user->portfolio;
        
        $response = array(); 
        if(!empty($user))
        {
            if($portfolio->is_step_locked == 1)
            {
                $response = ['type' => "warning", 'message' => "You cannot upload any documents because your application is locked, however you can download uploaded documents."];
            }
            else
            {
                $document_type = $request->get('document_type',"");
                
                $allowed_extensions = custom_implode(config('common.allow_doc_ext'));
                if($document_type == 3){
                    $allowed_extensions = custom_implode(config('common.allow_image_ext'));
                }
                
                $upload_file_size = config('common.upload_file_size');    
                $allowed_file_size = config('common.upload_file_size')*1000;    
                $rules = array('document_file' => "required|max:{$allowed_file_size}|mimes:{$allowed_extensions}");
                
                $validationErrorMessages =  [  
                    'document_file.required' => "Document file is required.",
                    'document_file.mimes' => "Document file must be a file of type: {$allowed_extensions}.",
                    'document_file.max' => "Document file allowed maximum size {$upload_file_size}mb." 
                ];
                
                $validator = Validator::make(Input::all(), $rules, $validationErrorMessages);

                if ($validator->fails()) {
                    $validation_message = $validator->messages()->toArray();
                    $temp_msg = array();
                    foreach($validation_message as $key => $value)
                    {
                        $msg = $value;
                        if(is_array($value))
                            $msg = custom_implode($value,", ");
                        $temp_msg[$key] = "<li>{$msg}</li>";
                    }
                    $validation_message = custom_implode($temp_msg,"");
                    $validation_message = "<ul>{$validation_message}</ul>";
                    return Response::json([
                        'type' => "error",
                        'message' => $validation_message,
                    ]);
                }

                if($request->hasFile('document_file')){

                    $document_file = $request->file('document_file');
                    $document_type = $request->get('document_type',""); 
                    $document_type_key = DocumentTypes::where('id',$document_type)->select('doc_key')->first();
                    $document_sent = $this->uploadDocument($user, $document_file, $document_type_key->doc_key, 0,1,true); 
                    if (!empty($document_sent) && $document_sent !== false) {
                        $response = $document_sent;
                    }
                    else {
                        $response = ['type' => "error", 'message' => "Failed to upload document"];
                    } 
                }
                else{
                    $response = ['type' => "error", 'message' => "Please select file"];
                }
            }
            
            return $response;
            
        }
        else
            return ['type' => "error", 'message' => "Failed to upload document"];
    }
    
    public function documentHistory(Request $request)
    {
        $data = [];
        $user_id = user_token();
        $user = User::where('id',$user_id)->first();        
        if(!empty($user) && !empty($request->doc_type_id) && is_numeric($request->doc_type_id))
        { 
            $doc_type_data = DocumentTypes::where('id',$request->doc_type_id)->select('name')->first(); 
            $data['doc_type_data'] = $doc_type_data;
            $document_history = $user->portfolio->getUserDocumentByType($request->doc_type_id);
            
            $upload_dir_path = config('common.user-documents').DS.$user->id.DS;
            foreach ($document_history as $key => $doc)
            {
                if(!empty($doc->document_id))
                {
                    $file_name = $doc->document_filename;
                    $file_path = $upload_dir_path.$file_name;

                    if(Storage::disk('public')->exists($file_path))
                    {
                        $doc_id = encrypt($doc->document_id);
                        $document_history[$key]->document_download_link = route("download",['dd',$doc_id]);;
                    }
                }
                $data['document_history'] = $document_history;
            }
            
            $data['action'] = "view_document_history"; 
        }
        
        $HTML = view('common.common-ajax')->with($data)->render();
        return ["type" => "success", "message" => "", "data" => $HTML];  
    }
    
    public function requireDocumentUploaded(Request $request)
    {
        $user_id = user_token();
        $user = User::where('id',$user_id)->first();
        if(!empty($user) && !empty($user->id))
        {
            $active_step_key = $request->active_step_key;
            $as = new AppStatus;
            if($as->checkChronologicalOrder($user, $active_step_key))
            {
                $new_status = "";
                switch($active_step_key) {
                
                    case "2_supporting_documents":
                        $new_status = "supporting-document-collected";
                        
                    break;
                
                    case "3_post_placement_documents":
                        $new_status = "post-placement-doc-collected";
                        
                    break;
                    
                    default:
                    break;
                }
                
                if(!empty($new_status)){
                    $this->changeUserStatus($user, $new_status);
                }
                return ['type' => "success"];
            }
            return ['type' => "error", 'message' => "Failed to process"];
        }
        else
            return ['type' => "error", 'message' => "Failed to process"];
    }
    
    public function uploadDocumentInstruction(Request $request)
    {
        $data = [];
        if(!empty($request->doc_req_id) && is_numeric($request->doc_req_id))
        {  
            $doc_req_data = DocumentRequirement::where('id',$request->doc_req_id)->first();
            if(!empty($doc_req_data) && !empty($doc_req_data->document_template))
            {
                $doc_template_dir ="document-template".DS.$doc_req_data->agency_id.DS; 
                $doc_template_path = $doc_template_dir.$doc_req_data->document_template;
                if(!empty($doc_template_path) && !empty(Storage::disk('public')->exists($doc_template_path))){ 
                    $doc_id = encrypt($request->doc_req_id);
                    $data['download_template_link'] = route("download",['ddt',$doc_id]);
                }

                $data['doc_desc'] = "";
                if(!empty($doc_req_data->document_desc))
                {
                    $data['doc_desc'] = $doc_req_data->document_desc;
                }
                
                $allowed_extensions = custom_implode(config('common.allow_doc_ext'));
                $upload_file_size = config('common.upload_file_size');
                if($doc_req_data->document_type == 3){
                    $allowed_extensions = custom_implode(config('common.allow_image_ext'));
                    $upload_file_size = config('common.upload_img_size');
                }
                $data['allowed_extensions'] = $allowed_extensions;
                $data['upload_file_size'] = $upload_file_size;
                
                $data['action'] = "doument_instruction";
            }
        }
        
        $HTML = view('common.common-ajax')->with($data)->render();
        return ["type" => "success", "message" => "", "data" => $HTML];
    }
    
}
