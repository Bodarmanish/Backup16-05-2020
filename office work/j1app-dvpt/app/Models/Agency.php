<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\DocumentRequirement;
use Illuminate\Support\Facades\Storage;

class Agency extends Model
{
    protected $table = "agency";
    public $primaryKey = "id";
    
    
    public static function deleteByAgencyId($id){
        
        $document = DocumentRequirement::where('agency_id', $id)->get();
        
        /*delete document requirements for the agency*/
        DB::table('document_requirements')->where('agency_id', $id)->delete();
      
        /*delete document requirements of the agency from the folder*/
        Storage::disk('public')->deleteDirectory('document-template/'.$id);
        
        /*delete agency*/
        return DB::table('agency')->where('id', $id)->delete();
    }
      
    public static function getAgency($id = 0){
        
        $query = DB::table('agency')
               ->select("id","agency_name","agency_type","agency_address","description","status");
              
    
        if(!empty($id)){
            $query = $query->where("id","=",$id);
        }
        
        $result = $query->get();
                        
        return (!empty($result)) ? $result : false;
    }
    
    /**
    * get Admin List using agency id
    *
    * @return array
    */
    public function admins()
    {
        return $this->hasMany('App\Models\Admin', 'agency_id');
    }
    
    public function agencyContract()
    {
        return $this->hasOne('App\Models\AgencyContract', 'agency_id');
    }
    
    public function docRequirement()
    {
        return $this->hasMany('App\Models\DocumentRequirement', 'agency_id');
    }
}
