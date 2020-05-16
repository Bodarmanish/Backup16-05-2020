<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;


class ForumTopicFollow extends Model 
{   
    protected $table = "forum_topic_follow";
    
    public $primaryKey = "id";
    
    public $timestamps = true;
    

}
