<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGeneral;
use App\Models\SystemSettings;
use App\Models\Timezone;
use App\Models\ForumTopicFollow;
use App\Models\ForumTopicLikes;
use App\Models\ForumTopic;
use App\Models\ForumTopicComment;
use App\Models\NotificationType;
use App\Models\NotificationSetting;
use Auth;
use Validator;
use Response;
use Hash;
use DB;

class ProfileController extends Controller
{
    /**
     * The authenticated user.protected.
     *
     *  
     */
    protected $user;  
    protected $user_general; 
    protected $timezone;
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
        
        $this->user = new User;
        $this->user_general = new UserGeneral;
        $this->system_settings = new SystemSettings;
        $this->timezone = new Timezone;
        $this->notification = new NotificationType;
        $this->notificationsetting = new NotificationSetting;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewProfile(Request $request)
    {   
        $profile_per = json_decode($this->user->countProfileCompletePercentage()); 
        $data = array();
        $data['profile_info'] = $this->user->getProfileInfo();
        $data['profile_percentage'] = $profile_per->percentage;
        $data['incomplete_warning_info'] = $profile_per->incomplete_warning_info;
        
        $forumtopic = new ForumTopic();
        $user = Auth::user();
        $user_id = $user->id;
      
      
        $data['favourite_count'] = count(ForumTopicFollow::all()->where('user_id',$user_id)->where('is_favorite',1));
        $data['topic_like_count'] = count(ForumTopicLikes::all()->where('user_id',$user_id)->where('status',1)->where('forum_comment_id',0));
        $data['topic_count'] = count(ForumTopic::all()->where('user_id',$user_id)->where('status',1));
        $data['following_count'] = count(ForumTopicFollow::all()->where('user_id',$user_id));
        $data['comment_count'] = count(ForumTopicComment::all()->where('user_id',$user_id));
        $data['user_favorite_topic'] = $forumtopic->getUserFavoriteTopic($user_id);
        
        if ($request->is('api/*')) {
            return apiResponse("success","",$data);
        }
        return view('user.profile')->with($data);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile($active_tab = null)
    {   
        $user         = Auth::user();
        $user_id      = $user->id;
        $data = array();
        $data['profile_info'] = $this->user->getProfileInfo();
        $data['password_setting'] = $this->system_settings->getJ1PasswordInstruction();
        $data['active_tab'] = $active_tab;
        $data['social_auto_info'] = $this->user->getSocialAuthInfo();
        $data['notificationlist'] = $this->notification->getNotificationList($user_id);
        return view('user.edit-profile')->with('data',$data);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {   
        $user         = Auth::user();
        $user_id      = $user->id;
        $portfolio_id = $user->portfolio_id;
        
        if(!empty($user_id) && !empty($portfolio_id))
        {
            $action = trim($request->action);
            switch($action){
                
                case "editProfile":
                    
                    $rules = [
                        'first_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
                        'last_name' => 'required|different:first_name|regex:/(^[A-Za-z0-9 ]+$)+/',
                        'phone_number' => 'required|numeric',
                        'timezone' => 'required',
                    ];

                    $validationErrorMessages = [
                        'first_name.required' => 'First Name field is required.',
                        'first_name.regex' => 'First name does not allow any special character.',
                        'last_name.regex' => 'Last Name does not allow any special character.',
                        'last_name.required' => 'Last Name field is required.',
                        'last_name.different' => 'First Name and Last Name should not be same.',
                        'phone_number.required' => 'Phone Number field is required.',
                        'timezone.required' => 'Timezone field is required.',
                    ];
                
                    $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                    if ( $validator->fails() ) {
                        if ($request->is('api/*')){
                            return apiResponse("error", $validator->messages()->toArray());
                        }
                        $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                        return Response::json($response);
                    }
                    
                    $request_data = [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'phone_number' => $request->phone_number,
                        'secondary_email' => $request->secondary_email,
                        'timezone' => $request->timezone,
                    ];
                    
                    $profile_info = $this->user->getProfileInfo();
                    $current_data = [
                        'first_name' => $profile_info->first_name,
                        'last_name' => $profile_info->last_name,
                        'phone_number' => $profile_info->phone_number,
                        'secondary_email' => $profile_info->secondary_email,
                        'timezone' => $profile_info->timezone,
                    ]; 
                    
                    $array_diff = array_diff($request_data, $current_data); 
                    $diff_count = count($array_diff); 
                    
                    if ($diff_count == 0 ) {
                        if ($request->is('api/*')){
                            return apiResponse("error", "You haven't made any changes.");
                        }else{
                            $response = ["type" => "error", "message" => ["You haven't made any changes."]];
                            return Response::json($response);
                        }
                    }
                    
                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->timezone = $request->timezone;
                    $user->save();

                    $payload = [   
                        'phone_number' => $request->phone_number,
                        'secondary_email' => $request->secondary_email,
                        'user_id' => $user_id,
                    ];

                    $this->user_general->updateGeneralInfo($portfolio_id,$payload);
                    if ($request->is('api/*')){
                        return apiResponse("success", "Your profile details has been updated successfully.");
                    } 
                    $response = ["type" => "success", "message" => "Your profile details has been updated successfully."];
                    
                break;
                
                case "changePassword":  
                    $rules = [
                            'current_password' => 'required', 
                            'new_password' => $this->system_settings->passwordValidation(),
                            'new_confirm_password' => 'required|same:new_password' 
                        ];
                    
                    $validationErrorMessages = [
                            'current_password.required' => 'Current Password field is required.',
                            'new_password.required' => 'New Password field is required.',
                            'new_confirm_password.required' => 'Confirm New Password field is required.',
                            'new_password.without_spaces' => 'New Password should not contain white spaces.',
                            'new_password.alphabet' => 'New Password field is required one alphabet.',
                            'new_password.digit' => 'New Password field is required one digit.',
                            'new_password.special' => 'New Password field is required one special character.',
                            'new_confirm_password.same' => 'Confirm New Password should match the New Password.'
                        ];
                    $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                    if ($validator->fails()){
                        if ($request->is('api/*')){
                            return apiResponse("error", $validator->messages()->toArray());
                        }
                        $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                        return Response::json($response);  
                    }
                    if (!\Hash::check($request->current_password, $user->password)) {
                        if ($request->is('api/*')){
                            return apiResponse("error", "Your current password does not matches with the password you provided.");
                        }
                        $response = ["type" => "error", "message" => ["Your current password does not matches with the password you provided."]];
                        return Response::json($response);
                    }
                
                    if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
                        if ($request->is('api/*')){
                            return apiResponse("error", "New password cannot be same as your current password. Please choose a different password.");
                        }
                        return Response::json($response);  
                    }
                    
                    $user->password = Hash::make($request->new_password); 
                    $user->save();
                    if ($request->is('api/*')){
                        return apiResponse("success", "Your password has been updated successfully.");
                    }
                    $response = ["type" => "success", "message" => "Your password has been updated successfully."]; 
                
                break;
                case "storeUserNotificationStatus":
                
                    $form_data = [ 
                        'notification_type_id' => $request->notification_type_id,
                        $request->status => $request->value, 
                    ]; 
                    
                    $user_notification_setting = $this->notificationsetting->updateUserNotificationStatus($user_id,$form_data);
                    
                    $response = ["type" => "success", "message" => "Your notification status has been changed successfully."];
                    
                break;
                case "editProfileAddress":
                    
                    $rules = [
                            'street' => 'required',
                            'city' => 'required',
                            'zip_code' => 'required',
                            'country' => 'required'
                        ];
                    
                    $validationErrorMessages = [
                            'street.required' => 'Street field is required.',
                            'city.required' => 'City field is required.',
                            'zip_code.required' => 'Zip Code field is required.',
                            'country.required' => 'Country field is required.'
                        ];
                    
                    $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                    if ( $validator->fails() ) {
                        if ($request->is('api/*')){
                            return apiResponse("error", $validator->messages()->toArray());
                        }
                        $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                        return Response::json($response);  
                    } 
                    
                    $request_data = [ 
                        'street' => $request->street,
                        'city' => $request->city,
                        'zip_code' => $request->zip_code,
                        'country' => $request->country,
                    ];
                    
                    $profile_info = $this->user->getProfileInfo();
                    $current_data = [
                        'street' => $profile_info->street,
                        'city' => $profile_info->city,
                        'zip_code' => $profile_info->zip_code,
                        'country' => $profile_info->country
                    ]; 
                    
                    $array_diff = array_diff($request_data, $current_data); 
                    $diff_count = count($array_diff); 
                    
                    if ($diff_count == 0 ) { 
                        if ($request->is('api/*')){
                            return apiResponse("error", "You haven't made any changes.");
                        }else{
                            $response = ["type" => "error", "message" => ["You haven't made any changes."]];
                            return Response::json($response);
                        }
                    }
                    
                    $payload = array_merge($request_data,['user_id' => $user_id]);
                    $this->user_general->updateGeneralInfo($portfolio_id,$payload);
                    
                    if ($request->is('api/*')){
                        return apiResponse("success", "Your address has been updated successfully.");
                    }
                    $response = ["type" => "success", "message" => "Your address has been updated successfully."];

                break;
                
                case "editSocialDetail":
                    
                    $rules = [
                        'facebook_url' => 'sometimes|nullable|url',
                        'twitter_url' => 'sometimes|nullable|url',
                        'skype_id' => 'required'  
                    ];

                    $validationErrorMessages = [ 
                        'skype_id.required' => 'Skype ID field is required.',
                        'facebook_url.sometimes' => 'Facebook URL format is invalid.',
                        'twitter_url.sometimes' => 'Twitter URL format is invalid.',
                    ];

                    $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                    if ( $validator->fails() ) {
                        if ($request->is('api/*')){
                            return apiResponse("error", $validator->messages()->toArray());
                        }
                        $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                        return Response::json($response); 
                    }
                    
                    $request_data = [
                        'facebook_url' => $request->facebook_url,
                        'twitter_url' => $request->twitter_url,
                        'skype_id' => $request->skype_id,
                    ]; 
                    
                    $profile_info = $this->user->getProfileInfo();
                    $current_data = [
                        'facebook_url' => $profile_info->facebook_url,
                        'twitter_url' => $profile_info->twitter_url,
                        'skype_id' => $profile_info->skype_id,
                    ]; 
                    
                    $array_diff = array_diff($request_data, $current_data); 
                    $diff_count = count($array_diff); 
                    
                    if ($diff_count == 0 ) { 
                        if ($request->is('api/*')){
                            return apiResponse("error", "You haven't made any changes.");
                        }else{
                            $response = ["type" => "error", "message" => ["You haven't made any changes."]];
                            return Response::json($response);
                        }
                    }
                    $this->user_general->updateGeneralInfo($portfolio_id,$request_data);
                    
                    if ($request->is('api/*')){
                        return apiResponse("success", "Your social detail has been updated successfully.");
                    }
                    $response = ["type" => "success", "message" => "Your social detail has been updated successfully."];
                break;
                
                case "deleteSocialID":
                    $social_id = $request->social_id; 
                    
                    $rules = [ 
                        'social_id' => 'required'  
                    ];

                    $validationErrorMessages = [ 
                        'social_id.required' => 'Social provider field is required.'
                    ];

                    $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                    if ( $validator->fails() ) {
                        if ($request->is('api/*')){
                            return apiResponse("error", $validator->messages()->toArray());
                        }
                        $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                        return Response::json($response); 
                    }
                    
                    $form_data = [
                        $social_id."_id" => NULL,
                        $social_id."_email" => NULL
                    ];

                    DB::table('socal_authorization')->where('user_id', $user_id)->update($form_data);
                    DB::table('socal_authorization')->whereNull('facebook_id')->whereNull('twitter_id')->whereNull('google_id')->delete();
                    
                    $response = ["type" => "success", "message" => "Your ".$request->social_id." has been deleted successfully."];
                break;
                
                default : 
                    $response = 'no_data';
            }
        } 
        else
        {
            $response = ['type' => "error", 'message' => ["Failed to update user profile."]];
        }
        
        return Response::json($response);
    }
}

