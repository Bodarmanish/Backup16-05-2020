<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

use App\Models\Document;
use App\Models\UserLog;

class ApplicationStatus extends Model
{
    protected $table = "application_status_steps";
    
    protected $primaryKey = "id";
    
    protected $columns = [
                            'id',
                            'as_stage_number',
                            'as_order_key',
                            'as_order',
                            'as_title',
                            'as_icon',
                            'as_desc_before',
                            'as_desc_current',
                            'as_desc_after',
                            'j1_status_id',
                        ];

    /**
     * common.php >> function setCurrentStep
     * @param int $user_id
     * @param int $status
     * @param string $timeline_order_key
     * This function update timeline order of candidate
     **/
    public function setCurrentStep(User $user,$status = null,$timeline_order_key = null)
    {
        if(!empty($user))
        {
            $portfolio = $user->portfolio;
            
            if($portfolio->is_step_locked != 1)
            {
                $program_id = $user->program_id;

                if(empty($status) && !empty($timeline_order_key) && is_string($timeline_order_key))
                {
                    $step_data = $this->getCurrentStep($user, $timeline_order_key);
                }
                else
                {
                    $step_data = $this->getStepByStatus($status);
                }
                
                if(!empty($step_data))
                {
                    $as_order_key = $step_data->as_order_key;

                    $portfolio->as_order_key = $as_order_key;
                    $portfolio->save();
                    
                    UserLog::log($user, null, "Application status order key updated");
                    return true;
                }
            }
            else
                return false;
        }
        else
            return false;
    }
    
    
    public function getCurrentStep(User $user, $order_key = null){
        if(!empty($order_key) && is_string($order_key)) {
            $order_key = $order_key;
        }
        else {
            $order_key = $user->portfolio->as_order_key;
        }
        
        if(!empty($order_key)) {
            
            $data = $this->where('as_order_key',$order_key)
                        ->select($this->columns)->first();
            
            if(!empty($data))
            {
                $stage = $data->as_stage_number;
                $order = $data->as_order;
                $order_key = $data->as_order_key;
                
                $step_list = $this->getStepsByStage($user,$stage,$data,false);
                if(!empty($step_list))
                {
                    $data->prev_order_key = "";
                    $data->next_order_key = "";
                    
                    $total_steps = count($step_list);
                    foreach($step_list as $key => $step)
                    {
                        $next_step_number = $key + 1;
                        $prev_step_number = $key - 1;
                        if($step->as_order_key == $order_key)
                        {
                            if($next_step_number < $total_steps)
                            {
                                $data->next_order_key = $step_list[$next_step_number]->as_order_key;
                            }
                            
                            if($prev_step_number >= 0)
                            {
                                $data->prev_order_key = $step_list[$prev_step_number]->as_order_key;
                            }
                            $data->as_order = $step->as_order;
                        }
                    }
                }
                else
                    return false;
            }
            else
                return false;
            
            return (!empty($data)) ? $data : false;
        }
        else
            return false;
    }
    
    
    /**
    * Function getStepByStatus
    * @param int $status, candidate's status
    * This function for return candidate's step data by candidate's status
    **/
    public function getStepByStatus($status)
    {
        if(!empty($status) && is_numeric($status))
        {
            $query = self::select($this->columns)
                        ->whereRaw("FIND_IN_SET('{$status}',j1_status_id)");
            
            $data = $query->first();
            
            return (!empty($data)) ? $data : false;
        }
        else
            return false;
    }
    
    /**
     * Function getStepsByStage
     * @param int $stage
     * **/
    public function getStepsByStage(User $user, $stage, $current_step_data, $is_verified = true, $_ = null)
    {
        if(!empty($user) && !empty($stage))
        {
            $portfolio = $user->portfolio;
        
            $columns = array();
            $arg_num = func_num_args();
            if($arg_num > 2)
            {
                $args = func_get_args();
                unset($args[0]);
                unset($args[1]);
                
                foreach ($args as $arg)
                {
                    $columns[] = $arg;
                }
            }
            
            if(empty($columns)){
                $columns = $this->columns;
            }
            
            $step_data = $this->where('as_stage_number',$stage)
                        ->select($this->columns)
                        ->orderby('as_order','ASC')
                        ->get()->all();
            
            if(empty($step_data))
                return false;
                        
            $order_counter = 1;
            
            $step_data_by_key = array();
            
            foreach($step_data as $key => $step)
            {
                $step->as_order = $order_counter;
                $step_data[$key]->user_step_status = 0;
                $step_data[$key]->admin_step_status = 0;
                $step_data[$key]->step_alert = 0;
                $step_data[$key]->is_user_step_display = true;
                if($is_verified == true && !empty($current_step_data))
                {
                    $this->verifyUserSteps($user, $step_data, $key, $current_step_data);
                }
                $step_data[$key]->as_order = $order_counter;

                $order_counter++;

                $step_data_by_key[$step->as_order_key] = $step_data[$key];
            }
            
            foreach($step_data_by_key as $key=>$step){
                if(empty($step->is_user_step_display)){
                    unset($step_data_by_key[$key]);
                }
            }
            
            if(!empty($step_data_by_key))
                return array_values($step_data_by_key);
            else
                return false;
        }
        else
            return false;
    }
    
    /**
     * user_step_status => 0 = Disabled, 1 = Active, 2 = Complete
     * admin_step_status => 0 = Disable, 1 = Done
     * step_alert => 0 = No Alert, 1 = Alert
     * is_user_step_display => 0 = Hide this step, 1 = Show this step
     * **/
    public function verifyUserSteps(User $user, &$step_data, $key, $current_step_data = null)
    {
        $portfolio = $user->portfolio;
        $usergeneral = $portfolio->userGeneral;
        
        $stage = $step_data[$key]->as_stage_number;
        $order_key = $step_data[$key]->as_order_key;
        $step = $step_data[$key];
        
        if(!empty($current_step_data)){
            $current_stage = $current_step_data->as_stage_number;
            $current_order = $current_step_data->as_order;
            $current_order_key = $current_step_data->as_order_key;
        }
        
        $step_data[$key]->user_step_status = 0;
        $step_data[$key]->admin_step_status = 0;
        $step_data[$key]->step_alert = 0;
        $step_data[$key]->is_user_step_display = true;
        switch($order_key)
        {
            case '1_eligibility_test':
                
                if(($stage == $current_stage && $step->as_order <= $current_order) || ($stage < $current_stage)) {
                    if(!empty($usergeneral->eligibility_test_output)) {
                        $quest = config('common.eligibility_quest');
                        $step_data[$key]->user_step_status = 2;
                        $step_data[$key]->admin_step_status = 1;
                        $step_data[$key]->timeline_desc_after = $quest[$usergeneral->eligibility_test_output]['desc'];
                    }
                    else
                        $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "1_resume_upload":
            case "1_resume_approval":
                
                $this->doc = new Document;
                $document = $this->doc->getDocumentByType($user, 'resume');
                if($order_key == "1_resume_upload"){
                    if(!empty($document)) {
                        $step_data[$key]->admin_step_status = 1;
                    }
                    
                    if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                        $step_data[$key]->user_step_status = 1;
                        if(!empty($document)){
                            $step_data[$key]->user_step_status = 2;
                        }
                    }
                    elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                        $step_data[$key]->user_step_status = 1;
                    }
                }
                else if($order_key == "1_resume_approval"){
                    if(!empty($document)) {
                        if(!empty($document->document_status) && $document->document_status != 2){
                            $step_data[$key]->admin_step_status = 1;
                        }
                        if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                            $step_data[$key]->user_step_status = 2;
                        }
                        elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                            if($document->document_status == 1) {
                                $step_data[$key]->user_step_status = 2;
                            }
                            else{
                                $step_data[$key]->user_step_status = 1;
                            }
                        }
                    }
                }
                
            break; 
            
            case '1_skype':
            
                if(!empty($usergeneral->skype_id)) {
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 2;
                }
                elseif(($stage == $current_stage && $step->as_order == $current_order && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                }
                
            break;
            
            case "1_j1_interview":
            
                $j1Interview = $user->interview;
                if(!empty($j1Interview) && $j1Interview->interview_status == 2){
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 2;
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                }

            break;
            
            case "1_j1_agreement":
            
                $this->doc = new Document;
                $document = $this->doc->getDocumentByType($user, 'j1_agreement');
                if(!empty($document) && !empty($document->document_status)) {
                    $step_data[$key]->admin_step_status = 1; 
                }
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 2;
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                }

            break;
            
            case "1_registration_fee":
            
                $j1Interview = $user->interview;
                if(!empty($j1Interview->reg_fee_status) && in_array($j1Interview->reg_fee_status,[1,2])){
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    if(!empty($j1Interview->reg_fee_status) && in_array($j1Interview->reg_fee_status,[1,2])){
                        $step_data[$key]->user_step_status = 2;
                    }
                    else{
                        $step_data[$key]->user_step_status = 1;
                    }
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                }

            break;
            
            case "1_additional_info":
                if(!empty($usergeneral->lock_additional_info)){
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    if(!empty($usergeneral->lock_additional_info)){
                        $step_data[$key]->user_step_status = 2;
                    }
                    else{
                        $step_data[$key]->user_step_status = 1;
                    }
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                }
                
            break;
            
            case "2_contract_placement":
                $portfolio = $user->portfolio;
                if(!empty($portfolio->placement_agency_id)){
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    if(!empty($portfolio->placement_agency_id)){
                        $step_data[$key]->user_step_status = 2;
                    }
                    else{
                        $step_data[$key]->user_step_status = 1;
                    }
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                } 
                
            break;
            
            case "2_supporting_documents":            
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 2;
                    $step_data[$key]->admin_step_status = 1;
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "2_searching_position":
                $lead_data = $portfolio->leads()->get();
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->admin_step_status = 1;
                    $step_data[$key]->user_step_status = 2;
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                    if(!empty($lead_data) && $lead_data->count() > 0)
                    {
                        $step_data[$key]->admin_step_status = 1;
                    }
                }
            break;
            
            case "2_booked":
                $booked_data = $portfolio->placements()->where('type',1)->get();
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 2;
                    $step_data[$key]->admin_step_status = 1;
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                    if(!empty($booked_data) && $booked_data->count() > 0)
                    {
                        $step_data[$key]->admin_step_status = 1;
                    }
                }
            break;
            
            case "2_placed":
                $placed_data = $portfolio->placements()->where('type',2)->get();
                if(!empty($placed_data) && $placed_data->count() > 0)
                {
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 2;
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "3_contract_sponsor":
                $portfolio = $user->portfolio;
                if(!empty($portfolio->sponsor_agency_id)){
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    if(!empty($portfolio->sponsor_agency_id)){
                        $step_data[$key]->user_step_status = 2;
                    }
                    else{
                        $step_data[$key]->user_step_status = 1;
                    }
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                } 
                
            break;
            
            case "3_post_placement_documents":
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4)) {
                    $step_data[$key]->user_step_status = 2;
                    $step_data[$key]->admin_step_status = 1;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "3_ds7002_pending":
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4))
                {
                    $step_data[$key]->user_step_status = 2;
                    $step_data[$key]->admin_step_status = 1;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4)
                {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "3_ds7002_created":
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4))
                {
                    $step_data[$key]->user_step_status = 2;
                    $step_data[$key]->admin_step_status = 1;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4)
                {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
        
            case "3_ds7002_signed":
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4))
                {
                    $step_data[$key]->user_step_status = 2;
                    $step_data[$key]->admin_step_status = 1;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4)
                {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "3_ds2019_sent":
                $legal = $portfolio->legal()->first();
                if(!empty($legal))
                {
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4))
                {
                    $step_data[$key]->user_step_status = 2;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4)
                {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "3_us_embassy_interview":
                if(!empty($usergeneral->embassy_interview) && !empty($usergeneral->embassy_timezone))
                {
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4))
                {
                    $step_data[$key]->user_step_status = 2;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4)
                {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "3_us_visa_outcome":
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4))
                {
                    $step_data[$key]->user_step_status = 2;
                    $step_data[$key]->admin_step_status = 1;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4)
                {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "3_flight_info":
                $flight_data = $portfolio->flightInfo()->first();
                if(!empty($flight_data))
                {
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4))
                {
                    $step_data[$key]->user_step_status = 2;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4)
                {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
            
            case "3_arrival_in_usa":
                
                $status_log = $portfolio->getLog()->where('action_status',4002)->latest()->first();
                if(!empty($status_log)){
                    $step_data[$key]->admin_step_status = 1;
                }
                
                if(($stage < $current_stage && $stage != 4) || ($stage == $current_stage && $step->as_order < $current_order && $stage != 4)){
                    $step_data[$key]->user_step_status = 2;
                }
                elseif($stage == $current_stage && $step->as_order <= $current_order && $stage != 4){
                    $step_data[$key]->user_step_status = 1;
                    if(!empty($status_log)){
                        $step_data[$key]->user_step_status = 2;
                    }
                }
            break;
            
            default:
                if(($stage == $current_stage && $step->as_order < $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 2;
                }
                elseif(($stage == $current_stage && $step->as_order <= $current_order && $stage != 4) || ($stage < $current_stage && $stage != 4)) {
                    $step_data[$key]->user_step_status = 1;
                }
            break;
        }
    }
    
    /**
     * check chronological order between user's current order key and next step order key.
     * @param int $user instance of User model
     * @param int $next_order_key step order key
     * @return bool return true or false
     * **/
    public function checkChronologicalOrder(User $user, $next_order_key)
    {
        if(!empty($user) && !empty($next_order_key))
        {
            $current_step_data = $this->getCurrentStep($user);
            $next_step_data = $this->getCurrentStep($user,$next_order_key);
            
            if(!empty($next_step_data))
            {
                if(!empty($current_step_data))
                {
                    $stage1 = $current_step_data->as_stage_number;
                    $stage2 = $next_step_data->as_stage_number;
                    
                    if($stage1 == $stage2) {
                        $step1 = $current_step_data->as_order;
                        $step2 = $next_step_data->as_order;
                        if($step1 < $step2)
                            return true;
                        else
                            return false;
                    }
                    else if($stage1 < $stage2)
                        return true;
                    else
                        return false;
                }
                else
                    return true;
            }
            else
                return false;
        }
        else
            return false;
    }
    
    public static function getStages(){
        
        $stages = config('common.application_status_stages');
        unset($stages[4]);
        
        $app_interface = config('common.app_interface');
        if($app_interface === "admin"){
            $admin = auth()->user();
            if($admin->role_name == 'agency-admin'){
                if($admin->agency_type==1){ 
                    unset($stages[2]);
                    unset($stages[3]);
                }
                elseif($admin->agency_type==2){
                    unset($stages[3]);
                }
                elseif($admin->agency_type==3){
                    unset($stages[1]);
                    unset($stages[2]);
                }
            }
        }
        return $stages;
    }
}
