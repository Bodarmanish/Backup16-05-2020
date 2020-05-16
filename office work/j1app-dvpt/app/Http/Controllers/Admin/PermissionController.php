<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
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
    public function index(Request $request, $role_name = null)
    {
        $permissions = "";
        
        if(!empty($role_name)) {
            $roles = collect(Role::where('role_name', $role_name)->get())->all();
        }
        else {
            $roles = collect(Role::all())->all();
        }
        
        if(!empty($request->all()) || $request->is('api/*')){
            $p = new Permission;
            $permissions = $p->getAllPermissions($request);
        }
        
        $permission_groups = collect(PermissionGroup::all())->all();
        
        $data = [
            'permissions' => $permissions,
            'permission_groups' => $permission_groups,
            'roles' => $roles,
            'role_name' => $role_name
        ];
        if ($request->is('api/*')) {
            return apiResponse("success","",$data);
        }
        return view('admin.permissions')->with($data);
    }
    
    public function updateRolePermission(Request $request){
        
        $r_id = $request->get('r_id');
        $p_id = $request->get('p_id');
        $is_checked = $request->get('is_checked');
        
        if($is_checked == 1){
            Role::find($r_id)->attachPermission($p_id);
        }
        else{
            Role::find($r_id)->detachPermission($p_id);
        }
        
        return apiResponse("success","Role-permission updated successfully");
    }

    public function permissionGroups(){
        $permission_groups = collect(PermissionGroup::all())->all();
        
        $data = [
            'permission_groups' => $permission_groups,
        ];
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('admin.pg')->with($data);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createGroup()
    {
        $pg = (object)[
                'id' => "",
                'display_name' => "",
                'description' => "",
                'is_menu_section' => "",
                'menu_description' => "",
            ];
        
        $data = [
            'pg' => $pg,
        ];
        return view('admin.pgadd')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeGroup(Request $request)
    {
        $rules = [
            'display_name' => 'required|unique:permission_group,display_name',
        ];

        $validationErrorMessages = [
            'display_name.required' => 'Permission Group Name field is required.',
            'display_name.unique' => 'Permission Group Name has already been taken.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('role.pg.add.form'))->with('errors', $validator->messages())->withInput();
        }
        
        $pg = new PermissionGroup;
        $pg->display_name = $request->display_name;
        $pg->description = $request->description;
        $pg->is_menu_section = $request->is_menu_section;
        $pg->menu_description = $request->menu_description;
        $pg->icon_class = $request->icon_class;
        $pg->save();
        if ($request->is('api/*')) {
            return apiResponse("success","Permission group added successfully.");
        }
        return redirect(route('role.pg.list'))->with('success', "Permission group added successfully.");
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
    public function editGroup($id)
    {
        $pg = PermissionGroup::where('id', $id)->first();
        
        if(!empty($pg))
        {
            $data = [
                'pg' => $pg,
                'pg_id' => $id,
            ];
            return view('admin.pgadd')->with($data);
        }
        else{
            return redirect(route('role.pg.list'))->with("error","No data found");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateGroup(Request $request, $id)
    {
        $rules = [
            'display_name' => "required|unique:permission_group,display_name,{$id},id",
        ];

        $validationErrorMessages = [
            'display_name.required' => 'Permission Group Name field is required.',
            'display_name.unique' => 'Permission Group Name has already been taken.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('role.pg.edit.form',$id))->with('errors', $validator->messages())->withInput();
        }
        
        $pg = PermissionGroup::where('id', $id)->first();
        $pg->display_name = $request->display_name;
        $pg->description = $request->description;
        $pg->is_menu_section = $request->is_menu_section;
        $pg->menu_description = $request->menu_description;
        $pg->icon_class = $request->icon_class;
        $pg->save();
        if ($request->is('api/*')) {
            return apiResponse("success","Permission updated successfully.");
        }
        return redirect(route('role.pg.list'))->with('success', "Permission updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pr_group =  PermissionGroup::where('id',$id)->first();

        if(!empty($pr_group)){
            
            $pr_group->delete();
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Permission deleted successfully.");
            }
            return redirect(route('role.pg.list'))->with('success', "Permission deleted successfully.");
        }
        else{
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error","Failed to delete Permission.");
            }
            return redirect(route('role.pg.list'))->with('success', "Permission deleted successfully.");
        }
    }
}
