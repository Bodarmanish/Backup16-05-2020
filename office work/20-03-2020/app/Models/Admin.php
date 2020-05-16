<?php


namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\PasswordReset;
use Auth;
use DB;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
 { 
    use Notifiable ,HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password','theme_color',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }
    
    /**
     * Get admin profile info
     * @param string $_ [optional] variable list of column names to return with query
     * @return array
     */
    public function getAdminProfileInfo($_ = null)
    {
        $admin = Auth::user();

        $columns = array();
        $arg_num = func_num_args();
        if($arg_num > 1)
        {
            $args = func_get_args();
            unset($args[0]);
            foreach ($args as $arg)
            {
                $columns[] = $arg;
            }
        }

        if(empty($columns)){
            $columns = ['a.id','a.first_name','a.last_name','a.email','a.created_at','a.updated_at','a.profile_photo','a.created_at','a.updated_at'];
        }

        $result = DB::table('admins as a')
                    ->select($columns) 
                    ->where('a.id',$admin->id)
                    ->first();
        return $result;
    }
    
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
    
    public function agency()
    {
        return $this->belongsTo('App\Models\Agency','agency_id');
    }
    
    /**
    * Get logged in agency admins list
    *
    * @return array
    */
    public function agencyAdmins()
    {
        return $this->hasMany('App\Models\Admin', 'agency_id');
    }
    
    public function adminDetails(){
        
        $role = $this->roles()->select('id','role_name','display_name')->first();
        if(!empty($role)){
            $this->role = $role;
            $this->role_name = $role->role_name;
            $permissions = collect($role->permissions()->get())->toArray();
            
            if($this->role_name == "agency-admin" && !empty($this->agency_id)){
                $agency = collect($this->agency()->get())->first();
                $this->agency_id = $agency->id;
                $this->agency_name = $agency->agency_name;
                $this->agency_type = $agency->agency_type;
                $this->agency_status = $agency->status;
            }
            
            $this->permissions = $permissions;
            $permission_ids = array_column($permissions, 'id');
            $this->permission_ids = $permission_ids;
            $route_names = array_column($permissions, 'route_name');
            $this->route_names = $route_names;
            $permission_id_routes = array_combine($permission_ids, $route_names);
            $this->permission_id_routes = $permission_id_routes;
        }
        else{
            $this->role = "";
        }
        return $this;
    }
    
    public function getAllPermissions(){
        $role = $this->roles()->first();
        $permissions = collect($role->permissions()->get())->all();
        return $permissions;
    }
    
    public function attachRole($roleId){
        $this->roles()->attach($roleId);
        return $this;
    }
    
    public function detachRole($roleId){
        $this->roles()->detach($roleId);
        return $this;
    }
    
    public static function deleteByAdminId($id){
        return DB::table('admins')->where('id', $id)->delete();
    }
    
    /**
    * admins table filter
    *
    * @return array
    */
    public function scopeFilter($query, $params)
    {    
        $query->select('admins.*');
        if ( isset($params['admin_name']) && trim($params['admin_name'] !== '') ){
            $query->where('first_name', 'LIKE', '%'.trim($params['admin_name']).'%');
            $query->orWhere('last_name', 'LIKE', '%'.trim($params['admin_name']).'%');
        }
        if ( isset($params['email_address']) && trim($params['email_address'] !== '') ){
            $query->where('email', 'LIKE', '%'.trim($params['email_address']).'%');
        } 
        if ( isset($params['agency_id']) && $params['agency_id'] !== ''){
            $query->where('agency_id', '=', $params['agency_id']);
        }
        return $query;
    }
}
