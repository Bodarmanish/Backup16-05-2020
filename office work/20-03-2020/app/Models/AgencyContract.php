<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\DocumentRequirement;
use Illuminate\Support\Facades\Storage;

class AgencyContract extends Model
{
    protected $table = "agency_contract";
    public $primaryKey = "id";
    protected $fillable = [
        'agency_id', 'user_id', 'portfolio_id', 'email','contract_type', 'request_status', 'request_by', 'is_expired' 
    ];
    
    public function agency()
    {
        return $this->belongsTo('App\Models\Agency','agency_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
    
    /**
    *
    * @return array
    */
    public function scopeFilter($query, $params)
    {
        $query->select('agency_contract.id' ,
                    'agency_contract.agency_id',
                    'agency_contract.user_id',
                    'agency_contract.portfolio_id',
                    'agency_contract.email',
                    'agency_contract.contract_type',
                    'agency_contract.request_status',
                    'agency_contract.request_by',
                    'agency_contract.is_expired',
                    'agency.agency_name',
                    'users.first_name',
                    'users.last_name',
                    'users.email'); 
        $query->leftjoin('agency','agency.id', '=', 'agency_contract.agency_id');
        $query->leftjoin('users','users.id', '=', 'agency_contract.user_id');
    
        if (isset($params['agency_id']) && trim($params['agency_id']) !== "" ){
            $query->where('agency_contract.agency_id',$params['agency_id']);
        }
        if (isset($params['request_by']) && trim($params['request_by']) !== "" ){
            $query->where('agency_contract.request_by',$params['request_by']);
        }
        if (isset($params['request_status']) && trim($params['request_status']) !== "" ){
            $query->where('agency_contract.request_status',$params['request_status']);
        }
        if (isset($params['email']) && trim($params['email']) !== "" ){
            $query->where('users.email', 'LIKE', '%'.trim($params['email']).'%');
        }
        
        return $query;
    }
}
