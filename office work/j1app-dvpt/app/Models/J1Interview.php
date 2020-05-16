<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class J1Interview extends Model
{
    protected $table = "j1_interview";
   
    public function interviewScheduleBy(){
        return $this->belongsTo('App\Models\Admin','interview_scheduled_by');
    }
    
    public function interviewedBy(){
        return $this->belongsTo('App\Models\Admin','interviewed_by');
    }
    public function getUserEmpInterviewSchedule($_ = null)
    {
        $user = Auth::user();
        if(!is_null($user))
        {
            $arg_num = func_num_args();
            $custom_column = func_get_args();
            
            if($arg_num > 0) {
                if($arg_num == 1 && is_array($custom_column[0]))
                {
                    $custom_column = $custom_column[0];
                }
            }
            else {
                $custom_column = ['*'];
            }
            $custom_column = refine_array($custom_column);
            
            $data = DB::table('j1_interview')
                    ->leftJoin('admins','admins.id','=','j1_interview.interviewed_by')
                    ->leftJoin('portfolio','portfolio.id','=','j1_interview.portfolio_id')
                    ->leftJoin('lead','lead.portfolio_id','=','j1_interview.portfolio_id')
                    ->select($custom_column)
                    ->where('j1_interview.interview_type',2)
                    ->where('j1_interview.portfolio_id',$user->portfolio_id)
                    ->get()->all();
            
            return $data;
        }
        else {
            return false;
        }
    }
}
