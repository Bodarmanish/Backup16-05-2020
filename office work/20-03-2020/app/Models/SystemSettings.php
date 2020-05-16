<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use DB;

class SystemSettings extends Model
{
    protected $table = "system_settings";
    public $primaryKey = "id";
    public $timestamps = false;
    public $inst;
    
    public static function getSystemSettings(){
        
        $data = DB::table('system_settings')->select('*')->get()->all();
        if(!empty($data)){
            $result = new \stdClass();
            foreach($data as $key=>$val){
                $ref = $val->field;
                $result->$ref = $val->value;
            }
            return $result;
        }else
            return false;
    }
    
    public static function getSystemSettingByKey($key){
        
        if(!empty($key))
        { 
            $data = SystemSettings::where('field',$key)
                    ->select('*')->first();
            
            return (!empty($data)) ? $data : false;
        }
        else
            return false;
        
    }
    
    /**
     * get password setting instruction
     *
     * @return array
     */
    public function getJ1PasswordInstruction()
    {  
        $result = self::getSystemSettingByKey('password_setting');
        
        if(!empty($result->value))
        {
            $password_setting = json_decode($result->value); 
            $password_setting_instruction = "";
            if(isset($password_setting->min_limit)){
                if($password_setting->min_limit > 0)
                { 
                    $password_setting_instruction .= "<li>- Min length ".$password_setting->min_limit." characters</li>";
                }
            }
            if(isset($password_setting->password_pattern)){
                if(in_array("one_alphabet",$password_setting->password_pattern))
                {
                    $password_setting_instruction .= "<li>- One alphabet</li>";
                }        
                if(in_array("one_digit",$password_setting->password_pattern))
                {
                    $password_setting_instruction .= "<li>- One digit</li>";
                }
                if(in_array("one_special",$password_setting->password_pattern))
                {
                    $password_setting_instruction .= "<li>- One special character from (!@#$%*)</li>";
                }
            }
            if(!empty($password_setting_instruction))
            {
                $password_setting_instruction = trim($password_setting_instruction);
                $password_setting_instruction = '<ul class="list-unstyled"><li>- No white space</li>'.$password_setting_instruction.'</ul>';
                $password_setting_instruction = "Please remember to choose a secure password. It should have: ".$password_setting_instruction; 
            }
            return (!empty($password_setting_instruction)) ? $password_setting_instruction : false;
        }
        else
            return false;
    }
    
    /**
     * Password Validation Rules.
     *
     * @return Password validation
     */
    public function passwordValidation() {

        /** Extend validation rule password may not contain white space **/
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value); 
        });
        /** Extend validation rule to make password strong **/ 
        Validator::extend('alphabet', function($attr, $value){
            return preg_match('/^.*(?=.*[a-zA-Z]).*$/', $value);
        });
        Validator::extend('special', function($attr, $value){
            return preg_match('/^(?=.*[\d\X])(?=.*[!$#%@]).*$/', $value);
        });
        Validator::extend('digit', function($attr, $value){
            return preg_match('/^.*(?=.{3,})(?=.*[0-9]).*$/', $value);
        }); 
        $validation = 'required|without_spaces';
        
        $result = self::getSystemSettingByKey('password_setting');
        $password_setting = json_decode($result->value);
        
        if(!empty($password_setting)){ 
            if($password_setting->min_limit>0){
                $validation .='|min:'.$password_setting->min_limit;
            }
            if(isset($password_setting->password_pattern)){
                if(in_array("one_alphabet",$password_setting->password_pattern))
                {
                    $validation .= '|alphabet'; 
                }
                if(in_array("one_special",$password_setting->password_pattern))
                {
                    $validation .= '|special'; 
                }
                if(in_array("one_digit",$password_setting->password_pattern))
                {
                    $validation .= '|digit'; 
                }
            }
        }  
        return $validation;
    }
    
}
