<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $table = "permission_group";
    
    public function menus(){
        return $this->hasMany('App\Models\Menu','permission_group_id')->orderBy('menu_item_order');
    }
}
