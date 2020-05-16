<?php 
namespace App\Http\Controllers\User\Auth;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Models\Token;
use App\Models\EmailNotification as EN;
use App\Models\Portfolio;
use App\Models\User;
use App\Models\SystemSettings;
use App\Models\Agency;
use App\Models\AgencyContract;
use Response;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    protected $username = 'email';
    
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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    { 
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email_address'],
            'password' => Hash::make($data['password']),
        ]);
    }
    
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {   
        $password_setting = $this->system_settings->getJ1PasswordInstruction();
        return view('user.auth.register')->with(['password_setting' => $password_setting]);
    }
    
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    { 
        $rules = [
                'first_name' => ['required', 'string', 'max:255', 'regex:/(^[A-Za-z0-9 ]+$)+/'],
                'last_name' => ['required', 'string', 'max:255', 'different:first_name', 'regex:/(^[A-Za-z0-9 ]+$)+/'],
                'email_address' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => $this->system_settings->passwordValidation(),
                'password_confirmation' => ['required', 'same:password'],
            ];
        $request->is('api/*') ? : $rules = ['g-recaptcha-response' => ['required', 'captcha']];
        $validationErrorMessages = [
            'first_name.required' => 'First Name field is required.',
            'first_name.regex' => 'Fist Name not allow any special character.',
            'last_name.required' => 'Last Name field is required.', 
            'last_name.regex' => 'Last Name not allow any special character.',
            'last_name.different' => 'First Name and Last Name should not be same.',
            'email_address.required' => 'Email Address field is required.',
            'password.required' => 'Password field is required.',
            'first_name.string' => 'First Name must be a string.',
            'last_name.string' => 'Last Name must be a string.', 
            'email_address.email' => 'Email Address must be a valid email address.',
            'email_address.unique' => 'Email Address has already been taken.',
            'password.without_spaces' => 'Password does not allowed white spaces.',
            'password.alphabet' => 'Password field is required one alphabet.',
            'password.digit' => 'Password field is required one digit.',
            'password.special' => 'Password field is required one special character.',
            'g-recaptcha-response.required' => 'Google Recaptcha field is required.',
            'g-recaptcha-response.captcha' => 'Wrong google captcha, please try again.',
            'password_confirmation.required' => 'Confirm Password field is required.',
            'password_confirmation.same' => 'Confirm Password should match the Password.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);
        if ( $validator->fails() ) { 
            if ($request->is('api/*')) { 
                return apiResponse("error",$validator->messages()->toArray(),null); 
            }else{
                return $validator->validate();
            }
        } 

        event(new Registered($user = $this->create($request->all())));
        $this->registered($request, $user);
        
        if ($request->is('api/*')){
            return apiResponse("success","Your account created successfully. Please check your inbox to activate your account.");
        }
        else{
            return redirect($this->redirectPath())->with('success', 'Your account created successfully. Please check your inbox to activate your account.');
        }
    }
    
    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $user_id = $user->id;
        
        /* Create Portfolio for current user id */ 
        $portfolio = new Portfolio;
        $portfolio->user_id = $user_id;
        $portfolio->portfolio_number = "PF".mt_rand(100000000, 999999999);
        $portfolio->save();
        
        /* Update portfolio id in users table */
        $user->portfolio_id = $portfolio->id;
        $user->save();
        $this->changeUserStatus($user,'just-register');
        return $this->generateActivationEmail($user_id); 
    }
     
    /**
     * Verify registered user account.
     *
     */
    public function verifyUserAccount($confirmation_token)
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
        $user = User::find($data->user_id);
        if($user->email_verified==1){
            return redirect(route('login'))->with('success', 'Your account already verified. Please login using your credentials.');
        }
        $user->email_verified = 1;
        $user->save();  
        
        $this->changeUserStatus($user,'email-verified');
        
        return redirect(route('login'))->with('success', 'You have successfully verified your account. Please login using your credentials.');
    }
    
    /*
     * Resend Verification Code
     */
    public function resendVerificationCode($user_id)
    {
        if(!empty($user_id)){
            $user_id = decrypt($user_id);
            $this->generateActivationEmail($user_id);
            return redirect(route('login'))->with('success', 'Verification link send successfully. Please check your inbox to activate your account.');
        }else{
            return redirect(route('login'))->with('error', 'This email address is not found in our database.');
        }
    }
    
    /*
     * Resend Verification Code
     */
    public function generateActivationEmail($user_id)
    {
        if(!empty($user_id)){
            $user = User::find($user_id);
            $token = new Token;
            $secure_token = $token->generateToken(['user_id' => $user_id],2,48);

            $candidate_email = $user->email;
            $first_name      = trim($user->first_name);
            $last_name       = trim($user->last_name);
            $candidate_name  = trim($user->first_name." ".$user->last_name);
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

            return $this->sendUIEmailNotification((object) $receiver, $subject, $data);
 
        }else
            return false;
    }
    
    /*
     * verify Captcha
     */
    public function verifyCaptcha()
    {
        $HTML = view('user.auth.verify-captcha')->render();       
        return Response::json( ["type" => "success", NULL, "data" => $HTML]);
    }
    
    /**
     * Check Invitation from agency.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkInvitation(Request $request)
    {
        if( ! $request->invitation_token )
        {  
            return redirect(route('login'))->with('error', 'Invitation link not found.');
        }
        
        $token = new Token; 
        $token_data = $token->getToken($request->invitation_token);
        
        if (!$token_data)
        { 
            return redirect(route('login'))->with('error', 'Invitation link is invalid.');
        }
         
        if($token_data->token_expire_time <  strtotime(date(DB_DATETIME_FORMAT))){
            return redirect(route('login'))->with('error', 'Invitation link is expired.');
        }
           
        $token_val = json_decode($token_data->token_value);
        $contract_id = $token_val->contract_id;
        $agency_contract = AgencyContract::where(['id' => $contract_id])->get()->first();
        $email_address = $agency_contract->email; 
        $agency_name = (!empty($agency_contract->agency->agency_name))?$agency_contract->agency->agency_name:'';
        
        $tokenm = Token::find($token_data->id);
        $tokenm->is_expired = 1; 
        $tokenm->save();
        
        $password_setting = $this->system_settings->getJ1PasswordInstruction();
        
        $input_data = [
            'password_setting' => $password_setting,
            'email_address' => $email_address,
            'contract_id' => $contract_id,
            'agency_name' => $agency_name,
        ];
        $user = User::where(['email' => $email_address])->get()->first();
        if(!empty($user)){
            $current_portfolio = collect($user->portfolio)->toArray();
            $portfolio_status_arr = array_column($current_portfolio, 'portfolio_status');
            $input_data['user_exist'] = 1;
            if(!empty(array_intersect([1,2],$portfolio_status_arr))){
                if(($agency_contract['contract_type']==1 && $current_portfolio["registration_agency_id"]==0) || ($agency_contract['contract_type']==2 && $current_portfolio["placement_agency_id"]==0) || ($agency_contract['contract_type']==3 && $current_portfolio["sponsor_agency_id"]==0) || ($agency_contract['contract_type']==4 && ($current_portfolio["registration_agency_id"]==0 || $current_portfolio["placement_agency_id"]==0 || $current_portfolio["sponsor_agency_id"]==0))){
                    return view('user.auth.invitation')->with($input_data);
                }
                else{
                    Auth::login($user);
                    return redirect(route('myportfolio'))->with('error', "You have already active portfolio. Please complete or closed active portfolio before accept invitation.");
                }
            }
            else{
                return view('user.auth.invitation')->with($input_data);
            }
        }
        $input_data['user_exist'] = 0;
        return view('user.auth.invitation')->with($input_data);
    }
    
    /**
     * Accept Invitation by User.
     *
     * @return \Illuminate\Http\Response
     */
    public function acceptInvitation(Request $request)
    {
        $data = $request->all();
        $request->is('api/*') ? $contract_id = $data['contract_id'] : $contract_id = decrypt($data['contract_id']);
        $agency_contract = AgencyContract::where(['id' => $contract_id])->get()->first();
        $data['email_address'] = $agency_contract->email;
        $user = User::where(['email' => $agency_contract->email])->first();
        if(!empty($user)){
            
            /* Create Portfolio and set agency for current user id */
            if(!empty($agency_contract)){
                $user->setAgency($agency_contract->id);
            }
            
            $response = ["type" => "success", "message" => "Your contract has been agreed.", "data" => array()]; 
            Auth::loginUsingId($user->id);
            return Response::json($response);
            
        }
        else{
            $rules = [
                    'first_name' => ['required', 'string', 'max:255', 'regex:/(^[A-Za-z0-9 ]+$)+/'],
                    'last_name' => ['required', 'string', 'max:255', 'different:first_name', 'regex:/(^[A-Za-z0-9 ]+$)+/'], 
                    'email_address' => ['unique:users,email'], 
                    'password' => $this->system_settings->passwordValidation(),
                    'password_confirmation' => ['required', 'same:password'],
                ];
            $request->is('api/*') ? : $rules = ['g-recaptcha-response' => ['required', 'captcha']];
            $validationErrorMessages = [
                'first_name.required' => 'First Name field is required.',
                'first_name.regex' => 'Fist Name not allow any special character.',
                'last_name.required' => 'Last Name field is required.', 
                'last_name.regex' => 'Last Name not allow any special character.',
                'last_name.different' => 'First Name and Last Name should not be same.',
                'password.required' => 'Password field is required.',
                'first_name.string' => 'First Name must be a string.',
                'last_name.string' => 'Last Name must be a string.', 
                'password.without_spaces' => 'Password does not allowed white spaces.',
                'password.alphabet' => 'Password field is required one alphabet.',
                'password.digit' => 'Password field is required one digit.',
                'password.special' => 'Password field is required one special character.',
                'g-recaptcha-response.required' => 'Google Recaptcha field is required.',
                'g-recaptcha-response.captcha' => 'Wrong google captcha, please try again.',
                'password_confirmation.required' => 'Confirm Password field is required.',
                'password_confirmation.same' => 'Confirm Password should match the Password.',
                'email_address.unique' => 'Email Address has already been taken.',
            ];
            $validator = Validator::make($data, $rules, $validationErrorMessages);
            if ( $validator->fails() ) {
                $response = ["type" => "error", "message" => $validator->messages()->toArray()];
                return Response::json($response);
            } 
            event(new Registered($user = $this->create($data)));
             
            /* Create Portfolio and set agency for current user id */ 
            $user = $user->setAgency()->fresh();
            $user->email_verified = 1;
            $user->save();
            $this->changeUserStatus($user, "email-verified");
            
            $response = ["type" => "success", "message" => "Your basic detail inserted successfully.", "data" => array('newuser'=>1)];
            return Response::json($response);
        }
    }
}