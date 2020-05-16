<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserGeneral extends Model
{
    protected $table = "user_details";
    
    public $timestamps = true;
    
    public $inst;
    
    public function updateGeneralInfo($portfolio_id,$field_data)
    {
        $this->inst = new UserGeneral(); 
        if(!empty($portfolio_id) && !empty($field_data))
        {
            $general_data = $this->inst
                                ->where('portfolio_id', $portfolio_id)
                                ->first();
            
            if(!empty($general_data))
            {
                $this->inst = $general_data;
            }
            else
            {
                $this->inst->user_id = $field_data['user_id'];
                $this->inst->portfolio_id = $portfolio_id;
            }
            
            foreach($field_data as $field_name => $field_value)
            {
                $this->inst->$field_name = (!empty($field_value)) ? $field_value : "";
            }
            
            $this->inst->save();
        }
        else
            return false;
    }
    
    public function program(){
        return $this->belongsTo('App\Models\Programs','eligibility_test_result');
    }
    
}
