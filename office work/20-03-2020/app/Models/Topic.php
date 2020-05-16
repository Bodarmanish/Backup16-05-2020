<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Topic extends Model
{
    protected $table = "forum_topics";
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
    /**
    * Function deleteByTopicName()
    * This function for delete topic by slug
     */
    public static function deleteByTopicId($id){
        DB::table('forum_topic_comments')->where('forum_topics_id', $id)->delete();
        DB::table('forum_topic_follow')->where('forum_topics_id', $id)->delete();
        DB::table('forum_topic_likes')->where('forum_topics_id', $id)->delete();
        DB::table('forum_topic_viewer')->where('forum_topics_id', $id)->delete();
        return DB::table('forum_topics')->where('id', $id)->delete();
    }
    /**
    * Forum Topic filter
    *
    * @return array
    */
    public function scopeFilter($query, $params)
    {    
       $query = DB::table('forum_topics')
               ->leftJoin('forum_category as fc', 'fc.id','=','forum_topics.forum_category_id')
               ->leftJoin('users', 'users.id','=','forum_topics.user_id')
               ->select('forum_topics.*','users.first_name','users.last_name','users.email','fc.title AS cat_name',
                       \DB::raw("(SELECT count(*) FROM forum_topic_follow
                          WHERE forum_topic_follow.forum_topics_id = forum_topics.id
                        ) as total_followers"),
                       \DB::raw("(SELECT count(*) FROM forum_topic_likes
                          WHERE forum_topic_likes.forum_topics_id = forum_topics.id AND status = 1
                        ) as total_likes"),
                       \DB::raw("(SELECT count(*) FROM forum_topic_likes
                          WHERE forum_topic_likes.forum_topics_id = forum_topics.id AND status = 0
                        ) as total_unlikes"),
                       \DB::raw("(SELECT count(*) FROM forum_topic_viewer
                          WHERE forum_topic_viewer.forum_topics_id = forum_topics.id
                        ) as total_views")
                       )
               ->groupBy('forum_topics.id');
        
        if ( isset($params['forum_category']) && trim($params['forum_category'] !== '') ){
            $query->where('fc.parent_category_id', '=', trim($params['forum_category']));
        }
        if ( isset($params['forum_sub_category']) && trim($params['forum_sub_category'] !== '') ){
            $query->where('forum_topics.forum_category_id', '=', trim($params['forum_sub_category']));
        }
        if ( isset($params['first_name']) && trim($params['first_name'] !== '') ){
            $query->where('users.first_name', 'LIKE', '%'.trim($params['first_name']).'%');
        }
        if ( isset($params['last_name']) && trim($params['last_name'] !== '') ){
            $query->where('users.last_name', 'LIKE', '%'.trim($params['last_name']).'%');
        }
        if ( isset($params['email']) && trim($params['email'] !== '') ){
            $query->where('users.email', 'LIKE', '%'.trim($params['email']).'%');
        }
        if ( isset($params['topic_title_description']) && trim($params['topic_title_description']) !== '' ){
            $query->where('forum_topics.title', 'LIKE', '%'.trim($params['topic_title_description']).'%');
            $query->orwhere('forum_topics.description', 'LIKE', '%'.trim($params['topic_title_description']).'%');
        }
        return $query;
    }
    /**
    * Function getfollows()
    * This function for get follow list
     */
    public function getFollows($tid){
        $query = DB::table('forum_topic_follow')
               ->leftJoin('forum_topics', 'forum_topics.id','=','forum_topic_follow.forum_topics_id')
                ->leftJoin('forum_category', 'forum_category.id','=','forum_topics.forum_category_id')
               ->leftJoin('users', 'users.id','=','forum_topic_follow.user_id')
               ->leftJoin('user_details', 'user_details.user_id','=','forum_topic_follow.user_id')
               ->select('forum_topic_follow.*',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),'users.first_name','users.last_name','users.email','user_details.skype_id','user_details.phone_number','forum_topics.title AS topic_title','forum_category.title AS category_title')
                ->where("forum_topic_follow.forum_topics_id","=",$tid);
        $follows = $query->get();
        
        if(!empty($follows))
        {
            return $follows;   
        }
        else
        {
            return false;
        }
    }
    /**
    * Function getTopicNameById()
    * This function for get topic name by id
     */
    public function getTopicNameById($tid){
        $query = DB::table('forum_topics')
               ->leftJoin('forum_category', 'forum_category.id','=','forum_topics.forum_category_id')
               ->leftJoin('forum_category as f1', 'f1.id', '=', 'forum_category.parent_category_id')
               ->select('forum_topics.*','forum_category.title AS sub_category_title','f1.title AS category_title')
               ->where("forum_topics.id","=",$tid);
        $category = $query->first();
        
        if(!empty($category))
        {
            return $category;   
        }
        else
        {
            return false;
        }
    }
    /**
    * Function getLikes()
    * This function for get follow list
     */
    public function getLikes($tid){
        $query = DB::table('forum_topic_likes')
               ->leftJoin('users', 'users.id','=','forum_topic_likes.user_id')
               ->leftJoin('user_details', 'user_details.user_id','=','forum_topic_likes.user_id')
               ->select('forum_topic_likes.*',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),'users.first_name','users.last_name','users.email','user_details.skype_id','user_details.phone_number')
                ->where("forum_topic_likes.status","=",1)
                ->where("forum_topic_likes.forum_topics_id","=",$tid);
        $likes = $query->get();
        
        if(!empty($likes))
        {
            return $likes;   
        }
        else
        {
            return false;
        }
    }
    /**
    * Function getLikes()
    * This function for get follow list
     */
    public function getUnLikes($tid){
        $query = DB::table('forum_topic_likes')
               ->leftJoin('users', 'users.id','=','forum_topic_likes.user_id')
               ->leftJoin('user_details', 'user_details.user_id','=','forum_topic_likes.user_id')
               ->select('forum_topic_likes.*',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),'users.first_name','users.last_name','users.email','user_details.skype_id','user_details.phone_number')
                ->where("forum_topic_likes.status","=",0)
                ->where("forum_topic_likes.forum_topics_id","=",$tid);
        $likes = $query->get();
        
        if(!empty($likes))
        {
            return $likes;   
        }
        else
        {
            return false;
        }
    }
    /**
    * Function getViews()
    * This function for get follow list
     */
    public function getViews($tid){
        $query = DB::table('forum_topic_viewer')
               ->leftJoin('users', 'users.id','=','forum_topic_viewer.user_id')
               ->leftJoin('user_details', 'user_details.user_id','=','forum_topic_viewer.user_id')
               ->select('forum_topic_viewer.*',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),'users.first_name','users.last_name','users.email','user_details.skype_id','user_details.phone_number')
                ->where("forum_topic_viewer.forum_topics_id","=",$tid);
        $views = $query->get();
        
        if(!empty($views))
        {
            return $views;   
        }
        else
        {
            return false;
        }
    }
    /**
    * Function getReplies()
    * This function for get comment reply list
     */
    public function getReplies($cid){
        $query = DB::table('forum_topic_comments')
               ->leftJoin('forum_topic_comments AS ftc', 'ftc.id','=','forum_topic_comments.parent_comment_id')
               ->leftJoin('forum_topics', 'forum_topics.id','=','forum_topic_comments.forum_topics_id')
               ->leftJoin('users', 'users.id','=','forum_topic_comments.user_id')
               ->leftJoin('user_details', 'user_details.user_id','=','forum_topic_comments.user_id')
               ->select('forum_topic_comments.*',DB::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'),'users.email','forum_topics.id AS ft_id','forum_topics.title','user_details.skype_id','user_details.phone_number','ftc.comment_text AS parent_comment_title')
                ->where("forum_topic_comments.parent_comment_id","=",$cid);
        $reply = $query->get();
        
        if(!empty($reply))
        {
            return $reply;   
        }
        else
        {
            return false;
        }
    }
}
