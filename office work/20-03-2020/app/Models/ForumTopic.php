<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Auth;
use App\Models\ForumTags;

class ForumTopic extends Model {

    protected $table = "forum_topics";
    public $primaryKey = "id";
    public $timestamps = true;

    
    /**
     * Get Topic List 
     *
     * @return array
     */
    public function getTopicList()
    { 
        $user = Auth::user(); 
        if(Auth::check()){
            $user_id = $user->id;
            $result = DB::table('forum_topics as ft')
                    ->select('ft.id as ft_id','ft.user_id','ft.title as ft_title','ft.slug as ft_slug','ft.description as ft_desc','ftf.is_favorite as fuft_status','ftf.notification_status as ftn_status',DB::raw("GROUP_CONCAT(DISTINCT(ftag.slug)) as tag_slug"),DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title"),'ftf.is_report')
                    ->leftJoin('forum_topic_follow as ftf', function($join) use($user_id) {
                        $join->on('ftf.forum_topics_id', '=', 'ft.id');
                        $join->where('ftf.user_id', '=', $user_id);
                    })
                    ->leftJoin('forum_topic_follow as ftr', function($join)
                        {
                            $join->on('ftr.forum_topics_id', '=', 'ft.id'); 
                            $join->where('ftf.is_report', '=', '1');
                        })
                    ->leftjoin("forum_tags as ftag",DB::raw("FIND_IN_SET(ftag.id,ft.tags)"),">",DB::raw("0"))
                    ->where('ft.status','1')
                    ->whereNull('ftr.id')
                    ->groupBy('ft.id')
                    ->orderBy('ft.id','DESC')
                    ->get();
        }else{
            $result = DB::table('forum_topics as ft')
                    ->select('ft.id as ft_id','ft.title as ft_title','ft.slug as ft_slug','ft.description as ft_desc','ftf.notification_status as ftn_status',DB::raw("GROUP_CONCAT(DISTINCT(ftag.slug)) as tag_slug"),DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title")) 
                    ->leftJoin('forum_topic_follow as ftf', function($join) {
                        $join->on('ftf.forum_topics_id', '=', 'ft.id');
                    })
                    ->leftJoin('forum_topic_follow as ftr', function($join)
                        {
                            $join->on('ftr.forum_topics_id', '=', 'ft.id'); 
                            $join->where('ftf.is_report', '=', '1');
                        })
                    ->leftjoin("forum_tags as ftag",DB::raw("FIND_IN_SET(ftag.id,ft.tags)"),">",DB::raw("0"))
                    ->where('ft.status','1')
                    ->whereNull('ftr.id')
                    ->groupBy('ft.id')
                    ->orderBy('ft.id','DESC')
                    ->get();   
        }
        return $result;
    } 
    
    /**
     * Get forum topic by sub category id
     *
     * @return array
     */
    public function getforumTopicsById($fs_id) {
       
        $user = Auth::user();

        if (Auth::check()) {
            $user_id = $user->id;
            $result = DB::table('forum_topics as ft')
                    ->select('ft.*', DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title"), DB::raw("GROUP_CONCAT(DISTINCT(ftag.slug)) as tag_slug"), DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"), 'ftf.is_favorite as fuft_status',DB::raw("(SELECT COUNT(DISTINCT ff.id) FROM forum_topic_follow as ff WHERE ff.forum_topics_id = $fs_id AND ff.user_id = $user_id) as ftf_status"),'ftf.notification_status as ftn_status', 'ftf.is_report')
                    ->leftJoin('users as ut', 'ut.id', '=', 'ft.user_id')
                    ->leftjoin("forum_tags as ftag", DB::raw("FIND_IN_SET(ftag.id,ft.tags)"), ">", DB::raw("0"))
                    ->leftJoin('forum_topic_follow as ftf', function($join) {
                        $join->on('ftf.forum_topics_id', '=', 'ft.id');
                    })
                    ->leftJoin('forum_topic_follow as ftr', function($join)
                        {
                            $join->on('ftr.forum_topics_id', '=', 'ft.id'); 
                            $join->where('ftf.is_report', '=', '1');
                        })
                    ->where('ft.forum_category_id', $fs_id)
                    ->where('ft.status', '1')
                    ->whereNull('ftr.id')         
                    ->groupBy('ft.id')
                    ->get();
                     
        } else {
            $result = DB::table('forum_topics as ft')
                    ->select('ft.*', DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title"), DB::raw("GROUP_CONCAT(DISTINCT(ftag.slug)) as tag_slug"), DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"))
                    ->leftJoin('users as ut', 'ut.id', '=', 'ft.user_id')
                    ->leftjoin("forum_tags as ftag", DB::raw("FIND_IN_SET(ftag.id,ft.tags)"), ">", DB::raw("0"))
                    ->leftJoin('forum_topic_follow as ftf', function($join) {
                        $join->on('ftf.forum_topics_id', '=', 'ft.id');
                    })
                    ->where('ft.forum_category_id', $fs_id)
                    ->where('ft.status', '1')
                    ->groupBy('ft.id')
                    ->get();
        }
       
        return $result;
    }

    /**
     * Get Topic List By Tag Id
     *
     * @return array
     */
    public function topicByTagId($tagSlug) {
    
        $tagslug = collect(ForumTags::where('slug', $tagSlug)->first())->all();
        $tagId = $tagslug['id'];

        $user = Auth::user();
        if (Auth::check()) {
            $user_id = $user->id;
            $result = DB::table('forum_topics as ft')
                    ->select('ft.id as ft_id', 'ft.user_id','ft.title as ft_title', 'ft.slug as ft_slug', 'ft.description as ft_desc', 'ftf.is_favorite as ftf_status', 'ftf.notification_status as ftn_status', 'ft.tags as ft_tags', 'ftag.title as tag_title')
                    ->leftJoin('forum_topic_follow as ftf', function($join) use($user_id) {
                        $join->on('ftf.forum_topics_id', '=', 'ft.id');
                        $join->where('ftf.user_id', '=', $user_id);
                    })
                    ->leftJoin('forum_topic_follow as ftr', function($join)
                    {
                        $join->on('ftr.forum_topics_id', '=', 'ft.id'); 
                        $join->where('ftf.is_report', '=', '1');
                    })
                    ->join("forum_tags as ftag", DB::raw("FIND_IN_SET($tagId,ft.tags)"), ">", DB::raw("'0'"))
                    ->where('ft.status', '1')
                    ->whereNull('ftr.id')
                    ->groupBy('ft.id')
                    ->orderBy('ft.id', 'DESC')
                    ->get();
        } else {
            $result = DB::table('forum_topics as ft')
                    ->select('ft.id as ft_id', 'ft.title as ft_title', 'ft.slug as ft_slug', 'ft.description as ft_desc', 'ft.tags as ft_tags', 'ftag.title as tag_title')
                    ->join("forum_tags as ftag", DB::raw("FIND_IN_SET($tagId,ft.tags)"), ">", DB::raw("'0'"))
                    ->where('ft.status', '1')
                    ->groupBy('ft.id')
                    ->orderBy('ft.id', 'DESC')
                    ->get();
        }
        return $result;
    }

    /**
     * add viewer count for topic
     *
     * @return array
     */
    public function addViewerId($ft_id) {
        $user = Auth::user();
        if (Auth::check()) {
            $user_id = $user->id;
            $insert_data = array();
            $insert_data['forum_topics_id'] = $ft_id;
            $insert_data['user_id'] = $user_id;
           
            $alreadyDidAction = DB::table('forum_topic_viewer')
                    ->select('id')
                    ->where('forum_topics_id', $ft_id)
                    ->where('user_id', $user_id)
                    ->first();
            if (empty($alreadyDidAction->id)) {
                $insert_data['created_at'] = DB::raw('NOW()');
                return DB::table('forum_topic_viewer')->insert($insert_data);
            }
        }
        return false;
    }

    /**
     * Get topic detail using topic id
     *
     * @return array
     */
    public function getTopicDetailById($ft_id) {
            
        $user = Auth::user();
        if (Auth::check()) {
            $user_id = $user->id;
            $result = DB::table('forum_topics as ft')
                    ->select('ft.id as ft_id', 'ft.title as ft_title', 'ft.description as ft_desc', 'ft.created_at as ft_created_at', 'ut.id', 'ut.profile_photo', 'geo.country_name', 'ftl.status as ftl_status', 'ftf.is_favorite as fuft_status', DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"), DB::raw("(SELECT COUNT(DISTINCT ff.id) FROM forum_topic_follow as ff WHERE ff.forum_topics_id = $ft_id AND ff.user_id = $user_id) as ftf_status"))
                    ->leftJoin('users as ut', 'ut.id', '=', 'ft.user_id')
                    ->leftJoin('user_details as ud', 'ud.user_id', '=', 'ft.user_id')
                    ->leftJoin('geo_country as geo', 'geo.country_id', '=', 'ud.country')
                    ->leftJoin('forum_topic_likes as ftl', function($join) use($ft_id, $user_id) {
                        $join->on('ftl.forum_topics_id', '=', 'ft.id')
                        ->where('ftl.user_id', '=', $user_id)
                        ->where('ftl.forum_comment_id', '=', '0')
                        ->where('ftl.forum_topics_id', '=', $ft_id);
                    })
                    ->leftJoin('forum_topic_follow as ftf', function($join) use($ft_id, $user_id) {
                        $join->on('ftf.forum_topics_id', '=', 'ft.id')
                        ->where('ftf.user_id', '=', $user_id)
                        ->where('ftf.forum_topics_id', '=', $ft_id);
                    })
                    ->where('ft.id', $ft_id)
                    ->first();
        } else {
            $result = DB::table('forum_topics as ft')
                    ->select('ft.id as ft_id', 'ft.title as ft_title', 'ft.description as ft_desc', 'ft.created_at as ft_created_at','ft.user_id', 'ut.id', 'ut.profile_photo', 'geo.country_name', DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"))
                    ->leftJoin('users as ut', 'ut.id', '=', 'ft.user_id')
                    ->leftJoin('user_details as ud', 'ud.user_id', '=', 'ft.user_id')
                    ->leftJoin('geo_country as geo', 'geo.country_id', '=', 'ud.country')
                    ->where('ft.id', $ft_id)
                    ->first();
        }

        return $result;
    }

    /**
     * Get topic comment list by topic id
     *
     * @return array
     */
    public function getTopicCommentList($ft_id) {
        
        $user = Auth::user();
      
        if (Auth::check()) 
        {
            $user_id = $user->id;
           /* $result = DB::table('forum_topic_comments as ftc')
                    ->select('ftc.id as ftc_id', 'ftc.parent_comment_id as ftc_parent_id', 'ftc.created_at as ftc_created_at', 'ftc.comment_text as ftc_comment', 'ut.id as user_id', 'ut.profile_photo', 'fcl.status as fcl_status', DB::raw("(SELECT COUNT(DISTINCT fcl.id) FROM forum_topic_comments as fcl WHERE fcl.status = '1' AND fcl.id = ftc.id) as comment_count"), DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"))
                    ->leftJoin('users as ut', 'ut.id', 'ftc.user_id')
                    ->leftJoin('forum_topic_comments as fcl', function($join) use($ft_id, $user_id)
                        {
                            $join->on('fcl.id', '=', 'ftc.id')
                            ->where('fcl.user_id', '=', $user_id)
                            ->where('fcl.forum_topics_id', '=', $ft_id); 
                        })
                    ->where('ftc.forum_topics_id', $ft_id)
                    ->orderBy('ftc.id', 'ASC')
                    ->get(); */
            $result = DB::table('forum_topic_comments as ftc')
                    ->select('ftc.id as ftc_id', 'ftc.parent_comment_id as ftc_parent_id', 'ftc.created_at as ftc_created_at', 'ftc.comment_text as ftc_comment', 'ut.id as user_id', 'ut.profile_photo', 'fcl.status as fcl_status', DB::raw("(SELECT COUNT(DISTINCT fcl.id) FROM forum_topic_likes as fcl WHERE fcl.status = '1' AND fcl.forum_comment_id = ftc.id) as comment_count"), DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"))
                    ->leftJoin('users as ut', 'ut.id', 'ftc.user_id')
                    ->leftJoin('forum_topic_likes as fcl', function($join) use($ft_id, $user_id)
                        {
                            $join->on('fcl.forum_comment_id', '=', 'ftc.id')
                            ->where('fcl.user_id', '=', $user_id)
                            ->where('fcl.forum_topics_id', '=', $ft_id); 
                        })
                    ->where('ftc.forum_topics_id', $ft_id)
                    ->orderBy('ftc.id', 'ASC')
                    ->get();
        }
        else
        {
            $result = DB::table('forum_topic_comments as ftc')
                    ->select('ftc.id as ftc_id', 'ftc.parent_comment_id as ftc_parent_id', 'ftc.created_at as ftc_created_at', 'ftc.comment_text as ftc_comment', 'ut.id as user_id', 'ut.profile_photo', DB::raw("(SELECT COUNT(DISTINCT fcl.id) FROM forum_topic_likes as fcl WHERE fcl.status = '1' AND fcl.forum_comment_id = ftc.id) as comment_count"), DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"))
                    ->leftJoin('users as ut', 'ut.id', 'ftc.user_id')
                    ->where('ftc.forum_topics_id', $ft_id)
                    ->orderBy('ftc.id', 'ASC')
                    ->get();
        }

        return $result;
    }

    /**
     * Like and Dislike Topic
     *
     * @return array
     */
    public function likeOrDislike($data) {
           
        if ($data['topic_actionfor'] == "comment") 
        {
            $insert_data = array();
            $insert_data['forum_comment_id'] = $data['comment_id'];
            $insert_data['user_id'] = $data['user_id'];
            $insert_data['status'] = $data['topic_action_value'];
            $insert_data['forum_topics_id'] = $data['topic_id'];

            $alreadyDidAction = DB::table('forum_topic_likes')
                    ->select('id')
                    ->where('forum_comment_id', $data['comment_id'])
                    ->where('user_id', $data['user_id'])
                    ->first();

            if (!empty($alreadyDidAction->id)) {
                $result = DB::table('forum_topic_likes')->where('id', $alreadyDidAction->id)->update($insert_data);
            } else {
                $insert_data['created_at'] = DB::raw('NOW()');
                $result = DB::table('forum_topic_likes')->insert($insert_data);
            }
            return $result;
        } 
        elseif ($data['topic_actionfor'] == "topic") 
        {
            $insert_data = array();
            $insert_data['forum_topics_id'] = $data['topic_id'];
            $insert_data['user_id'] = $data['user_id'];
            $insert_data['status'] = $data['topic_action_value'];

            $alreadyDidAction = DB::table('forum_topic_likes')
                    ->select('id')
                    ->where('forum_topics_id', $data['topic_id'])
                    ->where('user_id', $data['user_id'])
                    ->where('forum_comment_id', '0')
                    ->first();

            if (!empty($alreadyDidAction->id)) {
                $result = DB::table('forum_topic_likes')->where('id', $alreadyDidAction->id)->update($insert_data);
            } else {
                $insert_data['created_at'] = DB::raw('NOW()');
                $result = DB::table('forum_topic_likes')->insert($insert_data);
            }
            return $result;
        }
        else
        {
            return false;
        }
    }
    
     
    /**
     * Get latest comment data
     *
     * @return array
     */
    public function getLatestComment($comment_id)
    {
        $result = DB::table('forum_topic_comments as ftc') 
                    ->select('ftc.id as ftc_id','ftc.forum_topics_id as ftc_ft_id','ftc.parent_comment_id as ftc_parent_id','ftc.comment_text as ftc_comment','ut.id','ut.profile_photo','fcl.status as fcl_status', DB::raw("(SELECT COUNT(DISTINCT fcl.id) FROM forum_topic_likes as fcl WHERE fcl.status = '1' AND fcl.forum_comment_id = ftc.id) as comment_count"),DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name")) 
                    ->leftJoin('users as ut','ut.id','ftc.user_id')
                    ->leftJoin('forum_topic_likes as fcl', function($join) 
                        {
                            $join->on('fcl.forum_comment_id', '=', 'ftc.id');
                        })
                    ->where('ftc.id',$comment_id) 
                    ->first(); 
        return $result; 
    } 
    
        /**
     * Get User Topic List 
     *
     * @return array
     */
    public function getMyTopicList($user_id)
    {
        $result = DB::table('forum_topics as ft')
                ->select('ft.id as ft_id','ft.title as ft_title','ft.slug as ft_slug','ft.description as ft_desc','ft.notify_me_of_replies as ftn_status',DB::raw("GROUP_CONCAT(DISTINCT(ftag.slug)) as tag_slug"),DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title"))
                ->leftjoin("forum_tags as ftag",DB::raw("FIND_IN_SET(ftag.id,ft.tags)"),">",DB::raw("0"))
                ->where('ft.user_id','=',$user_id)
                ->where('ft.status','1')
                ->groupBy('ft.id')
                ->orderBy('ft.id','DESC')
                ->get(); 
        return $result;
    } 
    
    
      /**
     * Get User Following Topic List 
     *
     * @return array
     */
    public function getUserFollowingTopicList($user_id)
    { 
        $result = DB::table('forum_topics as ft')
                ->select('ft.id as ft_id','ft.title as ft_title','ft.slug as ft_slug','ft.description as ft_desc','ftf.is_favorite as fuft_status','ftf.notification_status as ftn_status',DB::raw("GROUP_CONCAT(DISTINCT(ftag.slug)) as tag_slug"),DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title"))
                ->leftJoin('forum_topic_follow as ftf', function($join) use($user_id) {
                    $join->on('ftf.forum_topics_id', '=', 'ft.id');
                    $join->where('ftf.user_id', '=', $user_id);
                })
                ->leftJoin('forum_topic_follow as ftr', function($join)
                {
                    $join->on('ftr.forum_topics_id', '=', 'ft.id'); 
                    $join->where('ftf.is_report', '=', '1');
                })
                ->leftjoin("forum_tags as ftag",DB::raw("FIND_IN_SET(ftag.id,ft.tags)"),">",DB::raw("0"))
                ->where('ftf.user_id', '=', $user_id)
                ->where('ft.user_id', '!=', $user_id)
                ->where('ft.status','1')
                ->whereNull('ftr.id')
                ->groupBy('ft.id')
                ->orderBy('ft.id','DESC') 
                ->get();
                
        return $result;
    } 
    
    
     /**
     * Function getUserFavoriteTopic 
     * @param int $user_id users >> id 
     * **/
    public function getUserFavoriteTopic($user_id)
    {
        $result = DB::table('forum_topics as ft')
                ->select('ft.id as ft_id','ft.title as ft_title','ft.slug as ft_slug','ft.description as ft_desc','ft.created_at as ft_created_at','ftf.id as fuft_id','ftf.is_favorite as fuft_status',DB::raw("CONCAT(ut.first_name,' ',ut.last_name) AS user_name"),DB::raw("GROUP_CONCAT(DISTINCT(ftag.title)) as tag_title"),'ftf.notification_status as ftn_status')
                ->leftJoin('users as ut','ut.id','ft.user_id')
                ->leftjoin("forum_tags as ftag",DB::raw("FIND_IN_SET(ftag.id,ft.tags)"),">",DB::raw("0"))
                ->leftJoin('forum_topic_follow as ftf', function($join) use($user_id) {
                    $join->on('ftf.forum_topics_id', '=', 'ft.id');
                    $join->where('ftf.user_id', '=', $user_id);
                    $join->where('ftf.is_favorite', '=', 1);
                    })
                ->leftJoin('forum_topic_follow as ftr', function($join)
                   {
                       $join->on('ftr.forum_topics_id', '=', 'ft.id'); 
                       $join->where('ftf.is_report', '=', '1');
                   })
                ->where('ftf.user_id', '=', $user_id)
                ->where('ft.user_id', '!=', $user_id)
                ->where('ftf.is_favorite', '=', '1')
                ->where('ft.status','=', '1')
                ->whereNull('ftr.id')
                ->groupBy('ft.id')
                ->orderBy('ft.id','DESC') 
                ->get();        
        return $result;
    }

}
