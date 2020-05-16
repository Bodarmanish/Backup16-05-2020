<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HostCompany;
use App\Models\Position;

class Lead extends Model
{
    protected $table = "lead";
    
    protected $fillable = [
        'user_id','hc_id','pos_id'
    ];
    
    public function hostCompany(){
        return $this->belongsTo('App\Models\HostCompany','hc_id');
    }
    
    public function position(){
        return $this->belongsTo('App\Models\Position','pos_id');
    }
}
