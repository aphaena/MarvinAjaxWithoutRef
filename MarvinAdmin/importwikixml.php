<?php

require_once "ikmInterface.php"; // class_km_server.php needed   
$ikmInt = new ikmInterface;
$IP = "127.0.0.1";
$port = "1254";
$ikmInt->connect($IP,$port);  
if( $ikmInt->KMServerGetisError()==true) {echo "connection error"; exit;}   
$ikmInt->KMId();  

try {

     //Creating an XMLReader
   $reader = new XMLReader();

   //Opening a reader
   $reader->open("wiki.xml");

   //Reading the contents of XML document
   $reader->read(); 
   $reader->read();
   $reader->next("page");

   //Reading the contents
   print($reader->name."\n");
   print($reader->readString());

   //Closing the reader
   $reader->close();

	//$ikmInt->tableInsert( $fielddatas, $table );
	//$ikmInt->closeSession(); 
}
	catch (Exception $e){
	if( $ikmInt->KMServerGetisError()==true) {
         echo $ikmInt->KMServerGetKMError();
        }
	$ikmInt->closeSession(); 
}




?>