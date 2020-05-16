<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class J1Status extends Model
{
    protected $table = "j1_statuses";
    
    public static function getId($key){
        
        $status = "";
        if(!empty($key) && is_string($key)){
            $status = self::where('status_key',$key)->select('id','category')->first();
        }
        else if(!empty($key) && is_numeric($key)){
            $status = self::where('id',$key)->select('id','category')->first();
        }
        
        if(!empty($status)){
            return $status;
        }
        return false;
    }
}
