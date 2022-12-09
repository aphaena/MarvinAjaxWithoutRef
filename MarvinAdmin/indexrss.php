<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <script type="text/javascript" src="jquery.js"></script>
<script>
var myInterval = 0;

var count = 0;


	myInterval = window.setTimeout(fluxrss ,10000);
	
	function fluxrss() 
	{
		//alert("fluxrss");
		
		readrss("http://www.lemonde.fr/rss/tag/international.xml", "1249K");
		
		readrss("http://www.lemonde.fr/rss/une.xml", "1246G");
		
		clearInterval(myInterval);

	}


function readrss( url,sn)
	{			
			//alert(thistext);			http://127.0.0.1/IKM_PRJ/marvinajaxclean/MarvinAdmin/rssreader/rss-direct.php?rssflux=http://syndication.lesechos.fr/rss/rss_finance-marches.xml&sn=1234
		   $.ajax({ type: "GET", url: "rss-direct.php",
				  data: "rssflux="+ url + "&sn=" + sn ,						  
				  success:  function(msg){					  
					$("#display_rss").html(msg); 		
					myInterval = window.setTimeout(fluxrss ,10000);							  											
				  },
				  async: true });							  	  
	}


</script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<div >RSS Recorder</div>
<div id="display_rss"></div>
</body>
</html>