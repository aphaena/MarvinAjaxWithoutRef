<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Marvinbot Insert Data</title>
</head>

<body>
<form action="insertwiki.php" method="get" name="form1" target="_self" id="form1">
  <p>
    <label for="wiki_title"></label>
    Title
    <input type="text" size="100" name="wiki_title" id="wiki_title" />
  </p>
  <p>link
    <label for="wiki_link"></label>
    <input type="text" size="100" name="wiki_link" id="wiki_link" />
  </p>
  <p>Text</p>
  <p>
    <label for="wiki_text"></label>
    <textarea name="wiki_text" id="wiki_text" cols="100" rows="30"></textarea>
  </p>
  <p>
    <input type="submit" name="Insert" id="Insert" value="Insert" />
  </p>
</form>
<p>
  <?php

include "connectIKM.php";

$sikm = new searchikm();

$sikm->connectIKM_FR();

if(isset($_GET["wiki_title"]) && isset($_GET["wiki_text"]) && isset($_GET["wiki_link"]))
{
	$texteclean = str_replace("[modifier]", " ", $_GET["wiki_text"]);
	$texteclean = str_replace("[masquer]", " ", $texteclean);
	$sikm->insertnewwikiLine($_GET["wiki_title"], $texteclean ,$_GET["wiki_link"]);
	$sikm->publish();
	echo "<br>";
	echo "<br>";
	echo "ok";

}
else
{
	echo "<br>";
	echo "<br>";
	echo "pas d'insert, il manque quelque chose...";
}

?>

Nouvelles Pages Wiki non presentes dans la base<br />
<a href="http://fr.wikipedia.org/w/index.php?title=Palmaria_(algue)">http://fr.wikipedia.org/w/index.php?title=Palmaria_(algue)</a><br />
<a href="http://fr.wikipedia.org/wiki/Institut_Mines-T%C3%A9l%C3%A9com">http://fr.wikipedia.org/wiki/Institut_Mines-T%C3%A9l%C3%A9com</a></p>
<p> <a href="http://fr.wikipedia.org/wiki/Telecom_Saint-%C3%89tienne">http://fr.wikipedia.org/wiki/Telecom_Saint-%C3%89tienne</a> <br />
</p>
</body>
</html>


