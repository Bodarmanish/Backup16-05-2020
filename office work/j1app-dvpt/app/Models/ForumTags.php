<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;


class ForumTags extends Model 
{   
    protected $table = "forum_tags";
    
    public $primaryKey = "id";
    
    public $timestamps = true;
    
       /**
     * Get tag list
     *
     * @return array
     */
    public function getTagList() {
        
        /*
            $result = DB::table('forum_tags as ftag')
                ->select('ftag.id', 'ftag.slug', 'ftag.title', DB::raw("COUNT(DISTINCT(ft.id)) as topic_total"))
                ->leftjoin("forum_topics as ft", DB::raw("FIND_IN_SET(ftag.id,ft.tags)"), ">", DB::raw("'0'"))
                ->where('ft.status', '1')
                ->groupBy('ftag.id')
                ->get();
         */
        
        $result = DB::table('forum_tags as ftag')
               ->select('ftag.id', 'ftag.slug', 'ftag.title', DB::raw("COUNT(DISTINCT(ft.id)) as topic_total"))
               ->leftjoin("forum_topics as ft", function ($join) {
                     $join->on( DB::raw("FIND_IN_SET(ftag.id,ft.tags)"), ">", DB::raw("'0'"));
                     $join->where('ft.status', '1');
               })
               ->groupBy('ftag.id')
               ->get();
        return $result;
    }
       
}
