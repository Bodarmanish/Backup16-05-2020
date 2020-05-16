<?php

namespace App\Models;
 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PasswordReset;
use Laravel\Passport\HasApiTokens;
use DB;
use App\Models\AgencyContract;
use App\Models\UserLog;

class User extends Authenticatable
{
    use Notifiable, CanResetPassword, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'email_verified', 'j1_status_id', 'portfolio_id', 'timezone', 'profile_photo', 
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
     * Get user login info using social account.
     *
     * @return string
    */
    public static function isUserAuthorized($email,$user_id = null)
    { 
        $query = DB::table('socal_authorization')
                    ->select("id","google_email","facebook_email","twitter_email")
                    ->orWhere(function($query) use($email) {
                            $query->orWhere('google_email', $email)
                                ->orWhere('facebook_email', $email)
                                ->orWhere('twitter_email', $email);
                        });
        if(!empty($user_id)){
            $query->where("user_id","!=",$user_id);
        }        
        $result = $query->first();
                        
        return (!empty($result)) ? $result : false;
    }
    
    /**
     * Get user profile info
     * @param string $_ [optional] variable list of column names to return with query
     * @return array
     */
    public function getProfileInfo($_ = null)
    {
        $user = Auth::user();

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
            $columns = ['u.id','u.portfolio_id','u.first_name','u.last_name','u.email','u.created_at','u.updated_at','u.profile_photo','u.timezone','ud.phone_number','ud.secondary_email','ud.street','ud.city','ud.zip_code','ud.country','ud.skype_id','ud.facebook_url','ud.twitter_url'];
        }

        $result = DB::table('users as u')
                    ->select($columns)
                    ->leftJoin('user_details as ud','ud.portfolio_id', '=' , 'u.portfolio_id')
                    ->where('u.id',$user->id)
                    ->first();
        return $result;
    }
    
    /**
     * Get user completeness profile percentage
     *
     * @return array
     */
    public function countProfileCompletePercentage()
    {  
        $user = Auth::user();
        $portfolio_id = $user->portfolio_id;
        $profile_percentage = DB::table('profile_percentage')->get();
        $add_per = (100/(count($profile_percentage)));
        $percentage = 0; 
        $incomplete_warning_info = '<ul class="list-unstyled font-13 incomplete_warning">';
        if (!empty($profile_percentage)){ 
            foreach($profile_percentage as $profile){
                $location = json_decode($profile->location);
                $table_name = $location->table_name;
                $column_name = $location->field_name; 

                $result = DB::table($table_name)->select($column_name)->where('portfolio_id',$portfolio_id)->first(); 
                $result = (array) $result;
                if(!empty($result)){
                    if(!empty(array_filter($result))){
                        $percentage += $add_per; 
                    }else{
                        $incomplete_warning_info .= '<li class="text-info">'.$profile->warning_info.'</li>';  
                    }
                }else{
                    $incomplete_warning_info .= '<li class="text-info">'.$profile->warning_info.'</li>'; 
                } 
            }
        }
        $incomplete_warning_info .= '</ul">';

        return json_encode([
            'incomplete_warning_info' => $incomplete_warning_info,
            'percentage' => round($percentage),
        ]);
    }
    
    /**
     * Get social auth info
     *
     * @return array
     */
    public function getSocialAuthInfo()
    {       
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $query = DB::table('socal_authorization')
                    ->select("id","google_email","facebook_email","twitter_email","google_id","facebook_id","twitter_id")
                    ->where('user_id', $user_id)
                    ->where(function($query){
                            $query->orWhere('google_id', "!=", "")
                                ->orWhere('facebook_id', "!=", "")
                                ->orWhere('twitter_id', "!=", "");
                        });
            $result = $query->first();

            return (!empty($result)) ? $result : false;
        }else{
            return false;
        }
    }
    
    /**
     * Get social users
     *
     * @return array
     */
    public function getUsers($user_id = NULL){
        
        $query = DB::table('users')
               ->leftJoin('user_details', 'user_details.user_id','=','users.id')
               ->leftJoin('geo_country', 'geo_country.country_id','=','user_details.country')
               ->leftJoin('portfolio as pt', 'pt.id','=','users.portfolio_id')
               ->leftJoin('programs as pg', 'pg.id','=','pt.program_id')
               ->select('users.*','pt.program_id','user_details.portfolio_id','user_details.phone_number','user_details.secondary_email','user_details.street','user_details.city','user_details.zip_code','user_details.country','user_details.skype_id','user_details.facebook_url','user_details.twitter_url','geo_country.country_name','pg.program_name');    
               
        if(!empty($user_id)){
            $query->where("users.id","=",$user_id);
        }
        $users = $query->get();
      
        if(!empty($users))
        {
            return $users;   
        }
        else
        {
            return false;
        }
    }
    /**
     * Get user logs
     *
     * @return array
     */
    public function getLogs($user_id = NULL){
        
        $query = DB::table('user_log')
               ->leftJoin('users', 'users.portfolio_id','=','user_log.portfolio_id')
               ->leftJoin('j1_statuses', 'j1_statuses.id','=','user_log.action_status')
               ->leftJoin('placement', 'placement.portfolio_id','=','user_log.portfolio_id')
               ->leftJoin('host_companies', 'host_companies.id','=','placement.hc_id')
               ->leftJoin('host_company_positions', 'host_company_positions.id','=','placement.pos_id')
               ->select('user_log.*','users.first_name','users.last_name','j1_statuses.status_name','host_companies.hc_name','host_company_positions.pos_name','placement.start_date','placement.end_date');    
               
        if(!empty($user_id)){
            $query->where("user_log.user_id","=",$user_id);
        }
        $logs = $query->get();
      
        if(!empty($logs))
        {
            return $logs;   
        }
        else
        {
            return false;
        }
    }
    
    /**
    * Get current user portfolio list using user id
    *
    * @return array
    */
    public function portfolios()
    {
        return $this->hasMany('App\Models\Portfolio', 'user_id');
    }
    
    /**
    * Get current user portfolio details using portfolio id
    *
    * @return array
    */
    public function portfolio()
    {
        return $this->belongsTo('App\Models\Portfolio', 'portfolio_id');
    }
    
    public function j1Status(){
        return $this->belongsTo('App\Models\J1Status','j1_status_id');
    }
    
    public function resume(){
        return $this->hasOne('App\Models\Resume','user_id');
    }
    
    public function interview(){
        return $this->hasOne('App\Models\J1Interview','portfolio_id','portfolio_id');
    }
    
    /**
    * Users table filter
    *
    * @return array
    */
    public function scopeFilter($query, $params)
    {    
        $query->select('users.*','ud.portfolio_id','ud.phone_number','ud.secondary_email','ud.street','ud.city','ud.zip_code','ud.country','ud.skype_id','ud.facebook_url','ud.twitter_url','gc.country_name','pg.program_name','pt.portfolio_number'); 
        $query->leftJoin('user_details as ud', 'ud.portfolio_id','=','users.portfolio_id');
        $query->leftJoin('geo_country as gc', 'gc.country_id','=','ud.country');
        $query->leftJoin('portfolio as pt', 'pt.id','=','users.portfolio_id');
        $query->leftJoin('programs as pg', 'pg.id','=','pt.program_id');
        if ( isset($params['first_name']) && trim($params['first_name'] !== '') ){
            $query->where('first_name', 'LIKE', '%'.trim($params['first_name']).'%');
        }
        if ( isset($params['last_name']) && trim($params['last_name']) !== '' ){
            $query->where('last_name', 'LIKE', '%'.trim($params['last_name']).'%');
        }
        if ( isset($params['email']) && trim($params['email']) !== '' ){
            $query->where('email', 'LIKE', '%'.trim($params['email']).'%');
        }
        if ( isset($params['program']) && trim($params['program']) !== '' ){
            $query->where("program_id","=",$params['program']);
        }
        if ( isset($params['agency_id']) && trim($params['agency_id']) !== '' && trim($params['agency_type']) !== '' && isset($params['agency_type'])){
            $query->join('portfolio as p', function($join) use($params)
            { 
                $join->on('p.id', '=', 'users.portfolio_id')
                    ->where(function ($query) use($params) {
                        if($params['agency_type'] == 1){
                            $query->where([
                                'p.registration_agency_id' => $params['agency_id'] 
                            ]);
                        }elseif($params['agency_type'] == 2){
                            $query->where([
                                'p.placement_agency_id' => $params['agency_id'] 
                            ]);
                        }elseif($params['agency_type'] == 3){
                            $query->where([
                                'p.sponsor_agency_id' => $params['agency_id'] 
                            ]);
                        }
                        /*$query->orWhere([
                            'p.registration_agency_id' => $params['agency_id'],
                            'p.placement_agency_id' => $params['agency_id'],
                            'p.sponsor_agency_id' => $params['agency_id']
                        ]);*/
                    }); 
            });
        }
        return $query;
    }
    
    public function topicFollow(){
        return $this->hasMany('App\Models\ForumTopicFollow','user_id');
    }
    
    public function getFollowCount(){
        return $this->topicFollow()->count();
    }
    
    public function userGeneral(){
        $portfolio = $this->portfolio;
        if(!empty($portfolio)){
            $userGeneral = false;
            if(!empty($portfolio->userGeneral)){
                $userGeneral = $portfolio->userGeneral;
            }
            else{
                $userGeneral = new UserGeneral;
                $userGeneral->user_id = $this->id;
                $userGeneral->portfolio_id = $this->portfolio_id;
                $userGeneral->save();
            }
            return $userGeneral;
        }
        return false;
    }
    
    public function j1Interview(){
        $portfolio = $this->portfolio;
        
        if(!empty($portfolio)){
            $j1_interview = false;
            if(!empty($portfolio->j1Interview)){
                $j1_interview = $portfolio->j1Interview;
            }
            else{
                $j1_interview = new J1Interview;
                $j1_interview->portfolio_id = $this->portfolio_id;
                $j1_interview->save();
            }
            return $j1_interview;
        }
        return false;
    }
    
    public function setAgency($contract_id = null){
        
        $portfolio = $this->portfolio;
        if(!empty($portfolio)){
            if(!empty($contract_id)){
                $agency_contract = AgencyContract::where(['id' => $contract_id])->first();
                if(!empty($agency_contract)){
                    $log_status = "";
                    if($agency_contract->contract_type == 1){
                        $portfolio->registration_agency_id = (!empty($portfolio->registration_agency_id)) ? $portfolio->registration_agency_id : $agency_contract->agency_id;
                    }
                    elseif($agency_contract->contract_type == 2){
                        $portfolio->registration_agency_id = (!empty($portfolio->registration_agency_id)) ? $portfolio->registration_agency_id : $agency_contract->agency_id;
                        $portfolio->placement_agency_id = (!empty($portfolio->placement_agency_id)) ? $portfolio->placement_agency_id : $agency_contract->agency_id;
                        $log_status = "";
                    }
                    elseif($agency_contract->contract_type == 3){
                        $portfolio->registration_agency_id = (!empty($portfolio->registration_agency_id)) ? $portfolio->registration_agency_id : $agency_contract->agency_id;
                        $portfolio->sponsor_agency_id = (!empty($portfolio->sponsor_agency_id)) ? $portfolio->sponsor_agency_id : $agency_contract->agency_id;
                    }
                    elseif($agency_contract->contract_type == 4){
                        $portfolio->registration_agency_id = (!empty($portfolio->registration_agency_id)) ? $portfolio->registration_agency_id : $agency_contract->agency_id;
                        $portfolio->placement_agency_id = (!empty($portfolio->placement_agency_id)) ? $portfolio->placement_agency_id : $agency_contract->agency_id;
                        $portfolio->sponsor_agency_id = (!empty($portfolio->sponsor_agency_id)) ? $portfolio->sponsor_agency_id : $agency_contract->agency_id;
                    }

                    if($portfolio->portfolio_status == 0){
                       $portfolio->portfolio_status = 1;
                    }

                    $portfolio->save();
                    
                    $agency_contract->portfolio_id = $portfolio->id;
                    $agency_contract->user_id = $this->id;
                    $agency_contract->request_status = 2;
                    $agency_contract->save();
                }
            }
        }
        else{
            $portfolio = new Portfolio;
            $portfolio->user_id = $this->id;
            $portfolio->portfolio_number = "PF".mt_rand(100000000, 999999999);
            $portfolio->save();

            $this->portfolio_id = $portfolio->id;
            $this->save();
        }
        
        return $this;
    }
    
    public function agencyContract(){
        return $this->hasOne('App\Models\AgencyContract','user_id');
    }
    
    
}