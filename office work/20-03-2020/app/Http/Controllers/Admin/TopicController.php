<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use App\Models\Topic;
use App\Models\ForumTags;
use App\Models\Forum;
use App\Models\Tag;
use Validator;
use Auth;

class TopicController extends Controller {

    /**
     * The authenticated admin.protected.
     *
     *  
     */
    protected $admin;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {

        $forums_category = Forum::all();
        $params = $request->except('_token');
        $topicdata = Topic::filter($params)->get();
        
        if (!empty($topicdata)) {
            $data = [
                'topics' => $topicdata,
                'forums_category' => $forums_category,
            ];
        } 
        if ($request->is('api/*')) {
            return apiResponse("success","",$data);
        }
        return view('admin.topic')->with($data);
    }
    
    /**
     * Change the notify status.
     *
     * @return \Illuminate\Http\Response
    */
    public function changeStatus(Request $request) {
        $action = $request->get('action',"");
        $notify_id = $request->get('notify_id',"");
        $is_active = $request->get('is_active',"");
        
        if($action == "change_notify_me_status"){
            $topic = Topic::where('id', $notify_id)->first();
            $topic->notify_me_of_replies = $is_active;
            $topic->save();
            $LastInsertId = $topic->id;
            return apiResponse("success","Notify status updated successfully");
        }
        
    }
    /**
     * Get Forums Tags.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadTags(Request $request) {
        
        $query = $request->get('term','');
        
        $tags=Tag::where('title','LIKE','%'.$query.'%')->get();

        $data=array();
        foreach ($tags as $tag) {
                $data[]=array('value'=>$tag->id,'label'=>$tag->title);
        }
        if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    
        
    }
    /**
     * Get likes detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadTopicData(Request $request) {
        
        $tid = $request->get('tid',"");
        $action = $request->get('action',"");
        
        $topic = new Topic;
        if (!empty($tid)) {
            if($action == 'loadlikes'){
                $topicdata = $topic->getLikes($tid);
                $case = 'likes' ;
            }
            elseif($action == 'loadunlikes'){
                $topicdata = $topic->getUnLikes($tid);
                $case = 'likes' ;
            }
            elseif($action == 'loadfollow'){
                $topicdata = $topic->getFollows($tid);
                $case = 'follow' ;
            }
            elseif($action == 'loadviews'){
                $topicdata = $topic->getViews($tid);
                $case = 'views' ;
            }
            elseif($action == 'loadreplies'){
                $topicdata = $topic->getReplies($tid);
                $case = 'replydata' ;
            }
            $categorydata = $topic->getTopicNameById($tid);
            $data = [
                    'action' => $action,
                    $case => $topicdata,
                    'categorydata' => $categorydata,
                ];

            $HTML = view('admin.ajax')->with($data)->render();
            if ($request->is('api/*')) {
                return apiResponse("success","",$data);
            }
            return apiResponse("success", "", $HTML);
        } 
        else{
            return apiResponse("error","Data not found");
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug) {
        $topic = Topic::where('slug', $slug)->first();
        
        $explode_id = array_map('intval', explode(',', $topic->tags));
        
        $tags=array();
        foreach ($explode_id as $val) {
            $tag = ForumTags::where('id', $val)->first();
            $tags[]=array('value'=>$tag->id,'label'=>$tag->title);    
        }
        $tags_json = json_encode($tags);
        
        $forum_categories = Forum::where('parent_category_id', '!=', '')
                ->select('id','title')
                ->get();
        
        if (!empty($topic)) {
            $data = [
                'topic' => $topic,
                'slug' => $slug,
                'forum_categories' => $forum_categories,
                'tags_json' => $tags_json,
            ];
            return view('admin.topicadd')->with($data);
        } else {
            return redirect(route('topic.list'))->with("error", "No data found");
        }
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug) {

        $rules = [
            'title' => 'required',
            'description' => 'required',
            'tags' => 'required',
            'sub_cat_id' => 'required',
        ];

        $validationErrorMessages = [
            'title.required' => 'Topic Title is required.',
            'description.required' => 'Topic Description is required.',
            'tags.required' => "Topic Tags is required.",
            'sub_cat_id.required' => "Subcategory is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('topic.edit.form', $slug))->with('errors', $validator->messages())->withInput();
        }
        $topic = Topic::where('slug', $slug)->first();

        $new_slug = Str::slug($request->title);

        $topic->title = $request->title;
        $topic->slug = $new_slug;
        $topic->description = $request->description;
        $topic->status = $request->status;
        $topic->tags = $request->tags;
        $topic->forum_category_id = $request->sub_cat_id;
        $topic->save();
        if ($request->is('api/*')) {
            return apiResponse('success', "Topic updated successfully.");
        }
        return redirect(route('topic.list'))->with('success', "Topic updated successfully.");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
       starts_with(request()->path(), 'api') ? $decrypt_id = $id : $decrypt_id = decrypt($id);
        if (Topic::deleteByTopicId($decrypt_id)) {
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success", "Topic deleted successfully");
            }
            return redirect(route('topic.list'))->with("success", "Topic deleted successfully");
        } else {
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error", "Failed to delete topic");
            }
            return redirect(route('topic.list'))->with("error", "Failed to delete topic");
        }
    }
}
