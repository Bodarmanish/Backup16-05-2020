<?php

namespace App\Traits;

use App\Models\Geo;

trait GeoTrait
{
    /**
    * Function getCountryList()
    * This function for get country list
    * **/
    function geoCountryList()
    {
        $geo = new Geo;
        return $geo->geoCountryList();
    }
    
    /**
    * Function geoStatesList()
    * This function for get state list
    * **/
    function geoStatesList() 
    {
        $geo = new Geo;
        return $geo->geoStatesList();
    }
}