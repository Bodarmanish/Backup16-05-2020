<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;


class ForumTopicViewer extends Model 
{   
    protected $table = "forum_topic_viewer";
    
    public $primaryKey = "id";
    
    public $timestamps = true;
    

}
