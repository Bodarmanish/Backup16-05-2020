<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApplicationStatus as AppStatus;
use App\Models\User;
use App\Models\Document;
use App\Models\PositionType;
use Illuminate\Support\Facades\Validator;
use Response;
use Carbon\Carbon;
use App\Models\AgencyContract;
use App\Models\Timezone; 
use App\Models\DocumentRequirement;
use App\Models\Position;
use App\Models\Lead;
use App\Models\Placement;
use App\Models\UserLog;
use App\Models\Legal;
use App\Models\FlightInfo;

class ApplicationStatusController extends Controller
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
        $this->as = new AppStatus;
        $this->timezone = new Timezone;
        $this->doc_req = new DocumentRequirement;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $admin = auth()->user();
        $params = $request->except('_token');
        if($admin->role_name == 'agency-admin'){
            $params['agency_id'] = $admin->agency_id;
            $params['agency_type'] = $admin->agency_type; 
        }
        $users = User::filter($params)->get(); 
        $data = [
                'users' => $users, 
            ];
        if ($request->is('api/*')) {
            return apiResponse("success","",$data);
        }
        return view('admin.user-application-list')->with($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function appProgress(Request $request)
    {
        $user_id = user_token();
        $user = User::where("id",$user_id)->first();
        $stages = AppStatus::getStages();
        
        $first_stage = (object) collect($stages)->first();
        $active_stage = (!empty($first_stage->stage_id)) ? $first_stage->stage_id : 1;      
        $active_stage_key = (!empty($active_stage)) ? $stages[$active_stage]['stage_key'] : $stages[1]['stage_key'];
        $current_order_key = (!empty($user->portfolio->as_order_key)) ? $user->portfolio->as_order_key: '';
        $current_step_data = $this->as->getCurrentStep($user, $current_order_key);
        if(!empty($current_step_data)){
            $active_stage = $current_step_data->as_stage_number;
            $active_stage_key = (!empty($active_stage)) ? $stages[$active_stage]['stage_key'] : $stages[1]['stage_key'];
        } 
        $step_list = $this->as->getStepsByStage($user, $active_stage, $current_step_data);
        $portfolio_key = $user->portfolio->as_order_key; 
        
        $active_step = 1;
        $active_step_key = (!empty(collect($step_list)->first()->as_order_key)) ?  collect($step_list)->first()->as_order_key : "";

        $current_step_data = $this->as->getCurrentStep($user, $active_step_key);
        $active_step_content = $this->getStepContent($user, $active_step_key);
        
        if(!empty($current_step_data) && !empty($active_step_key)){
            $data = [
                'action' => "navigate_stage",
                'step_list' => $step_list,
                'app_status_stages' => $stages, 
                'active_stage' => $active_stage, 
                'active_stage_key' => $active_stage_key, 
                'active_step' => $active_step,
                'active_step_key' => $active_step_key,
                'active_step_content' => $active_step_content,
                'current_step_data' => $current_step_data,
                'user_id' => $user_id,
                'portfolio_key' => $portfolio_key,
                'email' => $user->email,
                'full_name' => $user->first_name." ".$user->last_name,
            ]; 
        }
        else{
            $data = [];
        }
        if ($request->is('api/*')) {
            return apiResponse("success","",$data);
        }
        return view("admin.app-status.application-progress")->with($data);
        
    }
    
    /**
     * step_status => 0 = Disable, 1 = Done
     * 
     * **/
    public function getStepContent(User $user, $step_order_key)
    {
        $admin = auth()->user();
        if(!empty($user) && !empty($step_order_key)){
            $portfolio = $user->portfolio;
            $userGeneral = @$portfolio->userGeneral; 
            $userProgram = @$portfolio->program; 
            $stages = AppStatus::getStages();
            
            $current_step_data = $this->as->getCurrentStep($user);
            $request_step_data = $this->as->getCurrentStep($user, $step_order_key);
            
            $stage_number = $request_step_data->as_stage_number;
            $step_number = $request_step_data->as_order;
            
            $step_verified_data = array(); 
            $step_verified_data['step_status'] = 0; 
            if(($step_number <= $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
            {
                $step_verified_data['step_status'] = 1;
            }
            
            switch($step_order_key) {
                
                case "1_eligibility_test":
                    
                    if(!empty($userGeneral->eligibility_test_output)){ 
                        $step_verified_data['is_step_success'] = 1;
                        $step_verified_data['industry_selected'] = @$userGeneral->industry_selected;
                        $step_verified_data['program_name'] = @$userProgram->program_name;
                    }
                
                break; 
                case "1_resume_upload":
                case "1_resume_approval":
                
                    $document = $this->doc->getDocumentByType($user, 'resume'); 
                    if($step_order_key == "1_resume_upload"){
                        if(!empty($document)) {
                            $document_name = $document->documentType->name;
                            if($document->document_status == 1) {
                                $step_verified_data['is_step_success'] = 1;
                                $step_verified_data['type'] = "success";
                                $step_verified_data['message'] = "Thank you! user {$document_name} has been approved."; 
                            }
                            else if($document->document_status != 2) {
                                $step_verified_data['is_step_success'] = 2;
                                $step_verified_data['type'] = "warning";
                                $step_verified_data['message'] = "Thank you! user {$document_name} is uploaded and approval is in progress.";  
                            }else{
                                $step_verified_data['is_step_success'] = 0;
                                $step_verified_data['type'] = "warning";
                                $step_verified_data['message'] = "User {$document_name} is rejected. Please upload it again.";
                            } 
                        }
                    }
                    else if($step_order_key == "1_resume_approval"){
                        if(!empty($document)) {
                            $document_name = $document->documentType->name;
                            if($document->document_status == 1) {
                                $step_verified_data['is_step_success'] = 1;
                                $step_verified_data['type'] = "success";
                                $step_verified_data['message'] = "Your {$document_name} is approved successfully.";
                            }
                            else if($document->document_status == 2) {
                                $step_verified_data['is_step_success'] = 2;
                                $step_verified_data['type'] = "warning";
                                $step_verified_data['message'] = "Uploaded {$document_name} is rejected.<br> {$document->document_reject_reason}"; 
                            }
                            $step_verified_data['document_data'] = $document;
                        }
                    }
                
                break;
            
                case "1_skype":   
                     
                    if(!empty($userGeneral->skype_id)) {
                        $step_verified_data['skype_id'] = $userGeneral->skype_id;
                    }
                
                break;
                
                case "1_j1_interview":
                
                    $step_verified_data['is_step_success'] = 0;
                    $interview_data = $user->interview;
                    if(!empty($interview_data)){
                        $interview_data['interview_schedule_admin'] = $interview_data->interviewScheduleBy;
                        $interview_data['interviewed_admin'] = $interview_data->interviewedBy;
                        $admin_timezone_data = $this->timezone->getFullZoneLabel($interview_data->time_zone_admin);                 
                        $interview_data['admin_timezone_name'] = $admin_timezone_data->zone_label;
                        
                        $user_timezone_data = $this->timezone->getFullZoneLabel($interview_data->time_zone_user);
                        $interview_data['user_timezone_name'] = @$user_timezone_data->zone_label;
                
                        if($interview_data->interview_status==2){
                            $step_verified_data['is_step_success'] = 2; 
                        }
                        else{
                            $step_verified_data['is_step_success'] = 1;
                        }
                        $step_verified_data['interview_data'] = $interview_data;
                        $position_types = PositionType::where("status",1)->get();
                        if(!empty($position_types)){
                            $step_verified_data['position_types'] = $position_types;
                        }
                    } 
                break;
                
                case "1_j1_agreement":  
                    $document = $this->doc->getDocumentByType($user, 'j1_agreement');
                    $step_verified_data['is_step_success'] = 0;
                    if(!empty($document)) {
                        $document_name = $document->documentType->name;
                        if($document->document_status == 1) {
                            $step_verified_data['is_step_success'] = 1;
                            $step_verified_data['type'] = "success";
                            $step_verified_data['message'] = "User {$document_name} has been approved.";
                        }
                        else if($document->document_status != 2) {
                            $step_verified_data['is_step_success'] = 2;
                            $step_verified_data['type'] = "warning";
                            $step_verified_data['message'] = "{$document_name} is uploaded. Please review it.";
                        }else{ 
                            $step_verified_data['is_step_success'] = 0;
                            $step_verified_data['type'] = "warning";
                            $step_verified_data['message'] = "{$document_name} is rejected. Please upload it again.";
                        }
                        $step_verified_data['document_data'] = $document;
                    }
                
                break;
                
                case "1_registration_fee":
                    $J1interview = $user->interview;
                    if(!empty($J1interview)){
                        if(!empty($J1interview->reg_fee_status)){
                            $reg_fee_status = $J1interview->reg_fee_status;
                            if(in_array($reg_fee_status,[1,2])){
                                $step_verified_data['reg_fee_status'] = $reg_fee_status; 
                            }
                        }
                    }
                break;
                
                case "1_additional_info": 
                    if(!empty($user)){ 
                        $step_verified_data['user_info'] = $user;
                    }
                break;
                
                case "2_contract_placement":
                    if(!empty($portfolio->placement_agency_id)){
                        $placement_agency = $portfolio->placementAgency;
                        $step_verified_data['contract_status'] = 1; /* Contract already exist */
                        $step_verified_data['agency_data'] = $placement_agency;
                    }
                    else{
                        $query = $portfolio->agencyContracts()
                                        ->where('request_status', 1)
                                        ->where('request_by', 2);
                        if(!empty($admin->agency_id)){
                            $query->where('agency_id', $admin->agency_id);
                        }
                        $contract_data= $query->first();
                        
                        if(!empty($contract_data)){
                            $step_verified_data['user_email'] = $user->email;
                            $step_verified_data['contract_data']  = $contract_data;
                            $step_verified_data['contract_status'] = 2; /* Request send from user to loging agency */
                        }
                        else{
                            $query = $portfolio->agencyContracts()
                                        ->whereIn('request_status', [2,3])
                                        ->where('request_by', 2);
                            if(!empty($admin->agency_id)){
                                $query->where('agency_id', $admin->agency_id);
                            }
                            $contract_data= $query->first();
                            if(!empty($contract_data)){
                                $step_verified_data['contract_data']  = $contract_data;
                                $step_verified_data['contract_status'] = 3; /* Request accepted/rejected by loging agency */
                            } 
                        }
                    }
                    
                break;
                
                case "2_supporting_documents":
                    if(!empty($portfolio->placement_agency_id)){
                        $documents = $this->doc_req->getDocumentByDocSection($user,1,$portfolio->placement_agency_id);
                        $step_verified_data['documents']  = $documents['document_requirements'];
                        $step_verified_data['uploaded_documents']  = $documents['uploaded_documents'];
                        $step_verified_data['approved_documents']  = $documents['approved_documents'];
                        $step_verified_data['step_title'] = "Provide Supporting Documents";
                        $step_verified_data['step_key'] = "2_supporting_documents";
                        $step_verified_data['document_section_id'] = 1;
                        $req_doc = $this->doc_req->getRequiredDocBySec($user,1,$portfolio->placement_agency_id);
                        $step_verified_data['req_doc'] = $req_doc;
                    }
                break;
                
                case "2_searching_position":
                    $lead_data = $portfolio->leads()->with('hostCompany:id,hc_name','position')->get();
                    $program_enroll = !empty($portfolio->program->program_enroll_id) ? $portfolio->program->program_enroll_id : '';
                    $placed_data = $portfolio->placements()->with('position:id,pos_name','hostCompany:id,hc_name')->where('type',2)->get();
                    $step_verified_data['placed_data'] = (!empty($placed_data)) ? $placed_data : "";
                    $step_verified_data['lead_data'] = (!empty($lead_data)) ? $lead_data : "";
                    $step_verified_data['is_step_success'] = !empty($lead_data) ? 1 : 0;
                    $step_verified_data['is_route66'] = $portfolio->route66Program;
                    $step_verified_data['program_enroll'] = $program_enroll;
                break;
                
                case "2_booked":
                    $lead_data = $portfolio->leads()->with('hostCompany:id,hc_name','position')->get();
                    $booked_data = $portfolio->placements()->with('position:id,pos_name')->where('type',1)->get();
                    
                    if(!empty($lead_data))
                    {
                        $step_verified_data['lead_data'] = $lead_data;
                        $step_verified_data['booked_data'] = $booked_data;
                    }
                break;
                
                case "2_placed":
                    $program_enroll = !empty($portfolio->program->program_enroll_id) ? $portfolio->program->program_enroll_id : '';
                    $booked_data = $portfolio->placements()->with('position:id,pos_name','hostCompany:id,hc_name')->where('type',1)->first();
                    $placed_data = $portfolio->placements()->with('position:id,pos_name','hostCompany:id,hc_name')->where('type',2)->get();
                    
                    $step_verified_data['booked_data'] = (!empty($booked_data)) ? $booked_data : "";
                    $step_verified_data['placed_data'] = (!empty($placed_data)) ? $placed_data : "";
                    $step_verified_data['program_enroll'] = $program_enroll;
                    $step_verified_data['j1_status_id'] = $user->j1_status_id;
                break;
                
                case "3_contract_sponsor":
                    if(!empty($portfolio->sponsor_agency_id)){
                        $sponsor_agency = $portfolio->sponsorAgency;
                        $step_verified_data['contract_status'] = 1; /* Contract already exist */
                        $step_verified_data['agency_data'] = $sponsor_agency;
                    }
                    else{ 
                        $query = $portfolio->agencyContracts()
                                        ->where('request_status', 1)
                                        ->where('request_by', 2);
                        
                        if(!empty($admin->agency_id)){
                            $query->where('agency_id', $admin->agency_id);
                        }
                        $contract_data= $query->first();
                        
                        if(!empty($contract_data)){
                            $step_verified_data['user_email'] = $user->email;
                            $step_verified_data['contract_data']  = $contract_data;
                            $step_verified_data['contract_status'] = 2; /* Request send from user to loging agency */
                        }
                        else{
                            $query = $portfolio->agencyContracts()
                                        ->whereIn('request_status', [2,3])
                                        ->where('request_by', 2);
                            
                            if(!empty($admin->agency_id)){
                                $query->where('agency_id', $admin->agency_id);
                            }
                            $contract_data= $query->first();
                            
                            if(!empty($contract_data)){
                                $step_verified_data['contract_data']  = $contract_data;
                                $step_verified_data['contract_status'] = 3; /* Request accepted/rejected by loging agency */
                            } 
                        } 
                    }
                
                break;
                
                case "3_post_placement_documents":
                    if(!empty($portfolio->sponsor_agency_id)){
                        $documents = $this->doc_req->getDocumentByDocSection($user,2,$portfolio->sponsor_agency_id);
                        $step_verified_data['documents']  = $documents['document_requirements'];
                        $step_verified_data['uploaded_documents']  = $documents['uploaded_documents'];
                        $step_verified_data['approved_documents']  = $documents['approved_documents'];
                        $step_verified_data['step_title'] = "Post Placement Document";
                        $step_verified_data['step_key'] = "3_post_placement_documents";
                        $step_verified_data['document_section_id'] = 2;
                        $req_doc = $this->doc_req->getRequiredDocBySec($user,2,$portfolio->sponsor_agency_id);
                        $step_verified_data['req_doc'] = $req_doc;
                    }
                break;
                                
                case "3_ds7002_pending":
                    $step_verified_data['is_step_success'] = 0;
                    if(($step_number < $current_step_data->as_order && $stage_number == $current_step_data->as_stage_number) || $stage_number < $current_step_data->as_stage_number)
                    {
                        $step_verified_data['is_step_success'] = 1;
                    }
                break;
                
                case "3_ds7002_created":
                    $document = $this->doc->getDocumentByType($user, 'ds7002_template');
                    $step_verified_data['is_step_success'] = 0;
                    if(!empty($document)) {
                        $step_verified_data['document_data'] = $document;
                    }
                   
                break;
                
                case "3_ds7002_signed":
                    $document = $this->doc->getDocumentByType($user, 'training_plan_signed');
                    $step_verified_data['is_step_success'] = 0;
                    if(!empty($document)) {
                        if($document->document_status == 1) {
                            $step_verified_data['is_step_success'] = 1;
                            $step_verified_data['type'] = "success";
                            $step_verified_data['message'] = "User {$document->name} has been approved.";
                        }
                        else if($document->document_status != 2) {
                            $step_verified_data['is_step_success'] = 2;
                            $step_verified_data['type'] = "warning";
                            $step_verified_data['message'] = "{$document->name} is uploaded. Please review it.";
                        }
                        else{ 
                            $step_verified_data['is_step_success'] = 0;
                            $step_verified_data['type'] = "warning";
                            $step_verified_data['message'] = "{$document->name} is rejected. Please upload it again.";
                        }
                        $step_verified_data['document_data'] = $document;
                    }
                break;
            
                case "3_ds2019_sent":
                    $legal = $portfolio->legal()->first();
                    if(!empty($legal))
                    {
                        $step_verified_data['legal']  = $legal;
                        $step_verified_data['is_step_success'] = 1 ;
                    }
                break;
                
                case "3_us_embassy_interview":
                    $step_verified_data['is_step_success'] = 0 ;
                    if(!empty($userGeneral->embassy_interview) && !empty($userGeneral->embassy_timezone))
                    {
                        $step_verified_data['embassy_interview'] = $userGeneral->embassy_interview;
                        $step_verified_data['embassy_timezone'] =  $this->timezone->getFullZoneLabel($userGeneral->embassy_timezone);
                        $step_verified_data['is_step_success'] = 1 ;
                        $step_verified_data['embassy_timezone_id'] = $userGeneral->embassy_timezone;
                    }
                break;
                
                case "3_us_visa_outcome":
                    $step_verified_data['allow_visa_process'] = 0;
                    $embassy_interview_date = $userGeneral->embassy_interview;
                    $embassy_timezone = $userGeneral->embassy_timezone;
                    $visa_denied_count = $userGeneral->visa_denied_count;
                    $consecutive_visa_denied_flag = $userGeneral->consecutive_visa_denied_flag;
                    
                    if(!in_array($user->j1_status_id,[3013,5002,3012]) || session('visa_denied_undo') == 1)
                    {
                        if(!empty($embassy_interview_date) && substr($embassy_interview_date,0,4) > 1970)
                        {
                            $embassy_data = (object)[
                                'embassy_timezone' => $userGeneral->embassy_timezone,
                                'embassy_interview' => $userGeneral->embassy_interview,
                            ];
                            $step_verified_data['embassy_data'] = $embassy_data;

                            $current_datetime = Carbon::now()->format(DB_DATETIME_FORMAT);
                            if($embassy_data->embassy_interview < $current_datetime)
                            {
                                $step_verified_data['allow_visa_process'] = 1;
                            }

                            if(in_array($user->j1_status_id, [3010,3011]))
                            {
                                $step_verified_data['show_message'] = "We have already recorded the outcome of your US Embassy's ".__('application_term.exchange_visitor')." visa interview.";
                            }
                        }
                        
                        if($stage_number < $current_step_data->as_stage_number || ($stage_number == $current_step_data->as_stage_number && $step_number < $current_step_data->as_order))
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
                break;
                
                case "3_flight_info":
                    $arrival_timezone_list = $this->timezone->getTimezones('US');
                    $arrival_method = config('common.arrival_method');
                    $step_verified_data['arrival_timezone_list'] = $arrival_timezone_list;
           
                    $flight_data = $portfolio->flightInfo()->first();
                    $step_verified_data['is_step_success'] = 1;
                    if(!empty($flight_data))
                    {
                        $airport_data = $this->airports[array_search($flight_data->arrival_airport, array_column($this->airports, 'ap_abbr'))];
                        $dep_timezone_data = $this->timezones[array_search($flight_data->departure_timezone, array_column($this->timezones, 'zone_id'))];
                        $arr_timezone_data = $arrival_timezone_list[array_search($flight_data->arrival_timezone, array_column($arrival_timezone_list, 'zone_id'))];

                        $flight_data->airport_data = $airport_data;
                        $flight_data->dep_timezone_data = $dep_timezone_data;
                        $flight_data->arr_timezone_data = $arr_timezone_data;

                        $step_verified_data['flight_data'] = $flight_data;
                        $step_verified_data['is_step_success'] = 2;
                    }
                break;
                
                case "3_arrival_in_usa":
                    $flight_data = $portfolio->flightInfo()->first();
                    $step_verified_data['is_step_success'] = 0 ;
                
                    if(!empty($flight_data))
                    {
                        $arrival_timezone = $flight_data->arrival_timezone;
                        $arrival_date = $flight_data->arrival_date;
                        $timezone_data =  $this->timezone->getFullZoneLabel($arrival_timezone);
                        $flight_data = (object) collect($flight_data)->merge($timezone_data)->all();
                        $flight_data->is_date_passed = 0;
                        
                        $status_log = $portfolio->getLog()->where('action_status',4002)->latest()->first();
                        if(!empty($status_log)){
                            $flight_data->is_date_passed = 1;
                            $step_verified_data['is_step_success'] = 1;
                        }
                        
                        $step_verified_data['flight_data'] = $flight_data;
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
                        'portfolio' => $user->portfolio,
                        'userGeneral' => $portfolio->userGeneral,
                        'user_id' => $user->id,
                    ];
            $step_content = view('admin.app-status.application-status-stages')
                            ->with($data)
                            ->render();
            
            return $step_content;
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
        $user_id = user_token();
        $user = User::where('id',$user_id)->first();
        
        $portfolio_key = $user->portfolio->as_order_key;
        $current_step_data = $this->as->getCurrentStep($user);
        $stages = AppStatus::getStages();

        $response = array(); 
        $action = $request->action; 
        $active_stage = $request->active_stage;
        
        $step_list = $this->as->getStepsByStage($user, $active_stage, $current_step_data);        
        $active_step = 1;
        $active_step_key = (!empty(collect($step_list)->first()->as_order_key)) ?  collect($step_list)->first()->as_order_key : "";
           
        if($action == "navigate_stage") {
            if(!empty($active_stage)){ 
                $request_step_key = $request->get('request_step_key',"");
                $stage_data = $stages[$active_stage]; 
                
                /* Remove commnet if on click Stage inprogress stage */
                /*if($current_step_data->as_stage_number == $active_stage)
                {
                    $active_step = $current_step_data->as_order;
                    $active_step_key = $current_step_data->as_order_key;
                }*/
                
                if(!empty($request_step_key))
                {
                    $request_step_data = $this->as->getCurrentStep($user, $request_step_key);
                    if(!empty($request_step_data))
                    {
                        $active_step = $request_step_data->as_order;
                        $active_step_key = $request_step_data->as_order_key;
                    }
                }
                $step_content = $this->getStepContent($user,$active_step_key);
                
                $data = [
                    'action' => $action,
                    'active_stage' => $active_stage,
                    'active_stage_key' => $stage_data['stage_key'],
                    'active_step' => $active_step,
                    'active_step_key' => $active_step_key,
                    'portfolio_key' => $portfolio_key,
                    'active_step_content' => $step_content,
                    'current_step_data' => $current_step_data,
                    'step_list' => $step_list,
                    'app_status_stages' => $stages,
                    'portfolio' => $user->portfolio,
                    'userGeneral' => $user->portfolio->userGeneral,
                ];

                $compiled = view('admin.app-status.application-status-stages')
                            ->with($data)
                            ->render();

                $response['type'] = "success";
                $response['message'] = ""; 
                $response['active_stage'] = $active_stage;
                $response['active_step'] = $active_step;
                $response['active_step_key'] = $active_step_key;
                $response['application_status_content'] = $request->is('api/*') ? $data : $compiled;
                $response['active_stage_key'] = $stage_data['stage_key'];
            }
            else{
                $response['type'] = "error";
                $response['message'] = "Failed to Load stage";
            }
        }
        else if($action == "navigate_step") {
                
            if(!empty($request->active_step_key)) {
                $active_step_key = $request->active_step_key; 
            }

            $step_content = $this->getStepContent($user,$active_step_key);
           
            $response['type'] = "success";
            $response['step_content'] = $step_content;
        }
        else {
            $response['type'] = "error";
            $response['message'] = "Failed to Load Step Content";
        }
        if ($request->is('api/*')) {
            return apiResponse("success","",$response);
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
        $user_id = user_token();
        $user = User::where('id',$user_id)->first();
        
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
    public function uploadResume(Request $request)
    {
        $user_id = user_token();
        $user = User::where("id",$user_id)->first();
        
        if(!empty($user)){   
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

            /* Handle resume upload */
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
        } 
        else{
            $response = ['type' => "error", 'message' => "Failed to upload Resume"];
        }
        return Response::json($response);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function uploadAgreement(Request $request)
    {
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token(); 
        $user = User::where("id",$user_id)->first();
        
        if(!empty($user)){
            $allowed_extensions = custom_implode(config('common.allow_doc_ext'));
            $upload_file_size = config('common.upload_file_size');    
            $allowed_file_size = config('common.upload_file_size')*1000; 
            $rules = array('agreement' => "required|max:{$allowed_file_size}|mimes:{$allowed_extensions}");
            $validationErrorMessages = [
                                'resume.required' => "J1 Agreement is required.",
                                'resume.max' => "J1 Agreement allowed maximum size {$upload_file_size}mb.",
                                'resume.mimes' => "J1 Agreement must be a file of type: {$allowed_extensions}."
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

            /* Handle j1 agreement upload */
            if($request->hasFile('agreement')){

                $document_sent = $this->uploadDocument($user, $request->agreement, 'j1_agreement',0,1,true);

                if (!empty($document_sent) && $document_sent !== false) {
                    $response = $document_sent;
                }
                else {
                    $response = ['type' => "error", 'message' => "Failed to upload agreement"];
                }
            }
            else{
                $response = ['type' => "error", 'message' => "Please select agreement"];
            }
        } 
        else{
            $response = ['type' => "error", 'message' => "Failed to upload Agreement"];
        }
        return Response::json($response); 
    }
    
    public function schedulePreScreenInterview(Request $request)
    {
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
        $action = $request->get('action',"");
        $user = User::where('id',$user_id)->first();
        if($action == "interview_preview"){
            $int_data = new \stdClass();
            
            $admin_timezone = $request->time_zone_admin;
            $admin_datetime = $request->date_interview_admin;
            $user_timezone = $user->timezone;
            
            $admin_timezone_data = $this->timezone->getFullZoneLabel($admin_timezone);
            $admin_timezone_name = @$admin_timezone_data->zone_label;
            $user_timezone_data = $this->timezone->getFullZoneLabel($user_timezone);
            $user_timezone_name = @$user_timezone_data->zone_label; 
            $user_converted_data = $this->timezone->convertTZDateTime($admin_datetime,$admin_timezone_name,$user_timezone_name);
            $int_data->admin_id = $request->user->id;
            $int_data->admin_name = $request->user->admin_name;
            $int_data->time_zone_admin = $request->time_zone_admin;
            $int_data->admin_timezone_name = $admin_timezone_name;
            $int_data->user_timezone_name = $user_timezone_name;
            $int_data->date_interview_admin = $request->date_interview_admin;
            $int_data->user_name = $user->first_name." ".$user->last_name;
            $int_data->user_timezone = $user_timezone;
            $int_data->user_datetime = @$user_converted_data->dest_datetime; /* Add here converted date time*/
            $int_data->active_step_key = $request->active_step_key;
            
            $data = [
                'action' => "interview_preview",
                'int_data' => $int_data,
            ]; 
            $HTML = view('admin.ajax')->with($data)->render();
            $request->is('api/*') ? $HTML = $data : $HTML = $HTML;
            return ["type" => "success", "message" => "", "data" => $HTML, "isreplace" => 1];
        }
        elseif($action == "schedule_prescreen_interview"){
            
            $int = $user->j1Interview();
            $int->date_interview_admin = (!empty($request->admin_datetime))? Carbon::parse($request->admin_datetime)->format(DB_DATETIME_FORMAT):'';
            $int->date_interview_user = (!empty($request->user_datetime))? Carbon::parse($request->user_datetime)->format(DB_DATETIME_FORMAT): '';
            $int->time_zone_admin = $request->admin_timezone;
            $int->time_zone_user = $request->user_timezone;
            $int->portfolio_id = $user->portfolio->id;
            $int->interview_scheduled_by = $request->admin_id;
            $int->interview_status = 1;
            $int->save();
                
            $this->changeUserStatus($user,'j1-interview-schedule');
            
            return ["type" => "success", "message" => "J1 Interview has been scheduled", "data" => NULL, "isreplace" => 0];
        }
        elseif($action == "start_prescreen_interview"){
            
            /* Update user detais table */
            $usergeneral = $user->userGeneral();
            $usergeneral->gender = $request->gender;      
            $usergeneral->field_studied = $request->field_studied;        
            $usergeneral->save();
            
            /* Update portfolio table */
            $user->portfolio->program_id = $request->program_type;        
            $user->portfolio->save(); 
            
            /* Update j1_interview table */ 
            $int = $user->j1Interview();
            $int->graduation_date = Carbon::parse($request->date_of_graduation)->format(DB_DATE_FORMAT);
            $int->availability_date = Carbon::parse($request->date_of_availibility)->format(DB_DATE_FORMAT);
            $int->availability_type = $request->availability_date_type;
            $int->preferred_program_length = $request->program_length;
            $int->preferred_position_1 = $request->preferred_position_type_1;
            $int->preferred_position_2 = $request->preferred_position_type_2;
            $int->english_level = $request->english_level;
            $int->has_passport = $request->has_passport;
            $int->previous_us_visas = $request->prev_j1_visa;
            $int->reg_fee_status = $request->registration_fee_status;
            $int->interview_additonal_info = $request->interview_additonal_info;
            $int->interviewed_by = $request->user->id;
            $int->interview_date = Carbon::now();
            $int->interview_status = 2;
            $int->save();
            
            $this->changeUserStatus($user,'j1-interview-finished');
            
            return ["type" => "success", "message" => "J1 Interview has been scheduled", "data" => NULL, "isreplace" => 0];
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
    public function collectRegFee(Request $request)
    {
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
        $user = User::where("id",$user_id)->first();

        if(!empty($user)){
            $this->changeUserStatus($user,'registration-fee-completed');
            return ['type' => "success", 'message' => "Registration fee has been collected."];
        } 
        else{
            $response = ['type' => "error", 'message' => "Failed to collect fee"];
        }
        return Response::json($response);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function updateAdditionalInfo(Request $request)
    {
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
        $user = User::where("id",$user_id)->first();
        $portfolio = $user->portfolio;
                
        $required_field_list = ['gender','phone_number','best_call_time','street','city','zip_code','country','state', 'passport_number','passport_issued','passport_expires','birth_date','birth_city','birth_country','country_citizen','country_resident','country_issuer','previously_participated','material_status','currently_student','currently_employed','contact_name_first','contact_name_last','contact_phone','contact_relationship','contact_country','contact_english_speaking','contact_language','contact_email','criminal_record'];
        
        if(!empty($user))
        {
            if($portfolio->is_step_locked == 1)
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
                        
                        $material_status = $request->get('marital_status');
                        $rules = [
                            'marital_status' => 'required',
                        ];

                        $validationErrorMessages = [
                            'marital_status.required' => 'Marital Status field is required.', 
                        ];
                        
                        if($material_status == 2)
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
                            'contact_email' => 'required',
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

                $addinfo_lock = true;
                $addinfo_info = collect($user->portfolio->userGeneral)->all(); 
                foreach($addinfo_info as $key => $value)
                {
                    if(in_array($key,$required_field_list) && empty($value))
                    {
                        $addinfo_lock = false;
                        break;
                    }
                } 
                
                $user_general->lock_additional_info = ($addinfo_lock == true)? 1 : 0;
                $user_general->save();
                
                $user_general = $user_general->fresh(); 
                $user_general->lock_additional_info == 1 ? $this->changeUserStatus($user, "additional-information-saved") : "";
               
            }
        }
        else
        {
            $response = ['type' => "error", 'message' => "Failed to update additional information."];
        }
        
        return Response::json($response);
    }
    
    public function userContractAction(Request $request){
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
        $user = User::where('id',$user_id)->first();
        $contract = AgencyContract::where('id',$request->contract_id)->first();
        $active_step_key = $request->get('active_step_key','');
        
        if($request->action_type=='accept'){

            $user->setAgency($request->contract_id);

            if($active_step_key == "2_contract_placement"){
                $this->changeUserStatus($user, "placement-contract");
                $contract_type = ($action_contract->agency->agency_type == 4)?"2,4":"2";
            }
            else if($active_step_key == "3_contract_sponsor"){
                $this->changeUserStatus($user, "sponsor-contract");
                $contract_type = ($action_contract->agency->agency_type == 4)?"3,4":"3";
            }

            $contract->where('is_expired', 0)
                    ->where('request_status', 1)
                    ->whereIn('contract_type', $contract_type)
                    ->update(['is_expired' => 1]);
            
            $response = ['type' => "success", 'message' => "User contract accepted successfully."];
        }elseif($request->action_type=='reject'){ 
            
            $contract->update(['request_status' => 3, 'is_expired' => 1]);
            
            $response = ['type' => "success", 'message' => "User contract rejected successfully."];
        }else{
            $response = ['type' => "error", 'message' => "Please pass action to do process."];
        }
        
        return Response::json($response);
    }
    
    public function uploadSupportingDocument(Request $request){
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
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
    
    public function uploadDocumentInstruction(Request $request){
        
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
        $request->is('api/*') ? $HTML = $data : $HTML = $HTML;
        return ["type" => "success", "message" => "", "data" => $HTML];
    }
    
    public function documentHistory(Request $request){
        $data = [];
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
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
        $request->is('api/*') ? $HTML = $data : $HTML = $HTML;
        return ["type" => "success", "message" => "", "data" => $HTML];  
    }
    
    public function hiringStage(Request $request)
    {
        $action = $request->get('action',"");
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
        if(!empty($action) && !empty($user_id))
        {
            $user = User::where('id',$user_id)->first();
            $portfolio = $user->portfolio;
            
            switch($action)
            {
                case 'delete_lead':
                    $request->is('api/*') ? $leadId = $request->leadId : $leadId = decrypt($request->leadId);
                    $portfolio->leads()->where('id',$leadId)->delete();
                    $message =  "Lead deleted successfully.";
                    $response = ["type" => "success", "message" => $message, "redirectURL" => url(route("user.app.progress",encrypt($user->id)))];
                    return Response::json($response);                            
                break;
                
                case 'addlead':
                    $leads = $portfolio->leads();
                    $lead_count = $leads->count();

                    if($lead_count < 3)
                    {
                        $position = custom_explode($request->position,"-");
                        $hc_id = $position[0];
                        $pos_id = $position[1];
                        $lead_data = $leads->where('pos_id',$pos_id)->first();
                        if(empty($lead_data->id))
                        {
                            $insert_data = [
                                'user_id' => $user_id,
                                'hc_id' => $hc_id,
                                'pos_id' => $pos_id,
                            ];
                            $lead = new Lead($insert_data);
                            $leads->save($lead);
                            
                            $this->changeUserStatus($user, "searching-position");
                            
                            $message =  "Lead added successfully for this candidate.";
                            $response = ["type" => "success", "message" => $message, "redirectURL" => url(route("user.app.progress",encrypt($user->id)))];
                            return Response::json($response);
                        }
                        else
                        {
                            $response = ["type" => "error", "message" => ["This traning position alredy added as lead for this candidate."]];
                            return Response::json($response);
                        }
                    }
                    else
                    {
                        $response = ["type" => "error", "message" => ["Lead can not be added for this candidate. (Maximum add lead limit is three)"]];
                        return Response::json($response);
                    }  
                break;
                
                case 'booked_position':
                    $data = [
                        'action' => $action,
                        'pos_id' => $request->pos_id,
                        'hc_id' => $request->hc_id,
                    ];
                    $HTML = view('admin.ajax')->with($data)->render();
                    $request->is('api/*') ? $HTML = $data : $HTML = $HTML ;
                    $response = ["type" => "success", "message" => "", "data" => $HTML];
                    return Response::json($response);
                break;
            
                case 'save_booked_position':
                    $rules = [
                        'start_date' => 'required|date',
                        'end_date' => 'required|date|after:start_date',
                        'salary' => 'required',
                        'pay_rate_basis' => 'required',
                    ];

                    $validationErrorMessages = [
                        'start_date.required'  => 'Please select start date.',
                        'end_date.required'  => 'Please select end date.',
                        'salary.required'  => 'Please enter stipend.',
                        'pay_rate_basis.required'  => 'Please select pay rate basis.',
                    ];

                    $validator = Validator::make($request->all(), $rules, $validationErrorMessages);


                    if ( $validator->fails())
                    {
                        $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                        return Response::json($response);
                    } 
                    $pla_order = $portfolio->placements()->where('type',2)->count();
                    $pla_order = !empty($pla_order) ? 2 : 1;
                    $placement = $portfolio->placements()->where('type',1)->where('pos_id',$request->pos_id)->first();
                    if(empty($placement))
                    {
                        $placement = new Placement();
                        $placement->pla_order = $pla_order;
                        $placement->portfolio_id = $user->portfolio_id;
                    }
                                        
                    $placement->pos_id = $request->pos_id;
                    $placement->salary = $request->salary;
                    $placement->pay_rate_basis = $request->pay_rate_basis;
                    $placement->start_date = Carbon::parse($request->start_date)->format(DB_DATE_FORMAT);
                    $placement->end_date = Carbon::parse($request->end_date)->format(DB_DATE_FORMAT);
                    $placement->type = '1';
                    $placement->hc_id = $request->hc_id;
                    $placement->booked_date = Carbon::now()->format(DB_DATETIME_FORMAT);
                    $placement->save();
                    
                    $inserted_id = $placement->id;

                    $this->changeUserStatus($user, "booked");

                    if($inserted_id>0)
                    {
                        $message =  "Position has been booked successfully for this candidate.";
                        $response = ["type" => "success", "message" => $message, "redirectURL" => url(route("user.app.progress",encrypt($user->id)))];
                        return Response::json($response);
                    }
                    else
                    {
                        $response = ["type" => "error", "message" => ["Something went wrong. Please try again."]];
                        return Response::json($response);
                    }
                break;

                case 'hc_interview':
                    $data = [
                        'action' => $action,
                        'pos_id' => $request->pos_id,
                        'portfolio_id' => $request->portfolio_id,
                        'hc_id' => $request->hc_id,
                    ];
                    
                    $HTML = view('admin.ajax')->with($data)->render();
                    $response = ["type" => "success", "message" => "", "data" => $HTML];
                    return Response::json($response);
                break;
            
                case 'schedule_hc_interview':
                    /*PENDING*/
                    $rules = [
                        'time_zone_user' => 'required',
                        'interview_date' => 'required|date|after:start_date'
                    ];

                    $validationErrorMessages = [
                        'time_zone_user.required'  => 'Please select start date.',
                        'interview_date.required'  => 'Please select end date.'
                    ];

                    $validator = Validator::make($request->all(), $rules, $validationErrorMessages);


                    if ( $validator->fails())
                    {
                        $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                        return Response::json($response);
                    } 
                    
                    /*Pending to add status for the scheduled hc interview*/
                    
                    /*START HC INTERVIEW*/
                        $j1_interview = new J1Interview;
                        $j1_interview->portfolio_id = $request->portfolio_id;
                        $J1interview->interview_date = Carbon::parse($request->interview_date)->format(DB_DATETIME_FORMAT);
                        $J1interview->time_zone_user = $request->time_zone_user;
                        $j1_interview->save();
                        $inserted_id = $j1_interview->id;
                    /*END HC INTERVIEW*/

                    if($inserted_id>0)
                    {
                        $message =  "HC interview schedule successfully for this candidate.";
                        $response = ["type" => "success", "message" => $message];
                        return Response::json($response);
                    }
                    else
                    {
                        $response = ["type" => "error", "message" => ["Something went wrong. Please try again."]];
                        return Response::json($response);
                    }
                break;
                
                case 'confirm_placement':
                    $placement = Placement::where('id',$request->booked_pos_id)->first();
                    $placement->placed_date = Carbon::now()->format(DB_DATETIME_FORMAT);
                    $placement->type = '2';
                    $placement->save();
                    
                    /*delete user all lead*/
                    $portfolio->leads()->delete();
                    
                    $this->changeUserStatus($user, "Placed");
                    
                    $message =  "Placement conform successfully for this user.";
                    $response = ["type" => "success", "message" => $message, "redirectURL" => url(route("user.app.progress",encrypt($user->id)))];
                    return Response::json($response);
                break;
            
                case 'not_selected':
                case 'rejected_by_hc':
                case 'interview_refused_by_candidate':
                case 'traning_pos_not_open':
                    /*DELETE PLACEMENT*/
                    $portfolio->placements()->where('id',$request->booked_pos_id)->delete();
                    
                    /*USER LOG*/
                    Switch($action)
                    {
                        case 'not_selected': 
                           $status = "not-selected-by-host-company";
                        break;
                        
                        case 'rejected_by_hc': 
                            $status = "rejected-by-host-company";
                        break;
                        
                        case 'interview_refused_by_candidate': 
                            $status = "interview-refused-by-candidate";
                        break;
                        
                        case 'traning_pos_not_open': 
                            $status = "training-position-no-longer-opened";
                        break;
                    }
                   
                    UserLog::log($user,$status);
                    
                    $message =  "Placement Rejected for this user.";
                    $response = ["type" => "success", "message" => $message, "redirectURL" => url(route("user.app.progress",encrypt($user->id)))];
                    return Response::json($response);
                break;
                
                case 'enroll_in_route_66':
                    /*Change program id based on currunt program id*/
                    $program_id =  $portfolio->route66Program->id;
                    $user->portfolio->program_id = $program_id;        
                    $user->portfolio->save(); 
                    
                    /*User Log*/
                        /*Pending*/

                    $this->changeUserStatus($user, "supporting-document-collected",0,1);
                    $message =  "Candidate status changed to route 66 successfully.";
                    $response = ["type" => "success", "message" => $message];
                    return Response::json($response);
                break;

                default:
                break;
            }
        }
        
    }
    
    public function getLead(Request $request)
    {
        $request->is('api/*') ? $user_id = $request->id : $user_id = user_token();
        if(!empty($user_id)){
            $user = User::where("id",$user_id)->first();
            $position = Position::with('hostCompany:id,hc_name','positionAdmin'); 

            $portfolio = $user->portfolio;
            $program_enroll_id = $portfolio->program->program_enroll_id;
            
            $placement = $portfolio->placements->where('pla_order',1)->first();
            if(!empty($program_enroll_id) && !empty($placement)){
                $position->where('start_date','>',$placement->end_date);
            }
            $position_data = $position->get();
        }
        
        /*get count of placed position*/
        if(!empty($position_data))
        {
            foreach ($position_data as $key=>$value)
            {
                $placed_count  = Placement::where('pos_id',$value->id)->where('type',2)->count();
                $booked_count  = Placement::where('pos_id',$value->id)->where('type',1)->count();
                $position_data[$key]['placed_count'] = $placed_count;
                $position_data[$key]['leftAval_count'] = $value->no_of_openings - $placed_count;
                $position_data[$key]['booked_count'] = $booked_count;
            }
        }
        
        $data = [
                'position' => $position_data,
                'user_detail' => $user
            ];
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('admin.app-status.add-lead')->with($data);
    }
    
    public function supportingDocumentUploaded(Request $request){
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
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
    
    public function visaStage(Request $request) 
    {
        $action = $request->action;
        $request->is('api/*') ? $user_id = $request->user_id : $user_id = user_token();
        $user = User::where('id',$user_id)->first();
        $user_general = $user->userGeneral();
            
        switch ($action)
        {
            case 'ds7002_pending':
                $this->changeUserStatus($user, "ds7002-pending");
                $message =  "User Status changed successfully.";
                $response = ["type" => "success", "message" => $message];
                return Response::json($response);
            break;
        
            case 'ds7002_create':
                $allowed_extensions = custom_implode(config('common.allow_doc_ext'));
                $upload_file_size = config('common.upload_file_size');    
                $allowed_file_size = config('common.upload_file_size')*1000; 
                $rules = array('ds72002_file' => "required|max:{$allowed_file_size}|mimes:{$allowed_extensions}");
                $validationErrorMessages = [
                                    'ds72002_file.required' => "Training Plan - DS 7002 is required.",
                                    'ds72002_file.max' => "Training Plan - DS 7002 allowed maximum size {$upload_file_size}mb.",
                                    'ds72002_file.mimes' => "Training Plan - DS 7002 must be a file of type: {$allowed_extensions}."
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
                    if ($request->is('api/*')){
                        return apiResponse("error", "", $validator->messages()->toArray());
                    }
                    return Response::json([
                        'type' => "error",
                        'message' => $validation_message,
                    ]);
                }

                /* Handle DS7002 Training Plan upload */
                if($request->hasFile('ds72002_file')){

                    $document_sent = $this->uploadDocument($user, $request->ds72002_file, 'ds7002_template',0,1,true);
                    if (!empty($document_sent) && $document_sent !== false) {
                        $response = $document_sent;
                    }
                    else {
                        $response = ['type' => "error", 'message' => "Failed to upload ds7002 training plan"];
                    }
                }
                else{
                    $response = ['type' => "error", 'message' => "Please select file"];
                }
        
                $this->changeUserStatus($user, "ds7002-pending");
                $message =  "User Status changed successfully.";
                $response = ["type" => "success", "message" => $message];
                return Response::json($response);
            break;
            
            case 'ds7002_signed':
                $allowed_extensions = custom_implode(config('common.allow_doc_ext'));
                $upload_file_size = config('common.upload_file_size');    
                $allowed_file_size = config('common.upload_file_size')*1000; 
                $rules = array('ds72002_file' => "required|max:{$allowed_file_size}|mimes:{$allowed_extensions}");
                $validationErrorMessages = [
                                    'ds72002_file.required' => "Training Plan - DS 7002 (Signed) is required.",
                                    'ds72002_file.max' => "Training Plan - DS 7002 (Signed) allowed maximum size {$upload_file_size}mb.",
                                    'ds72002_file.mimes' => "Training Plan - DS 7002 (Signed) must be a file of type: {$allowed_extensions}."
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

                /* Handle DS7002 Training Plan upload */
                if($request->hasFile('ds72002_file')){

                    $document_sent = $this->uploadDocument($user, $request->ds72002_file, 'training_plan_signed',0,1,true);
                    if (!empty($document_sent) && $document_sent !== false) {
                        $response = $document_sent;
                    }
                    else {
                        $response = ['type' => "error", 'message' => "Failed to upload training plan - ds 7002 (signed)"];
                    }
                }
                else{
                    $response = ['type' => "error", 'message' => "Please select file"];
                }

                return Response::json($response);
            break;
            
            case 'ds2019_sent':
                $rules = [
                    'ds_number' => 'required',
                    'tracking_number' => 'required',
                    'ds_start_date' => 'required|date',
                    'ds_end_date' => 'required|date',
                    'ds_shipment_date' => 'required|date',
                ];

                $validationErrorMessages = [
                    'ds_start_date.required'  => 'Please select DS2019 start date.',
                    'ds_end_date.required'  => 'Please select DS2019 end date.',
                    'ds_shipment_date.required'  => 'Please select DS2019 shipment date.',
                    'tracking_number.required'  => 'Please enter tracking number.',
                    'ds_number.required'  => 'Please enter DS2019 number',
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);


                if ( $validator->fails())
                {
                    $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                    return Response::json($response);
                } 

                $legal = Legal::where('portfolio_id',$request->portfolio_id)->first(); 

                if(empty($legal))
                {
                    $legal = new Legal;
                }
                
                $legal->ds_number = $request->ds_number;
                $legal->portfolio_id = $request->portfolio_id;
                $legal->tracking_number = $request->tracking_number;
                $legal->user_id = $user_id;
                $legal->ds_start_date = Carbon::parse($request->ds_start_date)->format(DB_DATE_FORMAT);
                $legal->ds_end_date = Carbon::parse($request->ds_end_date)->format(DB_DATE_FORMAT);
                $legal->ds_shipment_date = Carbon::parse($request->ds_shipment_date)->format(DB_DATE_FORMAT);
                $legal->save();

                $inserted_id = $legal->id;

                $this->changeUserStatus($user, "ds2019-sent");

                if($inserted_id>0)
                {
                    $message =  "DS2019 has been Sent successfully.";
                    $response = ["type" => "success", "message" => $message];
                    return Response::json($response);
                }
                else
                {
                    $response = ["type" => "error", "message" => ["Something went wrong. Please try again."]];
                    return Response::json($response);
                }
            break;
            
            case 'embassy_interview':
                $rules = [
                    'embassy_timezone' => 'required',
                    'embassy_interview' => 'required|date'
                ];

                $validationErrorMessages = [
                    'embassy_timezone.required'  => 'Please select embassy timezone.',
                    'embassy_interview.required'  => 'Please select embassy interview date.',
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                if ( $validator->fails())
                {
                    $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                    return Response::json($response);
                }
                
                $user_general->embassy_interview = Carbon::parse($request->embassy_interview)->format(DB_DATETIME_FORMAT);
                $user_general->embassy_timezone = $request->embassy_timezone;
                $user_general->save();
                
                $inserted_id = $user_general->id;
                $this->changeUserStatus($user, "embassy-interview-scheduled");
                
                if($inserted_id>0)
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
            
            case 'visa_outcome':
                $visa_status = $request->visa_status;
                switch($visa_status) 
                {
                    case "visa_approved":
                        $this->changeUserStatus($user, "visa-approved",0,1);
                        session(['visa_denied_undo' => 0]);
                        $message = "We recorded your visa is approved.";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "green_form":
                        $this->changeUserStatus($user, "221g-letter-received-green-form",0,1);
                        session(['visa_denied_undo' => 0]);
                        $message = "We recorded your 221(g) Letter received (Green Form).";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "admin_process":
                        $this->changeUserStatus($user, "under-administrative-processing",0,1);
                        session(['visa_denied_undo' => 0]);
                        $message = "We recorded your visa is under administrative processing."; 
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "visa_denied":
                         /*Pending 
                            --> embassy_lock
                         * while visa denied count is >2 than update embassy_lock = 1
                         * if embassy_lock = 1 
                            - again send the mail for visa approve option                        
                        */
                        $user_general->visa_denied_count = ($user_general->visa_denied_count)+1;
                        $user_general->save();
                        session(['visa_denied_undo' => 0]);
                        $this->changeUserStatus($user, "visa-denined",0,1);
                        $message = "We have recorded the outcome of your US Embassy's J-1 visa interview.";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status];
                    break;

                    case "reschedule_appointment":
                        session(['visa_denied_undo' => 0]);
                        $deniedOrder       = ($user_general->visa_denied_count >= 2) ? 2 : 1;
                        $NewStatus         = ($deniedOrder == 2) ? "visa-denied-embassy-lock" : "visa-denied-back-embassy";

                        if($NewStatus == 'visa-denied-embassy-lock')
                        {
                            $user_general->consecutive_visa_denied_flag = 3;
                        }else{
                            $user_general->consecutive_visa_denied_flag = 2;
                        }
                        
                        $user_general->embassy_interview = "0000-00-00 00:00:00";
                        $user_general->embassy_timezone = "";
                        $user_general->save();

                        $this->changeUserStatus($user, $NewStatus,0,1);
                        UserLog::log($user,null,"Candidate apply for another interview.");
                        
                        $message = "We have taken note of your desire to schedule another interview at the US Embassy. You will receive an email shortly to communicate ";
                        $message .= ($deniedOrder == 2)?"for further process.":"to us the date and time of this interview.";
                        $response = ["type" => "success", "message" => $message, "visa_status" =>$visa_status,"denied_order"=>$deniedOrder];
                    break;

                    case "quit_program":
                        session(['visa_denied_undo' => 0]);
                        $user_general->consecutive_visa_denied_flag = 1;
                        $user_general->save();
                        $this->changeUserStatus($user, "visa-denied-quit-program",0,1);
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
                    'departure_date' => ['nullable','date'],
                    'arrival_timezone' => 'required',
                    'arrival_date' => 'date|required',
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
                    if ($request->is('api/*')){
                        return apiResponse("error", "", $validator->messages()->toArray());
                    }
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
            
            case 'arrived':
                $this->changeUserStatus($user, "arrived");
                $message =  "User Status changed successfully.";
                $response = ["type" => "success", "message" => $message];
                return Response::json($response);
            break;
        }
        
    }
}
