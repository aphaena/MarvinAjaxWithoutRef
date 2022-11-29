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
	 if($_GET['contextualize']=='true') {$sikm->contextPartition();  display_contexts($sikm);}
else { display_simple($sikm);}
}

if($_GET['sugestion']=='semanticspace') 
{
	/*
	echo "<div class='cadrecontextcolor1' >";
	 $sikm->searchShape($tmpsearch); 
		display_simple($sikm);
		echo "</div>";
	*/	
		
	echo "<div class='cadrecontextcolor2' >Composite";
	 $sikm->searchAttractor($tmpsearch); 	
	display_simple($sikm);
	echo "</div>";		


	echo "<div class='cadrecontextcolor1' >Connection";
	 $sikm->searchFromSemNew($tmpsearch); 
	 if($_GET['contextualize']=='true') {$sikm->contextPartition();  display_contexts($sikm);}
		else { display_simple($sikm);}
	echo "</div>";
				
	echo "<div class='cadrecontextcolor2' >Advanced";
	 $sikm->searchFromSpace($tmpsearch);
	 $space = (array) $sikm; 
	 if($_GET['contextualize']=='true') {$sikm->contextPartition();  display_contexts($sikm);}
		else 
		{
			//$spacetmp = array_diff($space["s"]["KMResults"][0]["results"][0], $attractor["s"]["KMResults"][0]["results"][0]);
		   display_simple($sikm);
		}
				echo "</div>";
		/*		
	echo "<div class='cadrecontextcolor2' >";
	 $sikm->searchFromSemCat($tmpsearch); 
	 if($_GET['contextualize']=='true') {$sikm->contextPartition();  display_contexts($sikm);}
		else { display_simple($sikm);}
				echo "</div>";
*/

/*
	echo "<div class='cadrecontextcolor2' >";
	 $sikm->searchFromSemTags($tmpsearch); 
	 if($_GET['contextualize']=='true') {$sikm->contextPartition();  display_contexts($sikm);}
		else { display_simple($sikm);}
				echo "</div>";
												
	echo "<div  class='cadrecontextcolor1' >mixSem";
	 $sikm->mixSem($tmpsearch); 
	 if($_GET['contextualize']=='true') {$sikm->contextPartition();  display_contexts($sikm);}
		else { display_simple($sikm);}
	  echo "</div>";
*/
}

if($_GET['sugestion']=='mixsem') 
{
	 $sikm->mixSem($tmpsearch); 
	 if($_GET['contextualize']=='true') {$sikm->contextPartition();  display_contexts($sikm);}
else { display_simple($sikm);}
}

if($_GET['sugestion']=='searchshape') 
{
	 $sikm->searchShape($tmpsearch); 
	 if($_GET['contextualize']=='true') {$sikm->contextPartition();  display_contexts($sikm);}
else { display_simple($sikm);}
}



function display_simple($sikm)
{
	$ret = $sikm->readArrayKMserver();
	$display = new display($ret);

	$display->horizontal_elements();
}

function display_contexts($sikm)
{
	$ret = $sikm->readArrayKMResultsMultiContent();
	$display = new display($ret);
	$display->aff_partition();	
}

//$sikm->closesession();


?>