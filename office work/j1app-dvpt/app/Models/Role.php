<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    protected $table = "roles";
    
    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        
    }
    
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission','role_permission','role_id','permission_id');
    }
    
    public static function deleteByRoleName($role_name){
        return DB::table('roles')->where('role_name', $role_name)->delete();
    }
    
    public function attachPermission($permissionId){
        $this->permissions()->attach($permissionId);
        return $this;
    }
    
    public function detachPermission($permissionId){
        $this->permissions()->detach($permissionId);
        return $this;
    }
    
}
