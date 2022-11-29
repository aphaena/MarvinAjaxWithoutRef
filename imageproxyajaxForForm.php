<?php

//$googleBase = file_get_contents("http://www.google.com");
//$googleBase = str_replace("images/","http://www.google.com/images/" , $googleBase);



include 'connectIKM.php';
session_start();

//require_once '/BingAPI.php';
//$bingAPI = new BingAPI('DAADD141DB925B86AF08BEC3D22288B0306FA73A');


$key ="ABQIAAAAFY4fNr4mQijaXbFlTW3W4hQW3G7vrT6Ta9HC_Dd7DYpCQyLKdhTPDCqnNpzjuXW2bJZsrWzeNa3I8w";
$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&"
    . "q=ours%20Hilton&key=$key&userip=88.189.240.38&rsz=filtered_cse";

     
if(isset($_GET['lang'])) 
{
	$lang = $_GET['lang'];
	if($lang=="eng") $lang="en";

} 
else
{
	$lang = "en";
}

$googlLang="com";

$search_orig = utf8_decode($_GET['qo']);
	
	$sikm = new searchikm();
//	$sikm = unserialize($_SESSION["smarvin"]);
//	$sikm = $_SESSION["smarvin"];
			//$_SESSION['$sikm'] = $sikm;
	include "connectlang.php";
	 		
//echo Request.ServerVariables("HTTP_ACCEPT_LANGUAGE");
//echo $_SERVER["HTTP_ACCEPT_LANGUAGE"];				

			//$searchn = str_replace(" ","%20",$_GET['q']);     urlencode(urldecode(
			$searchn = utf8_decode($_GET['q']);
			
						$returnImages = searchGoogleImageAPI($searchn,$lang,$googlLang,$search_orig,$sikm,$key, $url);
						if($returnImages!="") echo $returnImages;
						else
						{
						$returnImages = searchGoogleImageAPI($searchn,$lang,$googlLang,"",$sikm, $key, $url);
						if($returnImages!="") {echo "<div class='cadre4 rouge'><b>Possibly semantically ambiguous</b>"; echo $returnImages; echo "</div>";}					
						}	
					



function DOMinnerHTML($element) 
{ 
    $innerHTML = ""; 
    if(isset($element->childNodes))
    {
    $children = $element->childNodes; 
    foreach ($children as $child) 
    { 
        $tmp_dom = new DOMDocument(); 
        $tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
        $innerHTML.=trim($tmp_dom->saveHTML()); 
        //echo $innerHTML;
    } 
    return $innerHTML;
    }
    else {
    	return "Nothing Found";
    } 
} 


function searchGoogleImageAPI($searchn, $lang,$googlLang, $search_orig, $sikm, $key, $url)
{

	$resultdisplay =  '<div class="cadre cadrepreviewin">';	
	$resultdisplay .= '<div class="toolbarpreview">';
	$resultdisplay .= '<span id="buttonclosepreview"><img src="images/closered.gif" width="12" height="12" alt="close" /></span>';
	$resultdisplay .= '</div>';
	$resultdisplay .= '<div class="resultwebpreview">';

		if (strpos( $_SERVER['HTTP_USER_AGENT'], 'Firefoxxxxxx' ) !== FALSE) // en cas d'eeerreur avec firefox
		{
			//echo "firefox is bull cheat ".$_SERVER["HTTP_USER_AGENT"];
		
			$sikm->searchSemIntersection($search_orig,$searchn);
			$semIntersection = $sikm->readArrayKMserver();
			$searchn = utf8_decode($searchn);
			$search_orig = utf8_decode($search_orig);
			//echo '<br><b>"'.utf8_decode(str_replace($_SESSION["strsearch"],"",urldecode($searchn))).' " is linked with " '.$_SESSION["strsearch"].' " by: </b>';
			//$resultdisplay.= '<span class="smalltext grey">Marvin Infos: "'.str_replace("_"," ",$searchn).' " linked with " '.$search_orig.' ": </span>';
			
			//$searchn=utf8_decode($searchn);		
		}	
		else 
		{
		
			//echo "Chrome or Explorer ".$_SERVER["HTTP_USER_AGENT"];
			$sikm->searchSemIntersection($search_orig,$searchn);						
			$semIntersection = $sikm->readArrayKMserver();	
			//print_r($semIntersection);
			//echo '<br><b>"'.urldecode(str_replace($_SESSION["strsearch"],"",urldecode($searchn))).' " is linked with " '.$_SESSION["strsearch"].' " </b>';
			
			
			//$resultdisplay.=  '<span class="smalltext grey">Marvin Infos: "'.str_replace("_"," ",$searchn).'" linked with "'.$search_orig.'": </span>  ';
			
		}
		/* supprimer pour la démo
		$resultdisplay.='<span id="linked_with" class="smalltext orange">';
		if(isset($semIntersection[0]["results"][0]))
		{
			foreach ($semIntersection[0]["results"] AS $links)
			{
				$resultdisplay.=  "  <i> ".str_replace("_"," ",$links[0])." </i> .";	
			} 
			
		}			
		$resultdisplay.='</span>';
		*/
		/*
		$resultdisplay.=  "<br><br>";
		$resultdisplay.= 'Search with ';
		$resultdisplay.=  '<span class="cadre5"><a href="/index.php?textboxsearch='.$search_orig." ".$searchn.'&modepage=1" target="_blank" ><img src="../images/wikiicon.jpg" /> </a></span>';
		$resultdisplay.=  '<span class="cadre5"><a href="/index.php?textboxsearch='.$search_orig." ".$searchn.'&modepage=9" target="_blank" ><img src="../images/googleicon.jpg" /> </a></span>';		
		$resultdisplay.=  '<br><span> Search Image with marvinbot for ';
		$resultdisplay.=  '<a href="/index.php?textboxsearch='.$searchn.'&modepage=10">  <b>'.$searchn.'</b></a></span>';
		*/
		if($search_orig!="")
		{
		//$resultdisplay.=  ' or for ';
		//$resultdisplay.=  '<span><a href="/index.php?textboxsearch='.$search_orig." ".$searchn.'&modepage=10">  <b>'.$search_orig." + ".$searchn.'</b></a></span>';
		}		
		$opt = "";		
		//$searchadded = "%".str_replace("_","%20",$searchn)."%20".str_replace("_","%20",$search_orig);
//		$searchadded = "%22".str_replace("_","%20",$searchn)."%22%20+%20".str_replace("_","%20",$search_orig);
		$searchadded = $searchn." ".$search_orig;
		$searchadded = $searchadded;
//		echo "searchn=".$searchn." search_orig=".$search_orig." searchadded=".$searchadded."<br>";
		//$searchadded = "epee%20sabre";
		
		//$jsonTab = ImageBingApisearch( $searchadded, $bingAPI);
$jsonTab = google_search_api( array(
		'q' =>  utf8_encode($searchadded),
		'start'=> '0',			
		'lr'=>'lang_'.$lang,
		'key' => $key,		
		'hl' => $lang,	
 ),"","images");

		
		$count=0;
		$nbimage=0;
		//$json->SearchResponse->Image->Results[0]->Thumbnail->Url
		//<img src="http://www.allhtml.com/gif/logoalltml.gif">
		
		$countresults = 0;
		if(isset($jsonTab->responseData->results)) {
			foreach ($jsonTab->responseData->results as $results) 
			{ 			
				if($countresults>2) break;
				$countresults++;
			$image ='<img class="cadre2 cadreimage" src="'.$results->tbUrl.'">';	
			$resultdisplay.= '<span class="smalltext image"> <a target="_blank" href="http://www.google.com/images?q='.utf8_encode(str_replace('_','%20',$searchn)).'+'.utf8_encode(str_replace('_','%20',$search_orig)).'">'.$image.'</a></span>';
			 $count++; $nbimage++; 								   					
			}
		}
			
			//$resultdisplay.= "<div class='cadre2'>";
			//echo "xxxxxxxxxxx ".urldecode($searchadded);		
// $resultdisplay.=  "<div class='smalltext italic'> results with google.".$lang."</div> ";
$jsonTab = google_search_api( array(
		'q' => utf8_encode($searchadded),	
		'start'=> '0',			
		'lr'=>'lang_'.$lang,
		'key' => $key,		
		'hl' => $lang,

 ),"","web");			
 		/*
		$resultdisplay.= "<pre>";
		$resultdisplay.= print_r($jsonTab );
		$resultdisplay.= "</pre>";
		*/
 		$countresults = 0;
		//$resultdisplay.= $searchadded;
		if(isset($jsonTab->responseData->results)) 
		{
			foreach ($jsonTab->responseData->results as $results)
			{			
				if($countresults>10) break;
				$countresults++;
				$resultdisplay.= "<div class='smalltext'><a target='_blank' href='".urldecode($results->url)."'>".utf8_decode($results->title)."</a></div>";
				
				if(isset($results->content))
				{
					$strtmp = str_replace( "<div ","",utf8_decode($results->content));
					$strtmp = str_replace( "</div>","",$strtmp);
					$strtmp = str_replace( "<span ","",$strtmp);
					$strtmp = str_replace( "</span>","",$strtmp);
	     			$resultdisplay.= "<span class='smalltext grey'>".$strtmp."</span>";
				}
				
			}
		}
	$resultdisplay.=  '<div class="smalltext green"><a href="http://www.google.com/search?q='.urldecode($searchadded).'" target="_blank" >more results for '.urldecode($searchadded).' </a> </div>';	
	$resultdisplay.=   '</div></div>';
	if($nbimage!=0) return $resultdisplay;
	
	 return "";

}			



    
function google_search_api( $args, $referer='http://www.marvinbot.com' , $endpoint = 'images'){
	$url = "http://ajax.googleapis.com/ajax/services/search/".$endpoint;
 
	if ( !array_key_exists('v', $args) ) $args['v'] = '1.0';
 
	$url .= '?'.http_build_query($args, '', '&');
 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// note that the referer *must* be set
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	$body = curl_exec($ch);
	curl_close($ch);
	//decode and return the response
	return json_decode($body);
}


?> 

