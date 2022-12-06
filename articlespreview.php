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
//echo '<div id="buttonclosepreview alignleft"><img src="images/closered.gif" width="15" height="15" alt="close" /></div>';
if (strpos( $_SERVER['HTTP_USER_AGENT'], 'Firefox' ) !== FALSE) {$tmpsearchpreview = utf8_decode($_GET['qpreview']); }
else { $tmpsearchpreview =  utf8_decode($_GET['qpreview']); }

//echo $tmpsearch;
$sikm->initLasttime();
 include_once"surveil.php";
 //echo "GET[modepreview:]".$_GET['modepreview']."<br>";
if ($_GET['modepreview']=="") { $sikm->searchLikeGooglefornewsite(); }
if ($_GET['modepreview']=="1") { $sikm->searchLikeGooglefornewsite($tmpsearchpreview);} //$modestr = "<span class='display_mode'>procedural semantic base mode </span>"; }
if ($_GET['modepreview']=="2") { $sikm->searchLikeGoogleR($tmpsearchpreview);} //$modestr = "<span class='display_mode'>semantic mode with pertinence evaluation </span>";  }
if ($_GET['modepreview']=="3") { $sikm->searchStandard($tmpsearchpreview);} //$modestr = "<span class='display_mode'>classic procedural mode </span>";  }
if ($_GET['modepreview']=="similar") { $sikm->connexearticles($tmpsearchpreview);} //$modestr = "<span class='display_mode'>similar article mode </span>"; }

//echo "m:".$_GET['mode']." q:".$_GET['q']." pagestar:".$_GET['pagestart']."<br>";
if($sikm->KMResultsIsEmpty()) 
	{
		//$modestr = "<span red>mode changed for classic procedural mode </span>";		
		 $sikm->searchStandard($tmpsearchpreview); 
		 if($sikm->KMResultsIsEmpty()) { echo "<span class='smalltext'>no article found</span>";}		 
	}
	
	//echo $modestr;

	$ret = $sikm->readArrayKMserver();
	$display = new DisplayClass($ret);
	$countpreview = $display->aff_nb_articles();
	
	//$display->displaypagebuttons("", $count);
	if(isset($_GET['pagestart'])) { $pagestart = $_GET['pagestart']; } else {$pagestart = 1;}
	$sikm->formatResultNewSite("10", "title act link_wikifr ROWID KNW_LANGAGE KNW_MEANING link_wikieng texte", 1);
	$ret = $sikm->readArrayKMserver();
echo "<span class='querytime'> in ".$sikm->getTotalLastTime()." ms (".number_format(($sikm->getTotalLastTime()/1000),3)." seconde)</span>";	
echo "</div>";
	$display = new DisplayClass($ret);
	$display->aff_articles_preview();
	//$display->displaypagebuttons("", $count);


$sikm->closesession();

?>