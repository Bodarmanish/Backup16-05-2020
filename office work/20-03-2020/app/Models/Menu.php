<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Menu extends Model
{
    protected $table = "menu_items";
    
    public function menuSections(){
        return $this->belongsTo('App\Models\PermissionGroup','permission_group_id');
    }
    
    public function getAllMenu(){
        
        $sql = "SELECT 
                    mi.id,
                    mi.permission_group_id,
                    mi.title,
                    mi.route_name,
                    pg.display_name AS menu_section
                FROM menu_items AS mi
                LEFT JOIN permission_group AS pg ON pg.id = mi.permission_group_id 
                ";
        
        $menu_items = DB::select($sql);
        
        if(!empty($menu_items)){
            return $menu_items;
        }
        else{
            return false;
        }
    }
    
    public static function deleteById($id){
        return Menu::where('id', $id)->delete();
    }
    
    public function filterMenuItems($user){
        
        $permission_ids = $user->permission_ids;
        
        $menu_sections = collect(PermissionGroup::with('menus')
                            ->where('is_menu_section',1)
                            ->orderBy('menu_order','ASC')
                        ->get())->toArray();

        $menu_section_temp = [];
        foreach($menu_sections as $key => $section){
            if(!empty($section['menus'])){

                if($user->role_name == 'root'){
                    $menu_section_temp[$key] = $section;
                }
                else{
                    if(!empty($permission_ids)){
                        $menu_item_temp = [];
                        $menus = $section['menus'];
                        foreach($menus as $menu){
                            if(in_array($menu['permission_id'],$permission_ids)){
                                $menu_item_temp[] = $menu;
                            }
                        }
                        if(!empty($menu_item_temp)){
                            $section['menus'] = $menu_item_temp;
                            $menu_section_temp[$key] = $section;
                        }
                    }
                }
            }
        }
        
        $filtered_menu_items = $menu_section_temp;
        
        if(!empty($filtered_menu_items)){
            return $filtered_menu_items;
        }
        else{
            return false;
        }
    }
}
