<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserGeneral;
use App\Models\SystemSettings;
use App\Models\Portfolio;
use App\Models\Agency;
use App\Models\AgencyContract;
use Validator;
use Auth;
use Response;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageTrait;
use App\Models\DocumentRequirement;
use App\Models\Token;
use App\Models\EmailNotification as EN;

class UserController extends Controller {

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
        parent::__construct();
        $this->system_settings = new SystemSettings;
        $this->doc_req = new DocumentRequirement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        
        $admin = auth()->user();
        $params = $request->except('_token');
        if($admin->role_name == 'agency-admin'){
            $params['agency_id'] = $admin->agency_id; 
            $params['agency_type'] = $admin->agency_type; 
        }
        $users = User::filter($params)->get();
        $data = [
                'users' => $users,
            ];
        return view('admin.user')->with($data); 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $users = (object) [
                    'id' => "",
                    'first_name' => "",
                    'middle_name' => "",
                    'last_name' => "",
                    'email' => "",
                    'profile_photo' => "",
                    'j1_status_id' => "",
                    'timezone' => "",
                    'country' => "",
                    'phone_number' => "",
                    'secondary_email' => "",
                    'street' => "",
                    'city' => "",
                    'zip_code' => "",
                    'skype_id' => "",
                    'facebook_url' => "",
                    'twitter_url' => "",
        ];
        $data = [
            'users' => $users,
        ];
        return view('admin.useradd')->with($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $rules = [
            'first_name' => ['required', 'string', 'max:255', 'regex:/(^[A-Za-z0-9 ]+$)+/'],
            'last_name' => ['required', 'string', 'max:255', 'different:first_name', 'regex:/(^[A-Za-z0-9 ]+$)+/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->system_settings->passwordValidation(),
            'phone_number' => 'required|numeric',
            'street' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'timezone' => 'required',
            'facebook_url' => 'sometimes|nullable|url',
            'twitter_url' => 'sometimes|nullable|url',
            'email_verified' => 'required',
            'skype_id' => 'required'
        ];
        $validationErrorMessages = [
            'first_name.required' => 'First name field is required.',
            'first_name.regex' => 'Fist name not allow any special character.',
            'last_name.required' => 'Last name field is required.', 
            'last_name.regex' => 'Last name not allow any special character.',
            'last_name.different' => 'First name and last name should not be same.',
            'email.required' => 'Email address field is required.',
            'email.email' => 'Email address must be a valid email address.',
            'email.unique' => 'Email address has already been taken.',            
            'phone_number.required' => 'Phone number field is required.',
            'password.required' => 'Password field is required.',
            'password.without_spaces' => 'Password does not allowed white spaces.',
            'password.alphabet' => 'Password field is required one alphabet.',
            'password.digit' => 'Password field is required one digit.',
            'password.special' => 'Password field is required one special character.',
            'street.required' => 'Street field is required.',
            'city.required' => 'City field is required.',
            'zip_code.required' => 'Zip code field is required.',
            'country.required' => 'Country field is required.',
            'timezone.required' => 'Timezone field is required.',
            'facebook_url.sometimes' => 'Facebook URL format is invalid.',
            'twitter_url.sometimes' => 'Twitter URL format is invalid.',
            'skype_id.required' => 'Skype ID field is required.',
            'email_verified.required' => 'Email verified field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

       if ( $validator->fails() ) { 
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            else{
                return $validator->validate();
            }
        } 
        
        $user = new User();
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->timezone = $request->timezone;
        $user->j1_status_id = 1;
        $user->email_verified = $request->email_verified;

        $user->save();
        $user_id = $user->id;
        
        $portfolio = new Portfolio;
        $portfolio->user_id = $user_id;
        $portfolio->portfolio_number = "PF".mt_rand(100000000, 999999999);
        $portfolio->save();
        $portfolio_id = $portfolio->id;
        
        /* Update portfolio id in users table */
        $user->portfolio_id = $portfolio_id;
        $user->save();
        
        $user_general = $user->userGeneral();
        $user_general->phone_number = $request->phone_number;
        $user_general->secondary_email = $request->secondary_email;
        $user_general->user_id = $user_id;
        $user_general->street = $request->street;
        $user_general->city = $request->city;
        $user_general->zip_code = $request->zip_code;
        $user_general->country = $request->country;
        $user_general->facebook_url = $request->facebook_url;
        $user_general->twitter_url = $request->twitter_url;
        $user_general->skype_id = $request->skype_id;
        $user_general->portfolio_id = $portfolio_id;
        $user_general->save();
        
        /*EMAIL VERIFIED*/
        if($request->email_verified == 0)
        {
            $token = new Token;
            $secure_token = $token->generateToken(['user_id' => $user_id],2,48);

            $candidate_email = $request->email;
            $first_name      = trim($request->first_name);
            $last_name       = trim($request->last_name);
            $candidate_name  = trim($request->first_name." ".$request->last_name);
            $company         = config('app.name');
            $url             = config('app.url').'register/verify/'.$secure_token;
            $copy_url        = "<a href='{$url}' target='_blank' style='color:#e74c3c;word-break: break-all;'>{$url}</a>";
            $mail_format     = (array) EN::getMailTextByKey("activate_user_account");

            $subject      = $mail_format['subject'];
            $message_text = $mail_format['text'];
            $message_text = str_replace("{{first_name}}", $first_name, $message_text);
            $message_text = str_replace("{{last_name}}", $last_name, $message_text);
            $message_text = str_replace("{{company}}", $company, $message_text);
            $message_text = str_replace("{{url}}", $url, $message_text);
            $message_text = str_replace("{{copy_url}}", $copy_url, $message_text);

            $receiver = array();
            $receiver['toEmail']    = $candidate_email;
            $receiver['toName']     = $candidate_name;
            $data = ['message_text' => $message_text];

            $this->sendUIEmailNotification((object) $receiver, $subject, $data);
            $this->changeUserStatus($user, "just-register");
        } 
        else
        {
            $this->changeUserStatus($user, "email-verified");
        }
        
        if ($request->is('api/*')){
            return apiResponse("success","Your account created successfully.");
        }
        else{
            return redirect(route('user.list'))->with('success', 'Your account created successfully.');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {
        $user_id = user_token();
        
        $user = new User();
        $user= $user->getUsers($user_id)->first();
        
        if(!empty($user))
        {
            $data = [
                'users' => $user,
                'id' => $user_id,
            ];
            return view('admin.useradd')->with($data);
        }
        else {
            return redirect(route('user.list'))->with("error", "No data found");
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $user_id = user_token();
        $user_data = User::where('id', $user_id)->first();
        $user_general = UserGeneral::where('portfolio_id', $user_data->portfolio_id)->first();
        
        $img_rules = array();

        $upload_img_size = config('common.upload_img_size');
        $allowed_img_size = config('common.upload_img_size') * 1000;
        $upload_image_ext = collect(config('common.allow_image_ext'))->implode(',');
        $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');

        if (!empty($request->profile_photo)) {
            $img_rules = ['profile_photo' => "required|max:{$allowed_img_size}"];
        }

        $rules = [
            'first_name' => ['required', 'string', 'max:255', 'regex:/(^[A-Za-z0-9 ]+$)+/'],
            'last_name' => ['required', 'string', 'max:255', 'different:first_name', 'regex:/(^[A-Za-z0-9 ]+$)+/'],
            'phone_number' => 'required|numeric',
            'street' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'timezone' => 'required',
            'facebook_url' => 'sometimes|nullable|url',
            'twitter_url' => 'sometimes|nullable|url',
            'skype_id' => 'required'
        ];
        $rules = collect($rules)->merge($img_rules)->all();

        $validationErrorMessages = [
            'first_name.required' => 'First name field is required.',
            'first_name.regex' => 'Fist name not allow any special character.',
            'last_name.required' => 'Last name field is required.', 
            'last_name.regex' => 'Last name not allow any special character.',
            'last_name.different' => 'First name and last name should not be same.',
            'email.unique' => 'Email address has already been taken.',            
            'phone_number.required' => 'Phone number field is required.',
            'street.required' => 'Street field is required.',
            'city.required' => 'City field is required.',
            'zip_code.required' => 'Zip code field is required.',
            'country.required' => 'Country field is required.',
            'timezone.required' => 'Timezone field is required.',
            'profile_photo.required' => 'Profile picture is required.',
            'profile_photo.mimes' => "Profile picture must be a file of type: {$allow_image_ext}.",
            'profile_photo.max' => "Profile picture must be below {$upload_img_size} MB.",
            'facebook_url.sometimes' => 'Facebook URL format is invalid.',
            'twitter_url.sometimes' => 'Twitter URL format is invalid.',
            'skype_id.required' => 'Skype ID field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ( $validator->fails() ) { 
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            else{
                return $validator->validate();
            }
        }
        
        $user_data->first_name = $request->first_name;
        $user_data->middle_name = $request->middle_name;
        $user_data->last_name = $request->last_name;
        $user_data->timezone = $request->timezone;
        $user_data->save();
        
        if(empty($user_general)){
            $user_general = new UserGeneral;
            $user_general->user_id = $user_data->id;
            $user_general->portfolio_id = $user_data->portfolio_id;
        }
        $user_general->phone_number = $request->phone_number;
        $user_general->secondary_email = $request->secondary_email;
        $user_general->street = $request->street;
        $user_general->city = $request->city;
        $user_general->zip_code = $request->zip_code;
        $user_general->country = $request->country;
        $user_general->facebook_url = $request->facebook_url;
        $user_general->twitter_url = $request->twitter_url;
        $user_general->skype_id = $request->skype_id;
        $user_general->save();
        if ($request->is('api/*')){
            return apiResponse("success","User updated Successfully.");
        }        
        return redirect(route('user.list'))->with('success', "User updated Successfully.");
        
    }
    /**
     * Function crop() to the image
     */
    public function crop(Request $request)
    {       
        $data = $request->all();
        $id = $request->id;
        
        $user = User::where('id', $id)->first();
        
        $upload_img_size = config('common.upload_img_size');
        $allowed_img_size = config('common.upload_img_size') * 1000;
        $upload_image_ext = collect(config('common.allow_image_ext'))->implode(',');
        $allow_image_ext = collect(config('common.allow_image_ext'))->implode(', ');
        
        $validator = Validator::make($data,
            [   'img' => "required|mimes:{$upload_image_ext}|max:{$allowed_img_size}" ], 
            [   'img.mimes' => "Profile picture must be a file of type: {$allow_image_ext}.",
                'img.required' => "Profile picture is required.",
                'img.max' => "Profile picture must be below {$upload_img_size} MB." ]);
         
        if ($validator->fails()) {
            return Response::json([
                'type' => 'error',
                'message' => $validator->messages()->first(),
            ]);
        }
        
        $photo = $data['img'];
        
        /* All image upload path */
        $normal_img_path = "user-avatar/{$id}/";
        $crop_img_path = "user-avatar/{$id}/crop/";
        $thumbh_50_path = "user-avatar/{$id}/50/";
        $thumbh_200_path = "user-avatar/{$id}/200/";
        
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
            
        $user->profile_photo =$store_filename;
        $user->save();
        
        $img_url = get_url($crop_img_path.$store_filename);
        if ($request->is('api/*')){
            return apiResponse("success", "Your profile photo has been updated successfully.",['imgsrc' => $img_url]);
        }
        return Response::json([
            'type' => 'success',
            'url' => $img_url,
            'message' => 'Your profile photo has been updated successfully.'
        ]);
    }
    /**
     * Display a user detail of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail() {
        
        $user_id = user_token();
        
        $user = new User();
        $users = $user->getUsers($user_id)->first();
        
        if (!empty($users)) {
            $data = [
                'users' => $users,
                'id' => $user_id,
            ];
        } 
        else {
            $data = ["error", "No data found"];
        }

        return view('admin.userdetail')->with($data);
    }
    /**
     * To send invitation to user.
     *
     * @return \Illuminate\Http\Response
     */
    public function invite() {
        
        return view('admin.invitation');
    }
    /**
     * function createInvitation() to send invitation to user
     *
     * @return \Illuminate\Http\Response
     */
    public function createInvitation(Request $request) {
        
        $rules = [
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
        $validationErrorMessages = [
            'email.required' => 'Email address field is required.',
            'email.email' => 'Email address must be a valid email address.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ( $validator->fails() ) { 
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }
            else{
                return $validator->validate();
            }
        }
        
        $user = User::where('email', '=', $request->email)->first();
        
        $admin     = Auth::user();
        $role_name = $admin->role->role_name;
        $agency_id = $admin->agency_id;

        $data = array();
        $data['agency_id'] = $agency_id;
        $data['request_by_id'] = $admin->id;
        $data['email'] = $request->email;
        
        $agency_detail = Agency::where(['id' => $agency_id])->get()->first();
        if($role_name == 'root')
        {
           $data['agency_type'] = 4; 
        }
        else
        {
            $data['agency_type'] = $agency_detail->agency_type;
        }

        if ($user === null) {
            $data['user_id'] = 0;
            $data['portfolio_id'] = 0;
            $sent = $this->sendInvitation($data);
            if(!empty($sent)){
                if ($request->is('api/*')){
                    return apiResponse("success","Invitation sent successfully.");
                }
                else{
                    return redirect(route('user.invite'))->with('success', 'Invitation sent successfully.');
                }
            }
            else{
                return redirect(route('user.invite'))->with('error', 'Something went wrong. Please try again after some time.');
            }
        }
        else
        {
            $exist_agency_contract = AgencyContract::where('email', '=', $request->email)->first();
            
            if ($exist_agency_contract === null) {
                // user doesn't exist
                $user_id = $user->id;
                $portfoli_id = $user->portfolio_id;

                $data['user_id'] = $user_id;
                $data['portfolio_id'] = $portfoli_id;

                $portfolio_arr = collect(Portfolio::where('user_id', $user_id)->get()->all())->toArray();
                $portfolio_status_arr = array_column($portfolio_arr, 'portfolio_status');
                if (!empty(array_intersect([0, 3, 4], (array) $portfolio_status_arr))) {

                    $sent = $this->sendInvitation($data);
                    if (!empty($sent)) {
                        if ($request->is('api/*')) {
                            return apiResponse("success", "Invitation sent successfully.");
                        } 
                        else {
                            return redirect(route('user.invite'))->with('success', 'Invitation sent successfully.');
                        }
                    } 
                    else {
                        return redirect(route('user.invite'))->with('error', 'Something went wrong. Please try again after some time.');
                    }
                } 
                elseif (!empty(array_intersect([1, 2], (array) $portfolio_status_arr))) {
                    $current_portfolio = collect($user->portfolio)->toArray();
                    $agency_contract = AgencyContract::where(['user_id' => $user_id])->get()->first();

                    if (($agency_contract['contract_type'] == 1 && $current_portfolio["registration_agency_id"] == 0) || ($agency_contract['contract_type'] == 2 && $current_portfolio["placement_agency_id"] == 0) || ($agency_contract['contract_type'] == 3 && $current_portfolio["sponsor_agency_id"] == 0) || ($agency_contract['contract_type'] == 4 && ($current_portfolio["registration_agency_id"] == 0 || $current_portfolio["placement_agency_id"] == 0 || $current_portfolio["sponsor_agency_id"] == 0))) {

                        $sent = $this->sendInvitation($data);
                        if (!empty($sent)) {
                            if ($request->is('api/*')) {
                                return apiResponse("success", "Invitation sent successfully.");
                            } 
                            else {
                                return redirect(route('user.invite'))->with('success', 'Invitation sent successfully.');
                            }
                        } 
                        else {
                            return redirect(route('user.invite'))->with('error', 'Something went wrong. Please try again after some time.');
                        }
                    } 
                    else {
                        return redirect(route('user.invite'))->with('error', "This user is already working with other agency.");
                    }
                }
            } 
            else {
                return redirect(route('user.invite'))->with('error', "You have already sent invitation to this user.");
            }
        }
    }
    
    public function ajaxRequest(Request $request)
    {
        $action = $request->action;
        $id = $request->id;
        $user = User::where('id',$id)->first();
        switch ($action)
        {
            case 'update_user_status':
                $user->status = $request->value;
                $user->save();
                $response = ["type" => "success", "message" => "User status updated successfully."];
                return Response::json($response);
            break;
        }
    }
    public function userHistory(Request $request){
        if(!empty($request->user_id)){
            $user_id = user_token();
            $user = User::where('id',$user_id)->first();
            $full_name = $user->first_name." ".$user->last_name;
            
            $user = new User();
            $logs = $user->getLogs($user_id)->all();
            
            $data = [
                'logs' => $logs,
                'id' => $user_id,
                'full_name' => $full_name,
            ];
            
            return view('admin.userhistory')->with($data);
        }
        
    }
}
