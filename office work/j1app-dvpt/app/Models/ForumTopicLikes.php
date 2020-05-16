<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;


class ForumTopicLikes extends Model 
{   
    protected $table = "forum_topic_likes";
    
    public $primaryKey = "id";
    
    public $timestamps = true;
    

}
