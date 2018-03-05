<?php 

function get_web_page( $url,$curl_data ) 
{ 
    $options = array( 
        CURLOPT_RETURNTRANSFER => 1,         // return web page 
       CURLOPT_HEADER         => false,        // don't return headers 
//        CURLOPT_FOLLOWLOCATION => true,         // follow redirects 
//        CURLOPT_ENCODING       => "",           // handle all encodings 
        CURLOPT_USERAGENT      => "spider",     // who am i 
//        CURLOPT_AUTOREFERER    => true,         // set referer on redirect 
//        CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect 
//        CURLOPT_TIMEOUT        => 120,          // timeout on response 
//        CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects 
        CURLOPT_POST            => 1,            // i am sending post data 
        CURLOPT_POSTFIELDS     => $curl_data,    // this are my post vars 
//        CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl 
//        CURLOPT_SSL_VERIFYPEER => false,        // 
//        CURLOPT_VERBOSE        => 1,                // 
        CURLOPT_URL => $url
    ); 

    $ch      = curl_init(); 
    curl_setopt_array($ch,$options); 
    $content = curl_exec($ch); 
    $err     = curl_errno($ch); 
    $errmsg  = curl_error($ch) ; 
    //$header['code']  = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    $header['code']  = curl_getinfo($ch); 
    curl_close($ch); 

    $header['errno']   = $err; 
    $header['errmsg']  = $errmsg; 
    $header['content'] = $content; 
    return $header; 
} 

$curl_data = "var1=60&var2=test"; 
$url = "http://www.edu-china.com"; 
//$url = "http://jd.com"; 
$response = get_web_page($url,$curl_data); 

print '<pre>'; 
print_r($response); 
