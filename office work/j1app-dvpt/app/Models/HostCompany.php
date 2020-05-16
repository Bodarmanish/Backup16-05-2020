<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Geo;
use DB;

class HostCompany extends Model
{
    protected $table = 'host_companies';
    
    public function getHostCompanies(){
        
        $sql = "SELECT 
                    hc.*,
                    geo_states.state_id,
                    geo_states.state_name,
                    geo_states.state_abbr 
                FROM host_companies AS hc 
                LEFT JOIN geo_states ON geo_states.state_id = hc.hc_state 
                ";
        
        $data = DB::select($sql);
        
        if(!empty($data)){
            return $data;
        }
        else{
            return false;
        }
    }
    
    public function checkHCAccess($hc_id){
        $user = auth()->user();
        
        $query = DB::table('host_companies AS hc');
        $query->join('admins AS created_by_admin','created_by_admin.id', 'hc.created_by');
        
        $columns = ['hc.id'];
        
        if($user->role_name == "agency-admin"){
            
            $query->join('agency', function ($join) use($user) {
                    $join->on('agency.id', '=', 'created_by_admin.agency_id')
                         ->where('agency.id', '=', $user->agency_id);
                    });

        }
        else{
            $query->leftJoin('agency', 'agency.id', 'created_by_admin.agency_id');
        }
        
        $query->where('hc.id',$hc_id);
        $hc = $query->select($columns)->first();
        
        if(!empty($hc)){
            return true;
        }
        else{
            return false;
        }
    }
    
    public static function deleteById($id){
        return HostCompany::where('id', $id)->delete();
    }
}
