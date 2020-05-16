<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Auth;
use Validator;
use Response;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
       use ImageTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect(Testimonial::all())->all();

        if(!empty($data))
        {
            $data = [
                'testimonial_data' => $data
            ];
        }
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('admin.testimonials')->with($data);
    }
    
       
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testimonial = (object)[
                'id' => "",
                'title' => "",
                'description' => "",
                'image' => "",
                'client_name' => "",
                'client_country' => "",
                'status' => ""
            ];
        
     
        
        $data = [
            'testimonial' => $testimonial
        ];
        return view('admin.testimonial-add')->with($data);
    }
    
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $rules = [
            'title' => "required|regex:/(^[A-Za-z0-9 ]+$)+/|unique:testimonials,title",
        ];

        $validationErrorMessages = [
            'title.required' => 'Title Name field is required.',
            'title.unique' => 'Title Name already  exists.',
            'title.regex' => 'Title does not allow any special character.'
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('testimonials.add.form'))->with('errors', $validator->messages())->withInput();
        }

       $testimonial = new Testimonial;
   
        $testimonial->title = $request->title;
        $testimonial->description = $request->description;
        $testimonial->client_name = $request->client_name;
        $testimonial->client_country = $request->client_country;
        $testimonial->status = $request->status;
        $testimonial->save();
        if ($request->is('api/*')) {
            return apiResponse('success', "Testimonial added successfully.");
        }
        return redirect(route('testimonials.list'))->with('success', "Testimonial added successfully.");
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $role_name
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $id = decrypt($id);
       
        $testimonial = Testimonial::where('id', $id)->first();
        
    
        if(!empty($testimonial))
        {
            $data = [
                'testimonial' => $testimonial,
                'id' => $id
            ];
            return view('admin.testimonial-add')->with($data);
        }
        else{
            return redirect(route('testimonials.list'))->with("error","No data found");
        }
        
        
    }
    
    
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $rules = [
            'title' => "required|regex:/(^[A-Za-z0-9 ]+$)+/|unique:testimonials,title,{$id},id",
        ];

        $validationErrorMessages = [
            'title.required' => 'Title Name field is required.',
            'title.unique' => 'Title Name already  exists.',
            'title.regex' => 'Title does not allow any special character.'
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('testimonials.edit.form',encrypt($id)))->with('errors', $validator->messages())->withInput();
        }
                
        $testimonial = Testimonial::where('id', $id)->first();
   
        $testimonial->title = $request->title;
        $testimonial->description = $request->description;
        $testimonial->client_name = $request->client_name;
        $testimonial->client_country = $request->client_country;
        $testimonial->status = $request->status;
        $testimonial->save();
        if ($request->is('api/*')) {
            return apiResponse('success', "Testimonial updated successfully.");
        }
        return redirect(route('testimonials.list'))->with('success', "Testimonial updated successfully.");
    }
    
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        starts_with(request()->path(), 'api') ? $id = $id : $id = decrypt($id);
        if(Testimonial::deleteBytesTimonialId($id)){
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Testimonial deleted successfully.");
            }
            return redirect(route('testimonials.list'))->with("success","Testimonial deleted successfully.");
        }
        else{
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error","Failed to delete testimonial.");
            }
            return redirect(route('testimonials.list'))->with("error","Failed to delete testimonial.");
        }
    }
    
     public function crop(Request $request)
    {       
        $data = $request->all();
        $id = $request->id;
        $testimonial = Testimonial::where('id', $id)->first();
        
        $upload_img_size = config('common.upload_img_size');
        $allowed_img_size = config('common.upload_img_size') * 1000;
        $allow_image_ext = config('common.allow_image_ext');
        $upload_image_ext = collect(config('common.allow_image_ext'))->implode(',');
        $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
        
        $validator = Validator::make($data,
            [   'img' => "required|mimes:{$upload_image_ext}|max:{$allowed_img_size}" ], 
            [   'img.mimes' => "Profile Picture must be a file of type: {$allow_image_ext}.",
                'img.required' => "Profile Picture is required.",
                'img.max' => "Profile Picture must be below {$upload_img_size} MB." ]);
         
        if ($validator->fails()) {
            return Response::json([
                'type' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        
        $photo = $data['img'];
        
        /* All image upload path */
        $normal_img_path = "testimonial/{$id}/";
        $crop_img_path = "testimonial/{$id}/crop/";
        $thumbh_50_path = "testimonial/{$id}/50/";
        $thumbh_200_path = "testimonial/{$id}/200/";
        
        if($testimonial->image != ''){
            Storage::disk('public')->delete($normal_img_path.$testimonial->image); 
            Storage::disk('public')->delete($crop_img_path.$testimonial->image);
            Storage::disk('public')->delete($thumbh_50_path.$testimonial->image);
            Storage::disk('public')->delete($thumbh_200_path.$testimonial->image); 
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
            }
            else{
                return Response::json([
                    'type' => 'error',
                    'message' => 'Server error while uploading.',
                ]);
            }
            
        $testimonial->image =$store_filename;
        $testimonial->save();
        
        $img_url = get_url($crop_img_path.$store_filename);
        if ($request->is('api/*')){
            return apiResponse("success", "Testimonial Image uploaded succuessfully.",['imgsrc' => $img_url]);
        }
        return Response::json([
            'type' => 'success',
            'url' => $img_url,
            'message' => 'Testimonial Image uploaded succuessfully.'
        ]);
    }
    
}