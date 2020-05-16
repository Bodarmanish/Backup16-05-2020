<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SystemSettings;
use Auth;
use Validator;

class SystemSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $data = collect(SystemSettings::where('field','password_setting')->select('value')->first())->all();
    
        if(!empty($data))
        {
            $data = [
                'ps_data' => json_decode($data['value'])
            ];
        }
        return view('admin.system-settings')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $action  = $request->action;

        switch($action){
            
            case 'common_setting':
                $rules = [
                    'app_name' => "required",
                    'app_url' => "required",
                    'contact_email' => "required|email",
                    'upload_img_size' => "required|numeric",
                    'upload_file_size' => "required|numeric",
                ];

                $validationErrorMessages = [
                    'app_name.required' => 'Application Name field is required.',
                    'app_url.required' => 'App URL field is required.',
                    'contact_email.required' => 'Contact Email field is required.',
                    'contact_email.email' => 'Contact Email must be a valid email',
                    'upload_img_size.required' => 'Upload Image Size field is required.',
                    'upload_img_size.numeric' => 'Upload Image Size must be a number.',
                    'upload_file_size.required' => 'Upload File Size field is required.',
                    'upload_file_size.numeric' => 'Upload File Size must be a number.',
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                if ($validator->fails()) {
                    if ($request->is('api/*')){
                        return apiResponse("error", "", $validator->messages()->toArray());
                    }
                    return redirect(route('system.settings'))->with('errors', $validator->messages())->withInput();
                }

                $settings = [];
                $settings['app_name'] = $request->app_name;
                $settings['app_url'] = $request->app_url;
                $settings['upload_file_size'] = $request->upload_file_size;
                $settings['upload_img_size'] = $request->upload_img_size;
                $settings['contact_email'] = $request->contact_email;

                foreach ($settings as $key => $value) {

                    $system_settings = SystemSettings::where('field', $key)
                                    ->select('*')->first();
                    $system_settings->value = $value;
                    $system_settings->save();
                }
                if ($request->is('api/*')){
                    return apiResponse("success", "Common Settings updated successfully.");
                }
                else{
                    return redirect(route('system.settings'))->with('success', "Common Settings updated successfully.")->withInput();
                }
            break;
        
            case 'password_setting':
                $rules = [
                    'min_limit' => "required|numeric",
                    'password_pattern' => "required",
                ];

                $validationErrorMessages = [
                    'min_limit.required' => 'Minimum Password Length field is required.',
                    'min_limit.numeric' => 'Minimum Password Length must be a number.',
                    'password_pattern.required' => 'Password Pattern field is required.'
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                if ($validator->fails()) {
                    if ($request->is('api/*')){
                        return apiResponse("error", "", $validator->messages()->toArray());
                    }
                    return redirect(route('system.settings'))->with('errors', $validator->messages())->withInput();
                }

                $password_setting = [];
                $password_setting['min_limit'] = $request->min_limit;

                if (!empty($request->password_pattern)) {
                    $password_setting['password_pattern'] = $request->password_pattern;
                }

                $password_setting = json_encode($password_setting);


                $system_settings = SystemSettings::where('field', 'password_setting')->first();
                $system_settings->value = $password_setting;
                $system_settings->save();
                if ($request->is('api/*')){
                    return apiResponse("success", "Password settings updated successfully.");
                }
                else{
                    return redirect(route('system.settings'))->with('success', "Password settings updated successfully.")->withInput();
                }
            break;
        
            case 'social_setting':
                $rules = [
                    'google_client_id' => "required",
                    'google_client_secret' => "required",
                    'facebook_client_id' => "required",
                    'facebook_client_secret' => "required",
                    'twitter_client_id' => "required",
                    'twitter_client_secret' => "required",
                    'google_captcha_site_key' => "required",
                    'google_captcha_site_secret' => "required"
                ];

                $validationErrorMessages = [
                    'google_client_id.required' => 'Google Client Id field is required.',
                    'google_client_secret.required' => 'Google Client Secret field is required.',
                    'facebook_client_id.required' => 'Facebook Client Id field is required.',
                    'facebook_client_secret.required' => 'Facebook Client Secret field is required.',
                    'twitter_client_id.required' => 'Twitter Client Id field is required.',
                    'twitter_client_secret.required' => 'Twitter Client Secret field is required.',
                    'google_captcha_site_key.required' => 'Google reCAPTCHA Site Key field is required.',
                    'google_captcha_site_secret.required' => 'Google reCAPTCHA Site Secret field is required.',
                ];

                $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

                if ($validator->fails()) {
                    if ($request->is('api/*')){
                        return apiResponse("error", "", $validator->messages()->toArray());
                    }
                    return redirect(route('system.settings'))->with('errors', $validator->messages())->withInput();
                }

                $social_setting = [];
                $social_setting['google_client_id'] = $request->google_client_id;
                $social_setting['google_client_secret'] = $request->google_client_secret;
                $social_setting['facebook_client_id'] = $request->facebook_client_id;
                $social_setting['facebook_client_secret'] = $request->facebook_client_secret;
                $social_setting['twitter_client_id'] = $request->twitter_client_id;
                $social_setting['twitter_client_secret'] = $request->twitter_client_secret;
                $social_setting['google_captcha_site_key'] = $request->google_captcha_site_key;
                $social_setting['google_captcha_site_secret'] = $request->google_captcha_site_secret;

                $social_setting = json_encode($social_setting);

                $system_settings = SystemSettings::where('field', 'social_setting')->first();
                $system_settings->value = $social_setting;
                $system_settings->save();
                if ($request->is('api/*')){
                    return apiResponse("success", "Social settings updated successfully.");
                }
                else{
                    return redirect(route('system.settings'))->with('success', "Social settings updated successfully.")->withInput();
                }
            break;
            
            default :
                  return redirect(route('system.settings'))->with('error', "Failed to update");
            break;
            
        }
               
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
