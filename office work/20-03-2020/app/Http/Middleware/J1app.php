<?php

namespace App\Http\Middleware;
 
use Closure;
use Route;
use Auth;
use App\Models\Programs;
use App\Models\Timezone;
use App\Models\NotificationLog;

class J1app
{ 
    /**
     * Handle an incoming request.
     * Perform some task before the request is handled by the application
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $app_interface = config('common.app_interface');
        
        if($app_interface === "admin"){
            $this->initAdmin($request);
        }
        else if($app_interface === "user"){
            $this->initUser($request);
        }
        
        return $next($request);
    }
    
    public function initAdmin($request){
        
        $request->signed_in = Auth::check();
        if($request->signed_in)
        {
            $admin = Auth::user();
            $request->user = $admin;
            $request->user->admin_name = "{$request->user->first_name} {$request->user->last_name}";
            
            $profile_photo = $admin->profile_photo;
            $no_avatar = config('common.no_avatar');
            $profile_pic = "";
            
            if(!empty($profile_photo)){
                $profile_photo_path = "admin-avatar".DS.$admin->id.DS.$profile_photo;
                $profile_photo = get_url($profile_photo_path);
            }
            
            $profile_photo = (!empty($profile_photo))? $profile_photo : url($no_avatar);
            
            $theme_color = (!empty($request->user->theme_color)) ? $request->user->theme_color : "default";
            
            $menuitems = get_menuitems($request);
            
            view()->share('menuitems', $menuitems);
            view()->share('signed_in', $request->signed_in);
            view()->share('admin', $request->user);
            view()->share('admin_name', $request->user->admin_name);
            view()->share('admin_role', $request->user->role_name);
            view()->share('theme', $theme_color);
            view()->share('profile_photo', $profile_photo);
            
            /** Get Login Admin Timezone Data **/
            if(!empty($request->user->timezone)){
                $admin_timezone = $request->user->timezone; 
            }
            elseif(!empty(session('local_timezone'))){
                $admin_timezone = session('local_timezone');
            }
            else{
                $admin_timezone = config('common.default_timezone');
            }             
            
            /** get or set Cache admin time-zone data **/
            cache()->remember('admin_timezone_data', 60, function () use($admin_timezone) {
                $timezone = new Timezone;
                return $timezone->getFullZoneLabel($admin_timezone);
            });
            $admin_timezone_data = cache('admin_timezone_data');
            
            session([
                'admin_timezone' => $admin_timezone,
                'admin_timezone_data' => $admin_timezone_data,
            ]); 
        }
        
        /** get or set Cache programs **/
        cache()->remember('programs', 60, function () {
            return Programs::where('status','=',1)->get();
        });
        $programs = cache('programs');
        $request->programs = $programs;
        view()->share('programs', $programs);
        
        view()->share('self', Route::current()->getName());
        
        return $request;
    }
    
    public function initUser($request){
         
        $request->signed_in = Auth::check();
        if($request->signed_in)
        {
            $user = Auth::user();
            
            $portfolio = $user->portfolio;
            $userGeneral = $user->userGeneral();
            
            $request->user = $user;
            $request->portfolio = $portfolio;
            $request->userGeneral = $userGeneral;
            
            $profile_photo = $user->profile_photo;
            $no_avatar = config('common.no_avatar');
            $profile_pic = "";
            
            if(!empty($profile_photo)){
                $profile_photo_path = "user-avatar".DS.$user->id.DS."50".DS.$profile_photo;
                $profile_50x = get_url($profile_photo_path);
                $profile_photo_path = "user-avatar".DS.$user->id.DS."200".DS.$profile_photo;
                $profile_200x = get_url($profile_photo_path);
            }
            
            $profile_photo_50x = (!empty($profile_50x))? $profile_50x : url($no_avatar);
            $profile_photo_200x = (!empty($profile_200x))? $profile_200x : url($no_avatar);
            $notication_log = new NotificationLog;
            $request->user_notification_list = $notication_log->getUserNotificationList('unread',7);
            
            if(!empty($request->user_notification_list)){
                $request->total_user_notification_list = count($request->user_notification_list);
            } 
            view()->share('signed_in', $request->signed_in);
            view()->share('user', $user);
            view()->share('profile_photo_50x', $profile_photo_50x);
            view()->share('profile_photo_200x', $profile_photo_200x);
            view()->share('portfolio', $portfolio);
            view()->share('userGeneral', $userGeneral);
            view()->share('user_name', "{$user->first_name} {$user->last_name}");  
            
            /** Get Login User Timezone Data **/ 
            if(!empty($request->user->timezone)){
                $user_timezone = $request->user->timezone; 
            }
            elseif(!empty(session('local_timezone'))){
                $user_timezone = session('local_timezone');
            }
            else{
                $user_timezone = config('common.default_timezone');
            } 
            
            cache()->remember('user_timezone_data', 60, function () use($user_timezone) {
                $timezone = new Timezone;
                return $timezone->getFullZoneLabel($user_timezone);
            });
            $user_timezone_data = cache('user_timezone_data');
            
            session([
                'user_timezone' => $user_timezone,
                'user_timezone_data' => $user_timezone_data,
            ]);
        }
        
        view()->share('self', Route::current()->getName());
        
        return $request;
    }
}
