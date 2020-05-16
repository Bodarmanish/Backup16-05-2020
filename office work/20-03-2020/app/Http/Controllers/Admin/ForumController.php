<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use App\Models\Forum;
use Validator;
use Auth;
use Response;
use App\Models\ForumTags;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageTrait;
use Illuminate\Support\Arr;

class ForumController extends Controller {

    use ImageTrait;

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
    public function showCategory() {

        $params = $request->except('_token');
        $forums = Forum::forum($params)->get();
        if (!empty($forums)) {
            $data = [
                'forums' => $forums
            ];
        } 
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('admin.forum-cat')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCategory() {
        $forum = (object) [
                    'id' => "",
                    'title' => "",
                    'banner_image' => "",
                    'description' => "",
                    'keyword' => "",
                    'status' => "",
        ];

        $data = [
            'forum' => $forum,
        ];
        return view('admin.forum-cat-add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCategory(Request $request) {

        $rules = [
            'forum_title' => ['required','unique:forum_category,title'],
            'description' => 'required',
            'keyword' => 'required',
        ];
        
        $validationErrorMessages = [
            'forum_title.required' => 'Forum title is required.',
            'forum_title.unique' => 'Forum title has already been taken.', 
            'description.required' => 'Forum description is required.',
            'keyword.required' => "Forum keyword is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('forum.cat.add.form'))->with('errors', $validator->messages())->withInput();
        }
            $title = Str::slug($request->forum_title);
            $forum = new Forum;
            
            $forum->title = $request->forum_title;
            $forum->description = $request->description;
            $forum->keyword = $request->keyword;
            $forum->status = $request->status;
            $forum->slug = $title;

            $forum->save();
            $LastInsertId = $forum->id;
        return redirect(route('forum.cat.list'))->with('success', "Forum category has been added successfully.");
        if ($request->is('api/*')) {
            return apiResponse('success', "Your forum has been added successfully.");
        }
        return redirect(route('forum.cat.list'))->with('success', "Your forum has been added successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $forum_title
     * @return \Illuminate\Http\Response
     */
    public function editCategory($slug) {
        $forum = Forum::where('slug', $slug)->first();

        if (!empty($forum)) {
            $data = [
                'forum' => $forum,
                'slug' => $slug
            ];
            return view('admin.forum-cat-add')->with($data);
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCategory(Request $request, $slug) {

        $rules = [
            'forum_title' => "required|unique:forum_category,title,{$slug},slug",
            'description' => 'required',
            'keyword' => 'required',
        ];

        $validationErrorMessages = [
            'forum_title.required' => 'Forum title is required.',
            'forum_title.unique' => 'Forum title has already been taken.',
            'description.required' => 'Forum description is required.',
            'keyword.required' => "Forum keyword is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('forum.cat.edit.form', $slug))->with('errors', $validator->messages())->withInput();
        }
        $forum = Forum::where('slug', $slug)->first();

        $new_slug = Str::slug($request->forum_title);

        $forum->title = $request->forum_title;
        $forum->slug = $new_slug;
        $forum->description = $request->description;
        $forum->status = $request->status;
        $forum->keyword = $request->keyword;
        $forum->save();
        $LastInsertId = $forum->id;
        if ($request->is('api/*')) {
            return apiResponse('success', "Forum Updated Successfully.");
        }
        return redirect(route('forum.cat.list'))->with('success', "Forum Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCategory($slug) {
        if (Forum::deleteByForumName($slug)) {
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Forum Category Deleted Successfully");
            }
            return redirect(route('forum.cat.list'))->with("success", "Forum Category Deleted Successfully");
        } else {
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error", "Failed to delete forum");
            }
            return redirect(route('forum.cat.list'))->with("error", "Failed to delete forum");
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showSubCategory(Request $request) {

        $forums_category = Forum::all();
        $params = $request->except('_token');
        $data = Forum::filter($params)->get();
        if (!empty($data)) {
            $data = [
                'forums' => $data,
                'forums_category' => $forums_category,

                ];
        } 
        if ($request->is('api/*')) {
            return apiResponse("success","",$data);
        }
        return view('admin.forum-subcat')->with($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadSubCategory(Request $request) {
        
        $cat_id = $request->get('id', "");

        if (!empty($cat_id)) {
            
            $where = [
                'parent_category_id' => $cat_id,
            ];
            $forums = collect(Forum::where($where)->get())->all();
             
            $data = [
                'action' => "loadSubCategory",
                'forums' => $forums,
            ];

            $HTML = view('admin.ajax')->with($data)->render();
            
            return apiResponse("success", "", $HTML);
        } 
        else{
            return apiResponse("error","Data not found");
        }
    }
    
      
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSubCategory() {
        $forum = (object) [
                    'id' => "",
                    'title' => "",
                    'parent_category_id' => "",
                    'banner_image' => "",
                    'description' => "",
                    'keyword' => "",
                    'status' => "",
        ];

        $forum_categories = Forum::select('id', 'title')
                ->get();

        $data = [
            'forum' => $forum,
            'forum_categories' => $forum_categories,
        ];

        return view('admin.forum-subcat-add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSubCategory(Request $request) {

        $rules = [
            'forum_title' => ['required','unique:forum_category,title'],
            'description' => 'required',
            'keyword' => 'required',
            'parent_cat_id' => 'required',
            'tags' => 'required',
        ];
        
        $validationErrorMessages = [
            'forum_title.required' => 'Forum title is required.',
            'forum_title.unique' => 'Forum title has already been taken.', 
            'description.required' => 'Forum description is required.',
            'keyword.required' => "Forum keyword is required.",
            'parent_cat_id.required' => "Category is required.",
            'tags.required' => "Tags is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('forum.subcat.add.form'))->with('errors', $validator->messages())->withInput();
        }
        $title = Str::slug($request->forum_title);
        $forum = new Forum;
        
        $forum->title = $request->forum_title;
        $forum->description = $request->description;
        $forum->parent_category_id = $request->parent_cat_id;
        $forum->keyword = $request->keyword;
        $forum->tags = $request->tags;
        $forum->status = $request->status;
        $forum->slug = $title;
        $forum->save();
        
        return redirect(route('forum.subcat.list'))->with('success', "Forum sub category has been added successfully.");
        if ($request->is('api/*')){
            return apiResponse('success', "Your forum has been added successfully.");
        }
        return redirect(route('forum.subcat.list'))->with('success', "Your forum has been added successfully.");
    }

    /* For Sub Categories */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $forum_title
     * @return \Illuminate\Http\Response
     */
    public function editSubCategory($slug) {
        $forum = Forum::where('slug', $slug)->first();
        $forum_categories = Forum::select('id', 'title')
                ->get();

        if (!empty($forum)) {
            $data = [
                'forum' => $forum,
                'slug' => $slug,
                'forum_categories' => $forum_categories,
            ];
            if(!empty($forum->tags)){
                $explode_id = array_map('intval', explode(',', $forum->tags));
                $tags=array();
                foreach ($explode_id as $val) {
                    $tag = ForumTags::where('id', $val)->first();
                    $tags[]=array('value'=>$tag->id,'label'=>$tag->title);    
                }
                $tags_json = json_encode($tags);
                $data = Arr::prepend($data, $tags_json, 'tags_json');
            }
//            print_data($data);
            return view('admin.forum-subcat-add')->with($data);
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  text  $slug
     * @return \Illuminate\Http\Response
     */
    public function updateSubCategory(Request $request, $slug) {
        
        $rules = [
            'forum_title' => "required|unique:forum_category,title,{$slug},slug",
            'description' => 'required',
            'keyword' => 'required',
            'parent_cat_id' => 'required',
            'tags' => 'required',
        ];

        $validationErrorMessages = [
            'forum_title.required' => 'Forum title is required.',
            'forum_title.unique' => 'Forum title has already been taken.',
            'description.required' => 'Forum description is required.',
            'keyword.required' => "Forum keyword is required.",
            'parent_cat_id.required' => "Category is required.",
            'tags.required' => "Tags is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('forum.subcat.edit.form', $slug))->with('errors', $validator->messages())->withInput();
        }
        $forum = Forum::where('slug', $slug)->first();
        $new_slug = Str::slug($request->forum_title);

        $forum->title = $request->forum_title;
        $forum->slug = $new_slug;
        $forum->description = $request->description;
        $forum->parent_category_id = $request->parent_cat_id;
        $forum->status = $request->status;
        $forum->keyword = $request->keyword;
        $forum->tags = $request->tags;
        $forum->save();
        $LastInsertId = $forum->id;
        if ($request->is('api/*')){
            return apiResponse("success", "Forum Sub Category Updated Successfully.");
        }
        return redirect(route('forum.subcat.list'))->with('success', "Forum Sub Category Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  text  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroySubCategory(Request $request, $slug) {
        if (Forum::deleteByForumName($slug)) {
            return redirect(route('forum.subcat.list'))->with("success", "Forum sub category deleted successfully.");
        } else {
            return redirect(route('forum.subcat.list'))->with("error", "Failed to delete forum sub category.");
        }
    }
    public function crop(Request $request)
    {       
        $data = $request->all();
        $slug = $request->slug;
        
        $forum = Forum::where('slug', $slug)->first();
        
        $upload_img_size = config('common.upload_img_size');
        $allowed_img_size = config('common.upload_img_size') * 1000;
        $allow_image_ext = config('common.allow_image_ext');
        $upload_image_ext = collect(config('common.allow_image_ext'))->implode(',');
        $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
        
        $validator = Validator::make($data,
            [   'img' => "required|mimes:{$upload_image_ext}|max:{$allowed_img_size}" ], 
            [   'img.mimes' => "Forum category image must be a file of type: {$allow_image_ext}.",
                'img.required' => "Forum category image is required.",
                'img.max' => "Forum category image must be below {$upload_img_size} MB." ]);
         
        if ($validator->fails()) {
            return Response::json([
                'type' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        
        $photo = $data['img'];
        
        /* All image upload path */
        $normal_img_path = "forum-photo/{$forum->id}/";
        $crop_img_path = "forum-photo/{$forum->id}/crop/";
        $thumbh_50_path = "forum-photo/{$forum->id}/50/";
        $thumbh_200_path = "forum-photo/{$forum->id}/200/";
        
        if($forum->banner_image != ''){
            Storage::disk('public')->delete($normal_img_path.$forum->banner_image); 
            Storage::disk('public')->delete($crop_img_path.$forum->banner_image);
            Storage::disk('public')->delete($thumbh_50_path.$forum->banner_image);
            Storage::disk('public')->delete($thumbh_200_path.$forum->banner_image); 
        }
        
        $store_filename = $this->getStoreFileName($photo);
        
        /* Upload Original Image */
        $uploadimg = $this->uploadImage($photo,$normal_img_path.$store_filename);
        
            if($uploadimg){
               /* crop original image */
               $cropimg = $this->cropImage($photo,$crop_img_path.$store_filename,$request->imgW,$request->imgH,$request->cropW, $request->cropH, $request->imgX1, $request->imgY1, $request->rotation);

                if($cropimg){

                   /* generate thumb from crop image */
                   $this->createThumbImage("storage/".$crop_img_path.$store_filename,$thumbh_50_path.$store_filename,50);
                   $this->createThumbImage("storage/".$crop_img_path.$store_filename,$thumbh_200_path.$store_filename,200);
                } 
                else{
                   return Response::json([
                       'type' => 'error',
                       'message' => 'Server error while uploading.',
                   ]);
                } 
                if ($request->is('api/*')) {
                    return apiResponse("success", "Forum Sub Category Deleted Successfully");
                }
                return redirect(route('forum.subcat.list'))->with("success", "Forum Sub Category Deleted Successfully");
            }else {
            
                $forum->banner_image =$store_filename;
                $forum->save();

                $img_url = get_url($crop_img_path.$store_filename);
                if ($request->is('api/*')){
                    return apiResponse("success", "Forum category image uploaded succuessfully.",['imgsrc' => $img_url]);
                }
                return Response::json([
                    'type' => 'success',
                    'url' => $img_url,
                    'message' => 'Forum category image uploaded succuessfully.'
                ]);
            }

    }
}