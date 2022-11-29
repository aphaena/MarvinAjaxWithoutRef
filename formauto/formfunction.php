<head>
<link href="formauto/ikmsemiauto.css" rel="stylesheet" type="text/css" /> 
	<script type="text/javascript" src="../lib/jquery.js"></script>    
</head>

<?php
require_once "ikmInterface.php";
//require_once "../form_semi_auto.php";

$ikmCom = new ikmCom();

if(!isset($_GET['lang'])) $ikmCom->connect("127.0.0.1","1254","wikiknw");
if(isset($_GET['lang']) && $_GET['lang']=="fr") $ikmCom->connect("127.0.0.1","1254","wikiknw");
if(isset($_GET['lang']) && $_GET['lang']=="eng") $ikmCom->connect("127.0.0.1","1224","wikiknw");
if(isset($_GET['lang']) && $_GET['lang']=="fr2") $ikmCom->connect("127.0.0.1","1254","wikiknw");
if(isset($_GET['lang']) && $_GET['lang']=="it") $ikmCom->connect("127.0.0.1","1254","wikiknw");
if(isset($_GET['lang']) && $_GET['lang']=="de") $ikmCom->connect("127.0.0.1","1254","wikiknw");
if(isset($_GET['lang']) && $_GET['lang']=="es") $ikmCom->connect("127.0.0.1","1254","wikiknw");
if(isset($_GET['lang']) && $_GET['lang']=="brz") $ikmCom->connect("127.0.0.1","1254","wikiknw");
if(isset($_GET['lang']) && $_GET['lang']=="als") $ikmCom->connect("127.0.0.1","1254","wikiknw");
//display("test",$ikmavanced->test());

//echo $mySearch;

if(isset($_GET['q'])) $mySearch= urldecode($_GET['q']);
//echo $mySearch;

if(isset($_GET['d'])) $deph = $_GET['d'];
if(isset($_GET['lang']))$lang= $_GET['lang'];

?>
<script >
$(function(){
		
		//mysearch = '<?php echo $mySearch;?>';
		mysearch = $("#text_search").val();	
		//alert($("#textboxsearch").val());	
		mysearch=$.trim(mysearch);
		
		//mysearch = addslashes(mysearch);

		lang = '<?php echo $lang;?>';
		
		$("#preview").hide(0);
		 
		mysearchsplit = mysearch.split(' ');		
		for(var i=0; i < mysearchsplit.length; i++)
		{								
				$(".words:contains("+ mysearchsplit[i] + ")").addClass("DisplayOn");				
				//$("#google").html(i); 
		}
		
		$(".words:contains("+ mysearchsplit[0] + ")").addClass("bold");
		
		for(var j=0; j < mysearchsplit.length; j++)
		{								
			couleur ="#"+ Math.floor(Math.random()*999999);			
			 	
			$(".words:contains("+ mysearchsplit[j] + ")").css("color", couleur);
			//$("#google").html(couleur);
		}

		$("#preview").mouseleave(function(e){
			$("#preview").hide(100);
		});	
		
		$("#preview").click(function(e){
			$("#preview").hide(100);
		});	

		
		$(".words").click(function(e){			
			 currentsearch = $("#text_search").val(); 	

			 currentsearch = replacelastword(currentsearch);	
			 currentsearch =  $.trim(currentsearch);
			 				 
			 /*alert(currentsearch +" " + $(this).text());*/
			 searchadd = currentsearch +" " + $(this).text();			 			 
			
			searchaddclean = $.trim(searchadd);
			/* alert(searchaddclean);*/
			$("#text_search").val(searchaddclean);
			
			//$("#search").focus();
			mysearch = $("#text_search").val(); 	

			mysearchclean = mysearch.replace(/_/g, ' ');				
			//googlesearch(mysearchclean);	
			$("#text_search_form").submit();
			//$("#google").html(mysearchclean);	
		});
		

		mysearchclean = $.trim(mysearch);

		wordmouseover(mysearchclean);
		
		
				
});	


		
		function wordmouseover(mysearch) 
		{
		//alert('"'+mysearch+'"');
				// $("#google").html("trace ok");	
			$(".wordeye").mouseover(function(e){				
			
			$("#preview").show();
		 //alert(mysearch  + " " + $(this).text());			
		 	//mysearchCor = $.trim(mysearchCor);
			mysearchCor = mysearch.replace(/_/g, ' ');	
											
			mysearchadd = 	$(this).parent().text();
			//mysearchadd = $.trim(mysearchadd);
			mysearchadd = mysearchadd.replace(/_/g, ' ');	
			
			//alert(mysearchCor+" "+mysearchadd);
			$.ajax({ type: "GET", url: "imageproxyajaxForForm.php",data: "qo="+mysearchCor+"&q="+mysearchadd+"&lang="+lang, async: true,
			success: function(html){
   			 $("#preview").html(html); 	
  			}
			});
			
			// }).responseText;  							
			$("#preview").show();
			$("#preview").offset({ top: $("#form_search_main").offset().top+64, left: $("#page_results_sugestion_menu").offset().left+$("#page_results_sugestion_menu").width()+5 });
			//$("#preview").html(html); 								
			//alert(mysearchCor+" "+mysearchadd);
			});	
		}

		function replacelastword(_query)
		{
			newquery='';
			searchaddsplit =  _query.split(' ');
			for(var i=0; i < searchaddsplit.length-1; i++)
			{
				newquery = newquery+searchaddsplit[i]+' ';
			}			
			return newquery;
		}
		
		

</script>


<?php

echo "<div id='semiauto' class='cadresemiauto'>";
	
	//$mySearch= "baleine évolution ";
	//display("test",$ikmCom->test($mySearch));// searchFromSpace
	//$results = $ikmCom->searchFromSemLinks($mySearch);
echo '<div id="googlepreview">';
echo '</div>';

/*
	$results = $ikmCom->searchFromShape($mySearch);
	$tab = $ikmCom->make_tab($results);
	if(isset($tab) && count($tab) > 1) 
	{
		echo "<span class='cadresemiauto02'>";
		echo "<span>searchFromShape</span>";
	 	display_col($tab);
		echo "</span>";
	}
*/
	$mySearch = filterSearch($mySearch);
	$results = $ikmCom->searchFromAttrator($mySearch);	
	$tab = $ikmCom->make_tab($results);
	if(isset($_GET['debug'])&&$_GET['debug']=="ok")
	{
		if(isset($tab) && count($tab) > 1) 
		{
			echo "<span class='cadresemiauto02'>";
			echo "<span>searchFromAttrator</span>";
			display_col($tab);
			echo "</span>";
		}
	}


	$results = $ikmCom->searchFromSemAttrator($mySearch);
	$tab = $ikmCom->make_tab($results);
		if(isset($_GET['debug'])&&$_GET['debug']=="ok")
	{	
	if(isset($tab) && count($tab) > 1) 
		{
			echo "<span class='cadresemiauto02'>";
			echo "<span>searchFromSemAttrator</span>";
			display_col($tab);
			echo "</span>";
		}
	}
	
	$results = $ikmCom->searchFromSpace($mySearch, $deph);
	$tab = $ikmCom->make_tab($results);
	if(isset($tab) && count($tab) > 1) 
	{
		echo "<span class='cadresemiauto02'>";
		echo "<span>searchFromSpace</span>";
		display_col($tab);
		echo "</span>";
	}
	
	$results = $ikmCom->searchFromSem($mySearch);	
	$tab = $ikmCom->make_tab($results);
	if(isset($_GET['debug'])&& $_GET['debug']=="ok")
	{	
		if(isset($tab) && count($tab) > 1) 
		{
			echo "<span class='cadresemiauto02'>";
			echo "<span>searchFromSem</span>";
			display_col($tab);
			echo "</span>";
		}
	}
	
	
	$results = $ikmCom->searchFromBestAct($mySearch);	
	$tab = $ikmCom->make_tab($results);
	if(isset($_GET['debug'])&& $_GET['debug']=="ok")
	{	
		if(isset($tab) && count($tab) > 1) 
		{
			echo "<span class='cadresemiauto02'>";
			echo "<span>searchFromBestAct</span>";
			display_col($tab);
			echo "</span>";
		}
	}
	
	$results = $ikmCom->searchFromSemLinks($mySearch);
	$tab = $ikmCom->make_tab($results);
	if(isset($_GET['debug'])&& $_GET['debug']=="ok")
	{
		if(isset($tab) && count($tab) > 1) 
		{
			echo "<span class='cadresemiauto02'>";
			echo "<span>searchFromSemLinks</span>";
			display_col($tab);
			echo "</span>";
		}
	}
	
	
 	if(isset($_SESSION['tabtrie']) && count($_SESSION['tabtrie']) > 1) 
	{
		natcasesort($_SESSION['tabtrie']);
		$_SESSION['tabtrie'] = array_unique ($_SESSION['tabtrie']);



		if(isset($_GET['debug'])&& $_GET['debug']=="ok")
		{
			echo "<span class='cadresemiauto02'>";
			//echo "<span>SESSION tabtrie All tab added</span>";
			display_col($_SESSION['tabtrie']);
			echo "</span>";
		}
	
	}
	
	
	
	if(isset($_SESSION['tabtrie']) && count($_SESSION['tabtrie']) > 1) 
	{
	$matchSearch = maketabbysearch($_GET['q'], $_SESSION['tabtrie']);
		echo "<span class='cadresemiauto02'>";
		//echo "<span>matchSearch</span>";		
		display_col($matchSearch);
		echo "</span>";
	}
			
echo '</div>';





class ikmCom {
	public $ikmInt;
	

				
	function __construct() 
	{
		$this->ikmInt = new ikmInterface();
	}

	
	public function connect($_ip,$_port,$_knw)
	{
		$this->ikmInt->connect($_ip,$_port);
		$this->ikmInt->knowledge($_knw);	
		$this->ikmInt->KMId();
	}
	
		
	public function searchFromShape($mySearch)
	{
		$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch);
		$this->ikmInt->contextFilterVoidElements("true");
		$this->ikmInt->contextNewFromShape();
		$this->ikmInt->contextSortByGenerality("true");
		$this->ikmInt->contextGetElements();
		$results = $this->ikmInt->KMResults();
		$this->ikmInt->closeSession() ;
		
		return $results;
	}	
	
	public function searchFromAttrator($mySearch)
	{
		$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch);
		$this->ikmInt->contextFilterVoidElements("true");
		$this->ikmInt->contextNewFromAttractor();
		$this->ikmInt->contextSortByGenerality("true");
		$this->ikmInt->contextGetElements();
		$results = $this->ikmInt->KMResults();
		$this->ikmInt->closeSession() ;
		
		return $results;
	}

	public function searchFromSemAttrator($mySearch)
	{
		$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch);
		$this->ikmInt->contextFilterVoidElements("true");
		$this->ikmInt->contextNewFromSemAttractor();
		$this->ikmInt->contextSortByGenerality("true");
		$this->ikmInt->contextGetElements();
		$results = $this->ikmInt->KMResults();
		$this->ikmInt->closeSession() ;
		
		return $results;
	}	
	
	// TEST xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function searchFromBestAct($mySearch)
	{				
		$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch);
		$this->ikmInt->contextFilterVoidElements("true");		
		//$this->ikmInt->contextNewFromAttractor();
     	$this->ikmInt->contextNewFromSem("0","-1","-1");	
		//$this->ikmInt->contextNewFromSemLinks("2","0","0","-1");
			
		//$this->ikmInt->contextNewFromShape();
	
		$this->ikmInt->contextFilterAct("99", "true");	
		$this->ikmInt->contextSortByGenerality("true");
     	//$this->ikmInt->contextEvaluate();	

    	$this->ikmInt->contextGetElements();
		$results = $this->ikmInt->KMResults();
		$this->ikmInt->closeSession() ;		
		//print_r($results);
		return $results;     
	}
	
	
	public function searchFromSem($mySearch)
	{				
		$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch);
		$this->ikmInt->contextFilterVoidElements("true");		
     	$this->ikmInt->contextNewFromSem("0","-1","-1");	
     	$this->ikmInt->contextEvaluate();	
    	$this->ikmInt->contextGetElements();
		$results = $this->ikmInt->KMResults();
		$this->ikmInt->closeSession() ;		
		return $results;   
	}
	

       public function searchFromSemNew($mySearch)
       {                           
		$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch);
		$this->ikmInt->contextFilterVoidElements("true");

   //on évalue l'importance relative des termes de la requete
		$this->ikmInt->contextDup();
		$this->ikmInt->contextEvaluate();
		$this->ikmInt->contextUnion();
		$this->ikmInt->contextNormalize();

		$this->ikmInt->contextPartition("1","0","0","-1");
		$this->ikmInt->contextGetCount();
		$results = $this->ikmInt->KMResults();   

		$NC = $results[0]['results'][0][0];
		  $i = $NC;
     
     while ($i >0)
     {

   		$this->ikmInt->contextNewFromSem("0","-1","-1");	
 		$this->ikmInt->contextNewFromSem("0","-1","-1");	
		$this->ikmInt->contextUnion();
		$this->ikmInt->contextNormalize();		
		$this->ikmInt->contextSwap();
		$this->ikmInt->contextDrop();
		$this->ikmInt->contextRolldown($NC);
		$i--;
     	
    }
		$this->ikmInt->resultsIntersection($NC-1);
		
		$this->ikmInt->contextDup();
		$this->ikmInt->contextEvaluate();
		$this->ikmInt->contextUnion();
		$this->ikmInt->contextNormalize();
		$this->ikmInt->contextFilterAct("25", "true");	
		$this->ikmInt->contextSortByGenerality("true");
		$this->ikmInt->contextGetElements();
		$results = $this->ikmInt->KMResults();
		$this->ikmInt->closeSession() ;
		
		return $results;   
   }


	public function searchFromSemLinks($mySearch)
	{				
		$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch);
		$this->ikmInt->contextFilterVoidElements("true");		
		$this->ikmInt->contextNewFromSemLinks("2","0","0","-1");
     	$this->ikmInt->contextEvaluate();	
    	$this->ikmInt->contextGetElements();
		$results = $this->ikmInt->KMResults();
		$this->ikmInt->closeSession() ;		
		return $results;   
	}

	public function searchFromSemLinksNew($mySearch)
	{
		$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch);
		$this->ikmInt->contextFilterVoidElements("true");
		$this->ikmInt->contextDup();
		$this->ikmInt->contextEvaluate();
		$this->ikmInt->contextUnion();
		$this->ikmInt->contextNormalize();
		$this->ikmInt->contextPartition("1","0","0","-1");
		$this->ikmInt->contextGetCount();
		$results = $this->ikmInt->KMResults();   

		$NC = $results[0]['results'][0][0];
		  $i = $NC;
		  
     
     while ($i >0)
     {
		$this->ikmInt->contextNewFromSemLinks("2","0","0","-1");
   		$this->ikmInt->contextNewFromSem("0","-1","1");		
		$this->ikmInt->contextUnion();
		$this->ikmInt->contextEvaluate();
		$this->ikmInt->contextSwap();
		$this->ikmInt->contextDrop();
		$this->ikmInt->contextRolldown($NC);
		$i--;
	 }
		
		$this->ikmInt->resultsIntersection($NC-1);
		
		$this->ikmInt->contextDup();
		$this->ikmInt->contextEvaluate();
		$this->ikmInt->contextUnion();
		$this->ikmInt->contextNormalize();
		$this->ikmInt->contextFilteract("25", "true");	
		$this->ikmInt->contextSortByGenerality("true");
		$this->ikmInt->contextGetElements();
		$results = $this->ikmInt->KMResults();
		$this->ikmInt->closeSession() ;
		
		return $results;		
	}
	
	public function testFuntion()
	{
		$instance = $this->ikmInt->tableGetInstances();
		$results = $this->ikmInt->tableGetStructure();
		return $results;
	}
	
	public function testFunction2()
	{
		 $this->ikmInt->tableReadFirstLine("subject rowid");
		return $this->ikmInt->tableReadNextLine("subject rowid");
		//return $this->ikmInt->tableSelect('new', 'title', 'between', 'ab', 'ac');		
	}
		
	public function searchFromSpace($mysearch, $deph)
	{	
     $this->MVClear();
     $this->MVQuery($mysearch);               
     $this->MVGetSem($deph) ;
 	$this->ikmInt->contextSortByGenerality("true");
	 $this->ikmInt->contextGetelements();
 	 $results = $this->ikmInt->KMResults();
	 $this->ikmInt->closeSession() ;	
	 return $results;			 
	}

	public function MVMerge()
	{	
      //consolide 2 contextes, sans (trop de) réévaluation des activités
      //pile 2 contextes - > le resultat   stack -1
      //validée...a mettre dans l'aPI
      
      //on duplique les deux contextes 
	  $this->ikmInt->contextDup();
	  $this->ikmInt->contextRolldown("3");
	  $this->ikmInt->contextDup();
	  $this->ikmInt->contextRolldown("3");	        
      $this->MVDelta();
  	  $this->ikmInt->contextRolldown("3");	        
	  $this->ikmInt->contextIntersection();
	  $this->ikmInt->contextNormalize();
	  $this->ikmInt->contextUnion();
	  $this->ikmInt->contextNormalize();
  }
  
 	public function MVDelta()
	{	
     //calcul (A U B) - (A inter B)
     //pile 2 contextes - > le resultat   stack -1
     //testée..à modifier avec nouvelle aPI
      
      //on duplique les deux contextes 
	  $this->ikmInt->contextDup();
	  $this->ikmInt->contextRolldown("3");
	  $this->ikmInt->contextDup();
	  $this->ikmInt->contextRolldown("3");	 
      // A U B
	  $this->ikmInt->contextUnion();
  	  $this->ikmInt->contextRolldown("3");	  
      // A inter B      
	  $this->ikmInt->contextIntersection();
  	  $this->ikmInt->contextAmplify("-1");
      //somme des deux et filtrage des activités négatives  
	  $this->ikmInt->contextUnion();
	  $this->ikmInt->contextNormalize();
	  $this->ikmInt->contextFilteract("1");
  	  $this->ikmInt->contextAmplify("2");
	  $this->ikmInt->contextNormalize();
  
  } 
  
  public function MVClear()
	{	
	   //initialisation de la session user IKM, des piles de contexte et de RS
   	 	$this->ikmInt->contextClear();
		$this->ikmInt->ResultClear();
		$this->ikmInt->contextSetKnowledge("wikiknw");
	}
	
	public function MVQuery($mySearch)
	{	
		$this->ikmInt->contextNew();
		$this->ikmInt->contextAddElement($mySearch,"100");
		$this->ikmInt->contextFilterVoidElements("true");    
	}	
	
	public function MVQueryExt()
	{	     
     //$this->MVQuery($mysearch) ;    
	$this->ikmInt->contextPartition('0','0','0','-1');
	$results = $this->ikmInt->KMResults();	 
    $ctx = $results[0]["results"][0][0]; 	
	$this->ikmInt->contextRolldown( $ctx+1);  
	$this->ikmInt->contextRolldrop(); 
     return $ctx ;
	}
	
	public function MVContextExtend()
	{	
	 $this->ikmInt->contextShape("0","-1");
	 $this->ikmInt->contextUnion();
 	 $this->ikmInt->contextNewFromAttractor("0","-1");
	 $this->ikmInt->contextUnion();
	 $this->ikmInt->contextNewFromSem("0","-1","-1");	 
	 $this->ikmInt->contextSwap();
	 $this->ikmInt->contextNewFromSem("1","0","0","2");	 	
	 $this->ikmInt->contextUnion();
	 $this->ikmInt->contextUnion();	  
     //$this->s->Execute($this->session, 'CONTEXTS.EVALUATE' );
 
	}	
	
	public function MVGetCategories()
	{	
	  $this->ikmInt->contextShape("0","-1");
	  $this->ikmInt->contextUnion();
 	  $this->ikmInt->contextNewFromAttractor("0","-1");
 	  $this->ikmInt->contextUnion(); 
	  $this->ikmInt->contextNormalize();
	}	
	
	public function MVGetSem($level)
	{	
    //propagation dans le réseau sem
     $stop = 0;
	  $this->ikmInt->contextDup();
     $seuilprop = 20;
     for ($i=1;$i<=$level;$i++)
      {
	     $this->ikmInt->contextNewFromSem("0","-1","-1");
        if ($i == 1)
          {
		  $results = $this->ikmInt->KMResults();	 
             $max = 4 * $results[0]["results"][0][1];               
          }
        /*
        $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
        $this->s->Execute($this->session, 'CONTEXTS.NEWFROMSEMLINKS','1','0','0','2' );
        $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' ); 
        $this->MVMerge(); 
        */
        $this->ikmInt->contextFilteract($seuilprop);
		$this->ikmInt->contextSwap();
		$this->ikmInt->contextDrop();
		$this->ikmInt->contextSwap();
		$this->ikmInt->contextDup();
		$this->ikmInt->contextAmplify("-1");
		$this->ikmInt->contextRolldown("3");
		$this->ikmInt->contextUnion();
		$this->ikmInt->contextNormalize();
		$this->ikmInt->contextFilteract("1");
		$results = $this->ikmInt->KMResults();	
        if (isset($results[0]["results"][0][1] ))  
         {$nb = $results[0]["results"][0][1];} 
         else
          {$nb = 0;}
        
        //si rien du tout on arrete
        if ($nb > $max)
         {
   		  $this->ikmInt->contextEvaluate();
		  $this->ikmInt->contextFiltersize($max);
         }
		$this->ikmInt->contextDup();	
		$this->ikmInt->contextRolldown("3");
        $this->MVMerge(); 
        //$this->s->Execute($this->session, 'CONTEXTS.UNION' );
		$this->ikmInt->contextNormalize();
		$this->ikmInt->contextFilteract("1");
		$this->ikmInt->contextSwap();
        
        $seuilprop += 5;
        if ($seuilprop >75) {$seuilprop = 75;}
      
      }
     $this->ikmInt->contextUnion();
	 $this->ikmInt->contextNormalize();
  	 $this->ikmInt->contextFilteract("1");
 
	}	
	


	function make_tab($array)
	{
		if(isset($array[0]['lines']) && $array[0]['lines'] > 0 )
		{
			for($i=0; $i<$array[0]['lines'];$i++)
			{
				$tab[$i] = str_replace("_"," ",$array[0]['results'][$i][0]);							

			}
			$tabformerge = $tab;
			if(isset($_SESSION['tabtriememi'])) {$_SESSION['tabtrie']=array_merge($_SESSION['tabtriememi'], $tabformerge);} else {$_SESSION['tabtrie']=$tabformerge;}
			if(isset($_SESSION['tabtrie']))     {$_SESSION['tabtriememi']=$_SESSION['tabtrie'];} else{	$_SESSION['tabtriememi'] = $tabformerge;}
			
			
			return $tab;
		}
		$tab = array();
		return $tab;
	}	


		
}
	

	function filterSearch($mysearch) 
	{
		$newsearch = '';
		$tabsearch = explode( " ", $mysearch) ;
		foreach($tabsearch as $word)
		{	
			if(strlen($word)>2) $newsearch = $newsearch." ".$word;
		}
		return $newsearch;
	}
	
	function maketabbysearch($mySearch, $arraytab)
	{

	$i=0;
		foreach ($arraytab AS $locution)
		{
			//$locution = str_replace("_", " ", $locution );
			
			if( strlen(strstr($locution, $mySearch))>0)
			{
				$tab[$i] = $locution;
				$i++;
			}
		
		}

		//$locution = str_replace(" ", "_", $mySearch );
		
		
		
		foreach ($arraytab AS $locution)
		{
			//$locution = str_replace("_", " ", $locution );
		
			$mySearch02 = explode(" ",$mySearch);
			$nbelements = sizeof($mySearch02);
			//echo "nbelements ".$nbelements;

			//echo " ".$mySearch02[1];
				if(isset($mySearch02[$nbelements-1]) && $mySearch02[$nbelements-1]!='')
				{
				if( strlen(strstr($locution, $mySearch02[$nbelements-1]))>0)
				{
					$tab[$i] = $locution;
					$i++;
				}
				}				
		}
		
		
		
		
		
		/*
				echo "<pre>";
				print_r($tab);
				echo "</pre>";
		*/
		if(isset($tab))	{ $tab = array_unique($tab); $tab=triebylastwordsearch($tab , $mySearch02[$nbelements-1] ); return $tab;}
		else return array();
	}
	
	function display($title, $array)
	{		
		echo "<pre>";
		echo $title." => ";
		print_r($array);
		echo "</pre>";
	}
	
	function triebylastwordsearch($tab , $lastword )
	{
		$tab2=array(); $tab3=array();
		$i=0;
		$j=0;
		//echo $lastword;
		foreach ($tab AS $locution)
		{
			if(strncmp ($locution,$lastword,strlen($lastword))==0) 
			{
			 	$tab2[$i]=$locution; 
					$i++;
			 }
			 else
			 {
			 	$tab3[$j]=$locution;	
				$j++;
			 }
		
		}
		
		$tabresults = array_merge($tab2,$tab3);

		return ($tabresults);
	}
	
	function display_col($array)
	{
		if(isset($array))
		{		
			foreach($array AS $locution)
			{
			//$locution=str_replace("_"," ",$locution);
				print_r("<div class='words button2'><span class='wordeye'><img width='10' height='10' src='../../images/plusima2.png'></span>".$locution."</div>");
			}
		}
	}
	

	
	
	function result0($_results)
	{
		return $_results[0]["results"][0][0];
	}
?>