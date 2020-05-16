<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;

class Timezone extends Model
{
    /**
     * Function getGmtOffset
     * @param string $zone_name time zone name
     * @param string $datetime date time string
     * **/
    public function getGmtOffset($zone_name, $datetime) 
    {
        if(!empty($zone_name) && !empty($datetime))
        {
            $timestamp= strtotime($datetime);
            $unix_timestamp = gmdate("Y-m-d\TH:i:s\Z", $timestamp);

            $zone_data = DB::table('tz_timezone AS tz')
                    ->join('tz_zone AS z','z.zone_id','=','tz.zone_id')
                    ->select('z.country_code',
                                 'z.zone_name', 
                                 'z.zone_id', 
                                 'tz.abbreviation', 
                                 'tz.gmt_offset', 
                                 'tz.dst')
                    ->where('tz.time_start', '<', DB::raw("UNIX_TIMESTAMP('{$unix_timestamp}')"))
                    ->where('z.zone_name',"{$zone_name}")
                    ->latest('tz.time_start')->first();

            return $zone_data;
        }
        else
            return false;
    }

    /**
     * Function convertTZDateTime
     * @param string $datetime string of date time
     * @param string $source_timezone time zone name like "Asia/Kolkata"
     * @param string $dest_timezone time zone name like "America/Los_Angeles"
     * @param string $date_format option string of date format
     * **/
    public function convertTZDateTime($datetime,$source_timezone,$dest_timezone,$date_format = "m/d/Y H:i:s")
    {
        if(!empty($datetime) && !empty($source_timezone) && !empty($dest_timezone))
        {
            $timestamp= strtotime($datetime);

            /** Source timezone data **/
            $source_data = $this->getGmtOffset($source_timezone,$datetime);
            if(empty($source_data)) return false;
            
            $source_offset = $source_data->gmt_offset;
            $source_abbreviation = $source_data->abbreviation;

            /** Destination timezone data **/
            $dest_data = $this->getGmtOffset($dest_timezone,$datetime);
            if(empty($dest_data)) return false;
            
            $dest_offset = $dest_data->gmt_offset;
            $dest_abbreviation = $dest_data->abbreviation;
            $dest_timezone_id = $dest_data->zone_id;

            /** Difference of both timezone offset **/
            $diff_offset = $dest_offset - $source_offset;

            /** Converted datetime of destination timezone **/
            $dest_converted_date = date($date_format, $timestamp + $diff_offset);

            $convertion_data = [
                'source_datetime' => $datetime,
                'source_offset' => $source_offset,
                'source_abbreviation' => $source_abbreviation,
                'source_timezone' => $source_timezone,
                'dest_datetime' => $dest_converted_date,
                'dest_offset' => $dest_offset,
                'dest_abbreviation' => $dest_abbreviation,
                'dest_timezone' => $dest_timezone,
                'dest_timezone_id' => $dest_timezone_id,
            ];

            return (object) $convertion_data;
        }
        else
        {
            return false;
        }
    }

    /**
     * Function getZoneNameById
     * @param int $zone_id
     * @desc return zone name by zone id
     * **/
    public static function getZoneNameById($zone_id)
    {
        if(!empty($zone_id) && is_numeric($zone_id))
        {
            $zone_data = DB::table('tz_zone')->select('zone_name')->where('zone_id',$zone_id)->first();
            return (!empty($zone_data)) ? $zone_data->zone_name : false;
        }
        else
            return false;
    }
    
    /**
     * Function getZoneIdByName
     * @param string $zone_name
     * @desc return zone id by zone name
     * **/
    public static function getZoneIdByName($zone_name)
    {
        if(!empty($zone_name))
        {
            $zone_data = DB::table('tz_zone')->select('zone_id')->where('zone_name','like',$zone_name)->first();
            return (!empty($zone_data)) ? $zone_data->zone_id : false;
        }
        else
            return false;
    }

    /**
     * Function getTimezones
     * @param string $country_code
     * @param string $return_data use for return particular column name data
     * **/
    public static function getTimezones($country_code = "",$return_data = "")
    {
        $timestamp = time();
        $unix = gmdate("Y-m-d\TH:i:s\Z", $timestamp);
        
        $query = DB::table('tz_timezone AS tz')
                    ->leftJoin('tz_abbreviation_name AS an', function ($join) {
                        $join->on('an.offset', '=', 'tz.gmt_offset')
                            ->whereColumn('an.abbreviation', 'tz.abbreviation');
                    })
                    ->leftJoin('tz_zone AS z', 'z.zone_id', '=', 'tz.zone_id')
                    ->select('tz.zone_id',
                        'an.abbreviation',
                        'an.abbreviation_name',
                        'an.offset_utc',
                        'z.zone_name',
                        'z.country_code',
                        DB::raw("CONCAT(an.abbreviation_name,' (',an.abbreviation,') [',z.zone_name,'] ',an.offset_utc) AS zone_label"))
                    ->where('z.is_active', 1)
                    ->where('tz.is_valid_zone', 1)
                    ->where('tz.time_start', '<', DB::raw("UNIX_TIMESTAMP('{$unix}')"))
                    ->whereNotNull('an.abbreviation');
        
        if(!empty($country_code) && is_string($country_code))
        {
            $query->where('z.country_code','like',$country_code);
        }
        
        $zones = $query->groupBy('tz.zone_id')->orderBy('an.offset', 'asc')->get()->all();
        
        if(!empty($return_data) && !empty($zones))
        {
            $return_column = "";
            switch($return_data)
            {
                case "abbreviation":
                    $return_column = "abbreviation";
                    break;

                case "abbreviation_name":
                    $return_column = "abbreviation_name";
                    break;

                default:
                    break;
            }

            if(!empty($return_column))
            {
                $temp_zones = array();
                foreach($zones as $zone)
                {
                    $temp_zones[$zone->zone_id] = $zone->$return_column;
                }
                $zones = (object) $temp_zones;
            }
        }

        return $zones;
    }
    
    /**
     * Function getAbbreviation
     * @param string $country_code
     * **/
    public function getAbbreviation($country_code = "")
    {
        return $this->getTimezones($country_code,"abbreviation");
    }
    
    /**
     * Function getFullZoneLabel
     * @param int $zone_id
     * @desc returns full zone label of time zone
     * **/
    public function getFullZoneLabel($zone_id)
    {
        if(!empty($zone_id) && is_numeric($zone_id))
        {
            $timestamp = time();
            $unix = gmdate("Y-m-d\TH:i:s\Z", $timestamp);
            
            $zones = DB::table('tz_timezone AS tz')
                        ->leftJoin('tz_abbreviation_name AS an',function($join){
                            $join->on('an.offset','tz.gmt_offset')
                                    ->whereColumn('an.abbreviation', 'tz.abbreviation');
                        })
                        ->leftJoin('tz_zone AS z','z.zone_id','=','tz.zone_id')
                        ->select('tz.zone_id',
                            'z.zone_name',
                            'an.abbreviation',
                            DB::raw("CONCAT(an.abbreviation_name,' (',an.abbreviation,') [',z.zone_name,'] ',an.offset_utc) AS zone_label"))
                        ->where('tz.zone_id', $zone_id)
                        ->where('tz.is_valid_zone', 1)
                        ->where('tz.time_start', '<', DB::raw("UNIX_TIMESTAMP('{$unix}')"))
                        ->whereNotNull('an.abbreviation')
                        ->groupBy('tz.zone_id')->first();
            
            return (!empty($zones)) ? $zones : false;
        }
        else
            return false;
    }
    
    /**
     * Function getCountryByTimezone
     * @desc returns list of all countries which timezone is activated
     * **/
    public function getCountryByTimezone()
    {
        $countries = DB::table('tz_country AS tc')
                        ->leftJoin('tz_zone AS tz', 'tz.country_code', '=', 'tc.country_code')
                        ->select('tc.country_name','tc.country_code')
                        ->where('tz.is_active', 1)
                        ->groupBy('tc.country_code')->get()->all();
        
        return (!empty($countries)) ? $countries : false;
    }
    
    /**
     * Function getTimezoneOffsetList
     * @desc returns list of offsets of abbreviation
     * **/
    public function getTimezoneOffsetList()
    {
        $offset = DB::table('tz_abbreviation_name')
                ->select('offset_utc','offset')
                ->groupBy('offset')
                ->orderBy('offset', 'ASC')->get()->all();
        
        return (!empty($offset)) ? $offset : false;
    }
    
    /**
     * Function getTimezoneList
     * @desc returns list of all zones in ascending order of zone name.
     * **/
    public static function getTimezoneList()
    {
        $zones = DB::table('tz_zone')
                    ->select('zone_id','zone_name','country_code')
                    ->orderBy('zone_name','ASC')->get()->all();
        
        return (!empty($zones)) ? $zones : false;
    }
    
    /** 
     * Detect the timezone id(s) from an offset and dst
     * @param   int     $offset
     * @param   int     $dst
     * @param   bool    $multiple
     * @param   string  $default
     * @return  string|array
     */
    public static function detectTimezoneId($offset, $dst, $country_code = "", $multiple = FALSE, $default = 'UTC')
    {
        $detected_timezone_ids = array();

        $timezones = self::getTimezones();
        
        // Try to find a timezone for which both the offset and dst match
        foreach ($timezones as $timezone)
        {
            $timezone_data = self::getTimezoneData($timezone->zone_name);
            $timezone->country_code = (!empty($country_code))?$timezone->country_code:"";
            
            if ($offset == $timezone_data['offset'] && $dst == $timezone_data['dst'] && $country_code == $timezone->country_code)
            {
                array_push($detected_timezone_ids, $timezone);
                if ( ! $multiple)
                    break;
            }
        }

        if (empty($detected_timezone_ids))
        {
            $detected_timezone_ids = array($default);
        }

        return $multiple ? $detected_timezone_ids : $detected_timezone_ids[0];
    }
    
    /**
     * Get the current offset and dst for the given timezone name
     *
     * @param   string  $timezone_name
     * @return  mixed
     */
    public static function getTimezoneData($timezone_name)
    {
        $date = new DateTime("now");
        $date->setTimezone(timezone_open($timezone_name));

        return [
                'offset' => $date->getOffset() / 3600,
                'dst' => intval(date_format($date, "I"))
            ];
    }
    
    /**
     * Function getZoneNameByAbbreviation
     * @param int $abb
     * @desc return zone name by time zone abbreviation
     * **/
    public static function getZoneNameByAbb($abb)
    {
        if(!empty($abb))
        {
            $zone_data = DB::table('tz_timezone as tz')
                    ->select('z.zone_name')
                    ->leftJoin('tz_zone AS z','z.zone_id','=','tz.zone_id')
                    ->where('tz.abbreviation',$abb)
                    ->first();
            return (!empty($zone_data)) ? $zone_data->zone_name : false;
        }
        else
            return false;
    }
    
}
