<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;


class ForumTopicComment extends Model 
{   
    protected $table = "forum_topic_comments";
    
    public $primaryKey = "id";
    
    public $timestamps = true;
    

}
