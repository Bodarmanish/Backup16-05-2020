<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Position;
use App\Models\HostCompany;

class Placement extends Model
{
    protected $table = 'placement';
    
    public function position(){
        return $this->belongsTo('App\Models\Position','pos_id');
    }
    
    public function hostCompany(){
        return $this->belongsTo('App\Models\HostCompany','hc_id');
    }
    
    public function documents(){
        return $this->hasMany('App\Models\Document','placement_id');
    }
}
