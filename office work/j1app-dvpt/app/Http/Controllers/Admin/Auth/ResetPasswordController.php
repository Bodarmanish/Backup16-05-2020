<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Password;
use App\Models\SystemSettings;
use App\Models\Admin;
use App\Models\Token;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
 
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $system_settings = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->system_settings = new SystemSettings;
        parent::__construct();
    }
    
    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        $password_setting = $this->system_settings->getJ1PasswordInstruction();
        return view('admin.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email, 'password_setting' => $password_setting]
        );
    }
    
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        if( ! $request->token)
        {  
            return Redirect::back()->with('error', 'This password reset token not found.');
        }
        
        $token = new Token; 
        $token_data = $token->getToken($request->token);
        
        if (!$token_data)
        { 
            return Redirect::back()->with('error', 'This password reset token is invalid.');
        }
         
        if($token_data->token_expire_time <  strtotime(date(DB_DATETIME_FORMAT))){
            return Redirect::back()->with('error', 'This password reset token is expired.');
        }
        
        $matchWhere = ['email' => $request->email]; 
        $admin      = Admin::where($matchWhere)->get()->first();
        $password   = $request->password;
        
        $token_val = json_decode($token_data->token_value);
        $token_admin_id =  $token_val->admin_id;
        
        if(is_null($admin)){
            return Redirect::back()->with('error', 'This email address not found in our database.');
        }
            
        if($token_admin_id != $admin->id){
            return Redirect::back()->with('error', 'Your password reset token not match with given email address.');
        }
        
        $token = Token::find($token_data->id);
        $token->is_expired = 1; 
        $token->save();
        
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->resetPassword($admin, $password);
        
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if(Password::PASSWORD_RESET){
            Auth::logout();  
            return redirect(route('login'))->with('success', 'Your password reset successfully. Please login with new password.');
        }else{
            return $this->sendResetFailedResponse($request, $response);
        }
    }
    
    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {   
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => $this->system_settings->passwordValidation(),
            'password_confirmation' => 'required|same:password',
            'g-recaptcha-response' => 'required|captcha'
        ];
    }
    
    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
                'token.required' => 'Access Token field is required.',
                'email_address.required' => 'Email Address field is required.',
                'email_address.email' => 'Email Address must be a valid email address.',
                'g-recaptcha-response.required' => 'Google Recaptcha field is required.',
                'g-recaptcha-response.captcha'  => 'Wrong google captcha, please try again.',
                'password.required' => 'Password field is required.',
                'password.without_spaces' => 'Password does not allowed white spaces.',
                'password.alphabet' => 'Password field is required one alphabet.',
                'password.digit' => 'Password field is required one digit.',
                'password.special' => 'Password field is required one special character.',
                'password_confirmation.required' => 'Confirm Password field is required.',
                'password_confirmation.same' => 'Confirm Password should match the Password.',
            ];
    }
    
    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }
    
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $admin
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($admin, $password)
    {
        $admin->password = Hash::make($password);

        $admin->setRememberToken(Str::random(60));
        
        $admin->email_verified = 1;

        $admin->save();
        
        event(new PasswordReset($admin));
          
    }
}
