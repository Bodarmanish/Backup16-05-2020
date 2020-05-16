<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Exceptions\UnauthorizedException;

class RolesAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            Auth::user()->adminDetails();
            $route_name = get_current_route('name');
            if(check_route_access($route_name)){
                return $next($request);
            }
            
            throw new UnauthorizedException("Unauthorized Access",$this->redirectTo($request));
        }
        
        // authorized request
        return $next($request);
    }
    
    /**
     * Get the path the user should be redirected to when they are unauthorized.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        return route('dashboard');
    }
}
