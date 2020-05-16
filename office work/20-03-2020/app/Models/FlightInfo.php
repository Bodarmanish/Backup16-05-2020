<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Storage;

class FlightInfo extends Model
{
    protected $table = "flight_info";
    public $primaryKey = "id";
    
}
