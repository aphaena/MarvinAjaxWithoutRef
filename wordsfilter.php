<?php
header('Content-Type: text/html; charset=ISO-8859-15'); 
// permet de retirer les doublons d'une string
include_once "connectIKM.php";
include_once "DisplayClass.php";
session_start();
$sikm = new searchikm();
//$sikm = unserialize($_SESSION["smarvin"]);
//$sikm = $_SESSION["smarvin"];

include "connectlang.php";

 $tmpsearch = utf8_decode($_GET['q']);
 

 $tabsearch = explode(" ", $tmpsearch); 
 $tabsearch = array_unique($tabsearch);
 $tmpsearch = implode(" ", $tabsearch );
 echo $tmpsearch;
?>