<?php

include "connectIKM.php";

$sikm = new searchikm();

$sikm->connectIKM_FR();

echo $sikm->querylocutization("mer noire et tautou caspienne  audrey " );
echo "<br><br>";
$sikm->listContextsArticles("electron");

/*
	$ret = $sikm->readArrayKMserver();

	$sikm->formatResultNewSite("200", "ROWID title act link_wikifr  KNW_LANGAGE KNW_MEANING", 1);
	$ret = $sikm->readArrayKMserver();
		echo "<span class='querytime'> in ".$sikm->getTotalLastTime()." ms (".number_format(($sikm->getTotalLastTime()/1000),3)." seconde)</span>";	
	echo "</div>";
	
	echo "<pre>";
	print_r($ret);
	echo "</pre>";
	


$sikm->closesession();
*/

/*
echo "<pre>";
print_r($ikmInt->sessionGetId());
echo "</pre>";
*/
?>