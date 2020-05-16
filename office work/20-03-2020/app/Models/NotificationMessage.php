<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class NotificationMessage extends Model
{
    protected $table = "notification_messages";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
    /**
    * Function deleteByNotificationId()
    * This function for delete notification message by id
     */
    public static function deleteByNotificationId($id){
        return DB::table('notification_messages')->where('id', $id)->delete();
    }
    /**
    * Notification Message table filter
    *
    * @return array
    */
    public function scopeFilter($query, $params)
    {    
        $query->select('notification_messages.*','nt.notification_name'); 
        $query->leftJoin('notification_types as nt', 'nt.id','=','notification_messages.notification_type_id');
        if ( isset($params['notification_text_message']) && trim($params['notification_text_message'] !== '') ){
            $query->where('notification_text', 'LIKE', '%'.trim($params['notification_text_message']).'%');
            $query = $query->orwhere("notification_message","like","%".trim($params['notification_text_message'])."%");
        }
        if ( isset($params['notification_name']) && trim($params['notification_name'] !== '') ){
            $query->where('notification_type_id', '=',$params['notification_name']);
        }
        return $query;
    }
}
