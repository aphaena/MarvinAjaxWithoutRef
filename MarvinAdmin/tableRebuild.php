<?php 
/**
	 * @see If mode = ''  learn semantics from the parameters column from lines rowId1 to rowId2 of the mastertable
	 * if mode =  'ref'  proceed to indexation too
	 * 5 knw:wikiknw.rebuild (< 11 title texte/> , <1 0/>, <3 999/>, <3 ref/>) ;
	 * reindex and reads all documents from rowid = 0 to rowId = 999.
	 * if mode = 'abstract'   rebuilds all the references of the knowledge according to the field  KNW_ABSTRACT of  the mastertable. 
	 * This is equivalent of reconstructing the reverse index.
	 * Generally it is preceeded by a CLEAR () command
	 * @param string $_col fields names ex: "title texte" 
	 * @param string $_rowIn 
	 * @param string $_rowOut
	 * @param string $_mode ref or abstract or learn
	 * 	ref = indexation + learn
	 * 	abstract = 
	 * 	learn = apprend sans indexer l'article
	 */
if(isset($_GET['knowledge'])) {$linked_knowledge = $_GET['knowledge']; } else { $linked_knowledge = "myknw";} // sans knowledge associée impossible de créer une table MASTER
if(isset($_GET['table'])) {$table = $_GET['table']; } else { $table = "myMasterTable";}
if(isset($_GET['IP'])) {$IP = $_GET['IP']; } else { $IP = "127.0.0.1";}
if(isset($_GET['port'])) {$port = $_GET['port']; } else { $port = "1254";}

if(isset($_GET['col'])) {$col = $_GET['col']; } else { $col = "title text";} // liste des champs à indexer
if(isset($_GET['rowIn'])) {$rowIn = $_GET['rowIn']; } else { $rowIn = "0";} // departure 
if(isset($_GET['rowOut'])) {$rowOut = $_GET['rowOut']; } else { $rowout = "10000";} //  arrival
if(isset($_GET['mode'])) {$mode = $_GET['mode']; } else { $mode = "";} // $mode = "ref";


require_once "ikmInterface.php"; // class_km_server.php needed   
$ikmInt = new ikmInterface;  
$ikmInt->connect($IP,$port);  
if( $ikmInt->KMServerGetisError()==true) {echo "connection error"; exit;}   
$ikmInt->KMId();  
try {
	$ikmInt->tableRebuild( $col, $rowIn, $rowOut, $mode );
	$ikmInt->closeSession(); 
}
	catch (Exception $e){
	if( $ikmInt->KMServerGetisError()==true) { echo $ikmInt->KMServerGetKMError();}
	$ikmInt->closeSession(); 
}
?>