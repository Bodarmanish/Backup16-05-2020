<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Comment extends Model
{
    protected $table = "forum_topic_comments";
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
    
    public static function deleteByCommentId($id){
        return DB::table('forum_topic_comments')->where('id', $id)->delete();
    }
    public function getComment($decrypt_id,$request_data = Null){
        
        $query = DB::table('forum_topic_comments')
               ->leftJoin('forum_topics', 'forum_topics.id','=','forum_topic_comments.forum_topics_id')
               ->leftJoin('users', 'users.id','=','forum_topic_comments.user_id')
               ->where('forum_topic_comments.forum_topics_id', '=',$decrypt_id )
               ->where('forum_topic_comments.parent_comment_id', '=',0 )
               ->select('forum_topic_comments.*','forum_topics.title as topic_name','users.first_name','users.last_name','users.email','forum_topics.id AS t_id',
                       \DB::raw("(SELECT count(ftc.id) FROM forum_topic_comments AS ftc
                          WHERE ftc.parent_comment_id = forum_topic_comments.id
                        ) as total_reply"));
        
        if(!empty($request_data['comment_text']))
        {
            $query = $query->where("forum_topic_comments.comment_text","like","%".$request_data['comment_text']."%");
        }
        if(!empty($request_data['first_name']))
        {
            $query = $query->where("users.first_name","like","%".$request_data['first_name']."%");
        }
        if(!empty($request_data['last_name']))
        {
            $query = $query->where("users.last_name","like","%".$request_data['last_name']."%");
        }   
        if(!empty($request_data['email']))
        {
            $query = $query->where("users.email","like","%".$request_data['email']."%");
        }   
        $topics = $query->get();
      
        if(!empty($topics))
        {
            return $topics;   
        }
        else
        {
            return false;
        }
    
    }
}
