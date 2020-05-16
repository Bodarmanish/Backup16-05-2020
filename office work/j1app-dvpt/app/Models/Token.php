<?php

namespace App\Models;
 
use Illuminate\Foundation\Auth\User as Authenticatable; 
use DB;

class Token extends Authenticatable 
{  
    protected $table = "token_master";
    
    public $primaryKey = "id";
    
    public $timestamps = true;
    
    /**
    * function generateToken()
    * @param mixed $token_value (optional) mixed value variable
    * @param string $custom_token (optional) use custom string as a access token
    * @param string $token_type 1 = permanent, 2 = temporary, 3 = one time (token expires just after used)
    * @param int $expire_time token expire in following hours after generated (default 24 hours)
    * 
    **/
    public function generateToken($token_value = "", $token_type = 3, $expire_time = 24, $custom_token = null, $is_array = false)
    {  
        $plain_token = uniqid()."-".time();
        $access_token = secure_id($plain_token);

        if(!empty($custom_token))
            $access_token = $custom_token;

        if(empty($token_value))
            $token_value = $access_token;

        if(is_array($token_value))
        {
            $token_value = collect($token_value)->toJson();
        } 
        
        $token_time = time();

        if(empty($token_type) || !is_numeric($token_type))
            $token_type = 3;

        $token_expire_time = "";
        if($token_type == 2)
        {
            $expire_time = time() + (3600 * $expire_time);
            $token_expire_time = $expire_time;
        } 
        
        $token = new Token;
        $token->access_token = $access_token;
        $token->token_value = $token_value;
        $token->token_type  = $token_type; 
        $token->token_time  = $token_time; 
        $token->token_expire_time  = $token_expire_time;
        $token->save();
        
        if($is_array){
            return ['token_id' => $token->id, 'access_token' => $access_token];
        }else{
            return $access_token; 
        }
    }
    
    /**
    * function get_token()
    * @param string $token generated token to get token details
    * **/
   public function getToken($token)
   {  
       if(!empty($token))
       {
            $token_data = DB::table('token_master') 
                ->where('access_token',$token)
                ->where('is_expired',0) 
                ->first();
            return $token_data; 
       }
       else
       {
           return false;
       }
   }
   
   /**
    * function getTokenByTokenId()
    * @param int $token_id To get token data using token id
    * **/
   public function getTokenByTokenId($token_id)
   {  
       if(!empty($token_id))
       {
            $token_data = DB::table('token_master')
                ->where('id',$token_id)
                ->first();
            return $token_data; 
       }
       else
       {
           return false;
       }
   }
   
}