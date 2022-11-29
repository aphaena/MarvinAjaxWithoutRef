
<?PHP

$url = $_GET['url'];
//$url = str_replace( " " ,"_", $url);

$title = utf8_decode($_GET['title']);
$title = str_replace( " " ,"_", $title);
$title = urlencode($title);
$redirect = $_GET['redirect'];

$wikilink = $url.$title;
$url = $wikilink;
//echo $url."<br>";

//$result_search = file_get_contents($url);
$result_search = disguise_curl($url);

$result_search =  preg_replace('@<script[^>]*?>.*?</script>@si', '', $result_search);

$result_search = str_replace( 'href="/wiki/','target="_blank" href="http://fr.wikipedia.org/wiki/', $result_search  );

$result_search = str_replace( 'href="#','xhref="#', $result_search  );

$result_search = str_replace('<div id="p-logo">', '<div id="p-logo"><span id="closepreview"><img src="images/closebut.jpg" width="35" height="35"/></span> ', $result_search);


echo utf8_decode($result_search);

function get_url($url) { 
   $curl = curl_init($url); 
   curl_setopt($curl, CURLOPT_POST, false ); 
   curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1); //do a binary transfer
	//curl_setopt($curl, CURLOPT_FAILONERROR, 1); //stop if an error occurred
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
   curl_setopt($curl, CURLOPT_HEADER, true); 
   $page = curl_exec($curl); 
   $error = curl_errno($curl); 
   curl_close($curl); 
 
   if ($error == 0) return $page; 
   return false;  
}  

 function fetch_page($url, $host_ip = NULL) 
    { 

      $ch = curl_init(); 

      if (!is_null($host_ip)) 
      { 
        $urldata = parse_url($url); 

        //  Ensure we have the query too, if there is any... 
        if (!empty($urldata['query'])) 
          $urldata['path'] .= "?".$urldata['query']; 

        //  Specify the host (name) we want to fetch... 
        $headers = array("Host: ".$urldata['host']); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 

        //  create the connecting url (with the hostname replaced by IP) 
        $url = $urldata['scheme']."://".$host_ip.$urldata['path']; 
      } 

      curl_setopt($ch,  CURLOPT_URL, $url); 
      curl_setopt ($ch, CURLOPT_HEADER, 0); 
      curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true); 

      $result = curl_exec ($ch); 
      curl_close ($ch); 

      return $result; 
    } 
	
function disguise_curl($url) 
{ 
  $curl = curl_init(); 

  // Setup headers - I used the same headers from Firefox version 2.0.0.6 
  // below was split up because php.net said the line was too long. :/ 
  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,"; 
  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"; 
  $header[] = "Cache-Control: max-age=0"; 
  $header[] = "Connection: keep-alive"; 
  $header[] = "Keep-Alive: 300"; 
  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
  $header[] = "Accept-Language: en-us,en;q=0.5"; 
  $header[] = "Pragma: "; // browsers keep this blank. 

  curl_setopt($curl, CURLOPT_URL, $url); 
  curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)'); 
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
  curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com'); 
  curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); 
  curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($curl, CURLOPT_TIMEOUT, 10); 

  $html = curl_exec($curl); // execute the curl command 
  curl_close($curl); // close the connection 

  return $html; // and finally, return $html 
} 


?>