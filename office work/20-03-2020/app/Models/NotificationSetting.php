<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class NotificationSetting extends Model
{
    protected $table = "notification_settings";
    
    public $inst;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    public $timestamps = false;
    
   public function updateUserNotificationStatus($user_id,$field_data)
    {
        $this->inst = new NotificationSetting();
          
        if(!empty($user_id) && !empty($field_data))
        {
            $general_data = $this->inst
                            ->where('user_id', $user_id)
                            ->where('notification_type_id', $field_data['notification_type_id'])
                            ->first();
            
            if(!empty($general_data))
            {
                $general_data->updated_at = DB::raw("NOW()");
                $this->inst = $general_data;
            }
            else
            {
                $this->inst->user_id = $user_id;
                $this->inst->notification_type_id = $field_data['notification_type_id'];
                $this->inst->created_at = DB::raw("NOW()");
            }
            
            foreach($field_data as $field_name => $field_value)
            { 
                $this->inst->$field_name = $field_value;
            }
            
            $this->inst->save();
        }
        else
            return false;
    }
}
