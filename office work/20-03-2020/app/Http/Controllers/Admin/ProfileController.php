<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use App\Models\Admin;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageTrait;

class ProfileController extends Controller
{
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
    public function __construct()
    {
        $this->admin = new Admin;
        
        parent::__construct();
    }
    
    /**
     * profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.profile'); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {  
        $admin_id  = Auth::user()->id;
        $admin = Admin::where('id',$admin_id)->first();
        
        if(!empty($admin_id))
        {
            $upload_img_size = config('common.upload_img_size');
            $allowed_img_size = config('common.upload_img_size') * 1000;
            $upload_image_ext = collect(config('common.allow_image_ext'))->implode(',');
            $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
  
            $rules = [
                'first_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
                'last_name' => 'required|different:first_name|regex:/(^[A-Za-z0-9 ]+$)+/',
                'email' => 'required|string|email',
            ];
            
            if(!empty($request->profile_photo)){
                $rules['profile_photo'] = "mimes:{$upload_image_ext}|max:{$allowed_img_size}";
            } 
            
            $validationErrorMessages = [
                'first_name.required' => 'First Name field is required.',
                'first_name.regex' => 'First name does not allow any special character.',
                'last_name.regex' => 'Last Name does not allow any special character.',
                'last_name.required' => 'Last Name field is required.',
                'last_name.different' => 'First Name and Last Name should not be same.',
                'email.required' => 'Email Address field is required.',
                'email.email' => 'Email Address must be a valid email address.',
                'profile_photo.mimes' => "Profile Picture must be a file of type: {$allow_image_ext}.",
                'profile_photo.required' => "Profile Picture is required.",
                'profile_photo.max' => "Profile Picture must be below {$upload_img_size} MB."
            ];

            $validator = Validator::make($request->all(), $rules, $validationErrorMessages);
                
            if ( $validator->fails() ) {
                if ($request->is('api/*')){
                    return apiResponse("error", "", $validator->messages()->toArray());
                }
                return redirect(route('edit.profile'))->with('errors', $validator->messages());
            }

            if(!empty($request->profile_photo)){
                
                $store_filename = $this->getStoreFileName($request->profile_photo);
                $normal_img_path = "admin-avatar/{$admin_id}/";

                if($admin->profile_photo != ''){
                    Storage::disk('public')->delete($normal_img_path.$admin->profile_photo);
                }

                /* Upload Image */
                $uploadimg = $this->uploadImage($request->profile_photo,$normal_img_path.$store_filename);
                if($uploadimg){ 
                    $admin->profile_photo = $store_filename; 
                }
            }
            
            $admin->first_name = $request->first_name; 
            $admin->last_name = $request->last_name;
            if(!empty($request->password)){
                $admin->password = Hash::make($request->password);
            }
            $admin->save();
            if ($request->is('api/*')){
                return apiResponse("success", "Your profile details has been updated successfully.");
            }
            return redirect(route('edit.profile'))->with('success', "Your profile has been updated successfully.");
        }
        else{
            return redirect(route('login'))->with('error', "Your session expired."); 
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    { 
        $profile_info = $this->admin->getAdminProfileInfo();
        return view('admin.edit-profile')->with('data',$profile_info);
    }
}