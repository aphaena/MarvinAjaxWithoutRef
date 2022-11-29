<?php

include_once "connectIKM.php";
include_once "display.php";
 session_start();
$sikm = new searchikm();
//$sikm = unserialize($_SESSION["smarvin"]);
//$sikm = $_SESSION["smarvin"];

include "connectlang.php";

if (strpos( $_SERVER['HTTP_USER_AGENT'], 'Firefox' ) !== FALSE) {$tmpsearch = utf8_decode($_GET['q']); }
else { $tmpsearch = utf8_decode($_GET['q']); }

if($_GET['sugestion']=='categories') 
{
	 $sikm->searchAttractor($tmpsearch); 	
	returnjsontab($sikm);
}

if($_GET['sugestion']=='semanticspace') 
{
	$sikm->searchFromSpace($tmpsearch); 	
	returnjsontab($sikm);
			
}

if($_GET['sugestion']=='mixsem') 
{
	 $sikm->mixSem($tmpsearch); 	
	 returnjsontab($sikm);
}

if($_GET['sugestion']=='searchshape') 
{
	 $sikm->searchShape($tmpsearch); 		 
	 returnjsontab($sikm);
}

function returnjsontab($sikm) 
{
	$ret = $sikm->readArrayKMserver();
	
	$tablocution =  array();
	foreach ( $ret[0]["results"] as $locution )
	{
		array_push($tablocution, $locution[0]);
	}
	
	
	$ret = json_encode($tablocution);
	echo $ret;
	
}



//$sikm->closesession();


?>