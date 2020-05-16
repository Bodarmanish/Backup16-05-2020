<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Topic;
use App\Models\Forum;
use App\Models\Comment;
use Validator;
use Auth;

class CommentController extends Controller {

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
    public function show($id) {
        $request->is('api/*') ? $decrypt_id = $id :$decrypt_id = decrypt($id);
        
        $request_data = "";
                
        if(!empty($request->all())){
            $request_data = $request->all();
        }
        $comment = new Comment;
        $data = $comment->getComment($decrypt_id,$request_data);
        
        if (!empty($data)) {
            $data = [
                'comments' => $data,
                'id'=>$id,
            ];
        } else {
            $data = ["error", "No data found"];
        }
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('admin.comment')->with($data);
    }
    
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
        $decrypt_id = decrypt($id);
        
        $comment = Comment::where('forum_topic_comments.id', $decrypt_id)
                ->leftJoin('users', 'users.id','=','forum_topic_comments.user_id')
                ->select('forum_topic_comments.*','users.first_name','users.last_name')
                ->first();
        $topic_id = encrypt($comment->forum_topics_id);
        
        if (!empty($comment)) {
            $data = [
                'comment' => $comment,
                'id' => $id,
                'topic_id' => $topic_id,
            ];
            return view('admin.commentadd')->with($data);
        } else {
            return redirect(route('comment.list'))->with("error", "No data found");
        }
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $request->is('api/*') ? $decrypt_id = $id : $decrypt_id = decrypt($id);
        
        $topic_data = Comment::where('id', $decrypt_id)->first();
        $topic_id = encrypt($topic_data->forum_topics_id);
        
        $rules = [
            'comment_text' => 'required',
        ];

        $validationErrorMessages = [
            'comment_text.required' => 'Comment Text is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('comment.edit.form', $topic_id))->with('errors', $validator->messages())->withInput();
        }
        $comment = Comment::where('id', $decrypt_id)->first();
        $comment->comment_text = $request->comment_text;
        $comment->save();
        if ($request->is('api/*')) {
            return apiResponse("success", "Comment Updated Successfully.");
        }
        return redirect(route('comment.list',$topic_id))->with('success', "Comment Updated Successfully.");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        starts_with(request()->path(), 'api') ? $id = $id : $id = decrypt($id);
        if (Comment::deleteByCommentId($id)) {
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success", "Comment Deleted Successfully");
            }
            return redirect(route('comment.list',$id))->with("success", "Comment Deleted Successfully");
        } else {
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error", "Failed to delete comment");
            }
            return redirect(route('comment.list',$id))->with("error", "Failed to delete comment");
        }
    }
    /**
     * Change the notify status.
     *
     * @return \Illuminate\Http\Response
    */
    public function changeStatus(Request $request) {
        $action = $request->get('action',"");
        $comment_id = $request->get('comment_id',"");
        $is_active = $request->get('is_active',"");
        
        if($action == "change_comment_status"){
            $comment = Comment::where('id', $comment_id)->first();
            $comment->status = $is_active;
            $comment->save();
            return apiResponse("success","Comment status updated successfully");
        }
        
    }
}
