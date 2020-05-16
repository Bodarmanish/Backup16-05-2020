<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class UserLog extends Model
{
    protected $table = "user_log";
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'portfolio_id', 'action_status', 'action_by_id', 'action_by_type', 'action_note' 
    ];
    
    public static function log(User $user, $status = null, $note_text = null){
        
        if(!empty($user)){
            
            $status_data = J1Status::getId($status);
            $status_id = (!empty($status_data)) ? $status_data->id : "";
            
            $log_fields = [
                'user_id' => $user->id,
                'action_status' => ($status_id) ? $status_id : "",
                'action_note' => ($note_text) ? $note_text : "",
            ];
            
            if(!empty($user->portfolio->id)){
                $log_fields['portfolio_id'] = $user->portfolio->id;
            }
            
            if(!empty(Auth::user()->id)){
                $log_fields['action_by_id'] = Auth::user()->id;
            }
            
            $app_interface = config('common.app_interface');
            if($app_interface == 'admin'){
                $log_fields['action_by_type'] = 1;
            }
            else if($app_interface == 'user'){
                $log_fields['action_by_type'] = 2;
            }
            
            self::create($log_fields);
        }
        return false;
    }
}
