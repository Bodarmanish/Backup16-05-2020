<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HostCompany;
use DB;

class Position extends Model
{
    protected $table = 'host_company_positions';
    
    public static function deleteById($id){
        return Position::where('id', $id)->delete();
    }
    
    public function hostCompany(){
        return $this->belongsTo('App\Models\HostCompany','hc_id');
    }
    
    public function positionAdmin(){
        return $this->belongsTo('App\Models\Admin','created_by');
    }
    
    public function checkPositionAccess($position_id){
        $user = auth()->user();
        
        $query = DB::table('host_company_positions AS hcp');
        $query->join('host_companies AS hc','hc.id','hcp.hc_id');
        $query->join('admins AS created_by_admin','created_by_admin.id', 'hcp.created_by');
        
        $columns = ['hcp.id'];
        
        if($user->role_name == "agency-admin"){
            
            $query->join('agency', function ($join) use($user) {
                    $join->on('agency.id', '=', 'created_by_admin.agency_id')
                         ->where('agency.id', '=', $user->agency_id);
                    });

        }
        else{
            $query->leftJoin('agency', 'agency.id', 'created_by_admin.agency_id');
        }
        
        $query->where('hcp.id',$position_id);
        $positions = $query->select($columns)->first();
        
        if(!empty($positions)){
            return true;
        }
        else{
            return false;
        }
    }
}
