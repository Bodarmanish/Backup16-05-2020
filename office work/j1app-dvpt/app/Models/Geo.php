<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Geo extends Model
{
    /**
    * Function getCountries()
    * This function for get country list
     */
    public static function getCountries()
    {
        $country_query = DB::table("geo_country")
                        ->select('country_id','country_name')
                        ->orderby('country_name','ASC');
        
        $arg_num = func_num_args();
        if($arg_num > 0)
        {
            if($arg_num == 1)
            {
                $arg = func_get_arg(0);
                $arg = (is_array($arg) && count($arg) == 1) ? $arg[0] : $arg;
                 
                if(is_array($arg) && count($arg) > 1)
                    $country_list = $country_query->whereIn('country_id',$arg)->get();
                else
                    $country_list = $country_query->where('country_id',$arg)->first();
            }
            else
            {
                $arg = func_get_args();
                $country_list = $country_query->whereIn('country_id',$arg)->get();
            }
        }
        else
        {
            $country_list = $country_query->get();
        }
            
        return collect($country_list)->all();
    }
    
    /**
    * Function getStatesList()
    * This function for get state list
     */
    public static function getStates() 
    {
        $state_query = DB::table('geo_states')
                ->select('state_id','state_abbr','state_name')
                ->orderby('state_name','ASC');

        $arg_num = func_num_args();
        if($arg_num > 0)
        {
            if($arg_num == 1)
            {
                $arg = func_get_arg(0);
                $arg = (is_array($arg) && count($arg) == 1) ? $arg[0] : $arg;
                
                if(count($arg) > 1)
                    $state_list = $state_query->whereIn('state_id',$arg)->get();
                else
                    $state_list = $state_query->where('state_id',$arg)->first();
            }
            else
            {
                $arg = func_get_args();
                $state_list = $state_query->whereIn('state_id',$arg)->get();
            }
        }
        else
        {
            $state_list = $state_query->get();
        }
        
        return collect($state_list)->all();
    }
    
    /**
    * Function getAirportList()
    * This function for get airport list
     */
    public static function getAirportList()
    {
        $state_query = DB::table('geo_airports')
                ->select('*',DB::raw("CONCAT(state,', ',city,' - ',ap_name,' (',ap_abbr,')') AS airport_label"))
                ->orderby('city','ASC');

        $arg_num = func_num_args();
        if($arg_num > 0)
        {
            if($arg_num == 1)
            {
                $arg = func_get_arg(0);
                $arg = (is_array($arg) && count($arg) == 1) ? $arg[0] : $arg;
                
                if(count($arg) > 1)
                    $state_list = $state_query->whereIn('ap_id',$arg)->get();
                else
                    $state_list = $state_query->where('ap_id',$arg)->first();
            }
            else
            {
                $arg = func_get_args();
                $state_list = $state_query->whereIn('ap_id',$arg)->get();
            }
        }
        else
        {
            $state_list = $state_query->get();
        }
        
        return collect($state_list)->all();
    }
}
