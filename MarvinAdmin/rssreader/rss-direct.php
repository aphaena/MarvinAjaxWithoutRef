<?php
session_name($_GET['sn']);
session_start();

if(isset($_GET['rssflux'])) $_SESSION['rssflux']= $_GET['rssflux'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <META HTTP-EQUIV="Refresh" CONTENT="10"; URL=http://127.0.0.1/IKM_PRJ/marvinajaxclean/MarvinAdmin/rssreader/rss-direct.php?rssflux=<?php echo $_GET['rssflux']."&sn=".$_GET['sn'] ?>">  -->
<title>Charger directement un flux RSS et l'afficher</title></head>
<link type="text/css" href="rss-style.css" rel="stylesheet">
	
<body bgcolor="#FFFFFF">
<h1>RSS 2.0 mARC sniffer</h1>
<hr>
<div id="zone" > Dernier article <?php echo $_GET['rssflux']; ?></div>

<br>
<fieldset class="rsslib">
<?php
	//echo "session_name=".session_name()."<br><br>";
	require_once("rsslib.php");
	
	$url = $_GET['rssflux'];
	// http://www.lemonde.fr/rss/une.xml
	//http://www.agoravox.fr/spip.php?page=backend&id_rubrique=27
	$_SESSION["article"] = RSS_Display($url, 1, false, false);
	$_SESSION["article"] = str_replace("\"", " " ,$_SESSION["article"]);
	$link = utf8_decode(RSS_Links($url, 1));
	echo "links:".$link." endlinks <br><br>";

	if( isset($_SESSION["memarticle"]) ) 
	{
		//echo 'isset($_SESSION["memarticle"])';
		if($_SESSION["memarticle"] != $_SESSION["article"] )
			{
				$_SESSION["memarticle"] = $_SESSION["article"];
				$_SESSION["memarticle2"] = strip_tags($_SESSION["memarticle"], "<a>");
				$_SESSION["memarticle2"] = str_replace("<a>", "<a> ", $_SESSION["memarticle2"]);
				
				echo "memarticle: ".$_SESSION["memarticle"];
				echo "nouvel article<br><br>";
				echo strip_tags($_SESSION["memarticle2"]); 
				$linkok = str_replace("\"", "" , strip_tags($link,'<a>'));
				$linkok = str_replace("<a href=", "" , $linkok);
				$linkok = str_replace("</a>", "" , $linkok);
				$titreok = str_replace("\"", " " ,strip_tags($link));
				$fielddatas = "id;".$url.";title;".$titreok.";text;".$_SESSION["memarticle2"].";link;".$linkok; 
				echo "<br><br>".$fielddatas."<br><br>";
				insertTable("127.0.0.1", "1254", "mytable", $fielddatas);
				//id;1235;title;le titre;text;ceci est un texte;link;http://www.marvinbot.com
			}
		else 
		{ 
			//echo $_SESSION["memarticle"];
		 	echo "article ancien";
			echo strip_tags($_SESSION["memarticle"]); 
		 }
	}
	else
	{
		$_SESSION["memarticle"] = $_SESSION["article"];
		echo $_SESSION["memarticle"]; 
	}
	//echo $_SESSION["memarticle"];
	//echo RSS_Display($url, 1, false, true);
	


	
function insertTable ($IP, $port, $table, $fielddatas ) 
{
	require_once "../ikmInterface.php"; // class_km_server.php needed   
	$ikmInt = new ikmInterface;  
	$ikmInt->connect($IP,$port);  
	if( $ikmInt->KMServerGetisError()==true) {echo "connection error"; exit;}   
	$ikmInt->KMId();  
	try 
	{
		$ikmInt->tableInsert( $fielddatas, $table );		
		$ikmInt->closeSession(); 
	}
	catch (Exception $e)
	{
		if( $ikmInt->KMServerGetisError()==true) { echo $ikmInt->KMServerGetKMError();}
		$ikmInt->closeSession(); 
	}	
}
?>
</fieldset>
</body>
</html>
