<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use App\Http\Controllers\Controller;
use App\Models\ApplicationStatus AS AppStatus; 
use App\Models\Document;
use App\Models\Resume;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\EmailNotification AS EN;
use App\Models\Agency;
use App\Models\AgencyContract;
use App\Models\UserLog;
use App\Models\User;
use App\Models\DocumentRequirement;
use App\Models\DocumentTypes AS DT;
use App\Models\Placement;
use App\Models\J1Interview;
use App\Models\Timezone;
use App\Models\Lead;
use App\Models\Legal;
use App\Models\FlightInfo;
use App\Models\J1Status;

class ApplicationStatusController extends Controller
{
    protected $doc;
    
    public function __construct() {
        parent::__construct();
        
        $this->doc = new Document;
        $this->resume = new Resume;
        $this->j1interview = new J1Interview;
        $this->timezone = new Timezone;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        $user = auth()->user();
        
        $stages = AppStatus::getStages();
        
        $active_stage = 1;
        
        $as = new AppStatus;
        $current_order_key = $user->portfolio->as_order_key;
        $current_step_data = $as->getCurrentStep($user, $current_order_key);
        
        if(!empty($current_step_data)){
            
            $active_stage = $current_step_data->as_stage_number;
            $current_stage = $active_stage;
            
            $stage_data = $stages[$active_stage];

            $page_title = $stage_data['page_title'];
            $page_sub_title = $stage_data['page_subtitle'];
            
            $step_list = $as->getStepsByStage($user, $active_stage, $current_step_data);
            
            $active_step = (!empty(collect($step_list)->first()->as_order)) ?  collect($step_list)->first()->as_order : "";
            $active_step_key = (!empty(collect($step_list)->first()->as_order_key)) ?  collect($step_list)->first()->as_order_key : "";

            if($current_step_data->as_stage_number == $active_stage)
            {
                $active_step = $current_step_data->as_order;
                $active_step_key = $current_step_data->as_order_key;
            }
            
            $request->session()->put('active_stage',$active_stage);
            $request->session()->put('current_step_data',$current_step_data);
            
            $active_step_content = $this->getStepContent($active_step_key);

            $data = [
                'action' => "navigate_stage",
                'is_timeline_started' => 1,
                'stages' => $stages,
                'current_stage' => $current_stage,
                'active_stage' => $active_stage,
                'active_step' => $active_step,
                'active_step_key' => $active_step_key,
                'page_title' => $page_title,
                'page_sub_title' => $page_sub_title,
                'current_step_data' => $current_step_data,
                'step_list' => $step_list,
                'active_step_content' => $active_step_content,
            ];
        }
        else{
            $data = array(
                'is_timeline_started' => 0,
            );
        }
        
        if ($request->is('api/*')) {
            return apiResponse("success","",$data);
        }
        return view("user.app-status.application-status")->with($data);
    }
    
    /**
     * step_status => 0 = Disabled, 1 = Active, 2 = Complete
     * **/
    public function getStepContent($step_order_key){
        if(!empty($step_order_key)){
            $user = auth()->user();
            $j1Status = $user->j1Status;
            $portfolio = $user->portfolio;
            $userGeneral = $user->userGeneral();
            
            $stages = AppStatus::getStages();
        
            $as = new AppStatus;
            
            $current_step_data = $as->getCurrentStep($user);
            $request_step_data = $as->getCurrentStep($user, $step_order_key);
            
            $stage_number = $request_step_data->as_stage_number;
            $step_number = $request_step_data->as_order;
            
            $step_verified_data = array();
            $step_verified_data['is_multi_placement'] = false;
            $step_verified_data['step_status'] = 0;
            $step_verified_data['userGeneral'] = $userGeneral;
            
            switch($step_order_key) {
                
                case "1_eligibility_test":
                    
                    if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                        $step_verified_data['step_status'] = 2;
                    
                    if(!empty($userGeneral->eligibility_test_output)){
                        $step_verified_data['step_status'] = 2;
                    }
                    else if(empty($userGeneral->eligibility_test_output)){
                        $step_verified_data['step_status'] = 1;
                    }
                    
                break;
                
                case "1_resume_upload":
                case "1_resume_approval":
                    
                    /** Start Temporary code **/
                    $step_verified_data = [
                        'step_status' => 2,
                        'is_step_success' => 1,
                        'type' => "success",
                        'message' => "Thank you! your document has been approved.",
                    ];
                    
                    /** End Temporary code **/
                    
                    $document = $this->doc->getDocumentByType($user, 'resume');
                    
                    if($step_order_key == "1_resume_upload"){
                        
                        $resume_data = "";
                        $resume_data = $user->resume;
                        /** Start when resume builder updated **

                        if(!empty($resume_data))
                        {
                            session(['resume_id' => $resume_data['resume_id']]);
                        }
                        /****/

                        if(!empty($document)) {
                            $document_name = $document->documentType->name;
                            if($document->document_status == 1) {
                                $step_verified_data = [
                                    'is_step_success' => 1,
                                    'type' => "success", 
                                    'message' => "Thank you! your {$document_name} has been approved.",
                                ];
                            }
                            else if($document->document_status != 2) {
                                $step_verified_data = [
                                    'is_step_success' => 2,
                                    'type' => "warning", 
                                    'message' => "Thank you! your {$document_name} is already uploaded and approval in progress.",
                                ];
                            }
                            else {
                                $step_verified_data = ['is_step_success' => 0,];
                            }
                        }
                        else {
                            $step_verified_data = ['is_step_success' => 0,];
                        }

                        $step_verified_data['resume_data'] = $resume_data;
                    }
                    else if($step_order_key == "1_resume_approval"){
                        if(!empty($document)) {
                            $document_name = $document->documentType->name;
                            $created_at = $document->created_at;
                            $countdown_date = get_countdown_date($created_at,24,'Y-m-d H:i');
                            $document->countdown_date = $countdown_date;
                            
                            if($document->document_status == 1) {
                                $step_verified_data = [
                                    'is_step_success' => 1,
                                    'type' => "warning", 
                                    'message' => "Your {$document_name} is approved successfully."
                                ];
                            }
                            else if($document->document_status == 2) {
                                $step_verified_data = [
                                    'is_step_success' => 2,
                                    'type' => "warning", 
                                    'message' => "Uploaded {$document_name} is rejected.<br> {$document->document_reject_reason}",
                                    'document_data' => $document,
                                ];
                            }
                            else {
                                $step_verified_data = ['is_step_success' => 0,'document_data' => $document,];
                            }
                        }
                        else {
                            $step_verified_data = ['is_step_success' => 0];
                        }
                    }
                    
                break;
                
                case "1_skype":
                    
                    $skype_id = $userGeneral->skype_id;
                    
                    if(!empty($skype_id)) {
                        $step_verified_data['skype_id'] = $skype_id;
                        if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                        {
                            $step_verified_data['step_status'] = 2;
                        }
                        else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                        {
                            $step_verified_data['step_status'] = 1;
                        }
                    }
                break;
            
                case "1_j1_interview":
                    
                    $j1Interview = $user->interview;
                    if(!empty($j1Interview)){
                        
                        $schedule_date = $j1Interview->date_interview_admin;
                        $admin_timezone_name = Timezone::getZoneNameById($j1Interview->time_zone_admin);
                        $user_timezone_name = Timezone::getZoneNameById(session('user_timezone'));

                        $converted_data = $this->timezone->convertTZDateTime($schedule_date,$admin_timezone_name,$user_timezone_name,DB_DATETIME_FORMAT);
                        if(!empty($converted_data)){
                            $j1Interview = (object) collect($j1Interview)->merge($converted_data)->all();
                        }

                        $step_verified_data = [
                            'is_step_success' => 1,
                            'interview_data' => $j1Interview
                        ];
                        
                        if($j1Interview->interview_status == 2){
                            $step_verified_data = [
                                'is_interview_finished' => 1,
                                'is_step_success' => 2
                            ]; 
                        }
                    }
                    
                    if(!empty($userGeneral->payment_plan) && $userGeneral->payment_plan == 2)
                    {
                        $step_verified_data['is_payment_plan'] = 1;
                    }
                    
                    /****
                    $user_tags = $user->tag_id;

                    $tag_id = 19; // ITN Tag: Payment Plan Request 
                    $is_tag_available = false;
                    if(!empty($user_tags)){
                        $user_tag_array = custom_explode($user_tags);
                        if(in_array($tag_id,$user_tag_array)){
                            $is_tag_available = true;
                        }
                    }
                    /****/
                break;
            
                case "1_j1_agreement":
                    if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                    {
                        $step_verified_data = ['is_step_success' => 1];
                    }
                    else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                    {
                        $step_verified_data = ['is_step_success' => 0];
                    }
                break;
            
                case "1_registration_fee":
                    
                    $j1Interview = $user->j1Interview();
                    
                    if(!empty($j1Interview)){
                        if(!empty($j1Interview->reg_fee_status)){
                            $reg_fee_status = $j1Interview->reg_fee_status;
                            if(in_array($reg_fee_status,[1,2])){
                                $step_verified_data = ['is_step_success' => 1, 'reg_fee_status' => $reg_fee_status];
                            }
                            else{
                                $step_verified_data = ['is_step_success' => 0];
                            }
                        }
                        else{
                            $step_verified_data = ['is_step_success' => 0];
                        }
                    }
                    else{
                        $step_verified_data = ['is_step_success' => 0];
                    }
                    
                break;
            
                case "1_additional_info":
                    
                break;
                
                case "2_contract_placement":
                    
                    /**
                     * contract_status:
                     * 1 = contract already exist
                     * 2 = request from agency for contract
                     * 3 = request to agency for contract
                     * **/
                    
                    if(!empty($portfolio->placement_agency_id)){
                        $placement_agency = $portfolio->placementAgency;
                        $step_verified_data['contract_status'] = 1;
                        $step_verified_data['step_status'] = 2;
                        $step_verified_data['agency_data'] = $placement_agency;
                    }
                    else{
                        $contract_requests = $portfolio->agencyContracts()
                                        ->with('agency')
                                        ->where('request_status', 1)
                                        ->whereIn('contract_type', [2,4])
                                        ->get();
                        
                        $contract_requests = collect($contract_requests)->all();
                        if(!empty($contract_requests)){
                            $step_verified_data['contract_status'] = 2;
                            $step_verified_data['step_status'] = 1;
                        }
                        else{
                            $agencies = Agency::where('status',1)
                                    ->whereIn('agency_type',[2,4])
                                    ->get();
                            $agencies = collect($agencies)->all();
                            if(!empty($agencies)){
                                $step_verified_data['agencies'] = $agencies;
                                $step_verified_data['contract_status'] = 3;
                                $step_verified_data['step_status'] = 1;
                            }
                        }
                        
                    }
                    
                    $contracts = $portfolio->agencyContracts()
                                    ->with('agency')
                                    ->whereIn('contract_type', [2,4])
                                    ->get();
                    $contracts = collect($contracts)->all();

                    $step_verified_data['contracts'] = $contracts; 
                    if(!empty($contracts) && !empty($portfolio->placement_agency_id)){
                        $step_verified_data['contract_status'] = 1;
                    }
                    $step_verified_data['step_contract_type'] = 2;
                    $step_verified_data['step_title'] = "Contract With Placement Agency";
                    
                break;
                
                case "2_supporting_documents":
                    if(!empty($portfolio->placement_agency_id)){ 
                        
                        $dr = new DocumentRequirement;
                        $basic_documents = $dr->getDocumentByDocSection($user,1, $portfolio->placement_agency_id);
                        
                        $documents = $basic_documents['document_requirements'];
                        $uploaded_documents = $basic_documents['uploaded_documents'];
                        $approved_documents = $basic_documents['approved_documents'];
                        $total_document_requirements = $basic_documents['total_document_requirements'];
                        
                        $step_verified_data['documents'] = $documents;
                        if($total_document_requirements == $approved_documents){
                            $step_verified_data['all_document_collected'] = 1;
                        }
                        
                        if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                        {
                            $step_verified_data['step_status'] = 2;
                        }
                        else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                        {
                            $step_verified_data['step_status'] = 1;
                        }
                        $step_verified_data['step_title'] = "Provide Supporting Documents";
                    }
                break;
                
                case "2_searching_position":
                    $placement_data = $portfolio->placements()->with('position:id,pos_name','hostCompany')->where('type',2)->get()->all();
                    if(!empty($placement_data)){
                        
                        $step_verified_data['placement_data'] = $placement_data;
                        
                        $is_placement_confirmed = 1;
                        foreach($placement_data as $pla_key => $pla_item)
                        {
                            $placement_data[$pla_key]->is_complete_class = " tab-progress ";

                            if($pla_item->type == 2)
                            {
                                $placement_data[$pla_key]->is_complete_class = " tab-success ";
                            }

                            if($pla_item->type != 2)
                            {
                                $is_placement_confirmed = 0;
                            }
                        }
                        $step_verified_data['is_placement_confirmed'] = $is_placement_confirmed;
                        if(count($placement_data)>1){
                            $step_verified_data['is_multi_placement'] = 1;
                        }
                    }
                    
                    if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                    {
                        if(!empty($placement_data))
                            $step_verified_data['step_status'] = 2;
                        else
                            $step_verified_data['step_status'] = 1;
                    }
                    else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                    {
                        $step_verified_data['step_status'] = 1;
                    }
                break;
                
                case "2_booked":
                    
                    $placement_data = $portfolio->placements()->with('position:id,pos_name','hostCompany:id,hc_name')->get()->all();

                    $is_placement_confirmed = 1;
                    $fields = ['j1_interview.id',
                                 'j1_interview.portfolio_id',
                                 'j1_interview.date_interview_user',
                                 'j1_interview.time_zone_user',
                                 'j1_interview.date_interview_admin',
                                 'j1_interview.time_zone_admin',
                                 'admins.email as contact_email',
                                 'portfolio.user_id',
                                 'portfolio.program_id',
                                 'lead.pos_id',
                                 'lead.hc_id',
                             ];
                    $emp_interview_data = $this->j1interview->getUserEmpInterviewSchedule($fields);

                    if(!empty($placement_data) || !empty($emp_interview_data))
                    {
                        if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                        {
                            $step_verified_data['step_status'] = 2;
                        }
                        else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                        {
                            $step_verified_data['step_status'] = 1;
                        }
                    }
                    
                    if(!empty($placement_data))
                    {
                        $active_tab = 0;
                        foreach($placement_data as $pla_key => $pla_item)
                        {
                            $placement_data[$pla_key]->is_active_class = "";
                            $placement_data[$pla_key]->is_complete_class = "disabled";
                            
                            if(!empty($emp_interview_data))
                            {
                               
                                foreach($emp_interview_data as $key => $item)
                                {
                                    if($pla_item->portfolio_id == $item->portfolio_id
                                        && $pla_item->pos_id == $item->pos_id
                                        && $pla_item->hc_id == $item->hc_id
                                    ){
                                        $schedule_date = $item->date_interview_admin;
                                        $admin_timezone_name = Timezone::getZoneNameById($item->time_zone_admin);
                                        $user_timezone_name = Timezone::getZoneNameById(session('user_timezone'));

                                        $converted_data = $this->timezone->convertTZDateTime($schedule_date,$admin_timezone_name,$user_timezone_name,DB_DATETIME_FORMAT);
                                        $item_data = collect($item)->merge($converted_data)->all();
                                        
                                        $placement_data[$pla_key]->emp_interview_data = (object) $item_data;
                                        $this->changeUserStatus($user, "booked");
                                    }
                                }
                            }
                            
                            if($pla_item->type == 2 || !empty($placement_data[$pla_key]->emp_interview_data))
                            {
                                $placement_data[$pla_key]->is_complete_class = " tab-success ";
                            }
                            else
                            {
                                $placement_data[$pla_key]->is_complete_class = " tab-progress ";
                                if($active_tab == 0)
                                {
                                    $placement_data[$pla_key]->is_active_class = " active ";
                                    $active_tab = 1;
                                }
                            }
                            
                            if($pla_item->type != 2)
                            {
                                $is_placement_confirmed = 0;
                            }
                        }
                        
                        if(count($placement_data) > 1){
                            $step_verified_data['is_multi_placement'] = 1;
                        }
                        
                        $total_placement = count($placement_data);
                        
                        if($active_tab == 0)
                        {
                            if($total_placement > 1 && $step_verified_data['step_status'] == 2)
                                $placement_data[$total_placement - 1]->is_active_class = " active ";
                            else if($total_placement > 1 && $step_verified_data['step_status'] == 1)
                                $placement_data[0]->is_active_class = " active ";
                            else if($total_placement == 1 && $step_verified_data['is_multi_placement'] == true)
                                $placement_data[0]->is_active_class = " active ";
                        }

                        $step_verified_data['placement_data'] = $placement_data;
                        $step_verified_data['is_placement_confirmed'] = $is_placement_confirmed;
                    }

                    
                break;
                
                case "2_placed":
                    
                    $placement_data = $portfolio->placements()->with('position:id,pos_name','hostCompany:id,hc_name')->get()->all();
                   
                    $is_placement_confirmed = 1;
                    
                    if (!empty($placement_data))
                    {
                        $active_tab = 0;
                        if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                        {
                            $step_verified_data['step_status'] = 2;
                        }
                        else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                        {
                            $step_verified_data['step_status'] = 1;
                        }
                        
                        if(count($placement_data) > 1){
                            $step_verified_data['is_multi_placement'] = 1;
                        }
                        
                        foreach($placement_data as $key => $item)
                        {
                            $placement_data[$key]->is_active_class = "";
                            $placement_data[$key]->is_complete_class = "disabled";
                            
                            if($item->type == 2)
                            {
                                $placement_data[$key]->is_complete_class = " tab-success ";
                            }
                            else
                            {
                                $placement_data[$key]->is_complete_class = " tab-progress ";
                                if($active_tab == 0)
                                {
                                    $placement_data[$key]->is_active_class = " active ";
                                    $active_tab = 1;
                                }
                                
                                $is_placement_confirmed = 0;
                            }
                        }
                        
                        $total_placement = count($placement_data);
                        
                        if($active_tab == 0)
                        {
                            if($total_placement > 1 && $step_verified_data['step_status'] == 2)
                                $placement_data[$total_placement - 1]->is_active_class = " active ";
                            else if($total_placement > 1 && $step_verified_data['step_status'] == 1)
                                $placement_data[0]->is_active_class = " active ";
                            else if($total_placement == 1 && $step_verified_data['is_multi_placement'] == true)
                                $placement_data[0]->is_active_class = " active ";
                        }
                        
                        $step_verified_data['placement_data'] = $placement_data;
                        $step_verified_data['is_placement_confirmed'] = $is_placement_confirmed;
                    }
                    
                break;
                
                case "3_contract_sponsor":
                    
                    /**
                     * contract_status:
                     * 1 = contract already exist
                     * 2 = request from agency for contract
                     * 3 = request to agency for contract
                     * **/
                    
                    if(!empty($portfolio->sponsor_agency_id)){
                        $sponsor_agency = $portfolio->sponsorAgency;
                        $step_verified_data['contract_status'] = 1;
                        $step_verified_data['step_status'] = 2;
                        $step_verified_data['agency_data'] = $sponsor_agency;
                    }
                    else{
                        $contract_requests = $portfolio->agencyContracts()
                                        ->with('agency')
                                        ->where('request_status', 1)
                                        ->whereIn('contract_type', [3,4])
                                        ->get();
                        
                        $contract_requests = collect($contract_requests)->all();
                        if(!empty($contract_requests)){
                            $step_verified_data['contract_status'] = 2;
                            $step_verified_data['step_status'] = 1;
                        }
                        else{
                            $agencies = Agency::where('status',1)
                                    ->whereIn('agency_type',[3,4])
                                    ->get();
                            $agencies = collect($agencies)->all();
                            if(!empty($agencies)){
                                $step_verified_data['agencies'] = $agencies;
                                $step_verified_data['contract_status'] = 3;
                                $step_verified_data['step_status'] = 1;
                            }
                        }
                        
                    }
                    
                    $contracts = $portfolio->agencyContracts()
                                    ->with('agency')
                                    ->whereIn('contract_type', [3,4])
                                    ->get();
                    $contracts = collect($contracts)->all();

                    $step_verified_data['contracts'] = $contracts; 
                    if(!empty($contracts) && !empty($portfolio->sponsor_agency_id)){
                        $step_verified_data['contract_status'] = 1;
                    }
                    $step_verified_data['step_contract_type'] = 2;
                    $step_verified_data['step_title'] = "Contract With Sponsor Agency";
                    
                break;
                
                case "3_post_placement_documents":
                    if(!empty($portfolio->sponsor_agency_id)){ 
                        
                        $dr = new DocumentRequirement;
                        $documents = $dr->getDocumentByDocSection($user,2, $portfolio->sponsor_agency_id);
                        
                        $step_verified_data['documents']  = $documents['document_requirements'];
                        $step_verified_data['uploaded_documents']  = $documents['uploaded_documents'];
                        $step_verified_data['approved_documents']  = $documents['approved_documents'];
                        
                        if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                        {
                            $step_verified_data['step_status'] = 2;
                        }
                        else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                        {
                            $step_verified_data['step_status'] = 1;
                        }
                        $step_verified_data['step_title'] = "Post Placement Document";
                    }
                break;
                
                case "3_ds7002_pending":
                    
                    if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                    {
                        $step_verified_data['step_status'] = 2;
                    }
                    else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                    {
                        $step_verified_data['step_status'] = 1;
                    }
                    
                break;
                
                case "3_ds7002_created":
                    
                    $placement_data = $portfolio->placements()
                                        ->with([
                                                'position:id,pos_name',
                                                'hostCompany:id,hc_name',
                                                'documents' => function ($query) {
                                                        $query->where('document_type',DT::getIdByKey('ds7002_template'))
                                                            ->select('documents.id','placement_id','document_filename');
                                                    }
                                                ])
                                        ->where('type',2)
                                        ->get();

                    if(!empty($placement_data)){
                        $active_tab = 0;
                         
                        if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                        {
                            $step_verified_data['step_status'] = 2;
                        }
                        else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                        {
                            $step_verified_data['step_status'] = 1;
                        }
                         
                        $path = "storage".DS."user_documents".DS.$user->id.DS;
                        $signed_upload_dir_path = public_path($path);

                        foreach($placement_data as $pla_key => $pla_item)
                        {
                            $pla_id = $pla_item->id;
                            $placement_data[$pla_key]->is_active_class = "";
                            $placement_data[$pla_key]->is_complete_class = "disabled";
                            $placement_data[$pla_key]->dstp_download_link = "";
                            $placement_data[$pla_key]->is_signed = 0;
                            
                            $document = $this->doc->getDocumentByType($user, 'training_plan_signed');
                            if(!empty($document)){
                                $placement_data[$pla_key]->dstp_download_link = $document->document_download_link;
                                $placement_data[$pla_key]->is_signed = 1;
                            }
                            else{
                                $document = $this->doc->getDocumentByType($user, 'ds7002_template');
                                if(!empty($document)){
                                    $placement_data[$pla_key]->dstp_download_link = $document->document_download_link;
                                }
                            }
                            
                            if(!empty($placement_data[$pla_key]->dstp_download_link) && $step_verified_data['step_status'] == 2)
                            {
                                $placement_data[$pla_key]->is_complete_class = " tab-success ";
                            }
                            else
                            {
                                $placement_data[$pla_key]->is_complete_class = " tab-progress ";
                                if($active_tab == 0)
                                {
                                    $placement_data[$pla_key]->is_active_class = " active ";
                                    $active_tab = 1;
                                }
                            }

                        }

                        $total_placement = count($placement_data);

                        if($active_tab == 0)
                        {
                            if($total_placement > 1 && $step_verified_data['step_status'] == 2)
                                $placement_data[$total_placement - 1]->is_active_class = " active ";
                            else if($total_placement > 1 && $step_verified_data['step_status'] == 1)
                                $placement_data[0]->is_active_class = " active ";
                        }

                        $step_verified_data['placement_data'] = $placement_data;
                    }
                     
                break;
                
                case "3_ds7002_signed":
                     
                     /*if(in_array($current_program,$route66_program_id))
                     {
                         $step_verified_data['is_multi_placement'] = true;
                     }*/
                     
                    $path = "storage".DS."user_documents".DS.$user->id.DS;
                    $upload_dir_path = public_path($path);

                    $placement_data = $portfolio->placements()
                                        ->with([
                                            'position:id,pos_name',
                                            'hostCompany:id,hc_name',
                                            'documents' => function ($query) {
                                                $query->where('document_type',DT::getIdByKey('training_plan_signed'))
                                                    ->select('documents.id','placement_id','document_filename');
                                            }
                                        ])->where('type',2)->get();
                     
                    if(!empty($placement_data))
                    {
                        $active_tab = 0;
                         
                        if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                        {
                            $step_verified_data['step_status'] = 2;
                        }
                        else if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number))
                        {
                            $step_verified_data['step_status'] = 1;
                        }
                         
                        $secure_user_id = encrypt($user->id);
                         
                        foreach($placement_data as $pla_key => $pla_item)
                        {
                            $pla_id = $pla_item->id;
                            $placement_data[$pla_key]->is_active_class = "";
                            $placement_data[$pla_key]->is_complete_class = "disabled";
                            $placement_data[$pla_key]->dstp_download_link = "";

                            $document = $this->doc->getDocumentByType($user, 'training_plan_signed');
                            if(!empty($document)){
                                $placement_data[$pla_key]->dstp_download_link = $document->document_download_link;
                            }

                            if(!empty($placement_data[$pla_key]->dstp_download_link) && $step_verified_data['step_status'] == 2)
                            {
                                $placement_data[$pla_key]->is_complete_class = " tab-success ";
                            }
                            else
                            {
                                $placement_data[$pla_key]->is_complete_class = " tab-progress ";
                                if($active_tab == 0)
                                {
                                    $placement_data[$pla_key]->is_active_class = " active ";
                                    $active_tab = 1;
                                }
                            }

                        }
                         
                        $total_placement = count($placement_data);

                        if($active_tab == 0)
                        {
                            if($total_placement > 1 && $step_verified_data['step_status'] == 2)
                                $placement_data[$total_placement - 1]->is_active_class = " active ";
                            else if($total_placement > 1 && $step_verified_data['step_status'] == 1)
                                $placement_data[0]->is_active_class = " active ";
                        }

                        $step_verified_data['placement_data'] = $placement_data;
                    }
                break;
                 
                case "3_ds2019_sent":
                    $legal = Legal::where('portfolio_id',$portfolio->id)->first();
                    if(!empty($legal))
                    {
                        $step_verified_data['legal']  = $legal;
                        $step_verified_data['is_step_success'] = 1 ;
                        $this->changeUserStatus($user, "ds2019-sent");
                    }
                    
                break;
                
                case "3_us_embassy_interview":
                    
                    $timezone_list = $this->timezone->getTimezones();
                    $step_verified_data['timezone_list'] = $timezone_list;
                    
                    if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number <= $current_step_data->as_stage_number)
                    {
                        $step_verified_data['step_status'] = 1;
                        
                        if(!empty($userGeneral->embassy_interview) && !empty($userGeneral->embassy_timezone))
                        {
                            $step_verified_data['embassy_interview'] = $userGeneral->embassy_interview;
                            $step_verified_data['embassy_timezone'] =  $this->timezone->getFullZoneLabel($userGeneral->embassy_timezone);
                        }
                        $embassy_interview_date = $userGeneral->embassy_interview;
                        $embassy_timezone = $userGeneral->embassy_timezone;

                        if(!empty($embassy_interview_date) && substr($embassy_interview_date,0,4) > 1970)
                        {
                            $embassy_timezone_name = Timezone::getZoneNameById($embassy_timezone);
                            $user_timezone_name = Timezone::getZoneNameById(session('user_timezone'));
                            $converted_data = $this->timezone->convertTZDateTime($embassy_interview_date,$embassy_timezone_name,$user_timezone_name,DB_DATETIME_FORMAT);
                            $embassy_data = collect($converted_data)->all();
                            $step_verified_data['embassy_data'] = (object) $embassy_data;
                        }

                        if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number){
                            $step_verified_data['step_status'] = 2;
                        }
                    }
                break;
                
                case "3_us_visa_outcome":
                    $step_verified_data['allow_visa_process'] = 0;
                    if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number <= $current_step_data->as_stage_number)
                    {
                        $step_verified_data['step_status'] = 1;
                        $embassy_interview_date = $userGeneral->embassy_interview;
                        $embassy_timezone = $userGeneral->embassy_timezone;
                        $visa_denied_count = $userGeneral->visa_denied_count;
                        $consecutive_visa_denied_flag = $userGeneral->consecutive_visa_denied_flag;

                        if(!in_array($user->j1_status_id,[3013,5002,3012]) || session('visa_denied_undo') == 1)
                        {
                            if(!empty($embassy_interview_date) && substr($embassy_interview_date,0,4) > 1970)
                            {
                                $embassy_data = new \stdClass();
                                $embassy_data->embassy_interview = $embassy_interview_date;
                                $embassy_data->embassy_timezone = $embassy_timezone;
                                
                                $current_datetime = get_current_datetime()->current_datetime;
                                $embassy_timezone_name = Timezone::getZoneNameById($embassy_timezone);
                                $user_timezone_name = Timezone::getZoneNameById(session('user_timezone'));
                                $converted_data = $this->timezone->convertTZDateTime($embassy_interview_date,$embassy_timezone_name,$user_timezone_name,DB_DATETIME_FORMAT);
                                
                                $step_verified_data['embassy_data'] = $embassy_data;
                                $step_verified_data['converted_data'] = $converted_data;
                                
                                if($embassy_data->embassy_interview < $current_datetime)
                                {
                                    $step_verified_data['allow_visa_process'] = 1;
                                }

                                if(in_array($user->j1_status_id, [3010,3011]))
                                {
                                    $step_verified_data['show_message'] = "We have already recorded the outcome of your US Embassy's ".__('application_term.exchange_visitor')." visa interview.";
                                }
                            }

                            if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                            {
                                $step_verified_data['step_status'] = 2;
                            }
                        }
                        elseif(in_array($user->j1_status_id,[3013,3012]) && $consecutive_visa_denied_flag != 2 && $visa_denied_count >= 2)
                        {
                            if($user->j1_status_id == 3013)
                                $step_verified_data['allow_visa_process'] = 3;

                            if($user->j1_status_id == 3012)
                                $step_verified_data['allow_visa_process'] = 4;
                        }
                        else
                        {
                            $step_verified_data['allow_visa_process'] = 2;
                        }
                        $step_verified_data['visa_denied_count'] = $visa_denied_count;
                        $step_verified_data['consecutive_visa_denied_flag'] = $consecutive_visa_denied_flag;
                    }
                break;
                
                case "3_flight_info":
                    
                    $arrival_timezone_list = $this->timezone->getTimezones('US');
                    $arrival_method = config('common.arrival_method');
                    
                    $step_verified_data['airport_list'] = $this->airports;
                    $step_verified_data['state_list'] = $this->states;
                    $step_verified_data['timezone_list'] = $this->timezones;
                    $step_verified_data['arrival_timezone_list'] = $arrival_timezone_list;

                    $flight_data = $portfolio->flightInfo()->first();
           
                    $step_verified_data['step_status'] = 1;
                    if(!empty($flight_data))
                    {
                        $airport_data = $this->airports[array_search($flight_data->arrival_airport, array_column($this->airports, 'ap_abbr'))];
                        $dep_timezone_data = $this->timezones[array_search($flight_data->departure_timezone, array_column($this->timezones, 'zone_id'))];
                        $arr_timezone_data = $arrival_timezone_list[array_search($flight_data->arrival_timezone, array_column($arrival_timezone_list, 'zone_id'))];

                        $flight_data->airport_data = $airport_data;
                        $flight_data->dep_timezone_data = $dep_timezone_data;
                        $flight_data->arr_timezone_data = $arr_timezone_data;
                        /* $flight_data->arrival_method = $arrival_method[$flight_data->arrival_method]; */
                        $step_verified_data['flight_data'] = $flight_data;
                        $step_verified_data['step_status'] = 2;
                    }

                break;
                
                case "3_arrival_in_usa":
                    
                    $flight_data = $portfolio->flightInfo()->first(); 
                
                    if(!empty($flight_data))
                    {
                        $arrival_timezone = $flight_data->arrival_timezone;
                        $arrival_date = $flight_data->arrival_date;
                        $timezone_data = $this->timezone->getFullZoneLabel($arrival_timezone);
                        
                        $flight_data = (object) collect($flight_data)->merge($timezone_data)->all();

                        $current_datetime = get_current_datetime($arrival_timezone)->current_datetime;
                        $flight_data->is_date_passed = 0;
                        if($flight_data->arrival_date < $current_datetime)
                        {
                            $flight_data->is_date_passed = 1;
                        }
                        
                        $step_verified_data['is_step_success'] = 1;
                        $step_verified_data['flight_data'] = (object) $flight_data;
                    }

                break;
                
                default:
                break;
            }
            
            $data = [
                        'action' => "navigate_step", 
                        'active_stage' => $stage_number,
                        'active_step' => $step_number,
                        'active_step_key' => $step_order_key,
                        'current_stage' => $current_step_data->as_stage_number,
                        'current_step' => $current_step_data->as_order,
                        'current_step_key' => $current_step_data->as_order_key,
                        'current_step_data' => $current_step_data,
                        'request_step_data' => $request_step_data,
                        'step_verified_data' => $step_verified_data,
                        'app_status_stages' => $stages,
                        'is_step_locked' => $portfolio->is_step_locked,
                    ];
            
            if (starts_with(request()->path(), 'api')) {
                return $data;
            }
            else{
                $step_content = view('user.app-status.application-status-stages')
                            ->with($data)
                        ->render();

                return $step_content;
            }
        }
        else{
            return false;
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function navigateApplicationSteps(Request $request)
    {
        $user = auth()->user();
        
        $as = new AppStatus;
        $current_order_key = $user->portfolio->as_order_key;
        $current_step_data = $as->getCurrentStep($user);
        
        $stages = AppStatus::getStages();
        
        $response = array();
        $action = $request->action;
        $active_stage = $request->active_stage;
        
        if($action == "navigate_stage") {

            if(!empty($active_stage)){
                
                $request_step_key = $request->get('request_step_key',"");
                $stage_data = $stages[$active_stage];
                $page_title = $stage_data['page_title'];
                $page_sub_title = $stage_data['page_subtitle'];
                
                $step_list = $as->getStepsByStage($user, $active_stage, $current_step_data);
                
                $active_step = 1;
                $active_step_key = (!empty(collect($step_list)->first()->as_order_key)) ?  collect($step_list)->first()->as_order_key : "";

                if($current_step_data->as_stage_number == $active_stage)
                {
                    $active_step = $current_step_data->as_order;
                    $active_step_key = $current_step_data->as_order_key;
                }
                
                if(!empty($request_step_key))
                {
                    $request_step_data = $as->getCurrentStep($user, $request_step_key);
                    if(!empty($request_step_data))
                    {
                        $active_step = $request_step_data->as_order;
                        $active_step_key = $request_step_data->as_order_key;
                    }
                }
                
                $request->session()->put('active_stage',$active_stage);
                
                $step_content = $this->getStepContent($active_step_key);
                
                $data = [
                    'action' => $action, 
                    'active_stage' => $active_stage,
                    'active_step' => $active_step,
                    'active_step_key' => $active_step_key,
                    'active_step_content' => $step_content,
                    'current_step_data' => $current_step_data,
                    'step_list' => $step_list,
                    'app_status_stages' => $stages,
                ];
                
                $response['type'] = "success";
                $response['message'] = "";
                $response['page_title'] = $page_title;
                $response['page_sub_title'] = $page_sub_title;
                $response['active_stage'] = $active_stage;
                $response['active_step'] = $active_step;
                $response['active_step_key'] = $active_step_key;
                if ($request->is('api/*')) {
                   $response['application_status_content'] = $data;
                }else{
                    $compiled = view('user.app-status.application-status-stages')
                            ->with($data)
                            ->render();
                    $response['application_status_content'] = $compiled;

                }
            }
            else{
                $response['type'] = "error";
                $response['message'] = "Failed to Load stage";
            }
        }
        else if($action == "navigate_step") {
            
            $active_step_key = "1_eligibility_test";
            
            if(!empty($request->active_step_key)) {
                $active_step_key = $request->active_step_key;
            }
            
            $step_content = $this->getStepContent($active_step_key);

            $response['type'] = "success";
            $response['message'] = "";
            $response['step_content'] = $step_content;
        }
        else {
            $response['type'] = "error";
            $response['message'] = "Failed to Load Step Content";
        }
        return apiResponse($response['type'],$response['message'],$response);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function applicationStatusProgress(Request $request)
    {
        $user = auth()->user();
        $portfolio = $user->portfolio;

        $stages = AppStatus::getStages();
        unset($stages[4]);

        $as = new AppStatus;
        $current_order_key = $portfolio->as_order_key;
        $current_step_data = $as->getCurrentStep($user, $current_order_key);
            
        foreach($stages as  $stage_key => $stage_item)
        {
            $stage_data = $as->getStepsByStage($user, $stage_key, $current_step_data);
            if(!empty($stage_data))
            {
                $total_completed_steps = 0;
                $stage_alert = 0;
                $step_progress_percent = 0;
                $total_steps = count($stage_data);

                foreach($stage_data as $stage)
                {
                    if($stage->user_step_status == 2)
                    {
                        $total_completed_steps++;
                    }
                    
                    if($stage->step_alert == 1)
                        $stage_alert = 1;
                }

                if($total_completed_steps > 0)
                {
                    $step_progress_percent = (int) (($total_completed_steps * 100) / $total_steps);
                }

                $temp = [
                    'stage_num' => $stage_key,
                    'total_steps' => $total_steps,
                    'total_completed_steps' => $total_completed_steps,
                    'stage_alert' => $stage_alert,
                    'step_progress_percent' => $step_progress_percent."%",
                    'stage_title' => $stage_item["stage_title"],
                    'procedure_subtitle' => $stage_item["procedure_subtitle"],
                ];

                $stages[$stage_key] = $temp;
            }
        }
        
        $response = [
            'type' => "success",
            'message' => "",
            'data' => $stages,
        ];
        
        return $response;
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function eligibilityQuest(Request $request)
    {
        $eligibility_quest = config('common.eligibility_quest');
        
        $response = array();
        $user = auth()->user();
        $portfolio = $user->portfolio;
        $userGeneral = $user->userGeneral();
        
        if(!empty($request->eligibility_answer)) {
            if($request->eligibility_answer > 0 && $request->eligibility_answer < 6) {
                $eligibility_answer = 101;
                $request->session()->put('industry_selected', $request->eligibility_answer);
            }
            else {
                $eligibility_answer = $request->eligibility_answer;
            }
        }
        else if(session('eligibility_answer') == null || empty(session('eligibility_answer'))) {
            $eligibility_answer = 0;
        }
        else {
            $eligibility_answer = session('eligibility_answer');
        }
        
        if($request->eligibility_answer === '0') {
            $eligibility_answer = $request->eligibility_answer;
            
            $userGeneral->eligibility_test_output = $eligibility_answer;
            $userGeneral->save();
        }
        
        $request->session()->put('eligibility_answer', $eligibility_answer);
        
        if(array_key_exists($eligibility_answer, $eligibility_quest))
        {
            $quest_data = $eligibility_quest[$eligibility_answer];

            if(!empty($quest_data['result'])) {

                // Update selected industry, eligibility test result and program
                if($quest_data['result'] == "success")
                {
                    $portfolio->program_id = $quest_data['program'];
                    $portfolio->save();
                    
                    $userGeneral->industry_selected = session('industry_selected');
                    $userGeneral->eligibility_test_result = $quest_data['program'];
                    $userGeneral->eligibility_test_output = $eligibility_answer;
                    $userGeneral->save();
                    
                    $this->changeUserStatus($user,'eligibility-test-completed');
                    
                    $request->session()->forget('eligibility_answer');
                    return apiResponse("success", "Eligibility Test Completed Successfully", $quest_data);
                }
                else if($quest_data['result'] == "error")
                {
                    $request->session()->forget('eligibility_answer');
                    return apiResponse("success", "", $quest_data);
                }
            }
            else {
                
                $data = [
                    'action' => "eligibility_test",
                    'question' => (!empty($quest_data['question']))?$quest_data['question']:"",
                    'options' => (!empty($quest_data['options']))?$quest_data['options']:"",
                    'desc' => (!empty($quest_data['desc']))?$quest_data['desc']:"",
                    'result' => (!empty($quest_data['result']))?$quest_data['result']:"",
                ];

                $compiled = view('user.app-status.eligibility-test')
                            ->with($data)
                            ->render();
                if($request->is('api/*')){
                    $response['html_data'] = $data;
                    return apiResponse("success","",$response);
                }
                $response['html_data'] = $compiled;
                return apiResponse("success","",$response);

            }
        }
        else{
            return apiResponse("error","Invalid answer");
        }
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function uploadResume(Request $request)
    {
        $user = auth()->user();
        
        $allowed_extensions = custom_implode(config('common.allow_doc_ext'));
        $upload_file_size = config('common.upload_file_size');    
        $allowed_file_size = config('common.upload_file_size')*1000; 
        $rules = array('resume' => "required|max:{$allowed_file_size}|mimes:{$allowed_extensions}");
        $validationErrorMessages = [
                            'resume.required' => "Resume is required.",
                            'resume.max' => "Resume allowed maximum size {$upload_file_size}mb.",
                            'resume.mimes' => "Resume must be a file of type: {$allowed_extensions}."
                        ];
        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);
        
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
        
        //Handle resume upload
        if($request->hasFile('resume')){
            
            $document_sent = $this->uploadDocument($user, $request->resume, 'resume',0,1,true);
            
            if (!empty($document_sent) && $document_sent !== false) {
                $response = $document_sent;
            }
            else {
                $response = ['type' => "error", 'message' => "Failed to upload resume"];
            }
        }
        else{
            $response = ['type' => "error", 'message' => "Please select file"];
        }
        
        return Response::json($response);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function updateResumeBuilder(Request $request)
    {
        $user = auth()->user();
        $resume = new Resume;
        $education = $resume->education;
        
        if(!empty($user))
        {
            $rb_step = $request->rb_step;
            $btn_action = (!empty($request->btn_action))?$request->btn_action:"save";
            
            switch($rb_step)
            {
                case 1:
                    $upload_file_size = config('common.upload_file_size');
                    $allowed_extensions = custom_implode(config('common.allow_image_ext')); // allow_doc_ext
                    $upload_img_size = config('common.upload_img_size');
                    $allowed_img_size = config('common.upload_img_size')*1000;
                    
                    $rules = [
                        'first_name'=> 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
                        'last_name' => 'required|different:first_name|regex:/(^[A-Za-z0-9 ]+$)+/',
                        'address'   => 'required',
                        'phone_no'  => 'required',
                        'skype'     => 'required',
                        'email'     => 'required|email',
                        'objective' => 'required',
                    ];
                    
                    $validationErrorMessages = [
                            'first_name.required' => "First Name field is required.",
                            'first_name.regex' => "First name does not allow any special character.",
                            'last_name.regex' => "Last Name does not allow any special character.",
                            'last_name.required' => "Last Name field is required.",
                            'last_name.different' => "First Name and Last Name should not be same.",
                            'address.required' => "Mailing Address field is required.",
                            'phone_no.required' => "Phone Number field is required.",
                            'skype.required' => "Skype field is required.",
                            'email.required' => "Email Address field is required.",
                            'objective.required' => "Objective field is required.",
                            'email.email' => "Email Address must be a valid email address.",
                            'passport_photo.required' => "Passport style picture is required.",
                            'passport_photo.image' => "Passport style picture must be an image.",
                            'passport_photo.max' => "Passport style picture allowed maximum size {$upload_img_size} mb.",
                        ];

                    $file_check = $request->get('file_check',"");
                    if(empty($file_check))
                    {
                        $validationErrorMessages['passport_photo.required'] = "Passport style picture is required.";
                        $validationErrorMessages['passport_photo.image'] = "Passport style picture must be an image.";
                        $validationErrorMessages['passport_photo.max'] = "Passport style picture allowed maximum size {$upload_img_size} mb.";
                        $rules['passport_photo'] = "image|required|max:{$allowed_img_size}|mimes:{$allowed_extensions}";
                    }
                    
                    $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                    if ($validator->fails())
                    {
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
                    
                    $full_name = "{$request->first_name} {$request->last_name}";
                    
                    $field_data = [
                        'full_name' => $full_name,
                        'email' => $request->get('email',""),
                        'address' => $request->get('address',""),
                        'country_id' => $request->get('country',""),
                        'primary_phone' => $request->get('phone_no',""),
                        'secondary_phone' => $request->get('mobile_no',""),
                        'objective' => $request->get('objective',""),
                        'summary' => $request->get('summary',""),
                        'skype' => $request->get('skype',""),
                    ];
                    
                    if(empty($file_check)){
                        $file_dir = config('common.user-documents').DS.$user->id.DS;
                        
                        $rand_num = rand(101, 999).time();
                        $passport_photo = str_slug("{$full_name}_passport_photo_{$rand_num}","_");
                        $passport_photo = doc_name_word_to_upper($passport_photo);
                        
                        $output_file_name = $this->uploadFile($request->passport_photo,$file_dir,$passport_photo);
                        $field_data['passport_photo'] = $output_file_name;
                    }

                    $resume_id = $resume->updateResume($user, $field_data);
                    
                    if(!empty($resume_id)){
                        $response = ['type' => "success", 'message' => "Candidate Information saved", 'btn_action' => $btn_action, 'action_step' => $rb_step,'resume_id' => $resume_id ];
                    }
                    else{
                        $response = ['type' => "error", 'message' => "Failed to save Candidate information"];
                    }
                    
                break;
                
                case 2:
                    if(!empty($user->resume))
                    {
                        $rules = [
                            'skill_language_spoken' => 'required',
                        ];
                        $validationErrorMessages = [
                            'skill_language_spoken.required' => 'Languages Spoken field is required.' 
                        ];
                        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                        if ($validator->fails()) 
                        {
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
                        
                        
                        $field_data = [
                            'skill_computer_skills' => $request->get('skill_computer_skills',""),
                            'skill_computer_programs' => $request->get('skill_computer_programs',""),
                            'skill_industry_programs' => $request->get('skill_industry_programs',""),
                            'skill_language_spoken' => $request->get('skill_language_spoken',""),
                            'skill_other_skills' => $request->get('skill_other_skills',""),
                        ];
                        
                        $resume_id = $resume->updateResume($user, $field_data);
                        $response = !empty($resume_id) ? ['type' => "success", 'message' => "Skills & Abilities saved", 'btn_action' => $btn_action, 'action_step' => $rb_step,'resume_id' => $resume_id ] : ['type' => "error", 'message' => "Failed to save Skills & Abilities"];
                        
                    }
                    else
                    {
                        $response = ['type' => "error", 'message' => "Failed to save Skill & Abilities"];
                    }
                break;
            
                case 3:
                    if(!empty($user->resume))
                    {
                        $rules = [
                            'school' => 'required',
                            'degree' => 'required',
                            'start_date' => 'required',
                            'end_date' => 'required',
                        ];
                        
                        $validationErrorMessages = [
                            'school.required' => 'School Name/Location field is required.',
                            'degree.required' => 'Degree Name field is required.', 
                            'start_date.required' => 'Start Date field is required.',
                            'end_date.required' => 'End Date field is required.', 
                        ];
                    
                        $remove_existing_id = $request->remove_existing_id;
                        $education_data = $request->education;
                        if(!empty($education_data) || !empty($remove_existing_id))
                        {
                            if(!empty($remove_existing_id)){
                                $remove_existing_id = custom_explode($remove_existing_id);
                                $resume->removeEducation($user, $remove_existing_id);
                            }
                            
                            if(!empty($education_data)){
                                foreach($education_data as $edu)
                                {
                                    $edu = collect($edu);
                                    $validator = Validator::make($edu->all(), $rules, $validationErrorMessages);

                                    if ($validator->fails()) 
                                    {
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

                                    $education_id = $edu->get("education_id","");
                                    $field_data = [
                                        'school' => $edu->get("school",""),
                                        'degree' => $edu->get("degree",""),
                                        'start_date' => (!empty($edu['start_date']))?Carbon::parse($edu['start_date'])->format(DB_DATE_FORMAT):"",
                                        'end_date' => (!empty($edu['end_date']))?Carbon::parse($edu['end_date'])->format(DB_DATE_FORMAT):"",
                                        'minor' => $edu->get("minor",""),
                                        'description' => $edu->get("description",""),
                                    ];

                                    $education = $resume->updateEducation($user, $field_data, $education_id);
                                }
                            }
                            $response = !empty($education) ? ['type' => "success", 'message' => "Education details saved", 'btn_action' => $btn_action, 'action_step' => $rb_step ] : ['type' => "error", 'message' => "Failed to save Education details"];
                        }
                        else
                        {
                            $response = ['type' => "error", 'message' => "Failed to save Education details"];
                        }
                    }
                    else
                    {
                        $response = ['type' => "error", 'message' => "Failed to save Education details"];
                    }
                break;
            
                case 4:
                    if(!empty($user->resume))
                    {
                        $remove_existing_id = $request->remove_existing_id;
                        $employment_data = $request->employment;
                        
                        if(!empty($employment_data) || !empty($remove_existing_id)){
                            if(!empty($remove_existing_id)){
                                $remove_existing_id = custom_explode($remove_existing_id);
                                $resume->removeEmployment($user, $remove_existing_id);
                            }
                            
                            if(!empty($employment_data)){
                                foreach($employment_data as $emp)
                                {
                                    $emp = collect($emp);
                                    $employment_id = $emp->get('employment_id',"");
                                    $field_data = [
                                        'title' => $emp->get("title",""),
                                        'employer_name' => $emp->get("employer_name",""),
                                        'duties' => $emp->get("duties",""),
                                        'location' => $emp->get("location",""),
                                        'start_date' => (!empty($emp['start_date']))?Carbon::parse($emp['start_date'])->format(DB_DATE_FORMAT):"",
                                        'end_date' => (!empty($emp['end_date']))?Carbon::parse($emp['end_date'])->format(DB_DATE_FORMAT):"",
                                    ];

                                    $education = $resume->updateEmployment($user, $field_data, $employment_id);
                                }
                            }
                            
                            $response = ['type' => "success", 'message' => "Employment details saved", 'btn_action' => $btn_action, 'action_step' => $rb_step ];
                        }
                        else
                        {
                            $response = ['type' => "error", 'message' => "Failed to save Employment details"];
                        }
                    }
                    else
                    {
                        $response = ['type' => "error", 'message' => "Failed to save Employment details"];
                    }
                break;
            
                case 5:
                    if(!empty($user->resume))
                    {
                        $remove_existing_id = $request->remove_existing_id;
                        $certificate_data = $request->certificate;
                        
                        if(!empty($certificate_data) || !empty($remove_existing_id)){
                            if(!empty($remove_existing_id)){
                                $remove_existing_id = custom_explode($remove_existing_id);
                                $resume->removeEmployment($user, $remove_existing_id);
                            }
                            
                            if(!empty($certificate_data)){
                                foreach($certificate_data as $certi)
                                {
                                    $certi = collect($certi);
                                    $certificate_id = $certi->get('certificate_id',"");
                                    
                                    $field_data = [
                                        'title' => $certi->get('title',""),
                                        'date_of_certificate' => (!empty($certi['date_of_certificate']))?Carbon::parse($certi['date_of_certificate'])->format(DB_DATE_FORMAT):"",
                                        'description' => $certi->get("description",""),
                                        'location' => $certi->get("location",""),
                                    ];
                                    
                                    $certificate = $resume->updateCertificate($user, $field_data, $certificate_id);
                                }
                            }
                            
                            $response = ['type' => "success", 'message' => "Certificate details saved", 'btn_action' => $btn_action, 'action_step' => $rb_step ];
                        }
                        else
                        {
                            $response = ['type' => "error", 'message' => "Failed to save Certificate details"];
                        }
                    }
                    else
                    {
                        $response = ['type' => "error", 'message' => "Failed to save Certificate details"];
                    }
                break;
            
                case 6:
                    if(!empty($user->resume))
                    {
                        $remove_existing_id = $request->remove_existing_id;
                        $award_data = $request->award;

                        if(!empty($award_data) || !empty($remove_existing_id)){
                            if(!empty($remove_existing_id)){
                                $remove_existing_id = custom_explode($remove_existing_id);
                                $resume->removeAward($user, $remove_existing_id);
                            }
                            
                            if(!empty($award_data)){
                                foreach($award_data as $award)
                                {
                                    $award = collect($award);
                                    $award_id = $award->get('award_id',"");
                                    
                                    $field_data = [
                                        'title' => $award->get('title',""),
                                        'description' => $award->get("description",""),
                                        'award_date' => (!empty($award['award_date']))?Carbon::parse($award['award_date'])->format(DB_DATE_FORMAT):"",
                                    ];
                                    
                                    $award = $resume->updateAward($user, $field_data, $award_id);
                                }
                            }
                            $response = ['type' => "success", 'message' => "Certificate details saved", 'btn_action' => $btn_action, 'action_step' => $rb_step ];
                        }
                        else {
                            $response = ['type' => "error", 'message' => "Failed to save Award details"];
                        }
                    }
                    else {
                        $response = ['type' => "error", 'message' => "Failed to save Award details"];
                    }
                break;
            
                default:
                    $response = ['type' => "error", 'message' => "Failed to update resume."];
                break;
            }
        }
        else {
            $response = ['type' => "error", 'message' => "Failed to update resume."];
        }
        
        return Response::json($response);
    }
    
    public function loadRBForm(Request $request){
        
        $user = auth()->user();
        
        if(!empty($user)){
            $form_number = $request->form_number;
            $action = "form_rb_{$form_number}";
            $resume_data = $user->resume;
            
            $data = [
                'action' => $action,
                'form_number' => $form_number,
                'resume_data' => $resume_data,
            ];
            if ($request->is('api/*')) {
                return apiResponse("success","",$data);
            }else{
                $form_content = view('user.app-status.resume')
                            ->with($data)
                            ->render();

                return $response = ['type' => "success", 'message' => "", 'form_content' => $form_content];
            }
        }
        else{
            return $response = ['type' => "error", 'message' => "Failed to update resume."];
        }
    }
    
    public function resetResume(Request $request){
        $user = auth()->user();
        $resume = $user->resume;
        
        if(!empty($user) && !empty($resume)){
            $resume->education()->delete();
            $resume->employment()->delete();
            $resume->awards()->delete();
            $resume->certificates()->delete();
            $resume->delete();
            return ['type' => "success", 'message' => "Resume reset successfully."];
        }
        else{
            return ['type' => "error", 'message' => "Failed to reset resume."];
        }
    }
    
    public function previewResume(Request $request){
        
        $action = $request->get('action',"");
        
        if($action == "resume_preview"){
            
            $user = auth()->user();
            
            $resume_data = $user->resume;

            $data = [
                'action' => $action,
                'resume_data' => $resume_data,
            ];

            if ($request->is('api/*')) {
                return ["type" => "success", "message" => "", "data" => $data ];
            }
            $form_content = view('user.app-status.resume')
                            ->with($data)
                            ->render();
            
            $data = [
                'action' => "resume_preview_modal",
                'resume_preview' => $form_content,
            ];
            
            $HTML = view('user.app-status.resume')->with($data)->render();
            
            return ["type" => "success", "message" => "", "data" => $HTML];
        }
        else{
            return ['type' => "error","message" => "Invalid action"];
        }
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function generateResumePDF(Request $request)
    {
        $user = auth()->user();
        $resume_data = $user->resume;

        if(!empty($resume_data) && !empty($user))
        {
            $data = [
                'action' => "resume_preview",
                'resume_data' => $resume_data,
                'user' => $user
            ];
            
            $temp_dir = storage_path('tmp'.DS);
            check_directory($temp_dir);
            $temp_filename = "Resume_Temp_".uniqid().".pdf";
            $temp_file_path = "{$temp_dir}{$temp_filename}";
            
            PDF::loadView('user.app-status.resume',$data)->save($temp_file_path);
            
            if(file_exists($temp_file_path)){
                $document_sent = $this->uploadDocument($user, $temp_file_path, "resume", 0, 2, true);
                return $document_sent;
            }
            else
                return false;
        }
        else
        {
            $response = ['type' => "error", 'message' => "Failed to build resume."];
        }
        return Response::json($response);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function updateSkype(Request $request)
    {
        $user = auth()->user();
        
        if(!empty($user))
        {
            $userGeneral = $user->userGeneral();
            
            $skype_id = $request->get('skype_id',"");
            if(!empty($skype_id) && !empty($userGeneral))
            {
                $userGeneral->skype_id = $skype_id;
                $userGeneral->save();
                
                $this->changeUserStatus($user, "skype-added");
                
                return ['type' => "success", 'message' => "Skype updated successfully"];
            }
            else
                return ['type' => "validation_error", 'message' => ['skype_id'=> ["Please enter Skype ID"]]];
        }
        else
            return ['type' => "error", 'message' => "Failed to update skype Id"];
        
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function requestFinance(Request $request)
    {
        $user = auth()->user();
        
        if(!empty($user))
        {
            $userGeneral = $user->userGeneral();
            
            $candidate_email = $user->email;
            $candidate_name = "{$user->first_name} {$user->last_name}";
            
            $mail_format = (array) EN::getMailTextByKey("request_financing");

            if(!empty($mail_format)){
                $subject      = $mail_format['subject'];
                $message_text = $mail_format['text'];
                $message_text = str_replace("{{candidate_name}}", $candidate_name, $message_text);
                $message_text = str_replace("{{candidate_email}}", $candidate_email, $message_text);

                /** Pending to implement dynamic email address for toEmail **/
                $recipient = new \stdClass();
                $recipient->toEmail = "test_cc_qa@yopmail.com";
                $recipient->toName = "Test";
                $recipient->ccEmail = (!empty($mail_format['send_cc']))?$mail_format['send_cc']:"";

                $from = array();
                $from['email'] = $candidate_email;
                $from['name'] = $candidate_name;

                $data = ['message_text' => $message_text];

                $this->sendUIEmailNotification($recipient,$subject,$data,null,$from);
            }
            
            $userGeneral->payment_plan = 2;
            $userGeneral->save();
            
            if($userGeneral) {
                return ['type' => "success", 'message' => "We took note of your financing request, We will contact you shortly."];
            }
            else {
                return ['type' => "error", 'message' => "Failed to request for payment plan"];
            }
        }
        else
            return ['type' => "error", 'message' => "Failed to request for payment plan"];
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function j1Agreement(Request $request)
    {
        $user = auth()->user();
        
        if (!empty($request->terms_agreed) && $request->terms_agreed == 1) {

            $agreement_uploaded = $this->generateJ1AgreementPDF();
            
            if($agreement_uploaded === true)
            {
                $response = ['type' => "success", 'message' => "Agreement submitted successfully"];
            }
            elseif(is_array($agreement_uploaded))
            {
                $response = $agreement_uploaded;
            }
            else
            {
                $response = ['type' => "error", 'message' => "Failed to generate J1 Agreement"];
            }
        }
        else {
            $message = "To proceed you need agree with our agreement, by checking 'I accept these Terms and Conditions.'";
            $response = ['type' => "error", 'message' => $message];
        }
        
        return Response::json($response);
    }
    
    public function generateJ1AgreementPDF()
    {
        $user = auth()->user();
        $portfolio = $user->portfolio;
        
        $temp_dir = storage_path('tmp'.DS);
        check_directory($temp_dir);
        $temp_filename = "ITN_Agreement_Temp_".uniqid().".pdf";
        $temp_file_path = "{$temp_dir}{$temp_filename}";
        
        $data = [
            'action' => "j1_agreement_pdf_content",
            'data' => "test",
        ];
        $j1_agreement_pdf_content = view('user.app-status.j1-agreement')->with($data)->render(); 

        $pdf_data = [
            'action' => "j1_pdf_template",
            'office_address' => "International Trainee Network ~ 6300 Wilshire Blvd., Suite 610 ~ Los Angeles ~ California 90048",
            'contact' => "Ph: 213-385-2829 ~ Fax: 213-385-2836 ~ www.itnusa.com",
            'html_content' => $j1_agreement_pdf_content,
        ];

        PDF::loadView('user.app-status.j1-agreement',$pdf_data)->save($temp_file_path);

        if(file_exists($temp_file_path)){
            $document_sent = $this->uploadDocument($user, $temp_file_path, "j1_agreement", 1, 2, true);
            return $document_sent;
        }
        else
            return false;
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function updateAdditionalInfo(Request $request)
    {
        $user = auth()->user();
    
        $required_field_list = ['gender','phone_number','best_call_time','street','city','zip_code','country','state',
                'passport_number','passport_issued','passport_expires','birth_date','birth_city','birth_country',
                'country_citizen','country_resident','country_issuer','previously_participated','material_status',
                'currently_student','currently_employed','contact_name_first','contact_name_last','contact_phone',
                'contact_relationship','contact_country','contact_english_speaking','contact_language','contact_email','criminal_record'];
        
        if(!empty($user))
        {
            if($user->is_timeline_locked == 1)
            {
                $response = ['type' => "warning", 'message' => "You cannot update any details because your application is locked, however you can view all details."];
            }
            else
            {
                $user_general = $user->userGeneral();
                $addinfo_form_step = $request->addinfo_form_step;
                $btn_action = (!empty($request->btn_action))?$request->btn_action:"save";
           
                switch($addinfo_form_step)
                {
                    case 1:
                       
                        $rules = [
                            'fname' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
                            'mname' => 'sometimes|nullable|regex:/(^[A-Za-z0-9 ]+$)+/',
                            'lname' => 'required|different:fname|regex:/(^[A-Za-z0-9 ]+$)+/',
                            'phone_number' => 'required',
                        ];

                        $validationErrorMessages = [
                            'fname.required' => 'First Name field is required.',
                            'fname.regex' => 'First name does not allow any special character.',
                            'mname.regex' => 'Middle Name does not allow any special character.',
                            'lname.regex' => 'Last name does not allow any special character.',
                            'lname.required' => 'Last Name field is required.', 
                            'lname.different' => 'First Name and Last Name should not be same.',
                            'phone_number.required' => 'Phone Number field is required.', 
                        ];

                        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                        if ($validator->fails()) {
                            return Response::json([
                                'type' => "validation_error",
                                'message' => $validator->messages()->toArray(),
                            ]);
                        }

                        $user->first_name = $request->fname;
                        $user->middle_name = $request->mname;
                        $user->last_name = $request->lname;
                        $user->save();

                        $user_general->gender = $request->gender;
                        $user_general->phone_number = $request->phone_number;
                        $user_general->phone_number_two = $request->alternate_phone_number;
                        $user_general->secondary_email = $request->alternate_email;
                        $user_general->best_call_time = $request->best_call_time;
                        $user_general->save();

                        $response = ['type' => "success", 'message' => "Contact information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    case 2:
                        
                    $rules = [
                            'address_street' => 'required',
                            'address_city' => 'required',
                            'address_zip' => 'required',
                            'address_country' => 'required',
                            'state' => 'required',
                        ];

                        $validationErrorMessages = [
                            'address_street.required' => 'Street Address field is required.',
                            'address_city.required' => 'City field is required.', 
                            'address_zip.required' => 'Zip / Postal Code field is required.', 
                            'address_country.required' => 'Country field is required.', 
                            'state.required' => 'State field is required.', 
                        ];

                        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                        if ($validator->fails()) {
                            return Response::json([
                                'type' => "validation_error",
                                'message' => $validator->messages()->toArray(),
                            ]);
                        }
                        
                        $user_general->in_care_of = $request->in_care_of;
                        $user_general->deliver_my_name = $request->deliver_my_name;
                        $user_general->street = $request->address_street;
                        $user_general->street_2 = $request->address_street_2;
                        $user_general->city = $request->address_city;
                        $user_general->zip_code = $request->address_zip;
                        $user_general->country = $request->address_country;
                        $user_general->state = $request->state;
                        $user_general->save();
                    
                        $response = ['type' => "success", 'message' => "Address information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    case 3:
                        
                        $rules = [
                            'passport_number' => 'required',
                            'passport_issued' => 'required|date',
                            'passport_expires' => 'required|date',
                            'birth_date' => 'required|date',
                            'birth_city' => 'required',
                            'birth_country' => 'required',
                            'country_citizen' => 'required',
                            'country_resident' => 'required',
                            'country_issuer' => 'required',
                        ];

                        $validationErrorMessages = [
                            'passport_number.required' => 'Passport Number field is required.', 
                            'passport_issued.required' => 'Passport Issuing Date field is required.', 
                            'passport_issued.date' => 'Passport Issuing Date is invalid.', 
                            'passport_expires.required' => 'Passport Expiration Date field is required.', 
                            'passport_expires.date' => 'Passport Expiration Date is invalid.', 
                            'birth_date.required' => 'Date of Birth field is required.', 
                            'birth_date.date' => 'Date of Birth is invalid.', 
                            'birth_city.required' => 'City of Birth field is required.', 
                            'birth_country.required' => 'Country of Birth field is required.', 
                            'country_citizen.required' => 'Citizen of field is required.', 
                            'country_resident.required' => 'Legal Permanent Resident field is required.', 
                            'country_issuer.required' => 'Passport Issuing Country field is required.', 
                        ];

                        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                        if ($validator->fails()) {
                            return Response::json([
                                'type' => "validation_error",
                                'message' => $validator->messages()->toArray(),
                            ]);
                        }
                        
                        $user_general->birth_date = Carbon::parse($request->birth_date)->format(DB_DATE_FORMAT);
                        $user_general->birth_city = $request->birth_city;
                        $user_general->birth_country = $request->birth_country;
                        $user_general->passport_number = $request->passport_number;
                        $user_general->passport_issued = Carbon::parse($request->passport_issued)->format(DB_DATE_FORMAT);
                        $user_general->passport_expires = Carbon::parse($request->passport_expires)->format(DB_DATE_FORMAT);
                        $user_general->country_citizen = $request->country_citizen;
                        $user_general->country_resident = $request->country_resident;
                        $user_general->country_issuer = $request->country_issuer;
                        $user_general->save();
                    
                        $response = ['type' => "success", 'message' => "Passport information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    case 4:
                        
                        $previously_participated = $request->get('previously_participated');
                        
                        $rules = [
                            'previously_participated' => 'required',
                        ];
                        $validationErrorMessages = [
                            'previously_participated.required' => "Have you participated in a J-1 Programs in the past? field is required",
                        ];
                        
                        if($previously_participated == 1)
                        {
                            $rules = [
                                'j1_recent_program_name' => 'required',
                                'j1_recent_program_start' => 'required|date',
                                'j1_recent_program_end' => 'required|date',
                            ];
                            
                            if(!empty($request->j1_old_program_name) || !empty($request->j1_old_program_start) || !empty($request->j1_old_program_end))
                            {
                                $rules['j1_old_program_name'] = "required";
                                $rules['j1_old_program_start'] = "required|date";
                                $rules['j1_old_program_end'] = "required|date";
                            }
                            $validationErrorMessages = [
                                'j1_recent_program_name.required' => "Recent Program Name field is required",
                                'j1_recent_program_start.required' => "Recent Program Start Date field is required",
                                'j1_recent_program_start.date' => "Recent Program Start Date field is invalid",
                                'j1_recent_program_end.required' => "Recent Program End Date field is required",
                                'j1_recent_program_end.date' => "Recent Program End Date field is invalid",
                                'j1_old_program_name.required' => "Old Program Name field is required",
                                'j1_old_program_start.required' => "Old Program Start Date field is required",
                                'j1_old_program_start.date' => "Old Program Start Date field is invalid",
                                'j1_old_program_end.required' => "Old Program End Date field is required",
                                'j1_old_program_end.date' => "Old Program End Date field is invalid",
                            ];
                        }
                        
                        if(!empty($rules))
                        {
                            $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                            if ($validator->fails()) {
                                return Response::json([
                                    'type' => "validation_error",
                                    'message' => $validator->messages()->toArray(),
                                ]);
                            }
                        }
                      
                        $user_general->previously_participated = $request->get('previously_participated', "");
                        $user_general->j1_first_name = $request->get('j1_recent_program_name',"");
                        $user_general->j1_first_started = (!empty($request->j1_recent_program_start))?Carbon::parse($request->j1_recent_program_start)->format(DB_DATE_FORMAT):"";
                        $user_general->j1_first_ended = (!empty($request->j1_recent_program_end))?Carbon::parse($request->j1_recent_program_end)->format(DB_DATE_FORMAT):"";
                        $user_general->j2_second_name = $request->get('j1_old_program_name',"");
                        $user_general->j2_second_started = (!empty($request->j1_old_program_start))?Carbon::parse($request->j1_old_program_start)->format(DB_DATE_FORMAT):"";
                        $user_general->j2_second_ended = (!empty($request->j1_old_program_end))?Carbon::parse($request->j1_old_program_end)->format(DB_DATE_FORMAT):""; 
                        $user_general->save();
                        
                        $response = ['type' => "success", 'message' => "Previous program information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    case 5:
                        
                        $marital_status = $request->get('marital_status');
                        $rules = [
                            'marital_status' => 'required',
                        ];

                        $validationErrorMessages = [
                            'marital_status.required' => 'Marital Status field is required.', 
                        ];
                        
                        if($marital_status == 2)
                        {
                            $rules = [
                                'spouse_dep_needs_j2' => 'required',
                                'spouse_dep_last_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|different:spouse_dep_first_name',
                                'spouse_dep_first_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
                                'spouse_dep_middle_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
                                'spouse_dep_gender' => 'required',
                                'spouse_dep_birth_date' => 'required|date',
                                'spouse_dep_birth_city' => 'required',
                                'spouse_dep_birth_country' => 'required',
                                'other_dependants' => 'required',
                                'spouse_dep_us_entry_together' => 'required',
                            ];

                            $validationErrorMessages = [
                                'spouse_dep_needs_j2.required' => 'Will your spouse need J-2 Visa to enter U.S.? field is required.', 
                                'spouse_dep_last_name.required' => 'Spouse Family Name field is required.',
                                'spouse_dep_last_name.regex' => 'Spouse Last Name does not allow any special character.',
                                'spouse_dep_last_name.different' => 'Spouse Last Name not same as Spouse First Name.',
                                'spouse_dep_first_name.regex' => 'Spouse First Name does not allow any special character.',
                                'spouse_dep_middle_name.regex' => 'Spouse Middle Name does not allow any special character.',                                
                                'spouse_dep_first_name.required' => 'Spouse Given Name field is required.', 
                                'spouse_dep_middle_name.required' => 'Spouse Middle Name field is required.', 
                                'spouse_dep_gender.required' => 'Spouse Gender field is required.', 
                                'spouse_dep_birth_date.required' => 'Spouse Birth date field is required.', 
                                'spouse_dep_birth_date.date' => 'Spouse Birth date is invalid.', 
                                'spouse_dep_birth_city.required' => 'Spouse City of Birth field is required.', 
                                'spouse_dep_birth_country.required' => 'Spouse Country of Birth field is required.', 
                                'other_dependants.required' => 'Do you have any other dependents? field is required.', 
                                'spouse_dep_us_entry_together.required' => 'Spouse enters U.S. at same time with you.', 
                                'spouse_dep_entry_date.required' => 'If not same time, date spouse enters U.S field is required', 
                                'spouse_dep_entry_date.date' => 'If not same time, date spouse enters U.S date is invalid', 
                            ];
                            
                            $spouse_dep_us_entry_together = $request->get('spouse_dep_us_entry_together');
                            if($spouse_dep_us_entry_together == 2)
                            {
                                $rules['spouse_dep_entry_date'] = "required|date";
                            }
                        }
                        
                        if(!empty($rules))
                        {
                            $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                            if ($validator->fails()) {
                                return Response::json([
                                    'type' => "validation_error",
                                    'message' => $validator->messages()->toArray(),
                                ]);
                            }
                        }

                        $user_general->material_status = $request->get('marital_status', "");
                        $user_general->spouse_dep_needs_j2 = $request->get('spouse_dep_needs_j2',"");
                        $user_general->spouse_dep_last_name = $request->get('spouse_dep_last_name',"");
                        $user_general->spouse_dep_first_name = $request->get('spouse_dep_first_name',"");
                        $user_general->spouse_dep_middle_name = $request->get('spouse_dep_middle_name',"");
                        $user_general->spouse_dep_gender = $request->get('spouse_dep_gender',"");
                        $user_general->spouse_dep_birth_date = (!empty($request->spouse_dep_birth_date))?Carbon::parse($request->spouse_dep_birth_date)->format(DB_DATE_FORMAT):"";
                        $user_general->spouse_dep_birth_city = $request->get('spouse_dep_birth_city',"");
                        $user_general->spouse_dep_birth_country = $request->get('spouse_dep_birth_country',"");
                        $user_general->other_dependants = $request->get('other_dependants',"");
                        $user_general->spouse_dep_us_entry_together = $request->get('spouse_dep_us_entry_together',"");
                        $user_general->spouse_dep_entry_date = (!empty($request->spouse_dep_entry_date))?Carbon::parse($request->spouse_dep_entry_date)->format(DB_DATE_FORMAT):""; 
                        $user_general->save();
                    
                        $response = ['type' => "success", 'message' => "Spouse information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    case 6:
                        
                        $currently_student = $request->get('currently_student');
                        $rules = [
                            'currently_student' => 'required',
                        ];
                        $validationErrorMessages = [
                            'currently_student.required' => 'Are you currently a full time student? field is required.', 
                        ];
                        
                        if($currently_student == 1){
                            $rules = [
                                'institution' => 'required',
                                'institution_type' => 'required',
                                'field_studied' => 'required',
                                'study_level' => 'required',
                                'program_start' => 'required|date',
                                'program_end' => 'required|date',
                            ];

                            $validationErrorMessages = [
                                'institution.required' => 'Educational institution last or presently attending field is required.', 
                                'institution_type.required' => 'Educational institution type field is required.', 
                                'field_studied.required' => 'Field studied / presently studying field is required.', 
                                'study_level.required' => 'Study level field is required.', 
                                'program_start.required' => 'Date started studying field is required.', 
                                'program_end.required' => 'Date Graduated field is required.', 
                                'program_start.date' => 'Date started studying date is invalid.', 
                                'program_end.date' => 'Date Graduated date is invalid.', 
                            ];
                        }
                        
                        if(!empty($rules))
                        {
                            $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                            if ($validator->fails()) {
                                return Response::json([
                                    'type' => "validation_error",
                                    'message' => $validator->messages()->toArray(),
                                ]);
                            }
                        }
                        
                        $user_general->currently_student = $request->get('currently_student', "");
                        $user_general->institution = $request->get('institution',"");
                        $user_general->institution_type = $request->get('institution_type',"");
                        $user_general->field_studied = $request->get('field_studied',"");
                        $user_general->study_level = $request->get('study_level',"");
                        $user_general->program_start = (!empty($request->program_start))?Carbon::parse($request->program_start)->format(DB_DATE_FORMAT):"";
                        $user_general->program_end = (!empty($request->program_end))?Carbon::parse($request->program_end)->format(DB_DATE_FORMAT):"";
                        $user_general->advance_completed = $request->get('advance_completed',"");
                        $user_general->experience_year = $request->get('experience_year',"");
                        $user_general->save();
                    
                        $response = ['type' => "success", 'message' => "Education information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    case 7:
                        
                        $currently_employed = $request->get('currently_employed');
                        $rules = [
                            'currently_employed' => 'required',
                        ];

                        $validationErrorMessages = [
                            'currently_employed.required' => 'Are you Currently Employed? field is required.', 
                        ];
                        
                        if($currently_employed == 1)
                        {
                            $rules = [
                                'employer_name' => 'required',
                                'employer_address' => 'required',
                                'total_employees' => 'required',
                                'position' => 'required',
                                'sup_name' => 'required',
                                'employer_phone' => 'required',
                                'employer_fax' => 'required',
                                'computer_programs' => 'required',
                                'emp_start_date' => 'required',
                            ];

                            $validationErrorMessages = [
                                'employer_name.required' => 'Name of company field is required.', 
                                'employer_address.required' => 'Company address field is required.', 
                                'total_employees.required' => 'Total Number of employees field is required.', 
                                'position.required' => 'Your Training Position field is required.', 
                                'sup_name.required' => 'Full Name of supervisor / owner field is required.', 
                                'employer_phone.required' => 'Phone Number field is required.', 
                                'employer_fax.required' => 'Fax Number field is required.', 
                                'computer_programs.required' => 'Computer program skills field is required.', 
                                'emp_start_date.required' => 'Employment start date field is required.', 
                            ];
                            
                        }

                        if(!empty($rules))
                        {
                            $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                            if ($validator->fails()) {
                                return Response::json([
                                    'type' => "validation_error",
                                    'message' => $validator->messages()->toArray(),
                                ]);
                            }
                        }
                        
                        $user_general->currently_employed = $request->get('currently_employed');
                        $user_general->employer_name = $request->get('employer_name',"");
                        $user_general->employer_address = $request->get('employer_address',"");
                        $user_general->total_employees = $request->get('total_employees',"");
                        $user_general->position = $request->get('position',"");
                        $user_general->sup_name = $request->get('sup_name',"");
                        $user_general->employer_phone = $request->get('employer_phone',"");
                        $user_general->employer_fax = $request->get('employer_fax',"");
                        $user_general->computer_programs = $request->get('computer_programs',"");
                        $user_general->emp_start_date = (!empty($request->emp_start_date))?Carbon::parse($request->emp_start_date)->format(DB_DATE_FORMAT):"";
                        $user_general->save();
                    
                        $response = ['type' => "success", 'message' => "Experience information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    case 8:
                        
                          $rules = [
                            'contact_name_first' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
                            'contact_name_last' => 'required|different:contact_name_first|regex:/(^[A-Za-z0-9 ]+$)+/',
                            'contact_phone' => 'required',
                            'contact_relationship' => 'required',
                            'contact_country' => 'required',
                            'contact_english_speaking' => 'required',
                            'contact_language' => 'required',
                            'contact_email' => 'required|email',
                        ];

                        $validationErrorMessages = [
                            'contact_name_first.required' => 'Emergency Contact First Name field is required.',
                            'contact_name_first.regex' => 'First name does not allow any special character.',
                            'contact_name_last.regex' => 'Last Name does not allow any special character.',
                            'contact_name_last.different' => 'First Name and Last Name should not be same.',
                            'contact_name_last.required' => 'Emergency Contact Last Name field is required.', 
                            'contact_phone.required' => 'Phone Number field is required.', 
                            'contact_relationship.required' => 'Relationship field is required.', 
                            'contact_country.required' => 'Contact Location Country field is required.', 
                            'contact_english_speaking.required' => 'Contact is English Speaking field is required.', 
                            'contact_language.required' => 'Language spoken field is required.', 
                            'contact_email.required' => 'Email Address field is required.', 
                        ];

                        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                        if ($validator->fails()) {
                            return Response::json([
                                'type' => "validation_error",
                                'message' => $validator->messages()->toArray(),
                            ]);
                        }
                        
                        $user_general->contact_name_first = $request->get('contact_name_first');
                        $user_general->contact_name_last = $request->get('contact_name_last',"");
                        $user_general->contact_phone = $request->get('contact_phone',"");
                        $user_general->contact_phone_alternative = $request->get('contact_phone_alternative',"");
                        $user_general->contact_relationship = $request->get('contact_relationship',"");
                        $user_general->contact_country = $request->get('contact_country',"");
                        $user_general->contact_english_speaking = $request->get('contact_english_speaking',"");
                        $user_general->contact_language = $request->get('contact_language',"");
                        $user_general->contact_email = $request->get('contact_email',"");
                        $user_general->save();
                    
                        $response = ['type' => "success", 'message' => "Emergency contact information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    case 9:
                        
                        $criminal_record = $request->get('criminal_record');
                        $rules = [
                            'criminal_record' => 'required',
                        ];

                        $validationErrorMessages = [
                            'criminal_record.required' => 'Have you ever been convicted of a crime? field is required.', 
                        ];
                        
                        if($criminal_record == 1){
                            $rules = [
                                'criminal_explanation' => 'required',
                            ];

                            $validationErrorMessages = [
                                'criminal_explanation.required' => 'If you selected yes Please explain field is required.', 
                            ];
                        }
                        

                        if(!empty($rules))
                        {
                            $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                            if ($validator->fails()) {
                                return Response::json([
                                    'type' => "validation_error",
                                    'message' => $validator->messages()->toArray(),
                                ]);
                            }
                        }
                        
                        $user_general->criminal_record = $request->get('criminal_record');
                        $user_general->criminal_explanation = $request->get('criminal_explanation',"");
                        $user_general->save();
                        
                        $response = ['type' => "success", 'message' => "Criminal information saved", 'btn_action' => $btn_action, 'action_step' => $addinfo_form_step ];
                    break;

                    default:
                        $response = ['type' => "error", 'message' => "Failed to update additional information."];
                    break;
                }

                $addinfo_lock = 1;
                $addinfo_collection = collect($user->portfolio->userGeneral)->all();
                
                foreach($addinfo_collection as $key => $value)
                {
                    if(in_array($key,$required_field_list) && empty($value)){
                        $addinfo_lock = 0;
                        break;
                    }
                }
                
                if($addinfo_lock == 1){
                    $user_general->lock_additional_info = $addinfo_lock;
                    $user_general->save();
                    $this->changeUserStatus($user, "additional-information-saved");
                }
            }
        }
        else
        {
            $response = ['type' => "error", 'message' => "Failed to update additional information."];
        }
        
        return Response::json($response);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function agencyContractRequestAction(Request $request)
    {
        $user = auth()->user();
        $contract = $request->get("contract","");
        $agency = $request->get("agency","");
        $btn_action = $request->get('btn_action','');
        $active_step_key = $request->get('active_step_key','');
        
        if(!empty($user) && !empty($contract) && !empty($agency))
        {
            $portfolio = $user->portfolio;
            $request->is('api/*') ? $agency_id = $agency : $agency_id = decrypt($agency);
            $request->is('api/*') ? $contract_id = $contract : $contract_id = decrypt($contract);
            
            $contracts = $portfolio->agencyContracts();
            
            $action_contract = $contracts->with('agency')->where(['id' => $contract_id, 'agency_id' => $agency_id])->first();
            if(!empty($action_contract)){
                
                if($btn_action == "accept"){
                    $user->setAgency($action_contract->id);
                    
                    if($active_step_key == "2_contract_placement"){
                        $this->changeUserStatus($user, "placement-contract");
                        $contract_type = ($action_contract->agency->agency_type == 4)?"2,4":"2";
                    }
                    else if($active_step_key == "3_contract_sponsor"){
                        $this->changeUserStatus($user, "sponsor-contract");
                        $contract_type = ($action_contract->agency->agency_type == 4)?"3,4":"3";
                    }

                    $agencyContracts = $portfolio->agencyContracts();
                    $agencyContracts->where('is_expired', 0)
                            ->where('request_status', 1)
                            ->whereIn('contract_type', $contract_type)
                            ->update(['is_expired' => 1]);

                    UserLog::log($user,"contract-accepted");

                    return ['type' => "success", 'message' => "Agency contract accepted successfully"];
                }
                else if($btn_action == "reject"){
                    $action_contract->update(['request_status' => 3, 'is_expired' => 1]);
                    
                    UserLog::log($user,"contract-rejected");
                    
                    return ['type' => "success", 'message' => "Agency contract rejected successfully."];
                }
            }
            
            return ['type' => "error", 'message' => "Failed to update contract request."];
        }
        else
            return ['type' => "error", 'message' => "Failed to update contract request."];
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function agencyContractRequest(Request $request)
    {
        $user = auth()->user();
        $agency = $request->get("agency","");
        
        if(!empty($user) && !empty($agency))
        {
            $portfolio = $user->portfolio;
            $request->is('api/*') ? $agency_id = $agency : $agency_id = decrypt($agency);
            
            $agency_data = Agency::where('id',$agency_id)->first();
            
            if(!empty($agency_data)){
                $agency_id = $agency_data->id;
                $contract_type = $agency_data->agency_type;
                
                $agencycontract = AgencyContract::create(['agency_id' => $agency_id,
                                                        'user_id' => $user->id,
                                                        'portfolio_id' => $portfolio->id,
                                                        'contract_type' => $contract_type,
                                                        'request_status' => 1,
                                                        'request_by' => 2,
                                                        'is_expired' => 0]);
            
                UserLog::log($user,"contract-request-sent");
                
                return ['type' => "success", 'message' => "Request sent successfully."];
            }
        }

        return ['type' => "error", 'message' => "Failed to send request."];
    }
    
    public function uploadDocumentInstruction(Request $request){
        
        if(!empty($request->doc_req_id) && is_numeric($request->doc_req_id))
        {
            $doc_req_data = DocumentRequirement::where('id',$request->doc_req_id)->first();
            if(!empty($doc_req_data) && !empty($doc_req_data->document_template))
            {
                $doc_template_dir = "document-template".DS.$doc_req_data->agency_id.DS; 
                $doc_template_path = $doc_template_dir.$doc_req_data->document_template;
                if(!empty($doc_template_path) && !empty(Storage::disk('public')->exists($doc_template_path))){ 
                    $doc_id = encrypt($request->doc_req_id);
                    $data['download_template_link'] = route("download",["ddt",$doc_id]);
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
            }
        } 
        $data['action'] = "doument_instruction";
        $HTML = view('common.common-ajax')->with($data)->render();
        if($request->is('api/*')) {
            return ["type" => "success", "message" => "", "data" => $data];
        }
        else{
            return ["type" => "success", "message" => "", "data" => $HTML];
        }
    }
    
    public function documentHistory(Request $request){
        
        $user = auth()->user();
        
        if(!empty($user) && !empty($request->doc_type) && is_numeric($request->doc_type))
        {
            $doc_type_data = DT::where('id',$request->doc_type)->select('name')->first(); 
            $data['doc_type_data'] = $doc_type_data;
            $document_history = $user->portfolio->getUserDocumentByType($request->doc_type);
            
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
                        $document_history[$key]->document_download_link = route("download",['dd',$doc_id]);
                    }
                }
            }
            $data['document_history'] = $document_history;
        }
        $data['action'] = "view_document_history";
        $HTML = view('common.common-ajax')->with($data)->render();
        if($request->is('api/*')) {
            return ["type" => "success", "message" => "", "data" => $data];
        }
        else{
            return ["type" => "success", "message" => "", "data" => $HTML];
        }
    }
    
    public function supportingDocumentUploaded(Request $request){
        $user = auth()->user();
        if(!empty($user) && !empty($user->id))
        {
            $user_id = $user->id;
            $next_timeline_order_key = "2_searching_position";
                    
            $as = new AppStatus;
            if($as->checkChronologicalOrder($user, $next_timeline_order_key))
            {
                $this->changeUserStatus($user, "supporting-document-collected");
                return ['type' => "success"];
            }
            return ['type' => "error", 'message' => "Failed to process"];
        }
        else
            return ['type' => "error", 'message' => "Failed to process"];
    }
    
    public function uploadSupportingDocument(Request $request){
        
        $response = array();
        $user = auth()->user();
        
        if(!empty($user) && !empty($user->id))
        {
            if($user->is_timeline_locked == 1)
            {
                $response = ['type' => "warning", 'message' => "You cannot upload any documents because your application is locked, however you can download uploaded documents."];
            }
            else
            {
                $user_id = $user->id;
                
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
                
                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

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

                    $document_type = $request->get('document_type',"");
                    
                    $doc_key = DT::getKeyById($document_type);
                    $document_sent = $this->uploadDocument($user, $request->document_file, $doc_key,0,1,true);
            
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
    
    public function sponsorUpdated(Request $request){
        $user = auth()->user();
        if(!empty($user) && !empty($user->id))
        {
            $user_id = $user->id;
            $next_timeline_order_key = "3_contract_sponsor";
                    
            $as = new AppStatus;
            if($as->checkChronologicalOrder($user, $next_timeline_order_key))
            {
                $this->changeUserStatus($user, "placed");
                return ['type' => "success"];
            }
            return ['type' => "error", 'message' => "Failed to process"];
        }
        else
            return ['type' => "error", 'message' => "Failed to process"];
            
    }
    
    public function visaStage(Request $request) 
    {
        $action = $request->action;
        $user = auth()->user();
        $user_id = $user->id;
        $user_general = $user->userGeneral();
        
        switch ($action)
        {
            case "embassy_interview":
                
                $rules = array(
                    'embassy_interview_date' => "required|date|after_or_equal:today",
                    'embassy_timezone' => "required",
                );
                $validationErrorMessages = [
                    'embassy_interview_date.required' => "Embassy interview date is required.",
                    'embassy_interview_date.date' => "Embassy interview date is invalid, please use calendar.",
                    'embassy_interview_date.after_or_equal' => "Embassy interview date should be greater or equal today.",
                    'embassy_timezone.required' => "Local timezone is required.",
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                if ( $validator->fails())
                {
                    $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                    return Response::json($response);
                }
                
                /*$user_general = $user->userGeneral();*/
                $user_general->embassy_interview = Carbon::parse($request->embassy_interview_date)->format(DB_DATETIME_FORMAT);
                $user_general->embassy_timezone = $request->embassy_timezone;
                $user_general->save();
                
                $inserted_id = $user_general->id;
                $this->changeUserStatus($user, "embassy-interview-scheduled");
                
                if($inserted_id > 0)
                {
                    $message =  "US Embassy interview scheduled successfully.";
                    $response = ["type" => "success", "message" => $message];
                    return Response::json($response);
                }
                else
                {
                    $response = ["type" => "error", "message" => ["Something went wrong. Please try again."]];
                    return Response::json($response);
                }
            break;
            
            case "visa_outcome":
                $visa_status = $request->visa_status;
                
                switch($visa_status) 
                {
                    case "visa_approved":
                        $this->changeUserStatus($user, "visa-approved");
                        $message = "We recorded your visa is approved.";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "green_form":
                        $this->changeUserStatus($user, "221g-letter-received-green-form");
                        $message = "We recorded your 221(g) Letter received (Green Form).";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "admin_process":
                        $this->changeUserStatus($user, "under-administrative-processing");
                        $message = "We recorded your visa is under administrative processing."; 
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "visa_denied":
                        $user_general->visa_denied_count = ($user_general->visa_denied_count)+1;
                        $user_general->save();
                        session(['visa_denied_undo' => 0]);
                        $this->changeUserStatus($user, "visa-denined");
                        $message = "We have recorded the outcome of your US Embassy's J-1 visa interview.";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "reschedule_appointment":
                        $deniedOrder       = ($user_general->visa_denied_count >= 2) ? 2 : 1;
                        $NewStatus         = ($deniedOrder == 2) ? "visa-denied-embassy-lock" : "visa-denied-back-embassy";

                        $user_general->embassy_interview = "0000-00-00 00:00:00";
                        $user_general->embassy_timezone = "";
                        $user_general->save();

                        $this->changeUserStatus($user, $NewStatus);
                        UserLog::log($user,null,"Candidate apply for another interview.");
                        
                        $message = "We have taken note of your desire to schedule another interview at the US Embassy. You will receive an email shortly to communicate ";
                        $message .= ($deniedOrder == 2)?"for further process.":"to us the date and time of this interview.";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status,"denied_order"=>$deniedOrder];
                    break;

                    case "quit_program":
                        $this->changeUserStatus($user, "visa-denied-quit-program");
                        $message = "We have recorded your decision to not schedule another US Embassy interview.";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "visa_denied_undo":
                        $user_general->visa_denied_count = !empty($user_general->visa_denied_count) ? ($user_general->visa_denied_count)-1 : 0;
                        $user_general->save();
                        session(['visa_denied_undo' => 1]);
                        $message = "Wait while you are redirecting back..."; 
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    default:
                        $response = ["type" => "error", "message" => "Failed to update Visa Status"];
                    break;
                }
                return Response::json($response);
            break;
        
            case "flight_info":
                $rules = [
                    'arrival_airport' => 'required',
                    'departure_date' => 'date:m/d/Y H:i|nullable',
                    'arrival_timezone' => 'required',
                    'arrival_date' => 'date:m/d/Y H:i|required',
                ];

                $validationErrorMessages = [
                    'arrival_airport.required' => 'Arrival Airport field is required.',
                    'arrival_timezone.required' => 'Arrival Timezone field is required.', 
                    'arrival_date.required' => 'Arrival Date field is required.', 
                    'departure_date.date' => 'Departure Date is not a valid date.',
                    'arrival_date.date' => 'Arrival Date is not a valid date.',
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

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
                
                $flight_data = FlightInfo::where('portfolio_id',$user->portfolio_id)->first();
                
                if(empty($flight_data))
                {
                    $flight_data = new FlightInfo;
                }

                $flight_data->arrival_airport = $request->arrival_airport;
                $flight_data->airline = $request->airline;
                $flight_data->portfolio_id = $user->portfolio_id;
                $flight_data->user_id = $user_id;
                $flight_data->flight = $request->flight;
                $flight_data->departure_timezone = $request->departure_timezone;
                $flight_data->departure_date = Carbon::parse($request->departure_date)->format(DB_DATETIME_FORMAT);
                $flight_data->arrival_timezone = $request->arrival_timezone;
                $flight_data->arrival_date = Carbon::parse($request->arrival_date)->format(DB_DATETIME_FORMAT);
                $flight_data->additional_info = $request->additional_info;
                $flight_data->save();
                $inserted_id = $flight_data->id;
                
                if($inserted_id>0)
                {
                    $this->changeUserStatus($user, "arrival-scheduled");
                    $message =  "Flight info added successfully.";
                    $response = ["type" => "success", "message" => $message];
                    return Response::json($response);
                }
                else
                {
                    $response = ["type" => "error", "message" => ["Failed to update arrival flight information."]];
                    return Response::json($response);
                }
            break;
            
        }
        
    }
     
}
