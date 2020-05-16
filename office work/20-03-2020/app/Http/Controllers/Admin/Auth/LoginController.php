<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Token;
use Cookie;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
    
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
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
        $this->validateLogin($request);
       
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn(
                   $this->throttleKey($request)
               ); 
            return $this->sendLockoutResponse($request);
        }

        $email = $request->email;
        $pass = $request->password;
        $remember = $request->remember;

        $matchWhere = ['email' => $email, 'status' => 1]; 
        $user = Admin::where($matchWhere)->get()->first();
        if (!empty($user)) {
            if($user->email_verified == 1){
                if ($this->attemptLogin($request)) {
                    if ($request->is('api/*')) {

                        //Create API Access Token
                        $tokenResult = $user->createToken('Admin Personal Access Token');
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
                    return $this->sendFailedLoginResponse($request);
                } 
            }
            else{
                 if ($request->is('api/*')) {
                    return apiResponse("error","Your account still not activated. Please check your inbox to activate your account.",null,401); 
                }
                return redirect(route('login'))->with('error', 'Your account still not activated. Please check your inbox to activate your account.');
            }    
        }
        else{
            if ($request->is('api/*')) {
                 return apiResponse("error","These credentials do not match our records.",null,401);
            }
            return redirect(route('login'))->with('error', 'These credentials do not match our records.');
        }
    }
    
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email Address field is required.',
            'password.required' => 'Password field is required.',
        ]);
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
        
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }
     
    /**
     * Verify new added admin account.
     *
     */
    public function verifyAdminAccount($confirmation_token)
    { 
        if( empty($confirmation_token) )
        {  
            return redirect(route('login'))->with('error', 'Confirmation token not found.');
        }
        
        $token = new Token; 
        $token_data = $token->getToken($confirmation_token);
        
        if ( ! $token_data)
        { 
            return redirect(route('login'))->with('error', 'Confirmation token not match with our database.');
        }  
         
        if($token_data->token_expire_time <  strtotime(date(DB_DATETIME_FORMAT))){
            return redirect(route('login'))->with('error', 'Confirmation token is expired.');
        }
         
        $token = Token::find($token_data->id);
        $token->is_expired = 1; 
        $token->save();
        
        $data = json_decode($token_data->token_value);
        $admin = Admin::find($data->admin_id);
        if($admin->email_verified==1){
            return redirect(route('login'))->with('success', 'Your account already verified. Please login using your credentials.');
        }
        $admin->email_verified = 1;
        $admin->save();  
        
        //$this->changeUserStatus($user->user_id,2,1,0,0,'candidate');
        //$this->addUserNotificationLog($user->user_id,"other_email_verification");
        
        return redirect(route('login'))->with('success', 'You have successfully verified your account. Please login using your credentials.');
    }
    
    public function logout(Request $request)
    {
        if ($request->is('api/*')) {
            $request->user()->token()->revoke(); 
            return apiResponse("success","Successfully logged out.");
        }  
        
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect(route('login'));
    }
}
