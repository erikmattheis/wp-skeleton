<?PHP

// 
// * Assumes magic_quotes_gpc = Off in php.ini
// 
// Topic: Configuration Options
// 
// These variables can be manually edited in the PHP file if necessary.
// 
//   $enable_native - You can enable <Native requests>, but you should only do
//     this if you also whitelist specific URLs using $valid_url_regex, to avoid
//     possible XSS vulnerabilities. Defaults to false.
//   $valid_url_regex - This regex is matched against the url parameter to
//     ensure that it is valid. This setting only needs to be used if $enable_native 
//     is enabled. Defaults to '/.*/' which validates all URLs.
// 
// ############################################################################

$my_json_proxy = new Gsn_Json_Proxy();
$my_json_proxy->Run();

class Gsn_Json_Proxy {

  public function Run() {
  
    // Change these configuration options if needed, see above descriptions for info.
    $server_uri = $_SERVER['REQUEST_URI'];
    global $gsn_web_services;
    $api_base_url = $gsn_web_services->get_api_base_url().'/';
    
    // print $api_base_url;
    $url = str_replace('/proxy/', $api_base_url, $server_uri);
    // ############################################################################
    // Figure out requester's IP to shipt it to X-Forwarded-For
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    function gsn_get_headers() {
      if (function_exists('getallheaders')) {
        return getallheaders();
      }
      else if (function_exists('apache_request_headers')) {
        return apache_request_headers();
      }
      
      foreach($_SERVER as $key => $value) {
        if(substr($key, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
        } else if ($key == "CONTENT_TYPE") { 
             $headers["Content-Type"] = $value; 
        } else if ($key == "CONTENT_LENGTH") { 
           $headers["Content-Length"] = $value;
        } 
      }
      return $headers;
    }
    
    //header to curl shoud be in name:value format. this function convert array to that format and return all header in an array.
    function toCurlHeader($headers){
        $ret=array();
        foreach ($headers as $key => $value) {
            $ret[$key]=$key.":".$value;
        }
        return $ret;
    }

    //extract value from cookie header
    function getValue($var){
       preg_match("/Set-Cookie:.*?=(.*?);/is",$var,$restr); 
       if(count($restr)>=2){
        return $restr[1]; 
       }
       return "";
       
    }

    //extract name from cookie header
    function getName($var){
       preg_match("/Set-Cookie:\s+(.*?)=.*?;/is",$var,$restr); 
       if(count($restr)>=2){
        return $restr[1]; 
       }
       return "";
       
    }

    //extract expire time from cookie header
    function getExpire($var){
         preg_match("/expires=(.*);/i",$var,$restr);  
       if(count($restr)>=2){
        return (int)$restr[1]; 
       }
       return 0;
    }

    //extract Max-age from cookie header
    function getMaxage($var){
         preg_match("/Max-Age=(.*);/i",$var,$restr); 
       if(count($restr)>=2){
        return $restr[1]; 
       }
       return "";
    }

    //extract path from cookie header
    function getPath($var){
         preg_match("/path=(.*);?/i",$var,$restr); 
       if(count($restr)>=2){
        return $restr[1]; 
       }
       return "";
    }
    
    $ch = curl_init($url); 
    
    if ( strtolower($_SERVER['REQUEST_METHOD']) == 'post' ) {
      //curl_setopt($ch, CURLOPT_POSTFIELDS,file_get_contents("php://input"));
      curl_setopt( $ch, CURLOPT_POST, true );
      curl_setopt( $ch, CURLOPT_POSTFIELDS, $_POST );
    }
    
    $headers = gsn_get_headers();
    
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_HEADER, true );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_USERAGENT, $headers['User-Agent'] );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);

    $curlHeader = array();
    $arr = array('access_token', 'profile_id', 'shopping_list_id', 'site_id', 'store_id', 'Referer', 'Content-Type', 'Content-Length');
    foreach ($arr as $value) {
      if (array_key_exists($value, $headers)) {
        $curlHeader[$value] = $headers[$value];
      }
    }
    // add additional header
    $curlHeader['ip_address'] = $ip;
    $curlHeader['orig_host'] = $headers['Host'];
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, toCurlHeader($curlHeader) );
    
    $contents = curl_exec($ch); 
    
    curl_close($ch);
    //headers and body are mixed as a whole when returned by curl. this function seperate it into headers and body.
    list($header, $contents) = explode("\r\n\r\n", $contents, 2); 
    // Split header text into an array.
    $header_text = preg_split( '/[\r\n]+/', $header );
    // Propagate headers to response.
    foreach ( $header_text as $header ) {
      if ( preg_match( '/^(?:Content-Type|Content-Language|Set-Cookie):/i', $header ) ) {
        header( $header );
      }
    }
    echo $contents;//this is the body.*/
  }
}

?>
