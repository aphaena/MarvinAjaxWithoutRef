<?php 

if(isset($_GET['knowledge'])) {$linked_knowledge = $_GET['knowledge']; } else { $linked_knowledge = "myknw";} // sans knowledge associée impossible de créer une table MASTER
if(isset($_GET['table'])) {$table = $_GET['table']; } else { $table = "myMasterTable";}
if(isset($_GET['IP'])) {$IP = $_GET['IP']; } else { $IP = "127.0.0.1";}
if(isset($_GET['port'])) {$port = $_GET['port']; } else { $port = "1254";}
if(isset($_GET['param1'])) {$param1 = $_GET['param1']; } else { $param1 = "";}
if(isset($_GET['param2'])) {$param2 = $_GET['param2']; } else { $param2 = "";}
if(isset($_GET['type'])) {$type = $_GET['type']; } else { $type = "MASTER";} // MASTER or SINGLE
if(isset($_GET['fields'])) {$fields = $_GET['fields']; } else { $fields = "id INT64 , title STRING , text STRING , link STRING";}


$IP = "127.0.0.1";
$port = "1254";
$linked_knowledge = "knwtest2";
$param1 = "title";
$param2 = "text";
$table = "table1";

require_once "ikmInterface.php"; // class_km_server.php needed   
$ikmInt = new ikmInterface;  
$ikmInt->connect($IP,$port);  
if( $ikmInt->KMServerGetisError()==true) {echo "connection error"; exit;}   
$ikmInt->KMId();  
try {
$ikmInt->KnowledgeCreate($linked_knowledge); 
$ikmInt->TableCreate($table, $linked_knowledge);
$ikmInt->closeSession(); 
}
catch (Exception $e){
if( $ikmInt->KMServerGetisError()==true) { echo $ikmInt->KMServerGetKMError();}
$ikmInt->closeSession(); 
}
?>