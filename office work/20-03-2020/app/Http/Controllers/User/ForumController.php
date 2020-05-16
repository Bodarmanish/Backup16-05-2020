<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\ForumTopic;
use App\Models\ForumTags;
use App\Models\ForumTopicLikes;
use App\Models\ForumTopicViewer;
use App\Models\ForumTopicComment;
use App\Models\ForumTopicFollow;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Response; 
use DB;
use Validator;
use Auth;
use Profanity;

class ForumController extends Controller {

    protected $forumTopic;
    protected $forum;
    protected $forumTags;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->forumTopic = new ForumTopic();
        $this->forum = new Forum();
        $this->forumTags = new ForumTags();
        parent::__construct();
    }

    public function categories(Request $request) {
        
        $data = array();
        $data['forum_category_list'] = $this->forum->getForumCategories();
        $data['tag_list'] = $this->forumTags->getTagList();

        if (!empty($data['forum_category_list']) && $request->is('api/*') == 0) {
            $data['forum_category_list'] = ForumController::ApplyPagination($request, $data['forum_category_list']);
        }
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.categories')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request, $fc_slug) {
        
        $data = array();
        $parent_category = collect(Forum::where('slug', $fc_slug)->first())->all();

        if ($parent_category == NULL) {
            if($request->is('api/*')){
                return apiResponse("success","",$data);
            }
            return view('errors.404');
        }

        $data['forum_sub_category_list'] = $this->forum->getsubForumCategories($parent_category['id']);
        $data['parent_cat_name'] = $parent_category['title'];
        $data['tag_list'] = $this->forumTags->getTagList();

        if (!empty($data['forum_sub_category_list']) && $request->is('api/*') == 0) {
            $data['forum_sub_category_list'] = ForumController::ApplyPagination($request, $data['forum_sub_category_list']);
        }
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.category')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function topicByTagId(Request $request, $tagSlug) {
        
        $data = array();
        $data['topic_list'] = $this->forumTopic->topicByTagId($tagSlug);
        $tag_name = collect(ForumTags::where('slug', $tagSlug)->first())->all();

        $data['tag_name'] = $tag_name['slug'];
        $data['category_list'] = $this->forum->getCategoryList();

        if (!empty($data['topic_list']) && $request->is('api/*') == 0) {
            $data['topic_list'] = ForumController::ApplyPagination($request, $data['topic_list']);
        }
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.topicbytag')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subCategory(Request $request, $fs_slug) {
        
        $data = array();
        $cat_info = Forum::where('forum_category.slug', $fs_slug)
            ->leftJoin('forum_category as fc', 'fc.id', '=', 'forum_category.parent_category_id')
            ->select('forum_category.*','fc.title as CatTitle','fc.slug as CatTitleSlug')->first();
        
        $subcatId = $cat_info['id'];
        
        
        if ($subcatId == NULL) {
            
            return view('errors.404');
        }
        
        $data['topic_list'] = $this->forumTopic->getforumTopicsById($subcatId);
        
        if (!empty($data['topic_list']) && $request->is('api/*') == 0) {
            $data['topic_list'] = ForumController::ApplyPagination($request, $data['topic_list']);
        }
        
        $data['tag_list'] = $this->forumTags->getTagList();
        $data['cat_info'] = arrayToObject($cat_info);
       
        if (!empty($data)) {
            $data = [
                'topic_list' => $data['topic_list'],
                'tag_list' => $data['tag_list'],
                'cat_info' => $data['cat_info'],
                'sub_cat_info' => $data['cat_info']
            ];
        }      
       if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.subcategory')->with('data', $data);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTopic(){
        
        $data = array();
        $data['category'] =  collect(Forum::where('forum_category.parent_category_id', 0)
            ->join('forum_category as fc', 'fc.parent_category_id', '=', 'forum_category.id')
            ->select('forum_category.*')
            ->groupby('forum_category.id')
            ->get())->all();

        $data['tag_list'] = $this->forumTags->getTagList();
        return view('user.createtopic')->with('data',$data);
    }
    
    public function editTopic($id){
       
        $topic_id = decrypt($id);
        
        $data = array();
        $data['topic_id'] = $topic_id;
        $data['topic_detail'] = ForumTopic::where('id',$topic_id)->first();
        $topic = collect(Forum::where('id', $data['topic_detail']['forum_category_id'])->first())->all();
        $sub_category = collect(Forum::all()->where('parent_category_id', $topic['parent_category_id']))->all();
        $data['topic_detail']['main_cat'] =  $topic['parent_category_id'];
        $data['topic_detail']['sub_cat'] =  $sub_category;
        $data['topic_detail']['cat_tags'] = collect(ForumTags::whereRaw('FIND_IN_SET(id,?)', [$topic['tags']])->get())->all();
      
        $data['category'] =  collect(Forum::where('forum_category.parent_category_id', 0)
            ->join('forum_category as fc', 'fc.parent_category_id', '=', 'forum_category.id')
            ->select('forum_category.*')
            ->groupby('forum_category.id')
            ->get())->all();
        $data['tag_list'] = $this->forumTags->getTagList();
      
        return view('user.createtopic')->with('data',$data);
    }
    
    public function ajaxRequest(Request $request){
        
        $user = Auth::user();
        $user_id = $user->id;
        
        $action = trim($request->action); 
        switch($action){ 
            case "getSubCatByParentCatId":
                $data = array();
                $data['action'] = $action; 
                $data['subcategory'] = collect(Forum::all()->where('parent_category_id',$request->catId))->all();
                $HTML = view('user.ajax')->with('data',$data)->render(); 
                $response = ["type" => "success", "message" => "", "data" => $HTML];
            break;
        
            case "ForumTagListBySubCatId":
                $data = array();
                $data['action'] = $action; 
                $tag_info = Forum::where('forum_category.id', $request->subCatId)
                    ->leftJoin('forum_tags as ftag', DB::raw("FIND_IN_SET(ftag.id,forum_category.tags)"),">",DB::raw("'0'"))
                    ->select('ftag.title as tagtitle', 'ftag.id as tagid')->get();
                $data['forum_tag_list'] = $tag_info; 
                $HTML = view('user.ajax')->with('data',$data)->render(); 
                $response = ["type" => "success", "message" => "", "data" => $HTML];
            break;
        
            case "storeTopicDetail":
               
                $id = $request->topic_id;
                $mode = $request->mode;
                
                $title_rule =  $mode == 'Edit' ? 
                              ['title' => "required|unique:forum_topics,title,{$id},id"] :
                              ['title' => 'required|unique:forum_topics'];
               
                $rules = [
                    'category_id' => 'required',
                    'sub_category_id' => 'required',
                    'ft_desc' => 'required',
                ];

                $rules = array_merge($rules, $title_rule);
                
                $validationErrorMessages = [
                    'category_id.required'  => 'Please select category.',
                    'sub_category_id.required'  => 'Please select sub category.',
                    'title.required'  => 'Please enter forum title.',
                    'title.unique'  => 'The forum title has been already taken. Please select diffrent one.',
                    'ft_desc.required'  => 'Please enter forum description.',
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);
        

                if ( $validator->fails() ) {
                    $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                    return Response::json($response);
                } 
                
                if($mode == 'Edit' && !empty($id))
                {
                   $this->forumTopic = ForumTopic::where('id',$id)->first(); 
                
                }
               
                $forum_tag_list = isset($request->forum_tag_list) ? $request->forum_tag_list : "";
                $this->forumTopic->forum_category_id = $request->sub_category_id;
                $this->forumTopic->user_id = $user_id;
                $this->forumTopic->title = $request->title;
                $this->forumTopic->slug = str_slug($request->title."-". uniqid());
                $this->forumTopic->description = $request->ft_desc;
                $this->forumTopic->notify_me_of_replies = $request->notify_me_of_replies;
                $this->forumTopic->tags = custom_implode($forum_tag_list, ",");
                $this->forumTopic->status = '1';
                $this->forumTopic->save();
                $inserted_topic_id = $this->forumTopic->id;
                
                if($inserted_topic_id>0){
                    $ft_slug = collect(ForumTopic::where('id',$inserted_topic_id)->first())->all();
                    $message = $mode == 'Edit' ? "Your topic detail has been updated successfully." : "Your topic detail has been inserted successfully.";
                    $response = ["type" => "success", "message" => $message, "redirectURL" => url("topicdetail/".$ft_slug['slug'])];
                }else{
                    $response = ["type" => "error", "message" => ["Something went wrong. Please try again."]];
                    return Response::json($response); 
                }
                
            break;
            
            
            case "likeOrDislike": 
              
                $form_data = [ 
                    'user_id' => $user_id,
                    'topic_id' => safe_decrypt($request->topicId),
                    'topic_action_value' => $request->topic_action_value,
                    'topic_actionfor' => $request->topic_actionfor,
                    'comment_id' => $request->comment_id, 
                ];

                $data = $this->forumTopic->likeOrDislike($form_data);

                $data = array();
                if($request->comment_id > 0){
                    $data['likecount'] = count(ForumTopicLikes::all()
                      ->where('forum_comment_id',$request->comment_id)
                      ->where('status',1)
                    );
                }else{
                    $data['likecount'] = count(ForumTopicLikes::all()->where('forum_topics_id',safe_decrypt($request->topicId))->where('status',1)->where('forum_comment_id',0));
                }
                
                $response = ["type" => "success", "message" => "", "data" => $data];
            break; 
            
            case "notifyTopics": 
                $forum_topic_follow = ForumTopicFollow::where('forum_topics_id',$request->topicId)->where('user_id',$user_id)->first();
             
                if(!empty($forum_topic_follow->id))
                {
                    $forum_topic_follow->forum_topics_id = $request->topicId;
                    $forum_topic_follow->user_id = $user_id;
                    $forum_topic_follow->notification_status = $request->status; 
                    $forum_topic_follow->save();
                }
                else
                {
                    $forum_topic_follow = new ForumTopicFollow(); 
                    $forum_topic_follow->forum_topics_id = $request->topicId;
                    $forum_topic_follow->user_id = $user_id;
                    $forum_topic_follow->notification_status = $request->status; 
                    $forum_topic_follow->save();
                }
                
                $response = ["type" => "success", "message" => ""];
            break;
            
            case "notifyme": 
                $forum_topic = ForumTopic::where('id',$request->topicId)->where('user_id',$user_id)->first();
                $forum_topic->notify_me_of_replies = $request->status; 
                $forum_topic->save();
                
                $response = ["type" => "success", "message" => ""];
            break;
            
            case "followOrUnfollow":  
                
                $forum_topic_follow = ForumTopicFollow::where('forum_topics_id',safe_decrypt($request->topicId))->where('user_id',$user_id)->first();
           
                if(!empty($forum_topic_follow->id))
                {
                   ForumTopicFollow::where('id',$forum_topic_follow->id)->delete();
                }
                else
                {
                    $forum_topic_follow = new ForumTopicFollow(); 
                    $forum_topic_follow->forum_topics_id = safe_decrypt($request->topicId);
                    $forum_topic_follow->user_id = $user_id;
                    $forum_topic_follow->save();
                    $inserted_id = $forum_topic_follow->id;
                }
                
                $response = ["type" => "success", "message" => ""];
            break;
            
            
            case "addToFavorite":  
                $forum_topic_follow = ForumTopicFollow::where('forum_topics_id',safe_decrypt($request->topicId))->where('user_id',$user_id)->first();
           
                if(!empty($forum_topic_follow->id))
                {
                    $forum_topic_follow->forum_topics_id = safe_decrypt($request->topicId);
                    $forum_topic_follow->user_id = $user_id;
                    $forum_topic_follow->is_favorite = $request->status; 
                    $forum_topic_follow->save();
                    $inserted_id = $forum_topic_follow->id;
                }
                else
                {
                    $forum_topic_follow = new ForumTopicFollow(); 
                    $forum_topic_follow->forum_topics_id = safe_decrypt($request->topicId);
                    $forum_topic_follow->user_id = $user_id;
                    $forum_topic_follow->is_favorite = $request->status; 
                    $forum_topic_follow->save();
                    $inserted_id = $forum_topic_follow->id;
                }
                
                if( $inserted_id>0){ 
                    $topicdata = array();
                    $topicdata['action'] = 'fvttopicmenu';
                    $topicdata['status'] = $request->status;
                    $topicdata['topicdetail'] = ForumTopic::where('id',safe_decrypt($request->topicId))->first(); 
                    $topicdata['fuft_id'] = $inserted_id;
                    $HTML = view('user.ajax')->with('data',$topicdata)->render();  
                }else{
                    $HTML = "";
                }  
                $data = array();
                $data['html'] = $HTML;
                $data['last_id'] = $inserted_id;
                $data['topicId'] = safe_decrypt($request->topicId);
                $response = ["type" => "success", "message" => "","data" => $data];
            break; 
            
            case "addComment":
                $validator = Validator::make($request->all(), [
                    'topic_id' => 'required',
                    'comment_text' => 'required'
                ]);

                if ( $validator->fails() ) {
                    $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                    return Response::json($response);
                }

                $comment_text = $request->get('comment_text');
                
               /* $isDirty = Profanity::blocker($comment_text)->clean();
                if($isDirty>0){
                    $status = '1';
                }else{
                    $status = '0';
                } 
               $comment_text = Profanity::blocker($comment_text)->filter();*/
                
               $comment = new ForumTopicComment();
               $comment->parent_comment_id = $request->get('reply_id');
               $comment->forum_topics_id = safe_decrypt($request->get('topic_id'));
               $comment->user_id =$user_id;
               $comment->comment_text =$comment_text;
               $comment->status =1;
               $comment->save();
               $inserted_id = $comment->id;
               
                if(!is_null($inserted_id)){
                    $commentdata = $this->forumTopic->getLatestComment($inserted_id);
                    $HTML = view('user.latestcomment')->with('commentdata',$commentdata)->render();
                }else{
                    $HTML = "";
                }    

                $data = array();
                $data['topiccommentcount'] = count(ForumTopicComment::all()
                        ->where('forum_topics_id',safe_decrypt($request->topic_id))
                        ->where('parent_comment_id',0)
                        ->where('status',1)
                    );
                $data['html'] = $HTML;
                $data['last_reply_id'] = $inserted_id;
                $response = ["type" => "success", "message" => "Your comment has been inserted successfully.", "data" => $data];
            break; 
            
            case "reportTopic":	
                $data = array();
                $data['action'] = $action;
                $data['topicId'] = $request->topicId; 
                $HTML = view('user.ajax')->with('data',$data)->render(); 
                $response = ["type" => "success", "message" => "", "data" => $HTML];
            break;
           
            case "deleteTopics":
                $topic_id = $request->get('topicId'); 
                
                ForumTopic::where('id',$topic_id)->delete();
                ForumTopicComment::where('forum_topics_id',$topic_id)->delete();
                ForumTopicFollow::where('forum_topics_id',$topic_id)->delete();
                ForumTopicLikes::where('forum_topics_id',$topic_id)->delete();
                ForumTopicViewer::where('forum_topics_id',$topic_id)->delete();
                
                $response = ["type" => "success", "message" => ""];
            break;
        
            case "deleteComment":
                $comment_id = $request->get('commentId'); 
                $topic_id = $request->get('topicId');
             
                ForumTopicComment::where('id',$comment_id)->orWhere('parent_comment_id',$comment_id)->delete();
                ForumTopicLikes::where('forum_comment_id',$comment_id)->delete();
                
                $topic_comment_count = count(ForumTopicComment::all()
                    ->where('forum_topics_id',$topic_id)
                    ->where('parent_comment_id',0)
                );

                $response = ["type" => "success", "message" => "","topic_comment_count" => $topic_comment_count];
            break;
        
            case "submitReport":
                $forum_topic_follow = ForumTopicFollow::where('forum_topics_id',$request->topicId)->where('user_id',$user_id)->first();
                $status = isset($request->status) ? 0 : 1;
                $reason = isset($request->status) ? "" : $request->reason;
               
                if(!empty($forum_topic_follow->id))
                {
                    $forum_topic_follow->forum_topics_id = $request->topicId;
                    $forum_topic_follow->user_id = $user_id;
                    $forum_topic_follow->is_report = $status; 
                    $forum_topic_follow->report_reason = $reason; 
                    $forum_topic_follow->save();
                    $inserted_id = $forum_topic_follow->id;
                }
                else
                {
                    $forum_topic_follow = new ForumTopicFollow(); 
                    $forum_topic_follow->forum_topics_id = $request->topicId;
                    $forum_topic_follow->user_id = $user_id;
                    $forum_topic_follow->is_report = $status; 
                    $forum_topic_follow->report_reason = $reason; 
                    $forum_topic_follow->save();
                    $inserted_id = $forum_topic_follow->id;
                }

                $response = ["type" => "success", "message" => ""];
            break;
            
            default :
                /*$response = ["type" => "error", "message" => "", "data" => $HTML]; */
                $response = 'no_data'; 
            break;
        
        }
        return Response::json($response);  
    } 
    
    
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function topicDetail($ft_slug)
    { 
        $forum_topic = collect(ForumTopic::where('slug',$ft_slug)->first())->all();

        if ($forum_topic == NULL)
        {
            if(starts_with(request()->path(), 'api')) { 
                return ['type' => "success","message" => "Data Not Found!"]; 
            }
            return view('errors.404');
        }
     
        $this->forumTopic->addViewerId($forum_topic['id']);
        
        $data = array();
        $data['topic_detail'] = $this->forumTopic->getTopicDetailById($forum_topic['id']); 
        $data['topic_comment_list'] = $this->forumTopic->getTopicCommentList($forum_topic['id']);
        $data['topic_like_count'] = count(ForumTopicLikes::all()->where('forum_topics_id',$forum_topic['id'])->where('status',1)->where('forum_comment_id',0));
        $data['topic_view_count'] = count(ForumTopicViewer::all()->where('forum_topics_id',$forum_topic['id']));
        $data['topic_comment_count'] = count(ForumTopicComment::all()
                ->where('forum_topics_id',$forum_topic['id'])
                ->where('parent_comment_id',0)
            );
        $data['category_list'] = $this->forum->getCategoryList();
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('user.topicdetail')->with('data',$data); 
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myTopics(Request $request)
    { 
        $user = Auth::user();
        $user_id = $user->id;
        $data = array();
        $data['topic_list'] = $this->forumTopic->getMyTopicList($user_id);
        $data['tag_list'] = $this->forumTags->getTagList();
        
        if (!empty($data['topic_list']) && $request->is('api/*') == 0) {
            $data['topic_list'] = ForumController::ApplyPagination($request, $data['topic_list']);
        }
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.mytopics')->with('data',$data);
    }
    
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function following(Request $request)
    { 
        $user = Auth::user();
        $user_id = $user->id;
        $data = array();
        $data['topic_list'] = $this->forumTopic->getUserFollowingTopicList($user_id);
        $data['tag_list'] = $this->forumTags->getTagList();
        
        if (!empty($data['topic_list']) && $request->is('api/*') == 0) {
            $data['topic_list'] = ForumController::ApplyPagination($request, $data['topic_list']);
        }
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.following')->with('data',$data);
    }
    
    
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userFavoriteTopic(Request $request)
    {  
        $user = Auth::user();
        $user_id = $user->id;
        $data = array();
        $data['topic_list'] = $this->forumTopic->getUserFavoriteTopic($user_id);  
        $data['tag_list'] = $this->forumTags->getTagList();
        
        if (!empty($data['topic_list']) && $request->is('api/*') == 0)
        {
            $data['topic_list'] = ForumController::ApplyPagination($request, $data['topic_list']);
        }
        
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.favoritetopic')->with('data',$data);
    }
    
    
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recentDiscussions(Request $request)
    {   
        $data = array();
        $data['topic_list'] = $this->forumTopic->getTopicList();
        $data['tag_list'] = $this->forumTags->getTagList();
        
        if (!empty($data['topic_list']) && $request->is('api/*') == 0)
        {
            $data['topic_list'] = ForumController::ApplyPagination($request, $data['topic_list']);
        }
        if($request->is('api/*')){
            return apiResponse("success","",$data);
        }
        return view('user.recentdiscussions')->with('data',$data);
    } 

    
    public function ApplyPagination($request, $array_name) {
        /* Start Pagination */
        $currentPage = Paginator::resolveCurrentPage();
        $col = collect($array_name);
        $perPage = config("common.page_limit");
        $currentPageItems = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $result = new Paginator($currentPageItems, count($col), $perPage);
        $result->setPath($request->url());
        $result->appends($request->all());
        return $result;
        /* End Pagination */
    }
    

}
