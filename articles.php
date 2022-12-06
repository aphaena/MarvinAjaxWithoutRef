<?php

header('Content-Type: text/html; charset=ISO-8859-15'); 


include_once "connectIKM.php";
include_once "DisplayClass.php";
session_start();
$sikm = new searchikm();
//$sikm = unserialize($_SESSION["smarvin"]);
//$sikm = $_SESSION["smarvin"];

include "connectlang.php";

echo "<div class='bloc_query_infos'>";
if (strpos( $_SERVER['HTTP_USER_AGENT'], 'Firefox' ) !== FALSE) {$tmpsearch = utf8_decode($_GET['q']); }
else { $tmpsearch =  utf8_decode($_GET['q']); }

//echo $tmpsearch;
$sikm->initLasttime();
print_r("GET[mode:]".$_GET['mode']."<br>");
if ($_GET['mode']=="") { $sikm->searchLikeGooglefornewsite(); }
if ($_GET['mode']=="1") { $sikm->searchLikeGooglefornewsite($tmpsearch); } // $modestr = "<span class='display_mode'>procedural semantic base mode </span>"; }
//if ($_GET['mode']=="2") { $sikm->searchLikeGooglefornewsite2($tmpsearch); }// $modestr = "<span class='display_mode'>procedural semantic base mode </span>"; }
if ($_GET['mode']=="2") { $sikm->searchStandardxx($tmpsearch); $modestr = "<span class='display_mode'>semantic mode with pertinence evaluation </span>";  }
if ($_GET['mode']=="3") { $sikm->searchStandard($tmpsearch); } //$modestr = "<span class='display_mode'>classic procedural mode </span>";  }
if ($_GET['mode']=="similar") { $sikm->connexearticles($tmpsearch); } // $modestr = "<span class='display_mode'>similar article mode </span>"; }

// include_once"surveil.php";

//echo "m:".$_GET['mode']." q:".$_GET['q']." pagestar:".$_GET['pagestart']."<br>";
if($sikm->KMResultsIsEmpty()) 
	{
		$modestr = "<span >mode changed for classic procedural mode </span>";		
		 $sikm->searchStandard($tmpsearch); 
		 if($sikm->KMResultsIsEmpty()) { echo "<span class='smalltext'>no article found</span>";}		 
	}
	
	//echo $modestr;

	$ret = $sikm->readArrayKMserver();
	//echo "<br>DisplayClass:";
	//var_dump($ret);
	//echo "<br>";
	$display = new DisplayClass($ret);
	$count = $display->aff_nb_articles();

	
	$display->displaypagebuttons("", $count);
	if(isset($_GET['pagestart'])) { $pagestart = $_GET['pagestart']; } else {$pagestart = 1;}
	$sikm->formatResultNewSite("20", "title act link_wikifr ROWID KNW_LANGAGE KNW_MEANING link_wikieng texte", $pagestart);
	$ret = $sikm->readArrayKMserver();

//echo "<span class='querytime'> in ".$sikm->getTotalLastTime()." ms (".number_format(($sikm->getTotalLastTime()/1000),3)." seconde)</span>";	
echo "</div>";
	$display = new DisplayClass($ret);
	$display->aff_articles();
	//$display->displaypagebuttons("", $count);
	
/*
echo "<br>DisplayClass:";
echo "<pre>";
print_r($ret);
echo "</pre>";
*/

//$sikm->closesession(); 
?>


