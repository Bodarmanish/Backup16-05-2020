<?php

namespace App\Traits;

use App\Models\User;
use DB;
use J1AppLib\J1App;

trait J1AppApiTrait
{
    public function getJ1AppApiInst(){
        
        $apiUrl = config('j1appapi.apiUrl');
        $apiKey = config('j1appapi.apiKey');
        $apiUser = config('j1appapi.apiUser');
        $DeviceToken = config('j1appapi.DeviceToken');
        $enableLog = config('j1appapi.enableLog');
        
        $j1appObj = new J1App($apiKey, $apiUser, $enableLog);
        $j1appObj->setApiUri($apiUrl);
        
        return $j1appObj->setDeviceToken($DeviceToken);
    }
}