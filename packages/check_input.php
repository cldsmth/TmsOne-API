<?php
if(!function_exists('isAuthorize'))
{
    function isAuthorize($authorization, $access_token){
        return $authorization == $access_token ? true : false;
    }
}

if(!function_exists('getAllHeaders'))
{
    function getAllHeaders(){
        $headers = array();
        $result = null;

        if(!empty($_SERVER)){
            $result = array();
            foreach($_SERVER as $key => $value) {
                if(strpos($key, 'HTTP_') === 0) {
                    $headers = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                    $result[$headers] = $value;
                }else{
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }
}

if(!function_exists('smtpmailer'))
{
    function smtpmailer($to, $from_url, $from, $from_password, $from_name, $subject, $body){
        define('GWEB', $from_url); // Gmail username
        define('GUSER', $from); // Gmail username
        define('GPWD', $from_password); // Gmail password

        global $error;
        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = ''; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = ''; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = GWEB;
        $mail->Port = 587;
        $mail->Username = GUSER;
        $mail->Password = GPWD;
        $mail->SetFrom($from, $from_name);
        $mail->Subject = $subject;
        $mail->isHTML(true); //Send HTML or Plain Text email
        $mail->Body = $body;
        $mail->AddAddress($to);
        if(!$mail->Send()) {
            $error = 'Mail error: '.$mail->ErrorInfo;
            return false;
        } else {
            $error = 'Message sent!';
            return true;
        }
    }
}

if(!function_exists('generateRandomString'))
{
    function generateRandomString($length){
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('cleanSpace'))
{
    function cleanSpace($string) {
        $string = trim($string);
        while(strpos($string,' ')){
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        }
        return $string;
    }
}

if(!function_exists('charLength'))
{
    //function to put 3dots after lengthy string
    function charLength($data, $length) {
        $strLength = strlen($data);
        if ($strLength > $length) {
            $data = substr(strip_tags($data), 0, $length) . "...";
        }
        return $data;
    }
}

if(!function_exists('generate_code'))
{
    function generate_code($length=6,$type=1){
        $key = '';
        switch($type){
            case 2:
            $pattern = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
            break;
            case 3:
            $pattern = "12345678901234567890123456789012345678901234567890";
            break;
            default:
            $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
            break;
        }
        for($i=0;$i<$length;$i++){
            $key .= $pattern{rand(0,35)};
        }
        return strtoupper($key);
    }
}

if(!function_exists('doHash'))
{
    function doHash($secData, $salt) {
    //creates a random 5 character sequence
        $secData = hash('sha256', $salt . $secData);
        return $secData;
    }
}
if(!function_exists('contentDisplay'))
{
    function contentDisplay($data_short, $data_long, $size) {
        $result = "";

        if($data_short != ""){
            $content = htmlspecialchars_decode(stripslashes($data_short), ENT_QUOTES);
            $strLength = strlen($content);
            if ($strLength > $size) {
                $result = substr(strip_tags($content), 0, $size) . "...";
            } else {
                $result = $content;
            }
        } else {
            $string = preg_replace("/<img[^>]+\>/i", " ", $data_long);
            $content = htmlspecialchars_decode(stripslashes($string), ENT_QUOTES);
            $strLength = strlen($content);
            if ($strLength > $size) {
                $result = substr(strip_tags($content), 0, $size) . "...";
            } else {
                $result = $content;
            }
        }

        return $result;
    }
}
if(!function_exists('correctText'))
{
    function correctText($data){
        $data = nl2br(htmlspecialchars_decode(stripslashes($data), ENT_QUOTES));
        return $data;
    }
}
if(!function_exists('calculate_age'))
{
    function calculate_age($date){
        list($day, $month, $year) = explode("-",$date);
        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
        if ($day_diff < 0 && $month_diff==0) $year_diff--;
        if ($day_diff < 0 && $month_diff < 0) $year_diff--;
        return $year_diff;
    }
}
if(!function_exists('create_session'))
{
    function create_session($id, $fname, $code, $email, $image){
        $_SESSION['userData'] = array();
        $_SESSION['userData']['id'] = $id;
        $_SESSION['userData']['name'] = $fname;
        $_SESSION['userData']['img'] = $image;
        $_SESSION['userData']['auth_code'] = $code;
        $_SESSION['userData']['email'] = $email;
    }
}
if(!function_exists('end_session'))
{
    function end_session(){
        foreach($_SESSION['userData'] as $value){
            unset($value);
        }
        unset($_SESSION['userData']);
    }
}
if(!function_exists('relative_time'))
{
    function relative_time($ts)
    {
        if(!ctype_digit($ts))
            $ts = strtotime($ts);

        $diff = time() - $ts;
        if($diff == 0)
            return 'now';
        elseif($diff > 0)
        {
            $day_diff = floor($diff / 86400);
            if($day_diff == 0)
            {
                if($diff < 60) return 'just now';
                if($diff < 120) return '1 minute ago';
                if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                if($diff < 7200) return '1 hour ago';
                if($diff < 86400) return floor($diff / 3600) . ' hours ago';
            }
            if($day_diff == 1) return 'Yesterday';
            if($day_diff < 7) return $day_diff . ' days ago';
            if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
            if($day_diff < 60) return 'last month';
            return date('F Y', $ts);
        }
        else
        {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0)
            {
                if($diff < 120) return 'in a minute';
                if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
                if($diff < 7200) return 'in an hour';
                if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
            }
            if($day_diff == 1) return 'Tomorrow';
            if($day_diff < 4) return date('l', $ts);
            if($day_diff < 7 + (7 - date('w'))) return 'next week';
            if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
            if(date('n', $ts) == date('n') + 1) return 'next month';
            return date('F Y', $ts);
        }
    }
}
if(!function_exists('getBatch'))
{
    function getBatch($page){
        $test = 5;
        $batch =1;
        $flag = false;
        while(!$flag){
          if($page > $test){
              $test = $test + 5;
              $batch++;
          }else{  
              $flag = true;
          }
      }
      return $batch;
    }
}
if(!function_exists('clean'))
{
    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = strtolower($string); // Convert to lowercase

        return $string;
    }
}
if(!function_exists('compare_search_type'))
{
    function compare_search_type($array, $value){
        $len = count($array);
        $result = null;
        for($m = 0;$m < $len; $m++){
    //echo $array[$m]."-".$value.'<br/>';
           if(strcmp($array[$m],$value) == 0){
               $result = $array[$m];
               break;
           }
       }
       return $result;
   }
}
if(!function_exists('encode'))
{
   function encode($param){
        $new_result = $param;
        if($param != null){
            $result = clean($param);
            $new_result = rawurlencode($result);
        }
        return $new_result;
    }
}
if(!function_exists('decode'))
{
    function decode($param){
        $new_result = $param;
        if($param != null){
           $new_result = str_replace('-',' ',$param);
       }
       return $new_result;
    }
}
if(!function_exists('disease_bracket'))
{
    function disease_bracket($alias){
        $text= '';
        if($alias != null && $alias != ''){
           $text = "(".$alias.")";
       }
       return $text;
    }
}
if(!function_exists('isSelected'))
{
    function isSelected($real_value, $data){
        $text = "";
        if($real_value == $data){
           $text = " selected";
       }
       return $text;
    }
}
if(!function_exists('checkGet'))
{
    function checkGet(){
        $result = '?';
        $q = $_SERVER['QUERY_STRING'];
        if($q != null){
           $result = '&';
       }
       return $result;
    }
}
if(!function_exists('checkUrl'))
{
    function checkUrl($N_url,$param,$value){
        if(strpos($N_url,'?') || strpos($N_url,'&')){
            $url = $N_url."&".$param."=".$value;
        }else{
            $url = $N_url."?".$param."=".$value;
        }
        return $url;
    }
}
if(!function_exists('curPageName'))
{
    function curPageName() {
        /*     return substr($_SERVER["REQUEST_URI"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); */
        $url ="http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        return $url;
    }
}
if(!function_exists('defaultPic'))
{
    //BILLY - MPJ - function to display default image on categories.php
    function defaultPic($catID,$defaultIMG,$desiredIMG){
        if($catID == null || $catID == ''){
            return $defaultIMG;
        }else{
            return $desiredIMG;
        }
    }
}
if(!function_exists('tab'))
{
    function tab($n) {
        $tabs = null;
        while ($n > 0) {
            $tabs .= "\t";
            --$n;
        }
        return $tabs;
    }
}
if(!function_exists('checkImage1'))
{
    function checkImage1($photoThmb,$brandThmb) {
        if ($photoThmb == NULL) {
            $temp = "$brandThmb";
        } else {
            $temp = "$photoThmb";
        }
        return $temp;
    }
}
if(!function_exists('nowDate'))
{
    function nowDate() {
        $date = date_create("", timezone_open('Asia/Jakarta'));
        $date = date_format($date, 'Y-m-d');
        return $date;
    }
}
if(!function_exists('nowDateComplete'))
{
    function nowDateComplete() {
        $date = date_create("", timezone_open('Asia/Jakarta'));
        $date = date_format($date, 'jS F Y - g:i A');
        return $date;
    }
}
if(!function_exists('check_input'))
{
    //Function To check and secure the login info from hacker attack
    function check_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = addslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES);
        return $data;
    }
}
if(!function_exists('getDocType'))
{
    //Function get the extension of the document source
    function getDocType($data) {
        $data = substr($data, -5);
        $pos = strpos($data, ".");

        if ($pos !== false) {
            $data = substr($data, $pos + 1);
            if ($data == "pdf" || $data == "doc" || $data == "docx") {
                return $data;
            } else {
                return "link";
            }
        } else {
            return "link";
        }
    }
}
if(!function_exists('checkLink'))
{
    //Function to check "http://"
    function checkLink($data) {
        $preFix = substr($data, 0, 4);
        if ($preFix != 'http') {
            $data = "http://" . $data;
        }
        return $data;
    }
}
if(!function_exists('checkHref'))
{
    //Function to check "http://"
    function checkHref($data) {
        $result = "";
        
        if ($data != '') {
            $result = $data;
        } else {
            $result = "#";
        }
        return $result;
    }
}
if(!function_exists('correctDisplay'))
{
    function correctDisplay($data) {
        $data = htmlspecialchars_decode(stripslashes($data), ENT_QUOTES);
        return $data;
    }
}
if(!function_exists('encodeURLslash'))
{
    function encodeURLslash($data) {
        $data = str_replace("/", "&slash", $data);
        return $data;
    }
}

if(!function_exists('decodeURLslash'))
{
    function decodeURLslash($data) {
        $data = str_replace("&slash", "/", $data);
        return $data;
    }
}
if(!function_exists('encodeURLspace'))
{
    function encodeURLspace($data) {
        $data = str_replace(" ", "%20", $data);
        return $data;
    }
}
if(!function_exists('curPageURL'))
{
    function curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}
?>