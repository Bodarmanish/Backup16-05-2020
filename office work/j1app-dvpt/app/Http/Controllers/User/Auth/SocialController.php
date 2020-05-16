<?php

namespace App\Http\Controllers\User\Auth;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailNotification as EN;
use App\Models\User;
use App\Models\Token;
use App\Traits\ActivationTrait;
use App\Models\Portfolio;
use DB;
use Carbon\Carbon;

class SocialController extends Controller
{ 
    use ActivationTrait;
    
    /**
     * getSocialRedirect(Request $request, $provider, $type)
     * Get social redirect using provider
     * @param int $provider 
     * @param int $type
    */
    public function getSocialRedirect( Request $request, $provider, $type )
    {    
        $providerKey = Config::get('services.' . $provider);
        if (empty($providerKey)) {
            if ($request->is('api/*')) {
                return apiResponse("error","No such provider");
            }
            return view('UserInterface.includes.status')->with('error','No such provider');
        }
        
        if ($request->is('api/*')) {
            if($type="authorize"){
                $redirectUrl = url("api/social_auth/handle/{$provider}/{$type}");
            }
            else{
                $redirectUrl = url("api/social/handle/{$provider}/{$type}");
            }
        }else{
            $redirectUrl = url("social/handle/{$provider}/{$type}");  
        }
       
        config(['services.'.$provider.'.redirect' => $redirectUrl]); 
        if ($request->is('api/*')) {
            return Socialite::with($provider)->stateless()->redirect();
        }
        return Socialite::with($provider)->redirect();
    }
    
    /** 
     * getSocialHandle(Request $request, $provider, $type)
     * Handle social request
     * @param int $provider 
     * @param int $type
    */
    public function getSocialHandle(Request $request, $provider, $type){
         
        if ($request->is('api/*')) {
            if($type="authorize"){
                $redirectUrl = url("api/social_auth/handle/{$provider}/{$type}");
            }
            else{
                $redirectUrl = url("api/social/handle/{$provider}/{$type}"); 
            }
        }else{
            $redirectUrl = url("social/handle/{$provider}/{$type}");  
        } 
        
        config(['services.'.$provider.'.redirect' => $redirectUrl]);
        
        if (Input::get('error') != '' || Input::get('denied') != '') { 
            if ($request->is('api/*')) {
                return apiResponse("error","Permissions error.");
            }
            else{
                if($type=="authorize"){ 
                    return redirect(route('edit.profile','social'))->with("error","Permissions error.");
                }else{
                    return redirect(route('login'))->with("error","Permissions error.");
                }
            }
        }
       
        if ($request->is('api/*')) {
            $user = Socialite::with($provider)->stateless()->user();
        }else{
            $user = Socialite::driver( $provider )->user();
        }
        
        $social_id = $user->id;
        $email = $user->email;
        
        if ($email=="") {
            if ($request->is('api/*')) {
                return apiResponse("error","We are not able to access your email address.");
            }
            else{
                if($type=="authorize"){
                    return redirect(route("view.profile"))->with("error","We are not able to access your email address.");
                }else{
                   return redirect(route('login'))->with("error","We are not able to access your email address."); 
                }
            } 
        }
        
        /*Check is this email present*/
        $userCheck = User::where('email', '=', $user->email)->first();
        $social_provider_id = $provider.'_id';
          
        if($type=="login"){
            $chekSocialStatus = User::isUserAuthorized($user->email);
            if(!empty($chekSocialStatus)){
                $newuser = User::where(['id' => $chekSocialStatus->id])->get()->first();
                if(!empty($newuser)){
                    $userCheck = $newuser;
                    $email = $newuser->email;
                }
            }
            if (!empty($userCheck)){
                if($userCheck->status==1){
                    auth()->login($userCheck, true);

                    if ( auth()->user() ) {
                        if ($request->is('api/*')) {
                            // OAuth Two Providers
                            $user = User::find(auth()->user()->id);
                            $tokenResult = $users->createToken('Personal Login Access Token');
                            $token = $tokenResult->token;
                            $token->save();

                            $user_data = array();
                            $user_data['access_token'] = $tokenResult->accessToken;
                            $user_data['token_type'] = 'Bearer';
                            $user_data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                            return apiResponse("success","You are successfully login.",$user_data);
                        }
                        return redirect(route("view.profile"));
                    }
                }
                else{
                    if ($request->is('api/*')) {
                        return apiResponse("error","Your account is deactive. Please contact administrator to activate your account.");
                    }
                    return redirect(route('login'))->with("error","Your account is deactive. Please contact administrator to activate your account.");
                }
            }else{
                return $this->CreateSocialUser($request, $user, $provider, "login");
            }
        }
        else if($type=="register"){
            $chekSocialStatus = User::isUserAuthorized($user->email);
            if(!empty($chekSocialStatus)){
                $result = User::where(['id' => $chekSocialStatus->id])->get()->first();
                if(!empty($result)){
                    $userCheck = $result;
                    $email = $result->email;
                }
            }
            if(!empty($userCheck)){
                if ($request->is('api/*')) {
                    return apiResponse("error","This email address is already register with us. Please try to register using different email address.");
                }
                return redirect(route('register'))->with("error","This email address is already register with us. Please try to register using different email address.");
            }else{
                session()->flash('social_signup', 1);
                return $this->CreateSocialUser($request, $user, $provider, "register");
            }
        }
        else if($type=="authorize"){
            if(!empty($userCheck) && auth()->user()->id!=$userCheck->id){
                if ($request->is('api/*')) {
                    return apiResponse("error","This email address is already authorized.");
                }
                return redirect(route('edit.profile','social'))->with('error','This email address is already authorized.');
            }
            
            $chekSocialStatus = User::isUserAuthorized($user->email,auth()->user()->id);
            if (!empty($chekSocialStatus)) {
                if ($request->is('api/*')) { 
                    return apiResponse("error","This email address is already authorized.");
                }
                return redirect(route('edit.profile','social'))->with('error','This email address is already authorized.');
            }
            
            /** Update other email address entry have same provider already authorized by same user **/
            DB::table('socal_authorization')->where('user_id', auth()->user()->id)->where($social_provider_id, "!=", "")->update([$social_provider_id => ""]);
            
            $is_exist = DB::table('socal_authorization')
                            ->select('id')
                            ->where('user_id',auth()->user()->id)
                            ->whereNull($social_provider_id)
                            ->first();
            
            $insert_data = array();
            $insert_data['user_id'] = auth()->user()->id;
            $insert_data[$provider.'_email'] = $email;
            $insert_data[$social_provider_id] = $social_id;

            if(!empty($is_exist->id)){
                DB::table('socal_authorization')->where('id', $is_exist->id)->update($insert_data);
            }else{
                DB::table('socal_authorization')->insertGetId($insert_data);
            }
            
            /** Delete Blank Entry if any provider id not inserted for user id and email address **/
            DB::table('socal_authorization')->where('user_id', '=', auth()->user()->id)->whereNull("facebook_id")->whereNull("google_id")->whereNull("twitter_id")->delete();
            if ($request->is('api/*')) {
                return apiResponse("success","Your social email address has been authorised successfully.");
            }
            return redirect(route('edit.profile','social'))->with('success',"Your social email address has been authorised successfully.");
        }
        else{
            return redirect(route('login'))->with('error','We are not able to find this user in our database.');
        }
    }
    
    /**
     * CreateSocialUser(Request $request, $user = array(), $provider = null, $redirect = null)
     * Create New User when user email not found in database during social login/register
     * @param array $user
     * @param int $provider = google, twitter, facebook
     * @param int $redirect = login, register
    */
    public function CreateSocialUser(Request $request, $user = array(), $provider = null, $redirect = null){
        if(!empty($user) && !empty($provider) && !empty($user->email) ){
            
            $email = $user->email;
            $social_id = $user->id;
            $social_provider_id = $provider.'_id';
             
            if(empty($redirect)){
                $redirect = "login";
            }
            
            $newSocialUser = new User;
            $newSocialUser->email = $email;
            $name = explode(" ", $user->name);

            if (count($name) >= 1) {
                $newSocialUser->first_name = $name[0];
            }

            if (count($name) >= 2) {
                $newSocialUser->last_name = $name[1];
            }

            $newSocialUser->password = Hash::make(str_random(16));
            $newSocialUser->remember_token = str_random(64); 
            $newSocialUser->email_verified = 1;
            $newSocialUser->save();
            $user_id = $newSocialUser->id;
 
            $this->initiateEmailActivation($newSocialUser);

           // UserNotificationLog::importNotificationSettings($user_id);

            $is_exist = DB::table('socal_authorization')
                                    ->select('id')
                                    ->where($provider.'_email',$email)
                                    ->first();
            $insert_data = array();
            $insert_data['user_id'] = $user_id;
            $insert_data[$provider.'_email'] = $email;
            $insert_data[$social_provider_id] = $social_id;
            if(!empty($is_exist->id)){
                DB::table('socal_authorization')->where('id', $is_exist->id)->update($insert_data);
            }else{ 
                DB::table('socal_authorization')->insertGetId($insert_data);
            }

            if ( !empty($user_id)) {

                /* Create Portfolio for current user id */ 
                $portfolio = new Portfolio;
                $portfolio->user_id = $user_id;
                $portfolio->portfolio_number = "PF".mt_rand(100000000, 999999999);
                $portfolio->save();

                /* Update portfolio id in users table */
                $newSocialUser->portfolio_id = $portfolio->id;
                $newSocialUser->save();

                /* Send Reset password link */
                $candidate_email = $newSocialUser->email;
                $first_name = trim($newSocialUser->first_name);
                $last_name = trim($newSocialUser->last_name);
                $candidate_name = trim($first_name." ".$last_name);

                $token = new Token;
                $token = $token->generateToken(['user_id' => $user_id],2,48);

                $set_pwd_url = config('app.url')."password/setpassword/{$token}";
                $company = config("app.name");
                $mail_format = (array) EN::getMailTextByKey("set_password");

                $subject      = $mail_format['subject'];
                $message_text = $mail_format['text'];
                $message_text = str_replace("{{first_name}}", $first_name, $message_text);
                $message_text = str_replace("{{last_name}}", $last_name, $message_text);
                $message_text = str_replace("{{email}}", $candidate_email, $message_text);
                $message_text = str_replace("{{url}}", $set_pwd_url, $message_text);
                $message_text = str_replace("{{company_name}}", $company, $message_text);
                $message_text = str_replace("{{provider}}", $provider, $message_text);

                $receiver = array();
                $receiver['toEmail'] = $candidate_email;
                $receiver['toName'] = $candidate_name;
                $data = ['message_text' => $message_text];

                $sent = $this->sendUIEmailNotification((object) $receiver, $subject, $data);
                $newsocialuser = User::where(['id' => $user_id])->get()->first();
                auth()->login($newsocialuser, true);
                
                /*update user status*/
                $this->changeUserStatus($newSocialUser,'just-register');

                if ( auth()->user() ) { 
                    if ($request->is('api/*')) {
                        
                        //Create API Access Token
                        $user = User::find($user_id);
                        $tokenResult = $users->createToken('Personal Login Access Token');
                        $token = $tokenResult->token;
                        $token->save();

                        $user_data = array();
                        $user_data['access_token'] = $tokenResult->accessToken;
                        $user_data['token_type'] = 'Bearer';
                        $user_data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                        return apiResponse("success","Your account created successfully. Please check your inbox to activate your account.",$user_data);
                    }
                    return redirect(route("view.profile"));
                }else{
                    if ($request->is('api/*')) {
                        return apiResponse("error","Something went wrong. Please try again after some time.");
                    }
                    return redirect("{$redirect}")->with('error',"Something went wrong. Please try again after some time.");
                }
            }else{
                if ($request->is('api/*')) {
                    return apiResponse("error","Something went wrong. Please try again after some time.");
                }
                return redirect("{$redirect}")->with('error',"Something went wrong. Please try again after some time.");
            }
        }else{
            if ($request->is('api/*')) {
                return apiResponse("error","Something went wrong. Please try again after some time.");
            }
            return redirect("{$redirect}")->with('error',"Something went wrong. Please try again after some time.");
        }
    }
} 