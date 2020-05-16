<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
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

   // protected $sendResetLink = 'email';
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
        return view('user.auth.forgotpassword');
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
        ],
        [
            'email.required' => 'Email Address field is required.',
            'email.email' => 'Email Address must be a valid email address.',
            'g-recaptcha-response.required' => 'Google Recaptcha field is required.',
            'g-recaptcha-response.captcha'  => 'Wrong google captcha, please try again.',
        ]);
        $request->is('api/*') ? : $validator = ['g-recaptcha-response' => 'required|captcha',];
        if ( $validator->fails() ) { 
            if ($request->is('api/*')) { 
                return apiResponse("error",null,$validator->messages()->toArray(),401); 
            }else{
                return $validator->validate();
            }
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
        $user = $this->broker()->getUser($credentials); 
        if (is_null($user)) {
            return Password::INVALID_USER;
        }
        $user_id = $user->id;
        $token = new Token;
        $access_token = $token->generateToken(['user_id' => $user_id],2,48);
        
        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $user->sendPasswordResetNotification($access_token);

        return 'passwords.sent';
    } 
    
    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {   
        if ($request->is('api/*')){
            return apiResponse("success",trans($response));
        }
        else{
            return back()->with('status', trans($response));
        }
    }
    
    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    { 
        if ($request->is('api/*')){
            return apiResponse("error",trans($response));
        }
        else{
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
        }
        
    }
}
