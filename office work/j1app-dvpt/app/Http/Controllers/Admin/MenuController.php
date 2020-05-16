<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
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
    public function index(){
        $menu = new Menu();
        $menu_items = $menu->getAllMenu();
        
        $data = [
            'menu_items' => $menu_items,
        ];
        if(starts_with(request()->path(), 'api')){
            return apiResponse("success","",$data);
        }
        return view('admin.menuitems')->with($data);
    }
    
    public function createMenuItems(){
        
        $menu_sections = collect(PermissionGroup::where('is_menu_section',1)->orderBy('menu_order','ASC')->get())->all();
        
        $menu_item = (object) [
            'id'=>"",
            'title'=>"",
            'permission_group_id' => "",
            'route_name'=>"",
        ];
        
        $data = [
            'menu_item' => $menu_item,
            'menu_sections' => $menu_sections,
        ];
        
        return view('admin.menuadd')->with($data);
    }
    
    public function loadRoutes(Request $request){
        
        $permission_group_id = $request->get('permission_group_id',"");
        
        if(!empty($permission_group_id))
        {
            $where = [
                'permission_group_id' => $permission_group_id,
                'is_menu_item' => 1,
            ];
            $permissions = collect(Permission::where($where)->get())->all();

            $data = [
                'action' => "loadRouteDropdown",
                'permissions' => $permissions,
            ];

            $HTML = view('admin.ajax')->with($data)->render();
            return apiResponse("success","",$HTML);
        }
        else{
            return apiResponse("error","Data not found");
        }
    }
    
    public function storeMenuItems(Request $request){
        $rules = [
            'title' => 'required',
            'permission_group_id' => 'required',
            'permission_id' => 'required',
        ];

        $validationErrorMessages = [
            'title.required' => 'Menu Title field is required.',
            'permission_group_id.required' => 'Menu Section field is required.',
            'permission_id.required' => 'Route Name field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('menu.add.form'))->with('errors', $validator->messages())->withInput();
        }
        
        $route_name = "";
        if(!empty($request->permission_id)){
            $route_name = Permission::where('id',$request->permission_id)->first()->route_name;
        }
        
        $menu = new Menu;
        $menu->title = $request->get("title","");
        $menu->permission_id = $request->get("permission_id","");
        $menu->permission_group_id = $request->get("permission_group_id","");
        $menu->route_name = $route_name;
        $menu->save();
        if ($request->is('api/*')) {
            return apiResponse("success","Menu item added successfully.");
        }
        return redirect(route('menu.list'))->with('success', "Menu item added successfully.");
    }
    
    public function menuOrder(){
        
        $menu_sections = collect(PermissionGroup::with('menus')
                        ->where('is_menu_section',1)
                        ->orderBy('menu_order','ASC')
                        ->get())
                        ->all();
        
        $data = [
            'menu_sections' => $menu_sections,
        ];
        
        return view('admin.menuorder')->with($data);
    }
    
    public function updateMenuOrder(Request $request){
        
        $action = $request->get('action',"");
        if($action == "section_ordering"){
            $menu_section_order = $request->get('menu_section_order',"");
            if(!empty($menu_section_order)){
                foreach($menu_section_order as $id => $order){
                    $pg = PermissionGroup::where('id',$id)->first();
                    $pg->menu_order = $order;
                    $pg->save();
                }
                return apiResponse("success","Menu section order updated successfully.");
            }
            else{
                return apiResponse("error","Failed to update order.");
            }
        }
        else if($action == "item_ordering"){
            $menu_item_order = $request->get('menu_item_order',"");
            if(!empty($menu_item_order)){
                foreach($menu_item_order as $id => $order){
                    $menu = Menu::where('id',$id)->first();
                    $menu->menu_item_order = $order;
                    $menu->save();
                }

                return apiResponse("success","Menu item order updated successfully.");
            }
            else{
                return apiResponse("error","Failed to update order.");
            }
        }
        else{
            return apiResponse("error","Failed to update order.");
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editMenuItems($id){
        $menu_sections = collect(PermissionGroup::where('is_menu_section',1)->orderBy('menu_order','ASC')->get())->all();
        $menu_item = Menu::where('id', $id)->first();
        
        if(!empty($menu_item))
        {
            $data = [
                'menu_item' => $menu_item,
                'menu_sections' => $menu_sections,
                'id' => $id,
            ];
            return view('admin.menuadd')->with($data);
        }
        else{
            return redirect(route('menu.list'))->with("error","No data found");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMenuItems(Request $request, $id){
        $rules = [
            'title' => 'required',
            'permission_group_id' => 'required',
            'permission_id' => 'required',
        ];

        $validationErrorMessages = [
            'title.required' => 'Menu Title field is required.',
            'permission_group_id.required' => 'Menu Section field is required.',
            'permission_id.required' => 'Route Name field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $validationErrorMessages);

        if ($validator->fails()) {
            if ($request->is('api/*')){
                return apiResponse("error", "", $validator->messages()->toArray());
            }
            return redirect(route('menu.edit.form',$id))->with('errors', $validator->messages())->withInput();
        }
        
        $route_name = "";
        if(!empty($request->permission_id)){
            $route_name = Permission::where('id',$request->permission_id)->first()->route_name;
        }

        
        $menu = Menu::where('id',$id)->first();
        $menu->title = $request->get("title","");
        $menu->permission_id = $request->get("permission_id","");
        $menu->permission_group_id = $request->get("permission_group_id","");
        $menu->route_name = $route_name;
        $menu->save();
        if ($request->is('api/*')) {
            return apiResponse("success","Menu item updated successfully.");
        }
        return redirect(route('menu.list'))->with('success', "Menu item updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyMenu($id){
        if(Menu::deleteById($id)){
            if(starts_with(request()->path(), 'api')){
                return apiResponse("success","Menu deleted successfully.");
            }
            return redirect(route('menu.list'))->with("success","Menu deleted successfully.");
        }
        else{
            if(starts_with(request()->path(), 'api')){
                return apiResponse("error","Failed to delete menu.");
            }
            return redirect(route('menu.list'))->with("error","Failed to delete menu.");
        }
    }
}
