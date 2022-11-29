
<?PHP

$url = $_GET['url'];
$url = str_replace( " " ,"%20", $url);

echo $url;

$result_search = file_get_contents( $url );

$result_search = str_replace("sultats", "sultats (<b>reellement environ 800 accessibles en 110 a 300ms</b>)", $result_search);
$result_search = str_replace("results", "results (<b>really 800 beetween 110 and 300ms</b>)", $result_search);
$result_search = str_replace('<div id="logocont">', '<div id="logcont"><img src="images/closebut.jpg" width="35" height="35" /> ', $result_search);
$result_search = str_replace("/url?q=", "http://www.google.com/url?q=", $result_search);
$result_search = str_replace('<a href=', '<a target="blank" href=', $result_search);

$result_search = str_replace('href="/search?', 'target="blank" href="http://www.google.com/search?', $result_search);

//$result_search = mb_ereg_replace("&sa=(.*)>", ">",$result_search);


echo $result_search;

?>