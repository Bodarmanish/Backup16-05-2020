<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\SystemSettings;
use App\Models\Geo;
use App\Models\Timezone; 

class AppServiceProvider extends ServiceProvider
{   
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        View::share('css_path', config('themesetting.css_path'));
        View::share('js_path', config('themesetting.js_path')); 
        View::share('plugin_path', config('themesetting.plugin_path'));
        View::share('image_path', config('themesetting.image_path'));
        View::share('countdown_message', "One of our Registration coordinator will contact you shortly.");
        View::share('countries', Geo::getCountries());
        View::share('airports', Geo::getAirportList());
        View::share('timezones', Timezone::getTimezones());
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    { 
        $default_abbreviation = config('app.timezone');
        $default_timezone_name = Timezone::getZoneNameByAbb($default_abbreviation); 
        $default_timezone = Timezone::getZoneIdByName($default_timezone_name);
        $system_settings = $this->initSystemSettings();
        $configArray = [
            'app.name' => $system_settings['app_name'],
            'app.url'  =>  $system_settings['app_url'],
            'admin.url'  =>  $system_settings['admin_url'],
            'agency.url'  =>  $system_settings['agency_url'],
            'common.contact_email' => $system_settings['contact_email'], 
            'services.google.client_id'   => $system_settings['google_client_id'],
            'services.google.client_secret'   => $system_settings['google_client_secret'],
            'services.facebook.client_id' => $system_settings['facebook_client_id'],
            'services.facebook.client_secret' => $system_settings['facebook_client_secret'],
            'services.twitter.client_id' => $system_settings['twitter_client_id'],
            'services.twitter.client_secret' => $system_settings['twitter_client_secret'],
            'captcha.sitekey' => $system_settings['google_captcha_site_key'],
            'captcha.secret' => $system_settings['google_captcha_site_secret'],
            'common.upload_file_size' => $system_settings['upload_file_size'],
            'common.upload_img_size' => $system_settings['upload_img_size'],
            'common.default_timezone_name' => $default_timezone_name,
            'common.default_timezone' => $default_timezone,
        ];
        
        $configAry = collect($configArray)->all();
        
        if(!empty($configAry)) config($configAry);
    }
    
    public function initSystemSettings()
    {
        $system_settings = new SystemSettings;
        $system_data     = $system_settings->getSystemSettings();
        $social_setting  = json_decode($system_data->social_setting);
        
        $system_setting = [
            'app_name'                   => $system_data->app_name,
            'app_url'                    => $system_data->app_url,
            'admin_url'                  => $system_data->admin_url,
            'agency_url'                 => $system_data->agency_url,
            'contact_email'              => $system_data->contact_email,
            'google_client_id'           => $social_setting->google_client_id,
            'google_client_secret'       => $social_setting->google_client_secret,
            'facebook_client_id'         => $social_setting->facebook_client_id, 
            'facebook_client_secret'     => $social_setting->facebook_client_secret,
            'twitter_client_id'          => $social_setting->twitter_client_id,
            'twitter_client_secret'      => $social_setting->twitter_client_secret,
            'google_captcha_site_key'    => $social_setting->google_captcha_site_key,
            'google_captcha_site_secret' => $social_setting->google_captcha_site_secret,
            'upload_file_size'           => $system_data->upload_file_size,
            'upload_img_size'            => $system_data->upload_img_size,
        ]; 
        return $system_setting;
    }
}
