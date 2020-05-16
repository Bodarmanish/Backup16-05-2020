<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Programs extends Model
{
    protected $table = "programs";
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
}
