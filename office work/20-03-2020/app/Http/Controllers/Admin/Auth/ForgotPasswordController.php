<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Token;
use Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    protected $username = 'email';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgotpassword');
    }
    
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [  
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha',
        ],
        [
            'email.required' => 'Email Address field is required.',
            'email.email' => 'Email Address must be a valid email address.',
            'g-recaptcha-response.required' => 'Google Recaptcha field is required.',
            'g-recaptcha-response.captcha'  => 'Wrong google captcha, please try again.',
        ]);
        
        if ( $validator->fails() ) { 
            return $validator->validate();
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->sendResetLink(
            $this->credentials($request)
        );
        
        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
          
    /**
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @return string
     */
    public function sendResetLink(array $credentials)
    {
        
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $admin = $this->broker()->getUser($credentials); 
        if (is_null($admin)) {
            return Password::INVALID_USER;
        }
        $admin_id = $admin->id;
        $token = new Token;
        $access_token = $token->generateToken(['admin_id' => $admin_id],2,48);
        
        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $admin->sendPasswordResetNotification($access_token);

        return 'passwords.sent';
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
    * Get the broker to be used during password reset.
    *
    * @return PasswordBroker
    */
   protected function broker()
   {
       return Password::broker('admins');
   }
    
}
