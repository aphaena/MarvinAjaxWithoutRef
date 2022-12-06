<?php
   
//$sm = fopen ("sitemap.txt", "a+");
return;
//<url><loc>http://www.marvinbot.com/index.php?modepage=1&textboxsearch=rate%20agreement&lang=eng</loc></url>
//http://178-33-107-41.ovh.net/IKM_PRJ/marvinajaxclean/articles.php?q=baleine&lang=fr&mode=1
if(!isset($_GET['q'])) 
	{
		$_GET['q']= $_GET['qpreview'];
		$_GET['mode']= $_GET['modepreview'];
	}
	$mysearch = "q=".$_GET['q']."&mode=".$_GET['mode']."&lang=".$_GET['lang'];

	$urltmp = "178-33-107-41.ovh.net/IKM_PRJ/marvinajaxclean/articles.php?".$mysearch ;
	if(!isset($_GET['lang'])) {$urltmp = $urltmp."&lang=".$_SESSION['lang'];}
	//if(!isset($_GET['modepage'])) {$urltmp = $urltmp."&modepage=".$_SESSION['modepage'];}
	$urltmp = urlrewriting($urltmp);
	$querysm = "<url>\n<loc>".$urltmp;
	

	$querysm = $querysm."</loc>\n";
	$querysm = $querysm.'<lastmod>'.date("Y-m-d").'</lastmod>
		<changefreq>weekly</changefreq>
		<priority>1.0</priority>';
	$querysm = $querysm."\n</url>\n";

//fputs ($sm, $querysm);  
//fclose ($sm);  

if($_SERVER['REMOTE_ADDR']=="81.56.239.67" || $_SERVER['REMOTE_ADDR']=="192.168.0.10") {} 

else {
			$IpName = '';	
			
			$ip = $_SERVER['REMOTE_ADDR'];
			if(!isset($_SESSION['ip'])) $_SESSION['ip'] = $ip;
			$ipcolor = str_replace('.', "", $_SESSION['ip']);
			if(!isset($_SESSION['color'])) $_SESSION['color'] = substr ($ipcolor,3,2);
			if($_SESSION['ip'] != $ip) $_SESSION['color'] = substr ($ipcolor,3,2);
			
			$_SESSION['ip'] = $ip;
			
			//$script = "http://www.ieducatif.fr/geoloc/geo.php?IP=";
			//$retourloc = file_get_contents($script.$ip);
			$host="";
			
			
			/*
			 <url>
			  <loc>http://www.marvinbot.com/index.php?lang=fr</loc>
			</url>
			 */
			
			if($IpName!='NoRec')
			{
				if(isset($_SERVER['REMOTE_HOST'])) $host=$_SERVER['REMOTE_HOST'];
				
				$date = str_replace(", ", "", date("d, m, Y"));
				$classement = 365-date("z");
				$currentsearch = utf8_decode( urldecode( $_GET['q']));
				if($_SESSION['same'] != $currentsearch   )
				{
						$fp = fopen ("connections/".$classement."connections".$date.".php", "a+");
		
						
						$link='<a target="_blank" href="http://www.infosniper.net/index.php?ip_address='.$_SERVER['REMOTE_ADDR'].'&map_source=1&overview_map=1&lang=1&map_type=1&zoom_level=7">'.' InfosSniper '.'</a> ';
						$link.='<a target="_blank" href="http://domaintz.com/tools/overview/'.$_SERVER['REMOTE_ADDR'].'">'.' DTZ '.$_SERVER['REMOTE_ADDR'].'</a> ';
						
						//$link=$link.'<a target="_blank" http://www.geoiptool.com/fr/?ip='.$_SERVER['REMOTE_ADDR'].'">'.$_SERVER['REMOTE_ADDR'].'</a> ';
						// http://www.localiser-ip.com/?ip=117.196.138.181
						//<a href="http://ip-lookup.net/?ip=66.249.68.46">ip</a>
						$str = "<div style='background-color:#FFFF".$_SESSION['color']."'>(".date('l jS \of F Y h:i:s A').")\t".$link."\t<span style='margin-left:30px;'><b>".utf8_decode( urldecode( $_GET['q']))."</b></span>\t<span style='margin-left:30px;'>".$_GET['lang']."</span>\t\t".$IpName."\t\t<span style='margin-left:30px;'>".$host."</span>\t<span style='margin-left:30px;'>".utf8_decode( urldecode( $_SERVER['QUERY_STRING']))."</span>\t<span style='margin-left:30px;'><br>".$_SERVER['HTTP_USER_AGENT']."</span></div>";
						//$str = "(".date('l jS \of F Y h:i:s A').")\t".$link."\t".$_SESSION['strsearch']."\t".$IpName." ".$_SERVER['REMOTE_HOST']."<br>\n";
						fputs ($fp, $str);  
		
						fclose ($fp);  
				}
				$_SESSION['same'] = $currentsearch;
	}

}

function urlrewriting($url)
{
	//print '<br>';
	$textboxsearch="";
	$lang="";
	$modepage="";
	$query = parse_url($url,PHP_URL_QUERY   );
	//print_r ($query);
	//echo '<br>';
	$tab = explode('&', $query);
	//print_r ($tab);
	foreach($tab as $attribut)
	{
		$atrs = explode('=',$attribut);
		//echo '<br>';
		//print_r ($atrs);
		
		if($atrs[0]=='lang') $lang= $atrs[1];
		if($lang=="") $lang="eng";
		//if($atrs[0]=='textboxsearch') $textboxsearch = $atrs[1];
		//if($textboxsearch=="") $textboxsearch = $_SESSION['strsearch'];
		//$textboxsearch= trim($textboxsearch);
		//$textboxsearch= urlencode($textboxsearch);
		//if($atrs[0]=='modepage') $modepage=$atrs[1];
		//if($modepage=="") $modepage=1;
		//if($modepage==10) $modepage="x1";
		//if($modepage==5) $modepage="x1";
	}
	//print '<br>';

	$urlrewritting =  "178-33-107-41.ovh.net/IKM_PRJ/marvinajaxclean/articles.php?".$_GET['q']."_".$_GET['lang']."_".$_GET['mode'].".html";
	
	$urlrewritting = trim(urldecode($urlrewritting));
	$urlrewritting = str_replace("/ ", "/", $urlrewritting);
	$urlrewritting = str_replace(" ", "_", $urlrewritting);
	$urlrewritting = strtr($urlrewritting, '��������������������������', 'AAAAAACEEEEEIIIINOOOOOUUUUY');
	$urlrewritting = strtr($urlrewritting, '���������������������������', 'aaaaaaceeeeiiiinooooouuuuyy');	
	
	return $urlrewritting; 
}

?> 
