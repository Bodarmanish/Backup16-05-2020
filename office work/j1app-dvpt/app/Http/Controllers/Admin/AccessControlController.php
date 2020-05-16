<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\Input;

class AccessControlController extends Controller
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
        $roles = Role::all();
        
        foreach($roles as $key => $role){
            $permissions = collect($role->permissions()->get())->toArray();
            $roles[$key]->permissions = $permissions;
        }
        
        $roles = collect($roles)->all();

        if(!empty($roles))
        {
            $data = [
                'roles' => $roles
            ];
        }
        else{
            $data = ["error","No data found"];
        }
        
        return view('admin.roles')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = (object)[
                'role_name' => "",
                'display_name' => "",
                'description' => "",
                'status' => "",
            ];
        
        $data = [
            'role' => $role,
        ];
        return view('admin.roleadd')->with($data);
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
            'role_name' => 'required|unique:roles,display_name|regex:/(^[A-Za-z0-9 ]+$)+/',
        ];

        $validationErrorMessages = [
            'role_name.required' => 'Role Name field is required.',
            'role_name.regex' => 'Role Name does not allow any special character.',
            'role_name.unique' => 'Role Name has already been taken.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return redirect(route('role.add.form'))->with('errors', $validator->messages())->withInput();
        }
        
        $role_name = Str::slug($request->role_name);
        $role = new Role;
        $role->display_name = $request->role_name;
        $role->role_name = $role_name;
        $role->description = $request->description;
        $role->status = $request->status;
        $role->save();
        
        return redirect(route('role.list'))->with('success', "Role added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $role_name
     * @return \Illuminate\Http\Response
     */
    public function edit($role_name)
    {
        $role = Role::where('role_name', $role_name)->first();
        
        if(!empty($role))
        {
            $data = [
                'role' => $role,
                'role_name' => $role_name
            ];
            return view('admin.roleadd')->with($data);
        }
        else{
            return redirect(route('role.list'))->with("error","No data found");
        }
        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role_name)
    {
        $rules = [
            'role_name' => "required|unique:roles,display_name,{$role_name},role_name|regex:/(^[A-Za-z0-9 ]+$)+/",
        ];

        $validationErrorMessages = [
            'role_name.required' => 'Role Name field is required.',
            'role_name.regex' => 'Role Name does not allow any special character.',
            'role_name.unique' => 'Role Name has already been taken.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            return redirect(route('role.edit.form',$role_name))->with('errors', $validator->messages())->withInput();
        }
        
        $role_name = Str::slug($request->role_name);
        
        $role = Role::where('role_name', $role_name)->first();
        $role->display_name = $request->role_name;
        $role->role_name = $role_name;
        $role->description = $request->description;
        $role->status = $request->status;
        $role->save();
        
        return redirect(route('role.list'))->with('success', "Role updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role_name)
    {
        if(Role::deleteByRoleName($role_name)){
            return redirect(route('role.list'))->with("success","Role deleted successfully.");
        }
        else{
            return redirect(route('role.list'))->with("error","Failed to delete role.");
        }
    }
}
