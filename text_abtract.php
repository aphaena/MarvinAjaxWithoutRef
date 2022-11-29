<?php

include_once "connectIKM.php";
include_once "display.php";
session_start();
$sikm = new searchikm();
//$sikm = unserialize($_SESSION["smarvin"]);
//$sikm = $_SESSION["smarvin"];

include "connectlang.php";

$sikm->initLasttime();

$query = utf8_decode($_GET['q']); 


$ret = $sikm->Stream("wikimaster2",  $_GET['rowid'], "texte");

//$query = str_replace("_", " ", $query );
//$query = $sikm->mixword($query);
$query = $sikm->FILTERVOIDELEMENTS2String( $query);

/*
echo "<pre>";
echo $query."<br>";
print_r($kmresults);
echo "</pre>";
*/

$retmodifs="";

foreach (explode(" ",$query) as $word) // pour remplacer le hightlight javascript trop lent
{
	 //$ret = str_ireplace(utf8_decode($word), '<span class="hls"><b>'.utf8_decode($word).'</b></span>', $ret );
	 $retmodifs = highlight($word, $ret);
	 $ret = $retmodifs;
}
$retmodifs = str_replace(".", ".<br><br>", $retmodifs);


echo "<span id='cadre_text_abtract' class='cadre_text_abtract'>".$retmodifs."</span>";


function highlight($needle, $haystack){ 
    $ind = stripos($haystack, $needle); 

		$len = strlen($needle); 
		if($ind !== false){ 
			return substr($haystack, 0, $ind)."<span class='hls'><strong>".substr($haystack, $ind, $len)."</strong></span>" . 
				highlight($needle, substr($haystack, $ind + $len)); 
		} else return $haystack; 
} 
?>