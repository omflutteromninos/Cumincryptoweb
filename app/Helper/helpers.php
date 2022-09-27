<?php

use App\Model\ActivityLog;
use App\Model\AdminSetting;
use App\Model\Coin;
use App\Model\DepositeTransaction;
use App\Model\MembershipBonusDistributionHistory;
use App\Model\MembershipClub;
use App\Model\MembershipPlan;
use App\Model\VerificationDetails;
use App\Model\Wallet;
use App\Model\WithdrawHistory;
use App\Services\CoinPaymentsAPI;
use App\User;
use Carbon\Carbon;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Jenssegers\Agent\Agent;
use Ramsey\Uuid\Uuid;
use App\Model\IcoPhase;


/**
 * @param $role_task
 * @param $my_role
 * @return int
 */
function checkRolePermission($role_task, $my_role)
{

    $role = Role::find($my_role);

    if (!empty($role->task)) {

        if (!empty($role->task)) {
            $tasks = array_filter(explode('|', $role->task));
        }

        if (isset($tasks)) {
            if ((in_array($role_task, $tasks) && array_key_exists($role_task, actions())) || (Auth::user()->user_type == USER_ROLE_SUPER_ADMIN)) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    return 0;
}

function previousMonthName($m){
    $months = [];
    for ($i=$m; $i >= 0; $i--) {
        array_push($months, date('F', strtotime('-'.$i.' Month')));
    }

    return array_reverse($months);
}
function previousYearMonthName(){

    $months = [];
    for ($i=0; $i <12; $i++) {

        array_push($months, Carbon::now()->startOfYear()->addMonth($i)->format('F'));
    }

    return $months;
}

function previousDayName(){
    $days = array();
    for ($i = 1; $i < 8; $i++) {
        array_push($days,Carbon::now()->startOfWeek()->subDays($i)->format('l'));
    }

    return array_reverse($days);
}
function previousMonthDateName(){
    $days = array();
    for ($i = 0; $i < 30; $i++) {
        array_push($days,Carbon::now()->startOfMonth()->addDay($i)->format('d-M'));
    }

    return $days;
}


/**
 * @param null $array
 * @return array|bool
 */
function allsetting($array = null)
{
    if (!isset($array[0])) {
        $allsettings = AdminSetting::get();
        if ($allsettings) {
            $output = [];
            foreach ($allsettings as $setting) {
                $output[$setting->slug] = $setting->value;
            }
            return $output;
        }
        return false;
    } elseif (is_array($array)) {
        $allsettings = AdminSetting::whereIn('slug', $array)->get();
        if ($allsettings) {
            $output = [];
            foreach ($allsettings as $setting) {
                $output[$setting->slug] = $setting->value;
            }
            return $output;
        }
        return false;
    } else {
        $allsettings = AdminSetting::where(['slug' => $array])->first();
        if ($allsettings) {
            $output = $allsettings->value;
            return $output;
        }
        return false;
    }
}

/**
 * @param null $input
 * @return array|mixed
 */

function addActivityLog($action,$source,$ip_address,$location){
    $return = false;
    if (ActivityLog::create(['action'=>$action,'user_id'=>$source,'ip_address'=>$ip_address,'location'=>$location]))
        $return = true;
    return $return;


}

function country($input=null){
    $output = [
        'af' => 'Afghanistan',
        'al' => 'Albania',
        'dz' => 'Algeria',
        'ds' => 'American Samoa',
        'ad' => 'Andorra',
        'ao' => 'Angola',
        'ai' => 'Anguilla',
        'aq' => 'Antarctica',
        'ag' => 'Antigua and Barbuda',
        'ar' => 'Argentina',
        'am' => 'Armenia',
        'aw' => 'Aruba',
        'au' => 'Australia',
        'at' => 'Austria',
        'az' => 'Azerbaijan',
        'bs' => 'Bahamas',
        'bh' => 'Bahrain',
        'bd' => 'Bangladesh',
        'bb' => 'Barbados',
        'by' => 'Belarus',
        'be' => 'Belgium',
        'bz' => 'Belize',
        'bj' => 'Benin',
        'bm' => 'Bermuda',
        'bt' => 'Bhutan',
        'bo' => 'Bolivia',
        'ba' => 'Bosnia and Herzegovina',
        'bw' => 'Botswana',
        'br' => 'Brazil',
        'io' => 'British Indian Ocean Territory',
        'bn' => 'Brunei',
        'bg' => 'Bulgaria',
        'bf' => 'Burkina ',
        'bi' => 'Burundi',
        'kh' => 'Cambodia',
        'cm' => 'Cameroon',
        'ca' => 'Canada',
        'cv' => 'Cape Verde',
        'ky' => 'Cayman Islands',
        'cf' => 'Central African Republic',
        'td' => 'Chad',
        'cl' => 'Chile',
        'cn' => 'China',
        'cx' => 'Christmas Island',
        'cc' => 'Cocos Islands',
        'co' => 'Colombia',
        'km' => 'Comoros',
        'ck' => 'Cook Islands',
        'cr' => 'Costa Rica',
        'hr' => 'Croatia',
        'cu' => 'Cuba',
        'cy' => 'Cyprus',
        'cz' => 'Czech Republic',
        'cg' => 'Congo',
        'dk' => 'Denmark',
        'dj' => 'Djibouti',
        'dm' => 'Dominica',
        'tp' => 'East Timor',
        'ec' => 'Ecuador',
        'eg' => 'Egypt',
        'sv' => 'El Salvador',
        'gq' => 'Equatorial Guinea',
        'er' => 'Eritrea',
        'ee' => 'Estonia',
        'et' => 'Ethiopia',
        'fk' => 'Falkland Islands',
        'fo' => 'Faroe ',
        'fj' => 'Fiji',
        'fi' => 'Finland',
        'fr' => 'France',
        'pf' => 'French Polynesia',
        'ga' => 'Gabon',
        'gm' => 'Gambia',
        'ge' => 'Georgia',
        'de' => 'Germany',
        'gh' => 'Ghana',
        'gi' => 'Gibraltar',
        'gr' => 'Greece',
        'gl' => 'Greenland',
        'gd' => 'Grenada',
        'gu' => 'Guam',
        'gt' => 'Guatemala',
        'gk' => 'Guernsey',
        'gn' => 'Guinea',
        'gw' => 'Guinea-',
        'gy' => 'Guyana',
        'ht' => 'Haiti',
        'hn' => 'Honduras',
        'hk' => 'Hong Kong',
        'hu' => 'Hungary',
        'is' => 'Iceland',
        'in' => 'India',
        'id' => 'Indonesia',
        'ir' => 'Iran',
        'iq' => 'Iraq',
        'ie' => 'Ireland',
        'im' => 'Isle of ',
        'il' => 'Israel',
        'it' => 'Italy',
        'ci' => 'Ivory ',
        'jm' => 'Jamaica',
        'jp' => 'Japan',
        'je' => 'Jersey',
        'jo' => 'Jordan',
        'kz' => 'Kazakhstan',
        'ke' => 'Kenya',
        'ki' => 'Kiribati',
        'kp' => 'North Korea',
        'kr' => 'South Korea',
        'xk' => 'Kosovo',
        'kw' => 'Kuwait',
        'kg' => 'Kyrgyzstan',
        'la' => 'Laos',
        'lv' => 'Latvia',
        'lb' => 'Lebanon',
        'ls' => 'Lesotho',
        'lr' => 'Liberia',
        'ly' => 'Libya',
        'li' => 'Liechtenstein',
        'lt' => 'Lithuania',
        'lu' => 'Luxembourg',
        'mo' => 'Macau',
        'mk' => 'Macedonia',
        'mg' => 'Madagascar',
        'mw' => 'Malawi',
        'my' => 'Malaysia',
        'mv' => 'Maldives',
        'ml' => 'Mali',
        'mt' => 'Malta',
        'mh' => 'Marshall Islands',
        'mr' => 'Mauritania',
        'mu' => 'Mauritius',
        'ty' => 'Mayotte',
        'mx' => 'Mexico',
        'fm' => 'Micronesia',
        'md' => 'Moldova, Republic of',
        'mc' => 'Monaco',
        'mn' => 'Mongolia',
        'me' => 'Montenegro',
        'ms' => 'Montserrat',
        'ma' => 'Morocco',
        'mz' => 'Mozambique',
        'mm' => 'Myanmar',
        'na' => 'Namibia',
        'nr' => 'Nauru',
        'np' => 'Nepal',
        'nl' => 'Netherlands',
        'an' => 'Netherlands Antilles',
        'nc' => 'New Caledonia',
        'nz' => 'New Zealand',
        'ni' => 'Nicaragua',
        'ne' => 'Niger',
        'ng' => 'Nigeria',
        'nu' => 'Niue',
        'mp' => 'Northern Mariana Islands',
        'no' => 'Norway',
        'om' => 'Oman',
        'pk' => 'Pakistan',
        'pw' => 'Palau',
        'ps' => 'Palestine',
        'pa' => 'Panama',
        'pg' => 'Papua New Guinea',
        'py' => 'Paraguay',
        'pe' => 'Peru',
        'ph' => 'Philippines',
        'pn' => 'Pitcairn',
        'pl' => 'Poland',
        'pt' => 'Portugal',
        'qa' => 'Qatar',
        're' => 'Reunion',
        'ro' => 'Romania',
        'ru' => 'Russian',
        'rw' => 'Rwanda',
        'kn' => 'Saint Kitts and Nevis',
        'lc' => 'Saint Lucia',
        'vc' => 'Saint Vincent and the Grenadines',
        'ws' => 'Samoa',
        'sm' => 'San Marino',
        'st' => 'Sao Tome and ',
        'sa' => 'Saudi Arabia',
        'sn' => 'Senegal',
        'rs' => 'Serbia',
        'sc' => 'Seychelles',
        'sl' => 'Sierra ',
        'sg' => 'Singapore',
        'sk' => 'Slovakia',
        'si' => 'Slovenia',
        'sb' => 'Solomon Islands',
        'so' => 'Somalia',
        'za' => 'South Africa',
        'es' => 'Spain',
        'lk' => 'Sri Lanka',
        'sd' => 'Sudan',
        'sr' => 'Suriname',
        'sj' => 'Svalbard and Jan Mayen ',
        'sz' => 'Swaziland',
        'se' => 'Sweden',
        'ch' => 'Switzerland',
        'sy' => 'Syria',
        'tw' => 'Taiwan',
        'tj' => 'Tajikistan',
        'tz' => 'Tanzania',
        'th' => 'Thailand',
        'tg' => 'Togo',
        'tk' => 'Tokelau',
        'to' => 'Tonga',
        'tt' => 'Trinidad and Tobago',
        'tn' => 'Tunisia',
        'tr' => 'Turkey',
        'tm' => 'Turkmenistan',
        'tc' => 'Turks and Caicos Islands',
        'tv' => 'Tuvalu',
        'ug' => 'Uganda',
        'ua' => 'Ukraine',
        'ae' => 'United Arab Emirates',
        'gb' => 'United ',
        'uy' => 'Uruguay',
        'uz' => 'Uzbekistan',
        'vu' => 'Vanuatu',
        'va' => 'Vatican City State',
        've' => 'Venezuela',
        'vn' => 'Vietnam',
        'vi' => 'Virgin Islands (U.S.)',
        'wf' => 'Wallis and Futuna Islands',
        'eh' => 'Western ',
        'ye' => 'Yemen',
        'zm' => 'Zambia',
        'zw' => 'Zimbabwe'
    ];

    if (is_null($input)) {
        return $output;
    } else {

        return $output[$input];
    }
}

/**
 * @param $registrationIds
 * @param $type
 * @param $data_id
 * @param $count
 * @param $message
 * @return array
 */
//google firebase
function pushNotification($registrationIds,$type, $data_id, $count,$message)
{

    // $news = \App\News::find($data_id);
    $fields = array
    (
        'to' => $registrationIds,
        "delay_while_idle" => true,
        "time_to_live" => 3,
        /*    'notification' => [
                'body' => strip_tags(str_limit($news->description,30)),
                'title' => str_limit($news->title,25),
            ],*/
        'data'=> [
            'message' => $message,
            'title' => 'monttra',
            'id' =>$data_id,
            'is_background' => true,
            'content_available'=>true,

        ]
    );


    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);

    return $fields;

}

/**
 * @param $string
 * @return string|string[]|null
 */
//function clean($string) {
//    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
//    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
//    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
//}

/**
 * @param $registrationIds
 * @param $type
 * @param $data_id
 * @param $count
 * @param $message
 * @return array
 */
//google firebase
function pushNotificationIos($registrationIds,$type, $data_id, $count,$message)
{

//    $news = \App\News::find($data_id);

    $fields = array
    (
        'to' => $registrationIds,
        "delay_while_idle" => true,

        "time_to_live" => 3,
        'notification' => [
            'body' => '',
            'title' => $message,
            'vibrate' => 1,
            'sound' => 'default',
        ],
        'data'=> [
            'message' => '',
            'title' => $message,
            'id' => $data_id,
            'is_background' => true,
            'content_available'=>true,


        ]
    );

    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);

    return $fields;

}





/**
 * @param $a
 * @return string
 */
//Random string
function randomString($a)
{
    $x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $c = strlen($x) - 1;
    $z = '';
    for ($i = 0; $i < $a; $i++) {
        $y = rand(0, $c);
        $z .= substr($x, $y, 1);
    }
    return $z;
}

/**
 * @param int $a
 * @return string
 */
// random number
function randomNumber($a = 10)
{
    $x = '123456789';
    $c = strlen($x) - 1;
    $z = '';
    for ($i = 0; $i < $a; $i++) {
        $y = rand(0, $c);
        $z .= substr($x, $y, 1);
    }
    return $z;
}

//use array key for validator
/**
 * @param $array
 * @param string $seperator
 * @param array $exception
 * @return string
 */
function arrKeyOnly($array, $seperator = ',', $exception = [])
{
    $string = '';
    $sep = '';
    foreach ($array as $key => $val) {
        if (in_array($key, $exception) == false) {
            $string .= $sep . $key;
            $sep = $seperator;
        }
    }
    return $string;
}

/**
 * @param $img
 * @param $path
 * @param null $user_file_name
 * @param null $width
 * @param null $height
 * @return bool|string
 */
function uploadInStorage($img, $path, $user_file_name = null, $width = null, $height = null)
{

    if (!file_exists($path)) {

        mkdir($path, 777, true);
    }

    if (isset($user_file_name) && $user_file_name != "" && file_exists($path . $user_file_name)) {
        unlink($path . $user_file_name);
    }
    // saving image in target path
    $imgName = uniqid() . '.' . $img->getClientOriginalExtension();
    $imgPath = public_path($path . $imgName);
    // making image
    $makeImg = \Intervention\Image\Image::make($img)->orientate();
    if ($width != null && $height != null && is_int($width) && is_int($height)) {
        // $makeImg->resize($width, $height);
        $makeImg->fit($width, $height);
    }

    if ($makeImg->save($imgPath)) {
        return $imgName;
    }
    return false;
}

function uploadimage($img, $path, $user_file_name = null, $width = null, $height = null)
{

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    if (isset($user_file_name) && $user_file_name != "" && file_exists($path . $user_file_name)) {
        unlink($path . $user_file_name);
    }
    // saving image in target path
    $imgName = uniqid() . '.' . $img->getClientOriginalExtension();
    $imgPath = public_path($path . $imgName);
    // making image
    $makeImg = Image::make($img)->orientate();
    if ($width != null && $height != null && is_int($width) && is_int($height)) {
        // $makeImg->resize($width, $height);
        $makeImg->fit($width, $height);
    }

    if ($makeImg->save($imgPath)) {
        return $imgName;
    }
    return false;
}


/**
 * @param $path
 * @param $file_name
 */
function removeImage($path, $file_name)
{
    if (isset($file_name) && $file_name != "" && file_exists($path . $file_name)) {
        unlink($path . $file_name);
    }
}

//Advertisement image path
/**
 * @return string
 */
function path_image()
{
    return IMG_VIEW_PATH;
}

/**
 * @return string
 */
function upload_path()
{
    return 'uploads/';
}



/**
 * @param $file
 * @param $destinationPath
 * @param null $oldFile
 * @return bool|string
 */
function uploadFile($new_file, $path, $old_file_name = null, $width = null, $height = null)
{
    if (!file_exists(public_path($path))) {
        mkdir(public_path($path), 0777, true);
    }
    if (isset($old_file_name) && $old_file_name != "" && file_exists($path . substr($old_file_name, strrpos($old_file_name, '/') + 1))) {

        unlink($path . '/' . substr($old_file_name, strrpos($old_file_name, '/') + 1));
    }

    $input['imagename'] = uniqid() . time() . '.' . $new_file->getClientOriginalExtension();
    $imgPath = public_path($path . $input['imagename']);

    $makeImg = Image::make($new_file);
    if ($width != null && $height != null && is_int($width) && is_int($height)) {
        $makeImg->resize($width, $height);
        $makeImg->fit($width, $height);
    }

    if ($makeImg->save($imgPath)) {
        return $input['imagename'];
    }
    return false;

}
//function uploadFile($file, $destinationPath, $oldFile = null)
//{
////    if ($oldFile != null) {
////        deleteFile($destinationPath, $oldFile);
////    }
//
//
//    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
//    $uploaded = \Illuminate\Support\Facades\Storage::disk('local')->put($destinationPath . $fileName, file_get_contents($file->getRealPath()));
//    if ($uploaded == true) {
//        $name = $fileName;
//        return $name;
//    }
//    return false;
//}
function containsWord($str, $word)
{
    return !!preg_match('#\\b' . preg_quote($word, '#') . '\\b#i', $str);
}

/**
 * @param $destinationPath
 * @param $file
 */
function deleteFile($destinationPath, $file)
{
    if (isset($file) && $file != "" && file_exists($destinationPath . $file)) {
        unlink($destinationPath . $file);
    }
}

//function for getting client ip address
/**
 * @return mixed|string
 */
function get_clientIp()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

/**
 * @return array|bool
 */
function language()
{
    $lang = [];
    $path = base_path('resources/lang');
    foreach (glob($path . '/*.json') as $file) {
        $langName = basename($file, '.json');
        $lang[$langName] = $langName;
    }
    return empty($lang) ? false : $lang;
}

/**
 * @param null $input
 * @return array|mixed
 */
function langName($input = null)
{
    $output = [
        'ar' => 'Arabic',
        'de' => 'German',
        'en' => 'English',
        'es' => 'Spanish',
        'et' => 'Estonian',
        'fa' => 'Farsi',
        'fr' => 'French',
        'it' => 'Italian',
        'pl' => 'Polish',
        'pt' => 'Portuguese (European)',
        'pt-br' => 'Portuguese (Brazil)',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'th' => 'Thai',
        'tr' => 'Turkish',
        'zh-CN' => 'Chinese (Simplified)',
        'zh-TW' => 'Chinese (Traditional)',
        'zh-HK' => 'Chinese (Hong Kong)',
        'zh-SG' => 'Chinese (Singapore)',
        'ko' => 'Korean',
        'ja' => 'Japanese',
        'nl' => 'Dutch',
        'gr' => 'Greece',
        'id' => 'Indonesian',
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

/**
 * @param null $input
 * @return array|mixed|string
 */
function langNameMobile($input = null)
{
    $output = [
        'en' => 'English',
        'fr' => 'French',
        'it' => 'Italian',
        'pt-PT' => ' Português(Portugal)',
    ];
    if (is_null($input)) {
        return $output;
    } else {
        if(isset($output[$input]))
            return $output[$input];
        return '';
    }
}

if (!function_exists('settings')) {

    function settings($keys = null)
    {
        if ($keys && is_array($keys)) {
            return AdminSetting::whereIn('slug', $keys)->pluck('value', 'slug')->toArray();
        } elseif ($keys && is_string($keys)) {
            $setting = AdminSetting::where('slug', $keys)->first();
            return empty($setting) ? false : $setting->value;
        }
        return AdminSetting::pluck('value', 'slug')->toArray();
    }
}

function landingPageImage($index,$static_path){
    if (settings($index)){
        return asset(path_image()).'/'.settings($index);
    }
    return asset('landing').'/'.$static_path;
}

function userSettings($keys = null){
    if ($keys && is_array($keys)) {
        return UserSetting::whereIn('slug', $keys)->pluck('value', 'slug')->toArray();
    } elseif ($keys && is_string($keys)) {
        $setting = UserSetting::where('slug', $keys)->first();
        return empty($setting) ? false : $setting->value;
    }
    return UserSetting::pluck('value', 'slug')->toArray();
}
//Call this in every function
/**
 * @param $lang
 */
function set_lang($lang)
{
    $default = settings('lang');
    $lang = strtolower($lang);
    $languages = language();
    if (in_array($lang, $languages)) {
        app()->setLocale($lang);
    } else {
        if (isset($default)) {
            $lang = $default;
            app()->setLocale($lang);
        }
    }
}

/**
 * @param null $input
 * @return array|mixed
 */
function langflug($input = null)
{

    $output = [
        'en' => '<i class="flag-icon flag-icon-us"></i> ',
        'pt-PT' => '<i class="flag-icon flag-icon-pt"></i>',
        'fr' => '<i class="flag-icon flag-icon-fr"></i>',
        'it' => '<i class="flag-icon flag-icon-it"></i>',
    ];
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}


//find odd even
/**
 * @param $number
 * @return string
 */
function oddEven($number)
{
//    dd($number);
    if ($number % 2 == 0) {
        return 'even';
    } else {
        return 'odd';
    }
}

function convert_currency($amount, $to = 'USD', $from = 'USD')
{
    $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
    $json = file_get_contents($url); //,FALSE,$ctx);
    $jsondata = json_decode($json, TRUE);

    return bcmul($amount, $jsondata[$to],8);
}

// fees calculation
function calculate_fees($amount, $method)
{
    $settings = allsetting();

    try {
        if ($method == SEND_FEES_FIXED) {
            return $settings['send_fees_fixed'];
        } elseif ($method == SEND_FEES_PERCENTAGE) {
            return ($settings['send_fees_percentage'] * $amount) / 100;
        }  else {
            return 0;
        }
    } catch (\Exception $e) {
        return 0;
    }
}

/**
 * @param null $message
 * @return string
 */
function getToastrMessage($message = null)
{
    if (!empty($message)) {

        // example
        // return redirect()->back()->with('message','warning:Invalid username or password');

        $message = explode(':', $message);
        if (isset($message[1])) {
            $data = 'toastr.' . $message[0] . '("' . $message[1] . '")';
        } else {
            $data = "toastr.error(' write ( errorType:message ) ')";
        }

        return '<script>' . $data . '</script>';

    }

}

function getUserBalance($user_id){
    $wallets = Wallet::where(['user_id' => $user_id, 'coin_type' => 'Default']);

    $data['available_coin'] = $wallets->sum('balance');
    $data['available_used'] = $data['available_coin'] * settings('coin_price');
//    $data['pending_withdrawal_coin'] = WithdrawHistory::whereIn('wallet_id',$wallets->pluck('id'))->where('status',STATUS_PENDING)->sum('amount');
//    $data['pending_withdrawal_usd'] =  $data['pending_withdrawal_coin']*settings('coin_price');
    $coins = Coin::orderBy('id', 'ASC')->get();
    if (isset($coins[0])) {
        foreach($coins as $coin) {
            $walletAmounts = Wallet::where(['user_id' => $user_id, 'coin_type' => $coin->type])->sum('balance');
            $data[$coin->type] = $walletAmounts;
        }
    }
    $data['pending_withdrawal_coin'] = 0;
    $data['pending_withdrawal_usd'] = 0;
    return $data;
}

// total withdrawal
function total_withdrawal($user_id)
{
    $total = 0;
    $withdrawal = WithdrawHistory::join('wallets', 'wallets.id', '=','withdraw_histories.wallet_id')
        ->where('wallets.user_id', $user_id)
        ->where('withdraw_histories.status',STATUS_SUCCESS)
        ->get();
    if (isset($withdrawal[0])) {
        $total = $withdrawal->sum('amount');
    }

    return $total;
}
// total deposit
function total_deposit($user_id)
{
    $total = 0;
    $deposit = DepositeTransaction::join('wallets', 'wallets.id', '=','deposite_transactions.receiver_wallet_id')
        ->where('wallets.user_id', $user_id)
        ->where('deposite_transactions.status',STATUS_SUCCESS)
        ->get();
    if (isset($deposit[0])) {
        $total = $deposit->sum('amount');
    }

    return $total;
}

function getActionHtml($list_type,$user_id,$item){

    $html = '<div class="activity-icon"><ul>';
    if ($list_type == 'active_users'){
        $html .='
               <li><a title="'.__('View').'" href="'.route('adminUserProfile').'?id='.encrypt($user_id).'&type=view" class="user-two"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/view.svg").'" class="img-fluid" alt=""></span></a></li>
               <li><a title="'.__('Edit').'" href="'.route('admin.UserEdit').'?id='.encrypt($user_id).'&type=edit" class="user-two"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/user.svg").'" class="img-fluid" alt=""></span></a></li>
               <li>'.suspend_html('admin.user.suspend',encrypt($user_id)).'</li>';
                if(!empty($item->google2fa_secret)) {
                    $html .='<li>'.gauth_html('admin.user.remove.gauth',encrypt($user_id)).'</li>';
                }
                $html .='<li>'.delete_html('admin.user.delete',encrypt($user_id)).'</li>';

    } elseif ($list_type == 'suspend_user') {
        $html .='<li><a title="'.__('View').'" href="'.route('admin.UserEdit').'?id='.encrypt($user_id).'&type=view" class="view"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/view.svg").'" class="img-fluid" alt=""></span></a></li>
          <li><a data-toggle="tooltip" title="Activate" href="'.route('admin.user.active',encrypt($user_id)).'" class="check"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/check-square.svg").'" class="img-fluid" alt=""></span></a></li>
         ';

    } elseif($list_type == 'deleted_user') {
        $html .='<li><a title="'.__('View').'" href="'.route('admin.UserEdit').'?id='.encrypt($user_id).'&type=view" class="view"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/view.svg").'" class="img-fluid" alt=""></span></a></li>
          <li><a data-toggle="tooltip" title="Activate" href="'.route('admin.user.active',encrypt($user_id)).'" class="check"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/check-square.svg").'" class="img-fluid" alt=""></span></a></li>
         ';

    } elseif($list_type == 'email_pending') {
        $html .=' <li><a data-toggle="tooltip" title="Email verify" href="'.route('admin.user.email.verify',encrypt($user_id)).'" class="check"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/check-square.svg").'" class="img-fluid" alt=""></span></a></li>';

    } elseif ($list_type == 'phone_pending') {
        $html .=' <li><a data-toggle="tooltip" title="Phone verify" href="'.route('admin.user.phone.verify',encrypt($user_id)).'" class="check"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/check-square.svg").'" class="img-fluid" alt=""></span></a></li>';
    }
    $html .='</ul></div>';
    return $html;
}

// Html render
/**
 * @param $route
 * @param $id
 * @return string
 */
function edit_html($route, $id)
{
    $html = '<li class="viewuser"><a title="'.__('Edit').'" href="' . route($route, encrypt($id)) . '"><i class="fa fa-pencil"></i></a></li>';
    return $html;
}


/**
 * @param $route
 * @param $id
 * @return string
 * @throws Exception
 */

function receipt_view_html($image_link)
{
    $num = random_int(1111111111,9999999999999);
    $html = '<div class="deleteuser"><a title="'.__('Bank receipt').'" href="#view_' . $num . '" data-toggle="modal">Bank Deposit</a> </div>';
    $html .= '<div id="view_' . $num . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-lg">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Bank receipt') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><img src="'.$image_link.'" alt=""></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function delete_html($route, $id)
{
    $html = '<li class="deleteuser"><a title="'.__('delete').'" href="#delete_' . decrypt($id) . '" data-toggle="modal"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/delete-user.svg").'" class="img-fluid" alt=""></span></a> </li>';
    $html .= '<div id="delete_' . decrypt($id) . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Delete') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to delete ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-danger"href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}
function delete_html2($route, $id)
{
    $html = '<li class="deleteuser"><a title="'.__('delete').'" href="#delete_' . ($id) . '" data-toggle="modal"><span class="flaticon-delete-user"></span></a> </li>';
    $html .= '<div id="delete_' . ($id) . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Delete') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to delete ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-danger"href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function suspend_html($route, $id)
{
    $html = '<li class="deleteuser"><a title="'.__('Suspend').'" href="#suspends_' . decrypt($id) . '" data-toggle="modal"><span><img src="'.asset("assets/admin/images/user-management-icons/activity/block-user.svg").'" class="img-fluid" alt=""></span></a> </li>';
    $html .= '<div id="suspends_' . decrypt($id) . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Suspend') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to suspend ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-danger"href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function active_html($route, $id)
{
    $html = '<li class="deleteuser"><a title="'.__('Active').'" href="#active_' . decrypt($id) . '" data-toggle="modal"><span class="flaticon-delete"></span></a> </li>';
    $html .= '<div id="active_' . decrypt($id) . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Delete') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to Active ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-success" href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function accept_html($route, $id)
{
    $html = '<li class="deleteuser"><a title="' . __('Accept') . '" href="#accept_' . decrypt($id) . '" data-toggle="modal"><span class=""><i class="fa fa-check-circle-o" aria-hidden="true"></i>
    </span></a> </li>';
    $html .= '<div id="accept_' . decrypt($id) . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Accept') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to Accept ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-success" href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function default_accept_html($route, $id)
{
    $html = '<li class="deleteuser"><a title="'.__('Accept').'" href="#accept_' . decrypt($id) . '" data-toggle="modal"><span class=""><i class="fa fa-check-circle-o" aria-hidden="true"></i>
    </span></a> </li>';
    $html .= '<div id="accept_' . decrypt($id) . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<form action="'.route($route).'" method="get">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Accept') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body">';
    $html .= '<p class="text-warning">' . __('Do you want to Accept ?') . '</p>';
    $html .= '<input type="hidden" name="withdrawal_id" value="'.$id.'">';
    $html .= '<label>' . __('Transaction Hash') . '</label>';
    $html .= '<input type="text" required name="transaction_hash" class="form-control">';
    $html .= '<small>' . __('It is a default coin withdrawal . so please manually send coin and put here the transaction hash ') . '</small>';
    $html .= '</div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<button type="submit" class="btn btn-success" >' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</form>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function reject_html($route, $id)
{
    $html = '<li class="deleteuser"><a title="'.__('Reject').'" href="#reject_' . decrypt($id) . '" data-toggle="modal"><span class=""><i class="fa fa-minus-square" aria-hidden="true"></i>
    </span></a> </li>';
    $html .= '<div id="reject_' . decrypt($id) . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Reject') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to Reject ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-danger" href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}
/**
 * @param $route
 * @param $id
 * @return string
 */
function ChangeStatus($route, $id)
{
    $html = '<li class=""><a href="#status_' . $id . '" data-toggle="modal"><i class="fa fa-ban"></i></a> </li>';
    $html .= '<div id="status_' . $id . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Block') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to Block this product ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-danger"href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

/**
 * @param $route
 * @param $id
 * @return string
 */
function BlockStatusChange($route, $id)
{   $html = '<ul class="activity-menu">';
    $html .= '<li class=" "><a title="'.__('Status change').'" href="#blockuser' . $id . '" data-toggle="modal"><i class="fa fa-check"></i></a> </li>';
    $html .= '<div id="blockuser' . $id . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Block') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to Unblock this product ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-success"href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</ul>';

    return $html;
}

/**
 * @param $route
 * @param $param
 * @return string
 */
function cancelSentItem($route,$param)
{
    $html  = '<li class=""><a title="'.__('Cancel').'" class="delete" href="#blockuser' . $param . '" data-toggle="modal"><i class="fa fa-remove"></i></a> </li>';
    $html .= '<div id="blockuser' . $param . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Cancel') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to cancel this product ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-success"href="' . route($route).$param. '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';


    return $html;
}

//status search
/**
 * @param $keyword
 * @return array
 */
function status_search($keyword)
{
    $st = [];
    if (strpos('_active', strtolower($keyword)) != false) {
        array_push($st, STATUS_SUCCESS);
    }
    if (strpos('_pending', strtolower($keyword)) != false) {
        array_push($st, STATUS_PENDING);
    }
    if (strpos('_inactive', strtolower($keyword)) != false) {
        array_push($st, STATUS_PENDING);
    }

    if (strpos('_deleted', strtolower($keyword)) != false) {
        array_push($st, STATUS_DELETED);
    }
    return $st;
}

function cim_search($keyword)
{

    return $keyword;
}

/**
 * @param $route
 * @param $status
 * @param $id
 * @return string
 */
function statusChange_html($route, $status, $id)
{
    $icon = ($status != STATUS_SUCCESS) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>';
    $status_title = ($status != STATUS_SUCCESS) ? statusAction(STATUS_SUCCESS) : statusAction(STATUS_SUSPENDED);
    $html = '';
    $html .= '<a title="'.__('Status change').'" href="' . route($route, encrypt($id)) . '">' . $icon . '<span>' . $status_title . '</span></a> </li>';
    return $html;
}

//exists img search
/**
 * @param $image
 * @param $path
 * @return string
 */
function imageSrc($image, $path)
{

    $return = asset('admin/images/default.jpg');
    if (!empty($image) && file_exists(public_path($path . '/' . $image))) {
        $return = asset($path . '/' . $image);
    }
    return $return;
}
//exists img search
/**
 * @param $image
 * @param $path
 * @return string
 */
function imageSrcUser($image, $path)
{

    $return = asset('user/assets/images/profile/default.png');
    if (!empty($image) && file_exists(public_path($path . '/' . $image))) {
        $return = asset($path . '/' . $image);
    }
    return $return;
}

function imageSrcVerification($image, $path)
{


    $return = asset('/assets/images/default_card.svg');
    if (!empty($image) && file_exists(public_path($path . '/' . $image))) {
        $return = asset($path . '/' . $image);
    }
    return $return;
}

/**
 * @param $title
 */
function title($title)
{
    session(['title' => $title]);
}


/**
 * @param int $length
 * @return string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * @return bool|string|\Webpatser\Uuid\Uuid
 * @throws Exception
 */
function uniqueNumber()
{

    $rand = Uuid::generate();
    $rand = substr($rand,30);
    $prefix = Auth::user()->prefix;
    if(ProductSerialAndGhost::where('serial_id',$prefix.$rand)->orwhere('ghost_id',$prefix.'G'.$rand)->exists())
        return uniqueNumber();
    else
        return $rand;
}



function customNumberFormat($value)
{
    if (is_integer($value)) {
        return number_format($value, 8, '.', '');
    } elseif (is_string($value)) {
        $value = floatval($value);
    }
    $number = explode('.', number_format($value, 10, '.', ''));
    return $number[0] . '.' . substr($number[1], 0, 2);
}

if (!function_exists('max_level')) {
    function max_level()
    {
        $max_level = allsetting('max_affiliation_level');

        return $max_level ? $max_level : 3;
    }

}

if (!function_exists('user_balance')) {
    function user_balance($userId)
    {
        $balance = Wallet::where('user_id', $userId)->sum(DB::raw('balance + referral_balance'));

        return $balance ? $balance : 0;
    }

}

if (!function_exists('visual_number_format'))
{
    function visual_number_format($value)
    {
        if (is_integer($value)) {
            return number_format($value, 2, '.', '');
        } elseif (is_string($value)) {
            $value = floatval($value);
        }
        $number = explode('.', number_format($value, 14, '.', ''));
        $intVal = (int)$value;
        if ($value > $intVal || $value < 0) {
            $intPart = $number[0];
            $floatPart = substr($number[1], 0, 8);
            $floatPart = rtrim($floatPart, '0');
            if (strlen($floatPart) < 2) {
                $floatPart = substr($number[1], 0, 2);
            }
            return $intPart . '.' . $floatPart;
        }
        return $number[0] . '.' . substr($number[1], 0, 2);
    }
}

// comment author name
function comment_author_name($id)
{
    $name = '';
    $user = User::where('id', $id)->first();
    if(isset($user)) {
        $name = $user->first_name.' '.$user->last_name;
    }

    return $name;
}

function gauth_html($route, $id)
{
    $html = '<li class="deleteuser"><a title="' . __('Remove gauth') . '" href="#remove_gauth_' . decrypt($id) . '" data-toggle="modal"><span class=""><img src="'.asset("assets/admin/images/user-management-icons/activity/check-square.svg").'" class="img-fluid" alt=""></span></a> </li>';
    $html .= '<div id="remove_gauth_' . decrypt($id) . '" class="modal fade delete" role="dialog">';
    $html .= '<div class="modal-dialog modal-sm">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header"><h6 class="modal-title">' . __('Remove Gauth') . '</h6><button type="button" class="close" data-dismiss="modal">&times;</button></div>';
    $html .= '<div class="modal-body"><p>' . __('Do you want to remove gauth ?') . '</p></div>';
    $html .= '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' . __("Close") . '</button>';
    $html .= '<a class="btn btn-danger"href="' . route($route, $id) . '">' . __('Confirm') . '</a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}
if (!function_exists('all_months')) {
    function all_months($val = null)
    {
        $data = array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
            11 => 11,
            12 => 12,
        );
        if ($val == null) {
            return $data;
        } else {
            return $data[$val];
        }
    }
}
function custom_number_format($value)
{
    if (is_integer($value)) {
        return number_format($value, 8, '.', '');
    } elseif (is_string($value)) {
        $value = floatval($value);
    }
    $number = explode('.', number_format($value, 14, '.', ''));
    return $number[0] . '.' . substr($number[1], 0, 8);
}

function converts_currency($amountInUsd, $to = 'BTC', $price)
{
    try {
        $array['amount'] = $amountInUsd;

        if ($to == "LTCT"){
            $to = "LTC";
        }

        if ( ($price['error'] == "ok") ) {

            $one_coin = $price['result'][$to]['rate_btc']; // dynamic coin rate in btc

            $one_usd = $price['result']['USD']['rate_btc']; // 1 usd == btc rate

            $total_amount_in_usd = bcmul($one_usd, $amountInUsd,8);

            return custom_number_format(bcdiv($total_amount_in_usd, $one_coin,8));
        }
    } catch (\Exception $e) {

        return number_format(0, 8);
    }
}


function convert_to_crypt($amountInBTC, $to)
{
    try {
        $coinpayment = new CoinPaymentsAPI();

        $price = $coinpayment->GetRates('');
        if ( ($price['error'] == "ok") ) {

            $one_coin = $price['result'][$to]['rate_btc']; // dynamic coin rate in btc
            $one_usd = $price['result']['USD']['rate_btc']; // 1 usd ==  btc rate

            $total_amount_in_btc = bcmul($one_coin, $amountInBTC,8);
            $total_amount_in_usd = bcdiv($total_amount_in_btc, $one_usd,8);

            return custom_number_format(bcdiv($total_amount_in_usd, settings('coin_price'),8));
        }
    } catch (\Exception $e) {
        return custom_number_format($amountInBTC, 8);
    }
}


//User Activity
function createUserActivity($userId, $action = '', $ip = null, $device = null){
    if ($ip == null) {
        $current_ip = get_clientIp();
    } else {
        $current_ip = $ip;
    }
    if($device == null){
        $agent = new Agent();
        $deviceType = isset($agent) && $agent->isMobile() == true ? 'Mobile' : 'Web';
    }else{
        $deviceType = $device == 1 ?  'Mobile' : 'Web';
    }

//        try{
//            $location = GeoIP::getLocation($current_ip);
//            $country = $location->country;
//        }catch(\Exception $e){
//            $country  = '';
//        }

    $activity['user_id'] = $userId;
    $activity['action'] = $action;
    $activity['ip_address'] = isset($current_ip) ? $current_ip : '0.0.0.0';
    $activity['source'] = $deviceType;
    $activity['location'] = '';
    ActivityLog::create($activity);
}
// user image
function show_image($id=null, $type)
{
    $img = asset('assets/img/avater.png');
    if ($type =='logo') {
        if (!empty(allsetting('logo'))) {
            $img = asset(path_image().allsetting('logo'));
        } else {
            $img = asset('assets/user/images/logo.svg');
        }
    } elseif($type == 'login_logo') {
        if (!empty(allsetting('login_logo'))) {
            $img = asset(path_image().allsetting('login_logo'));
        } else {
            $img = asset('assets/user/images/logo.svg');
        }
    } else {
        $user = User::where('id',$id)->first();
        if (isset($user) && !empty($user->photo)) {
            $img = asset(IMG_USER_PATH.$user->photo);
        }
    }
    return $img;
}
// plan image
function show_plan_image($plan_id,$img=null)
{
    $image = asset('assets/img/badge/Gold.png');
    if (!empty($img)) {
        $image = asset(path_image().$img);
    } else {
        if ($plan_id == 1) {
            $image = asset('assets/img/badge/Silver.png');
        } elseif ($plan_id == 2) {
            $image = asset('assets/img/badge/Gold.png');
        } elseif ($plan_id == 3) {
            $image = asset('assets/img/badge/Platinum.png');
        }
    }

    return $image;
}

// member plan bonus percentage
function plan_bonus_percentage($type,$bonus,$amount)
{
    $bonus_percentage = $bonus;
    if ($type == PLAN_BONUS_TYPE_FIXED) {
        $bonus_percentage = (100 * $bonus) / $amount;
    }

    return number_format($bonus_percentage,2);
}
// calculate bonus
function calculate_plan_bonus($bonus_percentage,$amount)
{
    $bonus = ($bonus_percentage * $amount) / 100;

    return number_format($bonus,8);
}

// get coin payment address
function get_coin_payment_address($payment_type)
{
    $coin_payment = new CoinPaymentsAPI();
    $address = $coin_payment->GetCallbackAddress($payment_type);
    Log::info(json_encode($address));
    if ( isset($address['error']) && ($address['error'] == 'ok') ) {
        return $address['result']['address'];
    } else {
        return false;
    }
}

// get plan name by amount
function find_plan_by_amount($amount)
{
    $plans = MembershipPlan::where(['status' => STATUS_ACTIVE])->orderBy('amount','asc')->get();
    $plan = "";
    if (isset($plans[0])) {
        foreach ($plans as $pln) {
            if ($amount >= $pln->amount) {
                $plan = $pln;
            }
        }
    }

    return $plan;
}

// get blocked coin
function get_blocked_coin($user_id)
{
    $coin = 0;
    $membership = MembershipClub::where(['user_id' => $user_id, 'status' => STATUS_ACTIVE])->first();
    if (isset($membership)) {
        $coin = $membership->amount;
    }

    return $coin;
}

// get my plan info
function get_plan_info($user_id)
{
    $data['club_id'] = '';
    $data['plan_id'] = '';
    $data['blocked_coin'] = 0;
    $data['plan_name'] = '';
    $data['plan_image'] = '';
    $club = MembershipClub::where(['status' => STATUS_ACTIVE,'user_id' => $user_id])->first();
    if (isset($club)) {
        $data['club_id'] =  $club->id;
        $data['plan_id'] = $club->plan_id;
        $data['blocked_coin'] = $club->amount;
        if (!empty($club->plan_id)) {
            $data['plan_name'] = $club->plan->plan_name;
            $data['plan_image'] = show_plan_image( $club->plan_id,$club->plan->image);;
        }
    }

    return $data;
}


// check available phase
function checkAvailableBuyPhase()
{
    $activePhases = IcoPhase::where('status', STATUS_ACTIVE)->orderBy('start_date', 'asc')->get();
// dd($activePhases);
    if ( isset($activePhases[0])) {
        $phaseInfo = '';
        $phaseStatus = 0;
        $now = Carbon::now()->format("Y-m-d H:i:s");
        $futureDate = '';

        foreach ($activePhases as $activePhase) {
            if ( ($now >= $activePhase->start_date) && $now <= $activePhase->end_date ) {
                $phaseStatus = 1;
                $phaseInfo = $activePhase;
                break;
            } elseif ( $activePhase->start_date > $now ) {
                $phaseStatus = 2;
                $phaseInfo = '';
                $futureDate = $activePhase->start_date;
                break;
            }
        }

        if ( $phaseStatus == 0 ) {
            return [
                'status' => false
            ];
        } elseif ( $phaseStatus == 1 ) {
            return [
                'status' => true,
                'futurePhase' => false,
                'pahse_info' => $phaseInfo
            ];
        } else {
            return [
                'status' => true,
                'futurePhase' => true,
                'pahse_info' => $phaseInfo,
                'futureDate' => $futureDate
            ];
        }
    }

    return [
        'status' => false
    ];
}

// calculate fees of ico phase
function calculate_phase_percentage($amount, $fees)
{
    $fees = ($amount*$fees)/100;

    return $fees;
}

// check primary wallet
function is_primary_wallet($wallet_id, $coin_type)
{
    $wallets = Wallet::where(['user_id' => Auth::id(), 'coin_type' => $coin_type, 'is_primary'=> 1])->get();
    $this_primary_id = 0;
    $primary = 0;
    if (isset($wallets[0])) {
        foreach ($wallets as $wallet) {
            if ($wallet->id == $wallet_id) {
                $this_primary_id = $wallet->id;
            }
        }
    }
    if ($this_primary_id == $wallet_id) {
        $primary = 1;
    }

    return $primary;

}

// check coin type
function check_coin_type($type)
{
    $coin = Coin::where('type', $type)->first();
    if (isset($coin)) {
        return $coin->type;
    }

    return 'BTC';
}

// find primary wallet
function get_primary_wallet($user_id, $coin_type)
{
    $primaryWallet = Wallet::where(['user_id' => $user_id, 'coin_type' => $coin_type, 'is_primary' => 1])->first();
    $wallets = Wallet::where(['user_id' => $user_id, 'coin_type' => $coin_type])->first();
    if (isset($primaryWallet)) {
        return $primaryWallet;
    } elseif (isset($wallets)) {
        $wallets->update(['is_primary' =>1]);
        return $wallets;
    } else {
        $createWallet = Wallet::create(['user_id' => $user_id, 'name' => $coin_type.' Wallet', 'coin_type' => $coin_type, 'is_primary' => 1]);
        return $createWallet;
    }
}

// get user distributed plan bonus
function user_plan_bonus($user_id)
{
    $bonus_amount = 0;
    $bonus = MembershipBonusDistributionHistory::where('user_id', $user_id)->get();
    if (isset($bonus[0])) {
        $bonus_amount = $bonus->sum('bonus_amount_btc');
    }

    return $bonus_amount;
}

if(!function_exists('co_wallet_feature_active')) {
    function co_wallet_feature_active()
    {
        $coWalletFeatureActive = settings(CO_WALLET_FEATURE_ACTIVE_SLUG);
        if($coWalletFeatureActive == STATUS_ACTIVE) return true;
        else return false;
    }
}

function find_coin_type($coin_type)
{
    $type = $coin_type;
    if ($coin_type == DEFAULT_COIN_TYPE) {
        $type = settings('coin_name');
    }

    return $type;
}

function decryptId($encryptedId)
{
    try {
        $id = decrypt($encryptedId);
    } catch (Exception $e) {
        return ['success' => false];
    }
    return $id;
}

function show_image_path($img_name, $path)
{
    $img = asset('assets/img/dlr.png');
    if (!empty($img_name)) {
        $img = asset(path_image().$path.$img_name);
    }

    return $img;
}

function check_coin_status($wallet, $type, $amount, $fees = 0)
{
    $data = [
        'success' => false,
        'message' => 'ok',
    ];
    if(isset($wallet)) {

        if($type == CHECK_STATUS) {
            if($wallet->coin_status != STATUS_ACTIVE) {
                $data = [
                    'success' => true,
                    'message' => find_coin_type($wallet->coin_type).__(" is inactive right now.")
                ];
            }
        }
        if($type == CHECK_WITHDRAWAL_STATUS) {
            if($wallet->is_withdrawal != STATUS_ACTIVE) {
                $data = [
                    'success' => true,
                    'message' => find_coin_type($wallet->coin_type).__(" is not available for withdrawal right now")
                ];
            }
        }
        if($type == CHECK_WITHDRAWAL_FEES) {
            Log::info('wallet balance = '. $wallet->balance);
            Log::info('amount = '. $amount);
            Log::info('fees = '. $fees);
            if($wallet->balance < ($amount + $fees)) {
                $data = [
                    'success' => true,
                    'message' => __("Wallet has no enough balance to withdrawal")
                ];
            }
        }
        if($type == CHECK_MINIMUM_WITHDRAWAL) {
            if (($amount + $fees) < $wallet->minimum_withdrawal) {
                $data = [
                    'success' => true,
                    'message' => __('Minimum withdrawal amount ') . $wallet->minimum_withdrawal . ' ' . $wallet->coin_type
                ];
            }
        }
        if($type == CHECK_MAXIMUM_WITHDRAWAL) {
            if (($amount + $fees) > $wallet->maximum_withdrawal) {
                $data = [
                    'success' => true,
                    'message' => __('Maximum withdrawal amount ') . $wallet->maximum_withdrawal . ' ' . $wallet->coin_type
                ];
            }
        }
    }

    return $data;
}

function check_withdrawal_fees($amount, $fess_percentage)
{
    return ($fess_percentage * $amount) / 100;
}

function get_wallet_coin($coin_id)
{
    return Coin::find($coin_id);
}


function checkUserKyc($userId, $type, $verificationType)
{
    $response = ['success' => true, 'message' => 'success'];
    if ($type == KYC_DRIVING_REQUIRED) {
        $drive_front = VerificationDetails::where('user_id',$userId)->where('field_name','drive_front')->first();
        $drive_back = VerificationDetails::where('user_id',$userId)->where('field_name','drive_back')->first();
        if((isset($drive_front ) && isset($drive_back)) && (($drive_front->status == STATUS_SUCCESS) && ($drive_back->status == STATUS_SUCCESS))) {
            $response = ['success' => true, 'message' => 'success'];
        } else {
            $response = ['success' => false, 'message' => __('Before ').$verificationType.__(' you must have verified driving licence')];
        }
        return $response;
    } elseif($type == KYC_PASSPORT_REQUIRED) {
        $pass_front = VerificationDetails::where('user_id',$userId)->where('field_name','pass_front')->first();
        $pass_back = VerificationDetails::where('user_id',$userId)->where('field_name','pass_back')->first();
        if((isset($pass_front ) && isset($pass_back)) && (($pass_front->status == STATUS_SUCCESS) && ($pass_back->status == STATUS_SUCCESS))) {
            $response = ['success' => true, 'message' => 'success'];
        } else {
            $response = ['success' => false, 'message' => __('Before ').$verificationType.__(' you must have verified passport')];
        }
        return $response;
    } else {
        $nid_front = VerificationDetails::where('user_id',$userId)->where('field_name','nid_front')->first();
        $nid_back = VerificationDetails::where('user_id',$userId)->where('field_name','nid_back')->first();
        if((isset($nid_front ) && isset($nid_back)) && (($nid_front->status == STATUS_SUCCESS) && ($nid_back->status == STATUS_SUCCESS))) {
            $response = ['success' => true, 'message' => 'success'];
        } else {
            $response = ['success' => false, 'message' => __('Before ').$verificationType.__(' you must have verified NID')];
        }
        return $response;
    }
}


function sendMessage($message, $recipients)
{
    try {
        $all_settings = allsetting();
        $account_sid = $all_settings['twillo_secret_key'];
        $auth_token = $all_settings['twillo_auth_token'];
        $twilio_number = $all_settings['twillo_number'];
        $client = new Aloha\Twilio\Twilio($account_sid,$auth_token,$twilio_number);
        $client = $client->message($recipients, $message);
        $client = $client->status;
        return $client;
    }catch (\Exception $exception){
        return  false;
    }
}

// get wallet personal address
function get_wallet_personal_add($add1,$add2)
{
    $data = explode($add1,$add2);
    return $data[0];
}

function getSwapStatus(){
    $swap_status = 1;
    $data_swap = AdminSetting::where(['slug' => 'swap_enabled'])->first();
    if($data_swap && !$data_swap->value){
        $swap_status = 0;
    }
    return $swap_status;
}
