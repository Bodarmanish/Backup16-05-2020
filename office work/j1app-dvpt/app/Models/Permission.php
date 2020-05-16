<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Role;

class Permission extends Model
{
    protected $table = "permissions";
    
    public function getAllPermissions($request = null){
        
        $params = [];
        $where = "";
        
        
        if(!empty($request)){
            $permission_group = $request->get('permission_group',"");
            if(!empty($permission_group)){
                $params[] = $permission_group;
                $where .= " AND p.permission_group_id = ? ";
            }
        }
        
        $sql = "SELECT 
                    p.id,
                    p.permission_group_id,
                    p.permission_name,
                    p.display_name AS permission_label,
                    p.description AS description,
                    p.route_name,
                    pg.display_name AS group_label,
                    GROUP_CONCAT(roles.id) AS roles
                FROM permissions AS p
                LEFT JOIN permission_group AS pg ON pg.id = p.permission_group_id
                LEFT JOIN role_permission AS rp ON rp.permission_id = p.id
                LEFT JOIN roles ON roles.id = rp.role_id
                WHERE 1 = 1 {$where}
                GROUP BY p.id
                ";
        
        $permissions = DB::select($sql, $params);

        if(!empty($permissions)){
            foreach($permissions as $key => $permission){
                if(!empty($permission->roles)){
                    
                    $role_ids = custom_explode($permission->roles);
                    $roles = collect(Role::whereIn('id',$role_ids)->get())->toArray();
                    $temp = (object) [
                        'role_ids' => $role_ids,
                        'role_data' => $roles
                    ];
                    
                    $permissions[$key]->roles = $temp;
                }
            }

            return $permissions;
        }
        else{
            return false;
        }
    }
    
    public function roles(){
        return $this->belongsToMany('App\Models\Role','role_permission','role_id','permission_id');
    }
    
    public function getGroupName(){
        
    }
}
