<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Cookie;
use Validator;
use Carbon\Carbon;
use Response;
use App\Models\Token;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }
    
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {  
        if ($request->is('api/*' || $request->action == 'ajaxlogin')) {
            $rules = [
                'email' => 'required|email|string', 
                'password' => 'required|string', 
            ];

            $validationErrorMessages = [
                'email.required' => 'Email Address field is required.',
                'password.required' => 'Password field is required.',
            ];

            $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

            if ( $validator->fails() ) 
            { 
                $data = $validator->messages()->toArray();
                
                if($request->action == 'ajaxlogin')
                {
                    $response = ["type" => "error", "message" => $data];
                    return Response::json($response);
                }
                
                return apiResponse("error",$data,null);
            }
        }
        else{
            $this->validateLogin($request);
        }
       
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn(
                   $this->throttleKey($request)
               );
            if ($request->is('api/*')) { 
                return apiResponse("error","Too many login attempts. Please try again in {$seconds} seconds.",null,401);
            }
            if($request->action == 'ajaxlogin')
            {
                $response = ['type' => "error", 'message' => ['login_error' => ["Too many login attempts. Please try again in {$seconds} seconds."]]];
                return Response::json($response);
            }
            return $this->sendLockoutResponse($request);
        }

        $email = $request->email;
        $pass = $request->password;
        $remember = $request->remember;

        $matchWhere = ['email' => $email]; 
        $user = User::where($matchWhere)->get()->first();
        if (!empty($user)) {
            if($user->email_verified == 1){
                if ($this->attemptLogin($request)) {
                    if ($request->is('api/*')) {
                        
                        //Create API Access Token
                        $user = $request->user();
                        $tokenResult = $user->createToken('Personal Login Access Token');
                        $token = $tokenResult->token;
                        if ($remember)
                            $token->expires_at = Carbon::now()->addMinute(config("common.remember_me_timeout"));
                        $token->save();
                        
                        $data = array();
                        $data['access_token'] = $tokenResult->accessToken;
                        $data['token_type'] = 'Bearer';
                        $data['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                        return apiResponse("success",null,$data);
                    }
                    return $this->sendLoginResponse($request);
                }
                else{
                    // If the login attempt was unsuccessful we will increment the number of attempts
                    // to login and redirect the user back to the login form. Of course, when this
                    // user surpasses their maximum number of attempts they will get locked out.
                    $this->incrementLoginAttempts($request);
                    if ($request->is('api/*')) {
                        return apiResponse("error","These credentials do not match our records.",null,401);
                    }
                    
                    if($request->action == 'ajaxlogin')
                    {
                        $response = ['type' => "error", 'message' => ['login_error' =>[ 'These credentials do not match our records.']]];
                        return Response::json($response);
                    }
                    return $this->sendFailedLoginResponse($request);
                } 
            }
            else{ 
                if ($request->is('api/*')) {
                    return apiResponse("error","Your account still not activated. Please check your inbox to activate your account.",null,401); 
                }
                /* Only for web */
                $url = url("resend-verification/".encrypt($user->id));
                $resendlink = "<a href='javascript:void(0);' id='resend_activation_email' data-url='{$url}'>Resend Verification</a>";
                return redirect(route('login'))->with("error", "Your account still not activated. Please check your inbox to activate your account. Or {$resendlink} email.");
            }    
        }
        else{
            if ($request->is('api/*')) {
                 return apiResponse("error","These credentials do not match our records.",null,401);
            }
            if($request->action == 'ajaxlogin')
            {
                  $response = ['type' => "error", 'message' => ['login_error' =>[ 'These credentials do not match our records.']]];
                  return Response::json($response);
            }
            return redirect(route('login'))->with('error', 'These credentials do not match our records.');
        }
    }
    
    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $remember = $request->remember;
        if($remember == 1){
            $customRememberMeTimeInMinutes = config("common.remember_me_timeout");
            $rememberTokenCookieKey = Auth::getRecallerName(); 
            Cookie::queue($rememberTokenCookieKey, Cookie::get($rememberTokenCookieKey), $customRememberMeTimeInMinutes);
        }
        
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        
        if($request->action == 'ajaxlogin')
        {
              $response = ['type' => "success", 'message' => "Successfully login"];
              return Response::json($response);
        }
        
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if ($request->is('api/*')) {
            
            $request->user()->token()->revoke(); 
            return apiResponse("success","Successfully logged out.");
        }  
        
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect(route('home'));
    }
    
     /*
     * Resend Verification captcha
     */
    public function resendVerificationCaptcha()
    {
        $data = array();
        $data['action'] = 'resend_verification'; 
        $HTML = view('user.ajax')->with('data',$data)->render(); 
        $response = ["type" => "success", "message" => "", "data" => $HTML];
        return Response::json($response);  
    }   
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function directLogin(Request $request, $access_token)
    {
        
        if(!empty($access_token))
        {
            $token = new Token; 
            $token_data = $token->getToken($access_token);
            if(!empty($token_data))
            {
                $token_value = $token_data->token_value;
                $token_value = json_decode($token_value);

                $matchWhere = ['id' => $token_value->user_id];
                $user = User::where($matchWhere)->get()->first();

                if(!empty($user->id))
                {
                    $user_id = intval($user->id);
                    Auth::loginUsingId($user_id,TRUE);
                    return $this->sendLoginResponse($request);
                }
                else {
                    $this->incrementLoginAttempts($request);
                    return $this->sendFailedLoginResponse($request);
                }
            }
            else
            {
                return redirect(route('login'))->with("error", "Access token miss match.");
            }
        }
        else
        { 
            return redirect(route('login'))->with("error", "Access token miss match.");
        }
    }
}
