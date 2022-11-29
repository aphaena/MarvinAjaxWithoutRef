<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" dir="ltr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	require_once("rsslib.php");
?>

<title>Chargement et affichage d'un flux RSS</title></head>

	
<body bgcolor="#FFFFFF">
<h1>Chargement et affichage d'un flux RSS</h1>
<hr>
<p>Cette démonstration charge un flux RSS distant, ou sur le même site, et affiche le contenu ci-dessous.<br>
Elle utilise la librairie  rsslib.php, écrite en PHP 5, pour extraire l'informtion et l'afficher.</p>
<p> Donnez l'URL d'un flux RSS: </p>
<FORM name="rss" method="POST" action="rss-simple.php">
<p>
	<INPUT type="submit" value="Envoyer">
</p>
  <p> 
    <input type="text" name="dyn" size="48" value="http://fluxrss.fr/actualite/redirect?code=8Kln2kgfK7">
    <!-- 
    http://fluxrss.fr/actualite/redirect?code=jN6OgQfUsg boursier
    http://fluxrss.fr/actualite/redirect?code=8Kln2kgfK7 boursier
    http://fluxrss.fr/actualite/redirect?code=A35zCNI7OZ juridique
    http://fluxrss.fr/actualite/redirect?code=2t3Nk5RhVb agoravox
    http://fluxrss.fr/actualite/redirect?code=MXVq2hODZ5 scene de crime
    -->
  </p>
</FORM><?php

if (isset( $_POST ))
	$posted= &$_POST ;			
else
	$posted= &$HTTP_POST_VARS ;	


if($posted!= false && count($posted) > 0)
{	
	$url= $posted["dyn"];
	if($url != false)
	{
		echo RSS_Display($url, 15,0);
	}
}
?>



<div id="pasf">
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6574971-1");
pageTracker._trackPageview();
} catch(err) {}
</script></div>

</body>
</html>
