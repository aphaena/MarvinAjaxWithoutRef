<?php


require_once "ikmInterface.php";

$ikmInt = new ikmInterface;

$ikmInt->connect("192.168.0.12","1259");
if( $ikmInt->KMServerGetisError()==true) {echo "connection error"; exit;} 

	
//$ikmInt->knowledge("wikiknw");	
$ikmInt->KMId();
$ikmInt->sessionSetKnowledge("wikiknw"); // connaissance par défault
$ikmInt->contextClear();
 	displayInfos(  $ikmInt );

$ikmInt->resultClear();
 	displayInfos(  $ikmInt );

$ikmInt->contextNew(); // crééer un nouveau context
	displayInfos(  $ikmInt );

$ikmInt->contextSetKnowledge("wikiknw"); // connaissance pour chaque contextNew

$ikmInt->contextAddElement("muon"); 
$ret = $ikmInt->KMResults();	// renvoie le tableau KMResults
echo "<pre>"; print_r($ret); echo "</pre>";
	displayInfos(  $ikmInt );
	
$ikmInt->contextNewFromSem("0","-1","-1");	//exemple de fonction sémantique
$ikmInt->contextGetElements(); // copie les élements  dans kmresults
echo "<pre>"; print_r( $ikmInt->KMResults()); echo "</pre>";
displayInfos(  $ikmInt );

$ikmInt->closeSession(); // fermeture de la session


function displayInfos( ikmInterface $ikmInt )
{	
	echo  "Send:           ".$ikmInt->KMServerGettoSend()."<br>";
	echo  "Receive:        ".$ikmInt->KMServerGetReceived()."<br>";
	echo "Execution Time:  ".$ikmInt->sessionGetLastTime()."<br>"; // ATTENTION  la fonction Lasttime renvoie ses résultats dans KMResults. Il faut toujours l'utiliser après avoir récupéré les résultats qui vous intéresse dans KMResults.
}
?>