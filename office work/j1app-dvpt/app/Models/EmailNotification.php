<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmailNotification extends Model
{
    protected $table = "email_notification";
    public $primaryKey = "id";
    public $timestamps = false;
    
    /*
     * function getMailTextByKey()
     * @param string $en_key
     */
    public static function getMailTextByKey($en_key)
    {
        if(!empty($en_key))
        {
            $mail_details = DB::table('email_notification')
                ->select('id','subject','text','send_cc')
                ->where('en_key', '=', $en_key)
                ->get();
            
            return (array) $mail_details->first();
        }
        else
            return false;
    }
    /**
    * Function deleteByNotificationId()
    * This function for delete notification type by id
     */
    public static function deleteByEmailNotificationId($id){
        return DB::table('email_notification')->where('id', $id)->delete();
    }
    /**
    * Email Notification table filter
    *
    * @return array
    */
    public function scopeFilter($query, $params)
    {    
        $query->select('email_notification.*'); 
        if ( isset($params['mail_text']) && trim($params['mail_text'] !== '') ){
            $query->where('subject', 'LIKE', '%'.trim($params['mail_text']).'%');
            $query = $query->orwhere("text","like","%".trim($params['mail_text'])."%");
        }
        if ( isset($params['recipient_address']) && trim($params['recipient_address'] !== '') ){
            $query->where('send_cc', 'LIKE', '%'.trim($params['recipient_address']).'%');
        }
        if ( isset($params['send_to']) && !empty($params['send_to']) ){
            $query->where('send_to',$params['send_to']);
        }
        
       return $query;
    }
}
