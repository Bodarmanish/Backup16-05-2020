<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Forum extends Model
{
    protected $table = "forum_category";
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
    
    public static function deleteByForumName($forum_name){
        return DB::table('forum_category')->where('slug', $forum_name)->delete();
    }
    /**
    * Forum Sub Category filter
    *
    * @return array
    */
    public function scopeForum($query, $params)
    {    
        $query = DB::table('forum_category')
               ->select('forum_category.*')        
               ->where('parent_category_id', '=', '0');
        
        if ( isset($params['forum_category']) && trim($params['forum_category'] !== '') ){
            $query->where('forum_category.id', '=', trim($params['forum_category']));
        }
        
        return $query;
    }
    /**
    * Forum Sub Category filter
    *
    * @return array
    */
    public function scopeFilter($query, $params)
    {    
        $query = DB::table('forum_category')
               ->leftJoin('forum_category as f1', 'f1.id', '=', 'forum_category.parent_category_id')
               ->select('forum_category.*',
                        'f1.title as cat_name')        
               ->where('forum_category.parent_category_id', '!=', '0');
        
        if ( isset($params['forum_category']) && trim($params['forum_category'] !== '') ){
            $query->where('forum_category.parent_category_id', '=', trim($params['forum_category']));
        }
        if ( isset($params['forum_title_description']) && trim($params['forum_title_description']) !== '' ){
            $query->where('forum_category.title', 'LIKE', '%'.trim($params['forum_title_description']).'%');
            $query->orwhere('forum_category.description', 'LIKE', '%'.trim($params['forum_title_description']).'%');
        }
        return $query;
    }
    
    /**
     * Get forum category list for the  front end
     *
     * @return array
     */
    public function getForumCategories() {
      
        $result = DB::table('forum_category as fc')
                ->select('fc.*', 'ft.title as ft_title', 'ft.created_at as ft_created_at', DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title"), DB::raw("GROUP_CONCAT(DISTINCT(ftag.slug)) as tag_slug"), DB::raw("COUNT(DISTINCT(ft_total.id)) as topic_total"), DB::raw("COUNT(DISTINCT(ftc.id)) as comment_total"), DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"))
                ->leftJoin('forum_category as subcat', function ($join) {
                    $join->on('subcat.parent_category_id', '=', 'fc.id');
                })
                ->leftJoin('forum_topics as ft', function ($join) {
                    $join->on('ft.forum_category_id', '=', 'subcat.id');
                    $join->whereIn('ft.id', DB::table('forum_topics')
                            ->select(DB::raw('MAX(forum_topics.id) as ft_id'))
                            ->leftJoin('forum_category', 'forum_category.id', '=', 'forum_topics.forum_category_id')
                            ->where('forum_topics.status', '1')
                            ->groupBy('forum_category.id')
                    );
                })
                ->leftJoin('forum_topics as ft_total', function ($join) {
                    $join->on('ft_total.forum_category_id', '=', 'subcat.id');
                    $join->whereIn('ft_total.id', DB::table('forum_topics')
                            ->select('forum_topics.id as ft_id')
                            ->leftJoin('forum_category', 'forum_category.id', '=', 'forum_topics.forum_category_id')
                            ->where('forum_topics.status', '1')
                            ->groupBy('forum_category.id')
                    );
                })
                ->leftjoin("forum_tags as ftag", DB::raw("FIND_IN_SET(ftag.id,fc.tags)"), ">", DB::raw("0"))
                ->leftJoin('forum_topic_comments as ftc', function ($join) {
                    $join->on('ftc.forum_topics_id', '=', 'ft_total.id');
                    $join->where('ftc.parent_comment_id', '=', 0);
                    $join->where('ftc.status', '=', "1");
                })
                ->leftJoin('users as ut', 'ut.id', '=', 'ft.user_id')
                ->where('fc.status', '1')
                ->where('fc.parent_category_id', 0)
                ->groupBy('fc.id')
                ->orderBy('fc.id', 'ASC')
                ->get();
  
        return $result;
    }

    public function getsubForumCategories($fc_id) {
        
        $result = DB::table('forum_category as fs')
                        ->select('fs.*', 'ft.title as ft_title', 'ft.created_at as ft_created_at', DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title"), DB::raw("GROUP_CONCAT(DISTINCT(ftag.slug)) as tag_slug"), DB::raw("(SELECT COUNT(DISTINCT f.id) FROM forum_topics as f WHERE f.forum_category_id = fs.id) as topic_total"), DB::raw("COUNT(DISTINCT(ftc.id)) as comment_total"), DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"))
                        ->leftJoin('forum_topics as ft', function ($join) {
                            $join->on('ft.forum_category_id', '=', 'fs.id');
                            $join->whereIn('ft.id', DB::table('forum_topics')
                                    ->select(DB::raw('MAX(forum_topics.id) as ft_id'))
                                    ->leftJoin('forum_category', 'forum_category.id', '=', 'forum_topics.forum_category_id')
                                    ->where('forum_topics.status', '1')
                                    ->groupBy('forum_category.id')
                            );
                        })
                        ->leftJoin('forum_topics as ft_total', function ($join) {
                            $join->on('ft_total.forum_category_id', '=', 'fs.id');
                            $join->whereIn('ft_total.id', DB::table('forum_topics')
                                    ->select('forum_topics.id as ft_id')
                                    ->leftJoin('forum_category', 'forum_category.id', '=', 'forum_topics.forum_category_id')
                                    ->where('forum_topics.status', '1')
                            ->groupBy('forum_category.id')
                            );
                        })
                        ->leftJoin('users as ut', 'ut.id', '=', 'ft.user_id')
                        ->leftjoin("forum_tags as ftag", DB::raw("FIND_IN_SET(ftag.id,fs.tags)"), ">", DB::raw("'0'"))
                        ->leftJoin('forum_topic_comments as ftc', function ($join) {
                            $join->on('ftc.forum_topics_id', '=', 'ft_total.id');
                            $join->where('ftc.parent_comment_id', '=', 0);
                            $join->where('ftc.status', '=', "1");
                        })
                        ->where('fs.parent_category_id', $fc_id)
                        ->groupBy('fs.id')
                        ->orderBy('fs.id', 'DESC')->get();

        return $result;
    }

    /**
     * Get Category List
     *
     * @return array
     */
    public function getCategoryList()
    {
        $result = DB::table('forum_category as fc')
                ->select('fc.id','fc.slug','fc.title',DB::raw("(SELECT COUNT(DISTINCT f.id) FROM forum_category as f WHERE f.parent_category_id = fc.id) as topic_total"))
                ->where('fc.status','1') 
                ->where('fc.parent_category_id','0') 
                ->groupBy('fc.id')
                ->get();
        return $result;
    } 

}
