<?php 
use Carbon\Carbon;
use App\Models\Geo;
use App\Models\Timezone;
use App\Models\UserGeneral;

/**
 * Constants
 * **/
if(!defined('DS')){
    define('DS', DIRECTORY_SEPARATOR);
}

if(!defined('DB_DATE_FORMAT')){
    define('DB_DATE_FORMAT',"Y-m-d");
}

if(!defined('DB_TIME_FORMAT')){
    define('DB_TIME_FORMAT',"H:i:s");
}

if(!defined('DB_DATETIME_FORMAT')){
    define('DB_DATETIME_FORMAT', "Y-m-d H:i:s");
}

if(!defined('DISPLAY_FULL_DATETIME')){
    define('DISPLAY_FULL_DATETIME', "l M d, Y - h:i A");
}

if(!defined('DISPLAY_DATETIME')){
    define('DISPLAY_DATETIME', "M d, Y h:i A");
}

if(!defined('DISPLAY_DATE')){
    define('DISPLAY_DATE', "M d, Y");
}

/**
 * Function print_data
 * This is used for print array
 * **/
if (!function_exists('print_data')) {
    function print_data($data,$break = true)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";

        if($break === true) exit;
    }
}

/**
 * Function custom_storage_path
 * This is used for get external storage path
 * **/
if (!function_exists('custom_storage_path')) {
    function custom_storage_path($path = "")
    {
        $ds = DS;
        $bind_path = trim($path);
        
        return (!empty($bind_path))?base_path()."{$ds}..{$ds}{$bind_path}{$ds}":base_path();
    }
}   

/**
* Encrypt a message
* 
* @param string $message - message to encrypt
* @param string $key - encryption key
* @return string
* @throws RangeException
*/ 
if (!function_exists('safe_encrypt')) {
    function safe_encrypt($string, $encode = true)
    {
        $nonceSize = openssl_cipher_iv_length("aes-256-ctr");
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $string,
            "aes-256-ctr",
            config('common.encrypt_key'),
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce.$ciphertext);
        }
        return $nonce.$ciphertext; 
    }
}

/**
* Decrypt a message
* 
* @param string $encrypted - message encrypted with safe_encrypt()
* @param string $key - encryption key
* @return string
* @throws Exception
*/
if (!function_exists('safe_decrypt')) {
    function safe_decrypt($encrypted, $encoded = true) 
    { 
        if ($encoded) {
            $encrypted = base64_decode($encrypted, true);
            if ($encrypted === false) {
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length("aes-256-ctr");
        $nonce = mb_substr($encrypted, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($encrypted, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            "aes-256-ctr",
            config('common.encrypt_key'),
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }
}

/**
 * Function secure_id
 */
if (!function_exists('secure_id')) {
    function secure_id($string,$action = "encrypt") 
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_iv = 'j1_app';

        /* hash */
        $key = hash('sha256', config('common.encrypt_key'));

        /* iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning */
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
} 

/**
 * Function custom_implode
 */
if (!function_exists('custom_implode')) {
    function custom_implode($data,$sep=','){

        if(!empty($data))
            return implode($sep, array_filter($data));
        else
            return false;

    }
}

/**
 * Function custom_explode
 */
if (!function_exists('custom_explode')) {
    function custom_explode($data,$sep=','){

        if(!empty($data))
            return array_values(array_filter(explode($sep, $data)));
        else
            return false;

    }
}


/**
 * Function refine_array
 * Returns refined array 
 */
if (!function_exists('refine_array')) {
    function refine_array($array){

        if(!empty($array))
            return array_values(array_filter($array));
        else
            return false;

    }
}

/**
 * Function get_array_value
 * Returns array of comma separated id string
 * **/
if (!function_exists('get_array_value'))
{
    function get_array_value($data)
    {
        if(is_numeric($data))
        {
            return (array) $data;
        }
        else if(is_array($data))
        {
            return $data;
        }
        else if(is_string($data))
        {
            if(strpos($data, ","))
            {
                return custom_explode($data);
            }
            else
            {
                return false;
            }
        }
        
    }
}

/**
 * Function doc_name_word_to_upper
 * Returns capitalized name of doc file
 * **/
if (!function_exists('doc_name_word_to_upper')){
    function doc_name_word_to_upper($doc_name)
    {
        $str_arr = custom_explode($doc_name,"_");
        foreach($str_arr as $key => $word)
        {
            $str_arr[$key]= ucfirst($word);
        }

        $docNameStr = custom_implode($str_arr,"_");

        return $docNameStr;
    }
}

/**
 * Function get_eligibility_quest
 * Returns list of eligibility questions
 * **/
if (!function_exists('get_eligibility_quest')){
    function get_eligibility_quest()
    {
        $eligibility_quest = array(
                0 => array(
                    'question' => "What industry are you interested in?",
                    'options' => [
                        1 => ['label' => "Hospitality, Tourism & Culinary",'answer' => 1],
                        2 => ['label' => "Business Management",'answer' => 2],
                        3 => ['label' => "IT &amp; Systems",'answer' => 3],
                        4 => ['label' => "Engineering",'answer' => 4],
                        5 => ['label' => "Human Science (Philosophy, Sociology, politics etc)",'answer' => 5],
                    ],
                ),
                101 => array(
                    'question' => "How old are you?",
                    'options' => [
                        1 => ['label' => "Less than 18 years",'answer' => 105],
                        2 => ['label' => "Between 18 and 35 years",'answer' => 103],
                        3 => ['label' => "More than 35 years",'answer' => 105],
                    ],
                ),
                103 => array(
                    'question' => "Do you have a post-secondary degree in Hospitality, Tourism or Culinary Arts?",
                    'options' => [
                        1 => ['label' => "Yes",'answer' => 106],
                        2 => ['label' => "No",'answer' => 107],
                    ],
                    'desc' => "<p class=\"text-muted m-t-10\"><i class=\"fa fa-exclamation-circle fa-2x m-r-5\"></i>Post-secondary degree refers to any education beyond high school</p>",
                ),
                105 => array(
                    'result' => "error",
                    'desc' => "Unfortunately, you are not eligible for any of our current programs. You can <span class=\"text-info\">retake the eligibility test</span> again if any of your information were changed.<br><br>
                        Meanwhile, check our forums for tips on how to improve your chances to be eligible for coming to the United States:<br><br>
                        <a href=\"#\">How to Successfully Get Your ".__('application_term.exchange_visitor')." Student Visa</a><br>
                        <a href=\"#\">How to Apply for a J1 Visa</a><br>
                        <a href=\"#\">IMPROVE YOUR CHANCES OF BEING ISSUED A VISA</a><br>
                        <a href=\"#\">Common Questions - ".__('application_term.exchange_visitor')." Visa</a>"
                ),
                106 => array(
                    'question' => "When did you graduate?",
                    'options' => [
                        1 => ['label' => "Less than one year",'answer' => 108],
                        2 => ['label' => "More than one year",'answer' => 109],
                        3 => ['label' => "Other",'answer' => 109],
                    ],
                ),
                107 => array(
                    'question' => "Are you currently enrolled in a post-secondary degree program studying one of these specialty: <i>Hospitality, Tourism or Culinary</i> ?",
                    'options' => [
                        1 => ['label' => "Yes",'answer' => 110],
                        2 => ['label' => "No",'answer' => 111],
                    ],
                    'desc' => "<p class=\"text-muted m-t-10\"><i class=\"fa fa-exclamation-circle fa-2x m-r-5\"></i>Post-secondary degree refers to any education beyond high school</p>"
                ),
                108 => array(
                    'result' => "success",
                    'program' => 2,
                    'desc' => "You have successfully completed the eligibility test and you are eligible to apply as a <strong>J1 Intern</strong> in the Category <strong>Recent Graduate</strong>."
                ),
                109 => array(
                    "question" => "<label>How many years of work experience do you have in your field of training (Hospitality, Tourism or Culinary)?</label>",
                    "options"=>[
                        1 => ['label' => "Less than one year",'answer' => 105],
                        2 => ['label' => "One year or more",'answer' => 108],
                    ],
                    'desc' => "<p class=\"text-muted m-t-10\"><i class=\"fa fa-exclamation-circle fa-2x m-r-5\"></i>Work experience refers to full time jobs</p>"
                ),
                110 => array(
                    'result' => "success",
                    'program' => 1,
                    'desc' => "You are eligible to apply as a:<br><strong>J1 Intern</strong> in the category <strong>Current Student</strong> (up to 12 months) 
                        <br>or<br> <strong>J1 Summer Intern</strong> in the category <strong>Work & Travel</strong> (up to 4 months)."
                ),
                111 => array(
                    'question' => "Are you currently a student in a different field of study: Law, Business, Engineer, ...?",
                    'options' => [
                        1 => ['label' => "Yes",'answer' => 112],
                        2 => ['label' => "No",'answer' => 113],
                    ],
                ),
                112 => array(
                    'result' => "success",
                    'program' => 5,
                    'desc' => "You are eligible to apply as a <strong>J1 Summer Intern</strong> in the category <strong>Work & Travel</strong> (up to 4 months)."
                ),
                113 => array(
                    'question' => "How many years of work experience do you have in the Hospitality, Tourism and Culinary Industry?",
                    'options' => [
                        1 => ['label' => "Less than five years",'answer' => 105],
                        2 => ['label' => "Five years or more",'answer' => 114],
                    ],
                    'desc' => "<p class=\"text-muted m-t-10\"><i class=\"fa fa-exclamation-circle m-r-5\"></i>Work experience referrs to full time jobs.</p>"
                ),
                114 => array(
                    'result' => "success",
                    'program' => 4,
                    'desc' => "You have successfully completed the eligibility test and you are eligible to apply as a <strong>J1 Trainee</strong> in the Category \"Young Professional\"."
                ),
            );

        return $eligibility_quest;
    }
}

/**
 * Function get_hashed
 * @param $string string
 * This function return hashed string
 **/
if(!function_exists('get_hashed')) {
    function get_hashed($string) 
    { 
        $hash_string = hash('sha256', $string);
        $hash_string = sha1($hash_string);
        $hash_string = md5($hash_string);
        $hash_string = crypt($hash_string, substr($hash_string,0,2)); 
        return $hash_string;
    }
}

/**
 * Function get_url
 * Returns url
  */
if(!function_exists('get_url')) {
    function get_url($filepath) {
        $file_exist = Storage::disk('public')->exists($filepath);
        if(!empty($file_exist)){
            return Storage::disk('public')->url($filepath);
        }
        else{ 
            return false;  
        }
    }
}

/**
 * Function thumb_url
 * Returns url
 * **/
if(!function_exists('thumb_url')) {
    function thumb_url($file_path, $size = 50, $thumb_dir_name = "thumb") {
        
        if(!empty($file_path) && is_numeric($size) && $size > 0)
        {
            $file_info = pathinfo($file_path);

            $dirname = "{$file_info['dirname']}/{$thumb_dir_name}/";
            $filename = $file_info['filename']."_{$size}x.".$file_info['extension'];
            $thumb_path = $dirname.$filename;
            
            $path = public_path("storage/{$thumb_path}");
            
            if(file_exists($path)){
                return url("storage/{$thumb_path}");
            }
        }
        
        return url("UserInterface/assets/images/noavatar.png");
    }
}

/**
 * Function generate_thumb_path
 * Returns generated thumb path of given image path
 * **/
if(!function_exists('generate_thumb_path')) {
    function generate_thumb_path($image_path,$thumb_size,$thumb_dir_name = "thumb")
    {
        if(!empty($image_path))
        {
            $file_info = pathinfo($image_path);

            $dirname = $file_info['dirname'].DS.$thumb_dir_name.DS;
            check_directory($dirname);
            $filename = $file_info['filename']."_{$thumb_size}x.".$file_info['extension'];
            $new_filepath = $dirname.$filename;

            return $new_filepath;
        }
        else
            return false;
    }
}

/**
 * Function get_thumb_image_path
 * Returns find the list of thumb image path of given image
 * **/
if(!function_exists('get_thumb_image_path')) {
    function get_thumb_image_path($image_path,$thumb_dir_name = "thumb")
    {
        if(!empty($image_path))
        {
            $thumb_path = [];
            $exclude = array('.','..'); 
            $file_info = pathinfo($image_path);

            $dirname = $file_info['dirname'].DS.$thumb_dir_name.DS;
            $filename = $file_info['filename'];

            $files = scandir($dirname);
            foreach($files as $file)
            {
                if(strpos(strtolower($file), strtolower($filename)) !== false && !in_array($file,$exclude)) { 
                    $thumb_path[] = $dirname.$file;
                }
            }

            return $thumb_path;
        }
        else
            return false;
    }
}

/**
 * Function storage_url
 * Returns path
 * **/
if(!function_exists('storage_url')) {
    function storage_url($file_path) { 
        if(!empty($file_path))
        {
            $path = public_path("storage/{$file_path}");
            if(file_exists($path)){ 
                if( !is_dir( $path ) ){
                    return url("storage/{$file_path}");
                }else{
                    return url("assets/images/noimage.png");
                }    
            }
            else
            {
                return url("assets/images/noimage.png");
            }
        }
        else
        {
            return url("assets/images/noimage.png");
        }
    }
}

/**
 * Function check_directory
 * create directory
 * **/
if(!function_exists('check_directory')) {
    function check_directory($dirpath) { 
        $dirpath = str_replace("//","/", $dirpath);

	if ( ! file_exists( $dirpath ) )
        {
            mkdir($dirpath, 0777, true);
        } 
    }
}

/**
 * Function get_upload_path
 * Returns path
 * **/
if(!function_exists('get_upload_path')) {
    function get_upload_path($storage_folder, $id = null, $size = null){
        
        if(!empty($storage_folder)){
            $size_dir = ['crop','200','50'];
            $path_arr = ["app","public"];
            $path_arr[] = $storage_folder;
            
            if(!empty($id)){
                $path_arr[] = $id;
            }
            
            if(!empty($size) && in_array($size, $size_dir)){
                $path_arr[] = $size;
            }
            
            $path = custom_implode($path_arr,DS).DS;
            return storage_path($path);
        }
        else{
            return false;
        }
    }
}

function createUniqueFilename( $filename, $length = 5 )
{  
    /* Generate token for image */
    $image_token = substr(sha1(mt_rand()), 0, $length);
    return $filename . '-' . $image_token;
} 

function sanitize($string, $force_lowercase = true, $anal = false)
{
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]","}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;", "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;

    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}   
    
/**
 * Function del_file
 * Returns delete result
 * **/
if(!function_exists('del_file')) {
    function del_file($file_path){
        if ( file_exists( $file_path ) && is_file($file_path) )
        { 
            $delete_result = unlink($file_path);  
        }
    }
}

/**
 * Function dateformat
 * function is used for get formatted date
 * **/
if(!function_exists('dateformat')){
    function dateformat($date,$format = "m/d/Y")
    {
        if(!empty($date) && (substr($date, 0,4) > 1970) )
        {
            return Carbon::parse($date)->format($format);
        }
        else
            return "";
    }
}

/**
 * Function get_date_diff
 * function is used for get 2 date difference
 * **/
if(!function_exists('get_date_diff')){
    function get_date_diff($date1,$date2,$format = "%mM%dD")
    {
        if(!empty($date1) && !empty($date2) )
        {
            $date1 = new Carbon($date1);
            $date2 = new Carbon($date2);
            $datediff = date_diff($date2,$date1);
            
            if(!empty($datediff)){
                $y = $datediff->y;
                $m = $datediff->m;
                $d = $datediff->d;
                $format = ($y==0)?str_replace("%yY", "", $format):$format;
                $format = ($m==0)?str_replace("%mM", "", $format):$format;
                $format = ($d==0)?str_replace("%dD", "", $format):$format;
                return date_diff($date1,$date2)->format($format);
            }
            return false;
        }
        else
            return false;
    }
}
/**
 * Function is_selected
 * This function is used for compare two values to make selected dropdown options.
 * **/
if(!function_exists('is_selected')){
    function is_selected($val1,$val2,$return_bool = false)
    {
        $val = "";
        if($val1 == $val2 && !is_null($val1) && !is_null($val2))
            $val = "selected";
        
        if($return_bool === false){
            return $val;
        }
        else{
            if(!empty($val))
                return true;
            else
                return false;
        }
    }
}

/**
 * Function is_checked
 * This function is used for compare two values to keep checked Radio or Checkbox.
 * **/
if(!function_exists('is_checked')){
    function is_checked($val1,$val2,$return_bool = false)
    {
        $val = "";
        if($val1 == $val2 && !is_null($val1) && !is_null($val2))
            $val = "checked";
        
        if($return_bool === false){
            return $val;
        }
        else{
            if(!empty($val))
                return true;
            else
                return false;
        }
    }
}

/**
 * Function is_disabled
 * This function is used return disabled attribute and checked boolean value.
 * **/
if(!function_exists('is_disabled')){
    function is_disabled($bool_val)
    {
        if($bool_val == true)
            return "disabled";
        else
            return "";
    }
}

/**
 * Function get_countdown_date
 * @param string $date datetime string from the database
 * @param int $hours number of hours to set countdown
 * @param string $format set date format default "Y/m/d H:i:s"
 * 
 * @desc function will convert datetime from default timezone to local timezone then after add hours to datetime
 *      If no local timezone found then return datetime as default timezone.
 * **/
if(!function_exists('get_countdown_date')){
    function get_countdown_date($date,$hours = 24,$format = "Y/m/d H:i:s")
    {
        if(!empty($date)) {
            /****
            $converted_data = convert_datetime_to_local($date);
            if(!empty($converted_data)) {
                $startTime = strtotime($converted_data->dest_datetime);
                $convertedTime = date($format,strtotime("+{$hours} hour",$startTime));
            }
            else {
                
            }
            /****/
            
            $startTime = strtotime($date);
            $convertedTime = date($format,strtotime("+{$hours} hour",$startTime));
            return $convertedTime;
        }
        else
            return false;
    }
}

if(!function_exists('get_country_name')){
    function get_country_name($country_id)
    {
        $geo = new Geo;
        $data = $geo->getCountries($country_id);

        if(!empty($data))
        {
            return (is_object($data)) ? $data->country_name : $data['country_name'];
        }
        return false;
    }
}

if(!function_exists('get_countries')){
    function get_countries()
    {
        cache()->rememberForever('get_countries', function () {
            return Geo::getCountries();
        });
        
        return cache('get_countries');
    }
}

if(!function_exists('get_states')){
    function get_states()
    {
        cache()->rememberForever('get_states', function () {
            return Geo::getStates();
        });
        
        return cache('get_states');
    }
}

if(!function_exists('get_state_name')){
    function get_state_name($state_id)
    {
        $geo = new Geo;
        $data = $geo->getStates($state_id);

        if(!empty($data))
        {
            return (is_object($data)) ? $data->state_name : $data['state_name'];
        }
        return false;
    }
}

if(!function_exists('get_airport_list')){
    function get_airport_list()
    {
        cache()->rememberForever('get_airport_list', function () {
            return Geo::getAirportList();
        });
        
        return cache('get_airport_list');
    }
}

/**
 * function convert_datetime_to_local
 * @param string $datetime
 * @param mixed $source_timezone
 * @param string $user_type user type options: admin, user, employer
 * **/
if(!function_exists('convert_datetime_to_local')){
    function convert_datetime_to_local($datetime,$source_timezone = "",$user_type = "admin")
    {
        $timezone = new Timezone;
        $user_type_key = "";
        switch($user_type)
        {
            case "admin":
                $user_type_key = "admin_timezone";
            break;

            case "user":
                $user_type_key = "user_timezone";
            break;

            default:
            break;
        }

        if(!empty($datetime) && !in_array(substr($datetime, 0, 4),array(0000,1970,1969)))
        {
            if(!empty($source_timezone))
            {
                if(is_numeric($source_timezone))
                {
                    $source_tz_name = Timezone::getZoneNameById($source_timezone);
                }
                else {
                    $source_tz_name = $source_timezone;
                }
            }
            else {
                $source_tz_name = config('common.default_timezone_name');
            }

            $user_timezone = config('common.default_timezone');
            if(!empty($user_type_key))
            {
                $user_timezone = (!empty(session($user_type_key)))?session($user_type_key):(!empty(session('local_timezone'))?session('local_timezone'):config('common.default_timezone'));
            }
            $dest_tz_name = Timezone::getZoneNameById($user_timezone);

            $converted_data = $timezone->convertTZDateTime($datetime,$source_tz_name,$dest_tz_name,DB_DATETIME_FORMAT);

            return $converted_data;
        }
        else
        {
            return false;
        }
    }
}

if(!function_exists('get_current_datetime')){
    function get_current_datetime($custom_tz = null)
    {
        $current_datetime = new \stdClass();
        $current_datetime->current_timezone = "";
        $current_datetime->current_timezone_name = "";
        $current_datetime->current_datetime = "";
        
        if(!empty($custom_tz)){
            $timezone = $custom_tz;
        }
        else{
            
            $app_interface = config('common.app_interface');
            switch($app_interface)
            {
                case "admin":
                    $user_type_key = "admin_timezone";
                break;

                case "user":
                    $user_type_key = "user_timezone";
                break;

                default:
                break;
            }

            $timezone = config('common.default_timezone');
            if(!empty($user_type_key))
            {
                $timezone = (!empty(session($user_type_key)))?session($user_type_key):(!empty(session('local_timezone'))?session('local_timezone'):$timezone);
            }
        }
        
        $tz_name = Timezone::getZoneNameById($timezone);
        
        $current_date = Carbon::now($tz_name);
        
        if(!empty($current_date))
        {
            $current_datetime = (object) [
                'current_timezone' => $timezone,
                'current_timezone_name' => $tz_name,
                'current_datetime' => (string) $current_date
            ];
        }
        
        return $current_datetime;
    }
}

if(!function_exists('convert_user_date_time')){
    function convert_user_date_time($datetime,$user_type = "user")
    {
        $timezone = new Timezone;
        $user_type_key = "";
        switch($user_type == "admin")
        {
            case "admin":
                $user_type_key = "admin_timezone";
            break;

            case "user":
                $user_type_key = "user_timezone";
            break;

            default:
            break;
        }

        if(!empty($datetime) && intval(substr($datetime, 0, 4)) > 1970){
            $source_tz_name = config('common.default_timezone_name');
            
            $user_timezone = config('common.default_timezone');
            if(!empty($user_type_key))
            {
                $user_timezone = (!empty(session($user_type_key)))?session($user_type_key):(!empty(session('local_timezone'))?session('local_timezone'):config('common.default_timezone'));
            }
            
            $dest_tz_name = Timezone::getZoneNameById($user_timezone);

            $converted_data = $timezone->convertTZDateTime($datetime,$source_tz_name,$dest_tz_name,DB_DATETIME_FORMAT);
            return $converted_data['dest_datetime'];
        }
        else{
            return false;
        }
    }
}

if(!function_exists('get_timezone_label')){
    function get_timezone_label($zone_id, $column_name = "zone_label"){
        if(!empty($zone_id))
        {
            $timezone = new Timezone;
            $zone_data = $timezone->getFullZoneLabel($zone_id);
            if(!empty($zone_data))
            {
                return $zone_data->$column_name;
            }
            else
                return false;
        }
        else
            return false;
    }
}

if(!function_exists('get_timezones')){
    function get_timezones(){
        cache()->rememberForever('get_timezones', function () {
            return Timezone::getTimezones();
        });
        
        return cache('get_timezones');
    }
}

if(!function_exists('encrypt_decrypt_data')){
    function encrypt_decrypt_data($string, $action = "encrypt")
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = '1a2b3c4d5e6f7g8h9i10';
        $secret_iv = '1a2b3c4d5e6f7g8h9i10jklmnopqrstuvwxyz';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}

if(!function_exists('get_domain_key')){
    function get_domain_key($key,$is_encrypted = true)
    {
        if(!empty($key))
        {
            $data = DB::table('itn_domains_settings')->select('itn_key')->where('itn_key_type',$key)->first();
            
            if(!empty($data->itn_key))
            {
                if($is_encrypted == true)
                    $data = encrypt_decrypt_data($data->itn_key,'decrypt');
                else
                    $data = $data->itn_key;
                
                return (!empty($data)) ? $data : false;
            }
            else
                return false;
        }
        else
            return false;
    }
}

if(!function_exists('get_email_setting')){
    function get_email_setting($key)
    {
        if(!empty($key))
        {
            $data = DB::table('cron_job_setting')
                        ->select('id','cron_job_field_name','cron_job_field_value','cron_job_file_name')
                        ->where('cron_job_field_name',$key)->first();
            
            if(!empty($data->id))
            {
                $cron_job_field_value = $data->cron_job_field_value;
                $data->cron_job_field_value = custom_explode($cron_job_field_value);
                return (!empty($data)) ? $data : false;
            }
            else
                return false;
        }
        else
            return false;
    }
}

if(!function_exists('display_table')){
    function display_table($data_array,$stop = "")
    {
        $table = "";
        $th = "";
        $temp_th = "";
        $temp_th_sr_no = "<th>Sr. No.:</th>";
        $counter = 1;
        if(!empty($data_array))
        {
            $trs = "";
            foreach($data_array as $sub_array)
            {
                $td = "";
                $tr = "";
                foreach($sub_array as $key => $val)
                {
                    if(empty($th))
                    {
                        $temp_th .= "<th>{$key}</th>";
                    }
                    $td .= "<td>{$val}</td>";
                }
                $td_sr_no = "<td>{$counter}</td>";
                $th = "<tr>{$temp_th_sr_no}{$temp_th}</tr>";
                $tr = "<tr>{$td_sr_no}{$td}</tr>";
                $trs .= $tr;
                $counter++;
            }
            $table = "<table border='1'>{$th}{$trs}</table>";
        }
        else
        {
            $td = "<td>No Data Found</td>";
            $tr = "<tr>{$td}</tr>";
            $table = "<table border='1'>{$tr}</table>";
        }

        if(empty($stop))
        {
            echo $table;
            exit;
        }
        else
        {
            echo $table;
            return true;
        }
    }
}

if(!function_exists('parse_data_id')){
    function parse_data_id($data)
    {
        $return_data = array();

        if(!empty($data)){
            
            $data_arr = array();
            if(is_array($data))
            {
                $data_arr = $data;
            }
            else if(is_string($data) && is_numeric(str_ireplace(",", "", $data)))
            {
                $data_arr = custom_explode($data);
            }
            else if(is_numeric($data))
            {
                $data_arr = array($data);
            }
            
            $return_data['array'] = $data_arr;
            $return_data['string'] = custom_implode($data_arr);
        }
        return $return_data;
    }
}

if(!function_exists('objectToArray')){
    function objectToArray($d) {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map(__FUNCTION__, $d);
        }
        else {
            return $d;
        }
    }
}

if(!function_exists('arrayToObject')){
    function arrayToObject($d) {
        if (is_array($d)) {
            return (object) array_map(__FUNCTION__, $d);
        }
        else {
            return $d;
        }
    }
}

if(!function_exists('apiResponse')){
    function apiResponse($type,$message = null,$data = null,$code = null)
    {
        if(empty($code) && $type == "success"){
            $code = 200;
        }
        
        if(empty($code) && $type == "error"){
            $code = 404;
        }
        
        $response = [
            'type' => $type,
            'message' => $message,
            'data' => $data,
        ];
        
        return response()->json($response, $code);
    }
}

if(!function_exists('get_routes')){
    
    function get_routes(){
        
        $routeCollection = Route::getRoutes();

        $routes = [];
        foreach ($routeCollection as $route) {
            $routes[] = [
                'uri' => $route->uri,
                'name' => $route->getName(),
                'prefix' => $route->getPrefix(),
                'action_method' => $route->getActionMethod(),
                'action_name' => $route->getActionName(),
            ];
        }
        
        return $routes;
    }
}

/**
 * @param string $name pass route name as parameter
 * @param string $param pass the element from the route instance
 * @return return all data of route by default or value when second parameter passed
 *          [uri,name,prefix,action_method,action_name]
 * **/
if(!function_exists('get_route_by_name')){
    
    function get_route_by_name($name,$param = null){
        
        $route = Route::getRoutes()->getByName($name);
        $route_data = [
            'uri' => $route->uri,
            'name' => $route->getName(),
            'prefix' => $route->getPrefix(),
            'action' => $route->getAction(),
            'action_method' => $route->getActionMethod(),
            'action_name' => $route->getActionName(),
            'domain' => $route->getDomain(),
        ];

        if(!empty($param)){
            return $route_data[$param];
        }
        else{
            return $route_data;
        }
    }
}

/**
 * @param string $name pass route name as parameter
 * @param string $param pass the element from the route instance
 * @return return all data of route by default or value when second parameter passed
 *          [uri,name,prefix,action_method,action_name]
 * **/
if(!function_exists('route_uri')){
    function route_uri($name){
        return Route::getRoutes()->getByName($name)->uri;
    }
}

/**
 * @param string $param pass the element from the route instance
 * @return return all data of route by default or value when parameter passed
 *          [uri,name,prefix,action_method,action_name]
 * **/
if(!function_exists('get_current_route')){
    
    function get_current_route($param = null){
        
        $route = Route::current();
        $route_data = [
            'uri' => $route->uri,
            'name' => $route->getName(),
            'prefix' => $route->getPrefix(),
            'action' => $route->getAction(),
            'action_method' => $route->getActionMethod(),
            'action_name' => $route->getActionName(),
            'domain' => $route->getDomain(),
        ];

        if(!empty($param)){
            return $route_data[$param];
        }
        else{
            return $route_data;
        }
    }
}

if(!function_exists('check_route_access')){
    function check_route_access($route_name){
        $user = auth()->user();
        
        if(!empty($user->role_name)){
            if($user->role_name == 'root'){
                return true;
            }
            else{
                if($user->role_name == "agency-admin" && $user->agency_status !== 1){
                    return false;
                }
                
                $permissions = collect($user->permissions)->toArray();
                $route_names = array_column($permissions, 'route_name');

                if(in_array($route_name,$route_names)){
                    return true;
                }
            }
            return false;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('user_token')){
    function user_token(){
        $user_id = request('user_id',"");
        view()->share('user_token', $user_id);
        return decrypt($user_id);
    }
}

if(!function_exists('get_menuitems')){
    function get_menuitems($request)
    {
        $key = "get_menuitems_{$request->user->id}";
        cache()->rememberForever($key, function () use($request) {
            $menu = new \App\Models\Menu;
            return $menu->filterMenuItems($request->user);
        });
        
        return cache($key);
    }
}