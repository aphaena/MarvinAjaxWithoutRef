<?php

include_once "connectIKM.php";
include_once "display.php";
session_start();

if(isset($_SESSION["testglobal"])) $_SESSION["testglobal"]=$_SESSION["testglobal"]+1;


$sikm = new searchikm();
//$sikm = unserialize($_SESSION["smarvin"]);
//$sikm = $_SESSION["smarvin"];

include "connectlang.php";

// q recoit le contenu du champ de recherche "textsearch"
 $tmpsearch = utf8_decode($_GET['q']);
 

 $tabsearch = explode(" ", $tmpsearch); 
 $tabsearch = array_unique($tabsearch);
 $tmpsearch = implode($tabsearch , " ");
// echo $tmpsearch;
 //echo " > ".$tabsearch[count($tabsearch)-1]." < ";
 $espace=false;
 $strsize=0;
 $lastchar = substr( $tmpsearch, strlen($tmpsearch)-1, 1);
 if ($lastchar == " " ) {$espace=true; }
 else {
	 // chercher le nombre de caractère frappé après le dernier espace.	
	  $strsize =  strlen(substr($tmpsearch, strripos($tmpsearch, " ")));
	 }
 
 // pour chaque mot on ajoute les suggestions dans un tableau
 // pour chaque locution on envoie la requete pour obtenir les suggestions pour chaque locution 
 //  le tableau retourne l"ensemble des locutions
 // par défault la requête mixsem utilise 3 fonction de base pour proposer des locutions  en attendant la nouvelle fonction de l'API.

	 
if($_GET['sugestion']=='categories') 
{
	 $sikm->searchAttractor($tmpsearch); 
	display_simple($sikm);
}

/*
GetBestWords

<query><max_shape><max_related><context_count> int n

<cel_id-1>

<cel_id_n>

<inhibited_count> int m

<cel_id-1>

<cel_id_m>

<hisrory_count> int p

<cel_id-1>

<cel_id_p> 

A Table with following columns
cel_Id « ;cel_string « ;       cel_state « ;       
		*/
		
if($_GET['sugestion']=='mixsem') 
{
	
	// si c'est le premier mot et que la locution en n'est pas validé > from shape
	// si la locution est validée > from attrator
	// 3) si le dernier caractère est un espace > fromSem
	// mémorisation du tableau fromSem pour garder le context
	// le mot suivant:
	// pour les  1er a 2eme et 3éme caractère (surtout pas fromShape)	
	// on trie le tableau en fonction des caractère saisie. Les mots qui match les caractères, sont en haut du tableau
	// pour le deuxième mot on recommence ligne 3 en prenant le context de l'ensemble de la requête
	
	//echo "session ".$_SESSION["testglobal"];
	
clearSessionTabs();	
if ($strsize > 3) 
{
}
else
{
	$_SESSION["tabmemebestswords"] = $sikm->readArrayKMserver();
}
	 $sikm->getbestwords($tmpsearch,"20","20",$_SESSION["tabcontext"],  $_SESSION["tabsuggested"], $_SESSION["tabhistory"]); 
		// $ret contient le tableau de results [1] locution [0] activity  [2] type
		$ret = $sikm->readArrayKMserver();
		 display_getbestwords($ret);	
		

		/*
		echo "<pre>";
		print_r($ret);
		echo "</pre>";	
		*/
		

				
		foreach ( $ret[0]["results"] as $locution )
		{
			//echo "x";
			if($locution[2] == " validated") { 
			array_push($_SESSION["tabvalidated"], $locution[1]); 
			//echo "V"; 
			}	
			if($locution[2] == "predicted") {
				 array_push($_SESSION["tabpredicted"], $locution[0]); 
			//	 echo "P";  
				 }	
			if($locution[2] == "suggested") { 
				array_push($_SESSION["tabsuggested"], $locution[0]);
	 			 //echo "S"; 
			 }	
			if($locution[2] == "context") { 
			array_push($_SESSION["tabcontext"], $locution[0]); 
			//echo "C"; 
			}	
			if($locution[2] == " unknown") { 
			array_push($_SESSION["tabunknown"], $locution[0]); 
			//echo "U";
			 }	
			if($locution[2] == "history") { 
			array_push($_SESSION["tabhistory"], $locution[0]); 
			//echo "H"; 
			}	
			if($locution[2] == "ininbited") {
				 array_push($_SESSION["tabininbited"], $locution[0]); 
			//	 echo "I"; 
			}										
			//echo "<pre>";
			//	print_r($locution[2]);
			//echo "</pre>";			
		}
		

	/*
			echo "<pre>";	
			print_r($_SESSION["tabvalidated"]);	
			echo "</pre>";
	*/
		
	 display_getbestwords($ret);
	 	clearSessionTabs();
}

if($_GET['sugestion']=='searchshape') 
{
	 $sikm->searchShape($tmpsearch); 	 
	display_simple($sikm);
}


function counTabElems($tab) 
{
	return count($tab);
}

function tab2String($tab)
{ 
	foreach ($tab as $idcel) 
	{
		$GPstr = $GPstr.",";
	}
	return $GPstr;
}



function clearSessionTabs()
{
	$_SESSION["tabvalidated"] = array();
	$_SESSION["tabpredicted"] = array();
	$_SESSION["tabsuggested"] = array();
	$_SESSION["tabcontext"] = array();
	$_SESSION["tabininbited"] = array();
	$_SESSION["tabhistory"] = array();
	$_SESSION["tabininbited"] = array();
}

function display_simple($sikm)
{
	$ret = $sikm->readArrayKMserver();
	//$ret_unique = array_unique($ret);
	$display = new display($ret);
	$display->comma_elements();
}

function display_getbestwords($ret)
{
	//$ret = $sikm->readArrayKMserver();
	//$ret_unique = array_unique($ret);
	$display = new display($ret);
	$display->getbestwords_elements();
}

//$sikm->closesession();


?>