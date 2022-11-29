<head>
<link href="formauto/ikmsemiauto.css" rel="stylesheet" type="text/css" />

</head>
<?php

//session_start();

$_SESSION['tabtrie']=array();
$_SESSION['tabtriememi']=array();

$debug="";

if(isset($_GET['q']))$deph = utf8_decode(urlencode($_GET['q']));
else $_GET['q']="";

$mySearch= utf8_decode(urlencode($_GET['q']));
//$mySearch = trim($mySearch);
if(isset($_GET['d']))$deph = $_GET['d'];
else $_GET['d']="1";

if(isset($_GET['debug'])) {$debug = $_GET['debug']; $_SESSION['debug']=$_GET['debug']; }
if(isset($_SESSION['debug'])) { $debug =$_SESSION['debug']; }
if(isset($_SESSION['lang'])) {$lang =$_SESSION['lang']; }
if(isset($_GET['lang'])) {$lang = $_GET['lang']; $_SESSION['lang']=$_GET['lang'];}
//
else {$lang="fr";}

//$_GET['q']="";
$_GET['d']="1";

?>


<script>
var mysearch;
$(function(){		

		var myInterval;
		
		alert("form");
		$('#text_search').bind('keyup', function(event) { 	
			
		clearInterval(myInterval);
		myInterval = window.setTimeout("ajaxquery()" ,40);					
					
		});
	
		$('#text_search').bind('keydown', function(event) { 									
			var keycode = (event.keyCode ? event.keyCode : event.which);			
			if(keycode == '13') 
			 {				
			 mysearch = $("#text_search").val(); 
			 mysearch = mysearch.replace(/'/g, '');		
			
				googlesearch(mysearch);	
				$("#text_search").submit();
				//event.preventDefault();	
			
				//return false;
		  }			
		});
	
		
		$('#text_search').bind('focusin', function() { 	
			ajaxquery();			
		});
			
		
		$("#submit").click(function(e){
		//alert( mysearch );
			mysearch = $("#text_search").val(); 	
		
			googlesearch(mysearch);	
				
		});			
		
		
	$("#text_search").focus();

		
});


function ajaxquery()
{	
			//$("#googlepreview").hide(1000);
			mysearch = $("#text_search").val(); 	
			mysearch = mysearch.replace(/'/g, '');	
	//$("#search").val(mysearch); 
			//mysearch = $.trim(mysearch);		
			mysearch = wordfilter(mysearch);
	
			mydeph = $("#deph").val(); 		
				
				$.ajax({ type: "GET", url: "formauto/formfunction.php",data: "q="+mysearch+"&d="+mydeph+"&debug="+"<?php echo $debug;?>"+"&lang="+"<?php echo $lang;?>", async: true,
				success: function(html){
				 $("#cadresemiauto02").show(500);	
   				 $("#contenthelp").html(html); 					
				});			
		
  			}
			});		
				
				//$("#contenthelp").html(html); 				
				$("#google").css("z-index", "-1");	
				$("#text_search").focus();
}





function addslashes(ch) { 
	ch = ch.replace(/\\/g,"\\\\") 
	ch = ch.replace(/\'/g,"\\'") 
	ch = ch.replace(/\"/g,"\\\"") 
return ch 
}


function wordfilter(_mysearch)
{
		//alert("wordfilter");
		filtersearch ='';
		_mysearch = $.trim(_mysearch);	
		mysearchsplit = _mysearch.split(' ');		
		for(var i=0; i < mysearchsplit.length; i++)
		{
			if( mysearchsplit[i].length>0) {filtersearch = filtersearch +" "+  mysearchsplit[i];}
		}		
		return filtersearch;
}

function googlesearch(_mysearch) {
	//alert("googlesearch");
			mysearchCor = _mysearch.replace(/_/g, ' ');		
			motorchoice = $('input[type=radio][name=smc]:checked').attr('value');	
			html = $.ajax({ type: "GET", url: "formauto/proxyGoogleForForm.php",data: "q="+mysearchCor+"&start=0&sm="+motorchoice, async: true, success: function(html){
   			 $("#google").html(html); 	
  			}
			}); 
			
			//$("#google").html(html); 			
			$(".tsf-p").hide();
			$(".nobr").hide();
			$("#gog").hide();
			$("#google").css("z-index", "1");	
			$("#google").css("position", "absolute");			
}

</script>

<div id="contenthelp"></div>

