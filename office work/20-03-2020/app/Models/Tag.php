<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tag extends Model
{
    protected $table = "forum_tags";
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
}
