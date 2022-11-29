<?php 
// http://127.0.0.1/IKM_PRJ/marvinajaxclean/MarvinAdmin/tableinsert.php?knowledge=knwtest&table=mytable&fielddatas=id;1235;title;le titre;text;ceci est un texte;link;http://www.marvinbot.com

if(isset($_GET['knowledge'])) {$linked_knowledge = $_GET['knowledge']; } else { $linked_knowledge = "myknw";} // sans knowledge associée impossible de créer une table MASTER
if(isset($_GET['table'])) {$table = $_GET['table']; } else { $table = "myMasterTable";}
if(isset($_GET['IP'])) {$IP = $_GET['IP']; } else { $IP = "127.0.0.1";}
if(isset($_GET['port'])) {$port = $_GET['port']; } else { $port = "1254";}
if(isset($_GET['fielddatas'])) {$fielddatas = $_GET['fielddatas']; } else { $fielddatas = "";} // ex: "id;1235;title;titre1;texte;qsd qsd qsdq sdqsdqsd;link;http://www.marvinbot.com"  

require_once "ikmInterface.php"; // class_km_server.php needed   
$ikmInt = new ikmInterface;  
$ikmInt->connect($IP,$port);  
if( $ikmInt->KMServerGetisError()==true) {echo "connection error"; exit;}   
$ikmInt->KMId();  
try {
	$ikmInt->tableInsert( $fielddatas, $table );
	$ikmInt->closeSession(); 
}
	catch (Exception $e){
	if( $ikmInt->KMServerGetisError()==true) { echo $ikmInt->KMServerGetKMError();}
	$ikmInt->closeSession(); 
}
?>