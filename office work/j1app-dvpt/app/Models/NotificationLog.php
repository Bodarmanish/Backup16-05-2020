<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class NotificationLog extends Model
{
    protected $table = "notification_log";
    
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
    * @param int $id contain a digit as id
    * @param string $by options like "log,user"
    * **/
   public static function setRead($id, $by = 'log'){

       if(!empty($id)){

           $query = DB::table('notification_log');

           if($by == 'log'){
               $query->where('id',$id);
           }
           else if($by == 'user'){
               $query->where('notified_to',$id);
           }

           $query->update([
               'is_read' => 1
           ]);

           return true;
       }
       else{
           return false;
       }
   }
   public function getUserNotificationList($is_read = null,$latest_log = null){
          
         $user = Auth::user();
         if(!is_null($user))
         {   
             $columns = [
                 'nl.id as log_id',
                 'nl.created_at',
                 'nl.notification_log_text',
                 'nm.notification_text',
                 'nm.notification_type_key',
                 'nm.notification_type_data',
                 'nt.notification_key'
             ];
             $query = DB::table('notification_log AS nl')
                         ->select($columns)
                         ->leftJoin('notification_messages as nm', 'nm.id', '=', 'nl.notification_message_id')
                         ->leftJoin('notification_types as nt', 'nt.id', '=', 'nl.notification_type_id') 
                         ->where('nl.notified_to', $user->id);
                         
 
             if(!empty($is_read))
             {
                 $is_read = ($is_read=='read')?1:0;
                 
                 $query->where('is_read', $is_read);
             }
             if(!empty($latest_log))
             {
                $query->whereRaw('nl.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)'); 
             }
             $query->orderBy('nl.id', "DESC");
             
             $data = $query->get()->all();
             
             if(!empty($data)){
                 foreach($data as $key => $val){
                     if(!empty($val->created_at))
                     {
                         $converted_data = convert_datetime_to_local($val->created_at);
                         $data[$key]->created_at = $converted_data->dest_datetime;
                         $data[$key]->converted_data = $converted_data;
                     }
                     if(empty($val->notification_log_text))
                     {
                         $data[$key]->notification_log_text = $val->notification_text;
                     }
                 }
             }
             
             return (!empty($data)) ? $data : false;
             
         } 
         else
             return false;
     } 
}
