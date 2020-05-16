<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Portfolio extends Model
{
    protected $table = "portfolio";
    
    public function userGeneral(){
        return $this->hasOne('App\Models\UserGeneral','portfolio_id');
    }
    
    public function documents(){
        return $this->hasMany('App\Models\Document','portfolio_id');
    }
    
    public function program(){
        return $this->belongsTo('App\Models\Programs','program_id');
    }
    
    public function route66Program(){
        return $this->belongsTo('App\Models\Programs','program_id','program_enroll_id')->where('category',3);
    }
    
    public function j1Interview(){
        return $this->hasOne('App\Models\J1Interview','portfolio_id');
    }
    
    public function agencyContracts(){
        return $this->hasMany('App\Models\AgencyContract','portfolio_id');
    }
    
    public function registrationAgency(){
        return $this->belongsTo('App\Models\Agency','registration_agency_id');
    }
    
    public function placementAgency(){
        return $this->belongsTo('App\Models\Agency','placement_agency_id');
    }
    
    public function sponsorAgency(){
        return $this->belongsTo('App\Models\Agency','sponsor_agency_id');
    }
    
    public function placements(){
        return $this->hasMany('App\Models\Placement','portfolio_id');
    }
    
    public function leads(){
        return $this->hasMany('App\Models\Lead','portfolio_id');
    }
    
    public function flightInfo(){
        return $this->hasMany('App\Models\FlightInfo','portfolio_id');
    }
    
    public function legal(){
        return $this->hasMany('App\Models\Legal','portfolio_id');
    }
    
    public function getUserDocument(){
        return $this->hasMany('App\Models\Document', 'portfolio_id')
                    ->select('id as document_id',
                            'document_filename',
                            'created_at as document_uploaded',
                            'document_status',
                            'document_reject_reason',
                            DB::raw('IF(document_status = 1,"Approved",IF(document_status = 2,"Rejected","Pending to Review")) AS document_status_name'), 
                            'updated_at as document_status_date');
    }
    
    public function getUserDocumentByType($doc_type){
        return $this->getUserDocument()->where('document_type',$doc_type)->get()->all();
    }
    
    public function getLog(){
        return $this->hasMany('App\Models\UserLog','portfolio_id');
    }
}
