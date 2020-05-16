<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class NotificationType extends Model
{
    protected $table = "notification_types";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
    /**
    * Function deleteByNotificationId()
    * This function for delete notification type by id
     */
    public static function deleteByNotificationId($id){
        return DB::table('notification_types')->where('id', $id)->delete();
    }
    
    /**
     * Get notification list
     *
     * @return array
     */
    public function getNotificationList($user_id) {
        if (!empty($user_id)) {
            $columns = [
                'notification_types.id',
                'notification_types.notification_name',
                'notification_types.visible_to_user',
                'notification_settings.j1app_status',
                'notification_settings.email_status',
            ];
            $notification_list = DB::table('notification_types')
                    ->select($columns)
                    ->leftJoin('notification_settings', function ($join) use($user_id) {
                        $join->on('notification_settings.notification_type_id', '=', 'notification_types.id');
                        $join->where('notification_settings.user_id', '=', $user_id);
                    })
                    ->where('notification_types.status', 1)
                    ->where('notification_types.visible_to_user', '!=', 0)
                    ->orderBy('notification_types.id', 'asc')
                    ->get();
                    
            return $notification_list;
        } else {
            return $user_id;
        }
    }

}
