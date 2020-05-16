<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Agency;
use App\Models\Token;
use App\Models\EmailNotification as EN;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageTrait;
use Auth;

class AdministratorController extends Controller
{
    use ImageTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 
        parent::__construct();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admin = auth()->user();
        $params = $request->except('_token');
        if($admin->role_name == 'agency-admin'){
            $params['agency_id'] = $admin->agency_id;
        }
        $agencies = Agency::where('status',1)->get()->all();    
        $admins = Admin::filter($params)->with('roles','agency')->get();
        
        $data = [
                'admins' => $admins,
                'agencies' => $agencies,
                'agency_type' => $admin->role_name,
            ]; 
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('admin.admins')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $session_admin = auth()->user();
        $session_agency_id = $session_admin->agency_id;
        $session_role_type = $session_admin->role_name;
        $session_role_id = $session_admin->role->id;
        
        $admin = (object)[ 
                'id' => "",
                'first_name' => "",
                'last_name' => "",
                'email' => "",
                'password' => "",
                'status' => "",
                'profile_photo' => "",
                'role_id' => "",
                'role_name' => "",
                'agency_id' => "",
                'email_verified' => "",
                'timezone' => "",
                'session_role_type' => $session_role_type,
                'session_agency_id' => $session_agency_id,
                'session_role_id' => $session_role_id,
            ];
        
        $roles = Role::where('status',1)->get()->all();
        $agencies = Agency::where('status',1)->get()->all();
        $roles_arr = collect($roles)->toArray();
        $role_ids = array_column($roles_arr, 'id');
        $role_names = array_column($roles_arr, 'role_name');
        $roles_json = json_encode(array_combine($role_ids, $role_names)); 
        
        $data = [
            'id' => "",
            'admin' => $admin,
            'roles' => $roles,
            'agencies' => $agencies,
            'roles_json' => $roles_json,
        ];
        return view('admin.adminadd')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $rules = [
            'first_name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'last_name' => 'required|different:first_name|regex:/(^[A-Za-z0-9 ]+$)+/',
            'email' => 'required|string|email|unique:admins',
            'role_id' => 'required',
            'email_verified' => 'required',
            'timezone' => 'required',
        ]; 
        if($request->role_name=="agency-admin"){
            $rules['agency_id'] = "required";
        }
        
        $validationErrorMessages = [
            'first_name.required' => 'First Name field is required.',
            'first_name.regex' => 'First name does not allow any special character.',
            'last_name.regex' => 'Last Name does not allow any special character.',
            'last_name.required' => 'Last Name field is required.',
            'last_name.different' => 'First Name and Last Name should not be same.',
            'email.required' => 'Email Address field is required.',
            'email.email' => 'Email Address must be a valid email address.', 
            'role_id.required' => 'Role field is required.',
            'agency_id.required' => 'Agency field is required.',
            'email_verified.required' => 'Email Verified field is required.',
            'timezone.required' => 'Timezone field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);   
        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('admin.add.form'))->with('errors', $validator->messages())->withInput();
        }
        
        $admin = new Admin;
        
        $admin->first_name = $request->first_name; 
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->status = $request->status;
        $admin->email_verified = $request->email_verified;
        $admin->timezone = $request->timezone;
        if(!empty($request->agency_id)){
            $admin->agency_id = $request->agency_id;
        }
        $admin->save();
        $last_admin_id = $admin->id;
        
        $admin->attachRole($request->role_id);
        
        if($request->email_verified == 0){
            $token = new Token;
            $secure_token = $token->generateToken(['admin_id' => $last_admin_id],2,48);
            
            $admin_email = $request->email;
            $first_name      = trim($request->first_name);
            $last_name       = trim($request->last_name);
            $company         = config('app.name');
            $url             = config('admin.url').'register/verify/'.$secure_token;
            $copy_url        = "<a href='{$url}' target='_blank' style='color:#e74c3c;word-break: break-all;'>{$url}</a>";
            $mail_format     = (array) EN::getMailTextByKey("activate_admin_account");

            $subject      = $mail_format['subject'];
            $message_text = $mail_format['text'];
            $message_text = str_replace("{{first_name}}", $first_name, $message_text);
            $message_text = str_replace("{{last_name}}", $last_name, $message_text);
            $message_text = str_replace("{{company}}", $company, $message_text);
            $message_text = str_replace("{{url}}", $url, $message_text);
            $message_text = str_replace("{{copy_url}}", $copy_url, $message_text);

            $receiver = array();
            $receiver['toEmail']    = $admin_email;
            $receiver['toName']     = $first_name." ".$last_name;
            $data = ['message_text' => $message_text];

            $this->sendUIEmailNotification((object) $receiver, $subject, $data);
        }
        if ($request->is('api/*')) {
            return apiResponse("success","Admin Added Successfully.");
        }
        return redirect(route('admin.list'))->with('success', "Admin Added Successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $session_admin = auth()->user();
        $session_agency_id = $session_admin->agency_id;
        $session_role_type = $session_admin->role_name;
        $session_role_id = $session_admin->role->id;
        
        $id = decrypt($id);
        $admin = Admin::where('id', $id)->first()->adminDetails();
        if(!empty($admin->role)){
            $admin->role_id = $admin->role->id;
            $admin->role_name = $admin->role->role_name;
            $admin->session_role_type = $session_role_type;
            $admin->session_agency_id = $session_agency_id;
            $admin->session_role_id = $session_role_id;
        }
        
        if(!empty($admin))
        {
            $roles = Role::where('status',1)->get()->all();
            $agencies = Agency::where('status',1)->get()->all();
            $roles_arr = collect($roles)->toArray();
            $role_ids = array_column($roles_arr, 'id');
            $role_names = array_column($roles_arr, 'role_name');
            $roles_json = json_encode(array_combine($role_ids, $role_names));
            
            $data = [
                'id' => $id,
                'admin' => $admin,
                'roles' => $roles,
                'agencies' => $agencies,
                'roles_json' => $roles_json, 
            ]; 
            return view('admin.adminadd')->with($data);
        }
        else{
            return redirect(route('admin.list'))->with("error","No data found");
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->is('api/*') ? $id = $id : $id = decrypt($id); 
        $rules = [
            'first_name' => "required|regex:/(^[A-Za-z0-9 ]+$)+/",
            'last_name' => "required|different:first_name|regex:/(^[A-Za-z0-9 ]+$)+/",
            'email' => "required|string|email|unique:admins,email,{$id},id",
            'role_id' => 'required',
        ];
         
        if($request->role_name=="agency-admin"){
            $rules['agency_id'] = "required";
        }
        
        $validationErrorMessages = [
            'first_name.required' => 'First Name field is required.',
            'first_name.regex' => 'First name does not allow any special character.',
            'last_name.regex' => 'Last Name does not allow any special character.',
            'last_name.required' => 'Last Name field is required.',
            'last_name.different' => 'First Name and Last Name should not be same.',
            'email.required' => 'Email Address field is required.',
            'email.email' => 'Email Address must be a valid email address.', 
            'agency_id.required' => 'Agency field is required.',
            'role_id.required' => 'Role field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);
         
        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('admin.edit.form',encrypt($id)))->with('errors', $validator->messages())->withInput();
        }
        
        $admin = Admin::where('id', $id)->first();
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->timezone = $request->timezone;
        if(!empty($request->password)){
            $admin->password = Hash::make($request->password);
        }
        $admin->status = $request->status;
        
        if(!empty($request->agency_id)){          
            $admin->agency_id = $request->agency_id;
        }
        $admin->save();
        
        /* Attach & Detach Admin Role */
        $admin = Admin::where('id', $id)->first()->adminDetails();
        if(!empty($admin->role)){
            $admin->detachRole($admin->role->id);
        }
        $admin->attachRole($request->role_id); 
        if ($request->is('api/*')) {
            return apiResponse("success","Admin Updated Successfully.");
        }
        return redirect(route('admin.list'))->with('success', "Admin Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        starts_with(request()->path(), 'api') ? $id = $id : $id = decrypt($id); 
        $admin = Admin::where("id",$id)->get()->first();
        if(Admin::deleteByAdminId($id)){
            Storage::disk('public')->delete("admin-avatar/{$admin->id}/{$admin->profile_photo}");
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Admin Deleted Successfully.");
            }
            return redirect(route('admin.list'))->with("success","Admin Deleted Successfully.");
        }
        else{
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error","Failed to delete admin.");
            }
            return redirect(route('admin.list'))->with("error","Failed to delete admin.");
        }
    }
    
    public function setColor(Request $request){
        $admin_id = Auth::guard('admin')->user();
        $admin = Admin::where("id",$admin_id->id)->first();
        $admin->update([
            'theme_color' => $request->setcolor, 
        ]);
        return 'true';
    }
}
