<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; 
use Response;
use Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageTrait;

class PhotoController extends Controller
{  
    use ImageTrait;
    
    public function __construct()
    {  
        parent::__construct();
        $this->middleware('auth');
    }
    
    public function crop(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $data = $request->all();
        
        $upload_img_size = config('common.upload_img_size');
        $allowed_img_size = config('common.upload_img_size') * 1000;
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
        $normal_img_path = "user-avatar/{$user_id}/";
        $crop_img_path = "user-avatar/{$user_id}/crop/";
        $thumbh_50_path = "user-avatar/{$user_id}/50/";
        $thumbh_200_path = "user-avatar/{$user_id}/200/";
        
        if($user->profile_photo != ''){
            Storage::disk('public')->delete($normal_img_path.$user->profile_photo); 
            Storage::disk('public')->delete($crop_img_path.$user->profile_photo);
            Storage::disk('public')->delete($thumbh_50_path.$user->profile_photo);
            Storage::disk('public')->delete($thumbh_200_path.$user->profile_photo); 
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
        
        $user->profile_photo = $store_filename;
        $user->save();
        
        $img_url = get_url($normal_img_path.$store_filename);
        if ($request->is('api/*')){
            return apiResponse("success", "Your profile photo has been updated successfully.",['imgsrc' => $img_url]);
        }
        return Response::json([
            'type' => 'success',
            'url' => $img_url,
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function avatar(Request $request)
    {   
        $user = Auth::user();
        $user_id = $user->id;
        $profile_photo = $user->profile_photo;
        $img_exist = Storage::disk('public')->exists("user-avatar/{$user_id}/200/{$profile_photo}");
        if($img_exist){
            return response()->json([
                'type' => 'success',
                'imgsrc' => get_url("user-avatar/{$user_id}/200/{$profile_photo}"), 
                'message' => 'Your profile photo has been updated successfully.' 
            ]);
        }
        else{ 
            return response()->json([
                'type' => 'error', 
                'message' => 'Something went wrong plese try after some time.'
            ]); 
        }
    }
}
