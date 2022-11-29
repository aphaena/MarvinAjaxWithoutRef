<?php

//$googleBase = file_get_contents("http://www.google.com");
//$googleBase = str_replace("images/","http://www.google.com/images/" , $googleBase);


//$search = urlencode(stripAccents(urldecode($_GET['q'])));
$search = urlencode(urldecode($_GET['q']));
//echo "xxxxxxxxxxx ".$search;
/*
if($_SESSION['lang']=='fr') {$lang = "fr";$googlLang ="fr";}
if($_SESSION['lang']=='fr2') {$lang = "fr";$googlLang ="fr";}
if($_SESSION['lang']=='de') {$lang = "de";$googlLang ="de";}
if($_SESSION['lang']=='eng') {$lang = "en"; $googlLang ="com";}
if($_SESSION['lang']=='es') {$lang = "es"; $googlLang ="es";}
if($_SESSION['lang']=="it" ) {$lang="it";	$googlLang="it";}
if($_SESSION['lang']=="als" ) {$lang="com";	$googlLang="com";}
if($_SESSION['lang']=="brz" ) {$lang="com";	$googlLang="com";}
*/
$lang="com";	
$googlLang="com";

$opt = "";
//if(isset($_GET['q']))$opt=$opt."&q=".$_GET['q'];
if(isset($_GET['url']))$opt=$opt."&url=".$_GET['url'];
if(isset($_GET['um']))$opt=$opt."&um=".$_GET['um'];
if(isset($_GET['ie']))$opt=$opt."&ie=".$_GET['ie'];
if(isset($_GET['tbs']))$opt=$opt."&tbs=".$_GET['tbs'];
if(isset($_GET['prmd']))$opt=$opt."&prmd=".$_GET['prmd'];
if(isset($_GET['source']))$opt=$opt."&source=".$_GET['source'];
if(isset($_GET['tbo']))$opt=$opt."&tbo=".$_GET['tbo'];
if(isset($_GET['ei']))$opt=$opt."&ei=".$_GET['ei'];
if(isset($_GET['sa']))$opt=$opt."&sa=".$_GET['sa'];
if(isset($_GET['oi']))$opt=$opt."&oi=".$_GET['oi'];
if(isset($_GET['resnum']))$opt=$opt."&resnum=".$_GET['resnum'];
if(isset($_GET['ct']))$opt=$opt."&ct=".$_GET['ct'];
if(isset($_GET['ved']))$opt=$opt."&ved=".$_GET['ved'];
if(isset($_GET['revid']))$opt=$opt."&revid=".$_GET['revid'];


 
if($_GET['sm']=='bing') 
{
	$bingSearch = file_get_contents("http://www.bing.com/search?q=".$search."&start=".$_GET['start']."&tbs=img:1&hl=".$lang.$opt);
	
	$bingSearch = str_replace('href="http://','target="_blank" href="http://' , $bingSearch);	
	$bingSearch = str_replace('href="/search','target="_blank" href="http://www.bing.com/search' , $bingSearch);	
	$bingSearch = str_replace('href="/images/search','target="_blank" href="http://www.bing.com/images/search' , $bingSearch);		
	$bingSearch = str_replace('href="/images/search','target="_blank" href="http://www.bing.com/images/search' , $bingSearch);	
	$bingSearch = str_replace('href="/videos/search','target="_blank" href="http://www.bing.com/videos/search' , $bingSearch);		
	
	//$bingSearch = str_replace('/images/','http://www.bing.com/images/' , $bingSearch);	
	$bingSearch = str_replace('/news/','http://www.bing.com/news/' , $bingSearch);	
	$bingSearch = str_replace('/maps/','http://www.bing.com/maps/' , $bingSearch);		
	$bingSearch = str_replace('/videos/img/s/','http://www.bing.com/videos/img/s/' , $bingSearch);	
	$bingSearch = str_replace('/worldwide.aspx','http://www.bing.com/worldwide.aspx' , $bingSearch);	
	$bingSearch = str_replace('/settings.aspx','http://www.bing.com/settings.aspx' , $bingSearch);	
	$bingSearch = str_replace('form action="/search"','form action="http://www.bing.com/search"' , $bingSearch);	


	// <form action="/search" class="sw_box" id="sb_form"><a href="/?FORM=Z9FD1" class="sw_logo" onmousedown="return si_T('&amp;ID=FD,38.1')"><span class="sw_logoT">Bing</span></a><div id="sw_bta">Beta</div><div class="sw_bd"><div class="sw_b"><input class="sw_qbox" id="sb_form_q" name="q" title="Entrer le terme recherch√©" type="text" value="mot" autocomplete="off"><input class="sw_qbtn" id="sb_form_go" name="go" tabindex="0" title="Rechercher" type="submit" value=""></div><div id="sw_as"></div></div><input name="form" type="hidden" value="QBRE"><div class="sb_form_align"><div id="sc_mktb"><div><div id="sw_filt"><input checked="checked" id="nofilt" name="filt" type="radio" value="all"><label for="nofilt">Tout afficher</label><input id="langfilt" name="filt" type="radio" value="lf"><label for="langfilt">Seulement en <a href="http://www.bing.com/settings.aspx?sh=5&amp;ru=%2fsearch%3fq%3dmot%26start%3d0%26tbs%3dimg%3a1%26hl%3dcom">fran√ßais</a></label><input id="regionfilt" name="filt" type="radio" value="rf"><label for="regionfilt">France seulement</label></div></div></div></div></form>
}
else
{
	$googleSearch = file_get_contents("http://www.google.".$googlLang."/search?q=".$search."&start=".$_GET['start']."&tbs=img:1&hl=".$lang.$opt);
	
	$googleSearch = str_replace('http://t0.gstatic.com','' , $googleSearch);
	$googleSearch = str_replace('http://t1.gstatic.com','' , $googleSearch);
	$googleSearch = str_replace('http://t2.gstatic.com','' , $googleSearch);
	$googleSearch = str_replace('http://t3.gstatic.com','' , $googleSearch);
	$googleSearch = str_replace('http://t4.gstatic.com','' , $googleSearch);
	$googleSearch = str_replace('href="http://','target="_blank" href="http://' , $googleSearch);		
	$googleSearch = str_replace("images/","http://www.google.".$googlLang."/images/" , $googleSearch);
	$googleSearch = str_replace("/images","http://www.google.".$googlLang."/images" , $googleSearch);	
	$googleSearch = str_replace("/imgres?imgurl=","http://www.google.com/imgres?imgurl=" , $googleSearch);
	$googleSearch = str_replace("/search?","http://www.google.com/search?" , $googleSearch);
	$googleSearch = str_replace("www.google.comhttp://www.google.com/images","www.google.com/images" , $googleSearch);
}



?>

<?php
//$googleSearch=stripAccents($googleSearch);
//$html_string = $googleSearch;
//echo $html_string;
?>
 
<?php 




if($_GET['sm']=="google") {
	$dom= new DOMDocument(); 
	@$dom->loadHTML($googleSearch); 

	$dom->preserveWhiteSpace = false; 
	$imagebox = $dom->getElementById('imagebox');
	echo $googleSearch;
	}
else { 


$dom= new DOMDocument(); 
@$dom->loadHTML($bingSearch); 

$dom->preserveWhiteSpace = false; 

	//$bingform = $dom->getElementById('sw_hdr'); 
	//$bingformhtml = DOMinnerHTML($bingform);
	
	//$bingSearchhtml = DOMinnerHTML($bingSearch);
	//$bingSearchhtml = str_replace($bingformhtml,'' , $bingSearch);	
	//echo $bingformhtml;
	
	//$searchpages = $dom->getElementById('sw_content'); 
	//echo $bingSearch;
	}

//echo DOMinnerHTML($imagebox);

//echo DOMinnerHTML($searchpages);

//echo DOMinnerHTML($pager);


function DOMinnerHTML($element) 
{ 
    $innerHTML = ""; 
    if(isset($element->childNodes))
    {
    $children = $element->childNodes; 
    foreach ($children as $child) 
    { 
        $tmp_dom = new DOMDocument(); 
        $tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
        $innerHTML.=trim($tmp_dom->saveHTML()); 
    } 
    return $innerHTML;
    }
    else {
    	return "Found nothing";
    } 
} 

function stripAccents($string){
	return strtr($string,'‡·‚„‰ÁËÈÍÎÏÌÓÔÒÚÛÙıˆ˘˙˚¸˝ˇ¿¡¬√ƒ«»… ÀÃÕŒœ—“”‘’÷Ÿ⁄€‹›',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

?> 


<script>
$("#search").focus();
</script>
