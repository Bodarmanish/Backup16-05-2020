<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class DomainSettings extends Model
{
    protected $table = "domain_settings";
    public $primaryKey = "id";
    public $timestamps = false;
    public $inst;
        
    public static function getDomainDetail(){
        $current_domain = request()->getHttpHost();
        if(!empty($current_domain))
        {
            $data = DB::table('domain_settings')
                    ->select('*')
                    ->where("domain_name",$current_domain)
                    ->first();
            
            return (!empty($data)) ? $data : false;
        }
        else
            return false;
        
    }
    
}
