<?php 
include_once 'class_km_server.php';

class searchikm {
				
	 public $s;
	 public $session;
	 public $currentsession;
	 public $knw;
	 
	 public	$tabcontext;
	 public $tabresult;
	 public $tabcontents;
	 public $tablasttimes;
	 public $totalasttime;
	
	function __construct() 
	{
		$this->s = new KMServer;
		//$this->session = $this->s->KMId;
		//$this->knw = 'wikiknw';
	}
	
	public function connectIKM_TEST()
	{
			$this->s->IP = '192.168.0.12';
			$this->s->Port = '1254'; //52
			$this->s->connect();
			$this->knw = 'wikiknw';
			$this->session = $this->s->KMId;
	}
	
	
	public function connectIKM_TEST_ALPHA()
	{
			$this->s->IP = '192.168.0.12';
			$this->s->Port = '2000'; //52
			$this->s->connect();
			$this->knw = 'wikiknw';
			$this->session = $this->s->KMId;
	}	
	
	public function connectIKM_cancer()
	{
			$this->s->IP = '192.168.0.12';
			$this->s->Port = '1291'; //52
			$this->s->connect();
			$this->knw = 'wikiknw';
			$this->session = $this->s->KMId;
	}	
	
	public function connectIKM_cardio()
	{
			$this->s->IP = '192.168.0.12';
			$this->s->Port = '1292'; //52
			$this->s->connect();
			$this->knw = 'wikiknw';
			$this->session = $this->s->KMId;
	}		

	public function connectIKM_MED()
	{
			//$this->s->IP = '192.168.0.14';
			//$this->s->IP = '192.168.0.12';
			$this->s->IP = '192.168.0.12';
			//$this->s->IP = '192.168.0.10';
			//$this->s->IP = '90.31.162.245';
			$this->s->Port = '1261'; //52
			$this->s->connect();
			$this->knw = 'wikiknw';
			$this->session = $this->s->KMId;
			//$this->currentsession = $this->s->KMCurrentId;		
	}	
		
	public function connectIKM_ENG()
	{
			//$this->s->IP = '192.168.0.14';
			$this->s->IP = '127.0.0.1';
			//$this->s->IP = '192.168.0.10';
			//$this->s->IP = '90.31.162.245';
			$this->s->Port = '1252'; //52
			$this->s->connect();
			$this->knw = 'wikiknw';
			$this->session = $this->s->KMId;
			//$this->currentsession = $this->s->KMCurrentId;		
	}

	public function connectIKM_FR()
	{
			//$this->s->IP = '88.189.240.38';
			//$this->s->IP = '192.168.0.10';
			$this->s->IP = '192.168.0.12';
			//$this->s->IP = '127.0.0.1';
			$this->s->Port = '1255';//55
			//$this->s->Port = '1259';//55
			$this->s ->connect();
			$this->knw = 'wikiknw';
			$this->session = $this->s->KMId;
			//$this->currentsession = $this->s->KMCurrentId;			
	}
	
	

	public function connect($ip, $port)
	{
		$this->s->IP = $ip;
			$this->s->Port = $port;
			$this->s ->connect();
			$this->knw = 'wikiknw';
			$this->session = $this->s->KMId;	
	}
	
	
	public function Stream ($table,$dbid,$column)
	{
    $str ='';
	$pos = 1;
    $read = 0;
    $total = 0;
    // on peut augmenter largement cela, c'est la taille d'un block � lire 4096 est pas mal en g�n�ral
    // vis � vis de la taille des blocs IP
	// $str = Stream ($s,$s->KMId,"emailmaster", "186","texte");
    $size = 4096; 
	$f = 'TABLE:' .$table . '.READBLOCK' ;
	
	while ($pos > 0)
		{		
			$this->s->Execute ( $this->session, $f, $dbid, $column, $pos, $size ) ; 
			$total = (int) $this->s->KMResults [0]["results"][0][0];
			$read += (int) $this->s->KMResults [0]["results"][0][1];
			$pos   = (int) $this->s->KMResults [0]["results"][0][2];
			$str .= 	   $this->s->KMResults [0]["results"][0][3];	
		}	
	return $str;
	}	
	
	public function closesession() {$this->s->CloseKMSession($this->s->KMCurrentId);}
	public function getSession(){return $this->session; }
	
	public function readTOTALSEM(){
	$this->s->Execute ($this->session , 'KNOWLEDGE:wikiknw.GET','TOTALSEM');
	}		
	
	public function readTOTALREFS(){
	$this->s->Execute ($this->session , 'KNOWLEDGE:wikiknw.GET','TOTALREFS');
	}
	
	public function readTOTALKEYS(){
	$this->s->Execute ($this->session , 'KNOWLEDGE:wikiknw.GET','TOTALKEYS');
	}
	
	
	public function readINDEXATIONCACHEUSED(){
	$this->s->Execute ($this->session , 'KNOWLEDGE:wikiknw.GET','INDEXATIONCACHEUSED');
	}
	public function  initLasttime()
	{
		unset($tablasttimes);
		$this->totallastime=0;
		$this->tablasttimes = array(1 => 'Query Time:');
	}
	public function getLastTime($message)
	{
		$this->s->Execute ($this->session , 'GET','LASTTIME');//on vide la pile de contextes	
		$lasttime = $this->s->KMResults[0]['results'][0][0].' ms ';
		$this->totallastime = $this->totallastime +  $lasttime;
		$this->tablasttimes[] = $message.": \t\t".$lasttime." \t-> ".$this->totallastime." ms ";
		//echo 'lasttime: '.$lasttime;
	}
	
	public function FILTERVOIDELEMENTSok($mysearch)
	{
		$this->initLasttime();
		  //on met la connaissance par d�faut de l'objet contexts de la session
	    $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
	    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
	    $this->s->Execute ($this->session, 'CONTEXTS.NEW'); //on cr�e un nouveau contexte vide
	    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');   
	    // CONTEXTS.NEWFROMSUBATTRACTOR(<1 0/>, <2 -1/>)
	    $this->s->Execute ($this->session , 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1');	   
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');	   
		//$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');  
		$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	   

		 $filtered = $this->s->KMResults;
		 return $filtered;
	}
	
	public function filtervoidelements()
	{
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
		$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );
		return $this->s->KMResults[0]['results'][0][0];
	}
	
	public function getLastTimeTab() 
	{
			return $this->tablasttimes;
	}
	
	public function getTotalLastTime()
	{
		return $this->totallastime;
	}
	public function dirtydebug($mysearch) //renormalisation de jauge 
	{	
			
		$strtest =  strstr($mysearch, 'histoire d');
		$strtest =  $strtest.strstr($mysearch, 'Histoire d');
		$strtest =  $strtest.strstr($mysearch, 'history o');
		$strtest =  $strtest.strstr($mysearch, 'history i');
		$strtest =  $strtest.strstr($mysearch, 'history a');
		$strtest =  $strtest.strstr($mysearch, 'Le ');
		$strtest =  $strtest.strstr($mysearch, 'La ');
		if($strtest!="" ) 
		{
			$mysearch = $this->mixword($mysearch);			
		 }
		
		 return $mysearch;
	}
	
	public function mixword($str) 
	{
		$strmixed="";
		$table = explode (" ",$str);		
		shuffle($table);
	//$this->aff_tabl($table);
		foreach ( $table AS $str2)
		{			
			$strmixed = $strmixed.$str2." ";
		}		
		return $strmixed;
	}
	
	
		/*
		$this->s->Execute ($this->session , 'CONTEXTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session , 'RESULTS.CLEAR'); //on vide la pile de RS
		$this->s->Execute ($this->session , 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session , 'CONTEXTS.NEW'); //on cr�e un nouveau contexte vide
		$this->s->Execute ($this->session , 'CONTEXTS.ADDELEMENT',$mysearch,'100'); $this->getLastTime("'CONTEXTS.ADDELEMENT',$mysearch,'100'");
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true'); $this->getLastTime("'CONTEXTS.FILTERVOIDELEMENTS','true'");
		$this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','true','-1'); $this->getLastTime("'CONTEXTS.TORESULTS','true','-1'");
		//$this->s->Execute ($this->session , 'RESULTS.SHRINK','1000'); $this->getLastTime("'RESULTS.SHRINK','1000'");
		$this->s->Execute ($this->session , 'RESULTS.SortBy','Act','false'); $this->getLastTime("'RESULTS.SortBy','Act','false'");
    //$this->s->Execute ($this->session , 'RESULTS.SelectBy','Act','>','5'); $this->getLastTime("'RESULTS.SelectBy','Act','>','1'");		
		*/	
		public  function searchLikeGooglefornewsite($mysearch)
	{	
	//echo "searchLikeGoogle ";
		$this->initLasttime();  
		$mysearch = $this->dirtydebug($mysearch);
		$this->MVSearch($mysearch) ; 
		
    $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount'); 
	if (isset($this->s->KMResults))
		{
			$count = $this->s->KMResults;
			$N = $count[0]["results"][0][0];
			//$this->aff_tabl($count);		
			if ($count[0]["results"][0][0]<1) { 
				$this->searchLikeGoogleR($mysearch); echo '<div class="smalltexte cadre3">nothing found in procedural mode, I searched for you  by using Semantic Base Mode</div>'; 
			}
			else {
			
				//echo "<span class='ptextebig cadre4'>".$count[0]["results"][0][0]." results in procedural mode </span>";
				//$this->	displayCategoryButton();
				
				//$this->displaypagebuttons('index.php?modepage=1&pagestart=',  $count);
				
		
			}
			
			//$this->s->Execute ($this->session , 'RESULTS.SelectBy','Act','>','1'); $this->getLastTime("'RESULTS.SelectBy','Act','>','1'");		
			//$this->s->Execute ($this->session , 'RESULTS.SortBy','Act','false'); $this->getLastTime("'RESULTS.SortBy','Act','false'");		
		}
	}
	
	
	public  function searchLikeGoogle($mysearch)
	{	
	echo "searchLikeGoogle ";
		$this->initLasttime();  
		$mysearch = $this->dirtydebug($mysearch);
		$this->MVSearch($mysearch) ; 
		
    $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount'); 
	if (isset($this->s->KMResults))
		{
			$count = $this->s->KMResults;
			$N = $count[0]["results"][0][0];
			//$this->aff_tabl($count);		
			if ($count[0]["results"][0][0]<1) { 
				$this->searchLikeGoogleR($mysearch); echo '<div class="smalltexte cadre3">nothing found in procedural mode, I searched for you  by using Semantic Base Mode</div>'; 
			}
			else {
			
				echo "<span class='ptextebig cadre4'>".$count[0]["results"][0][0]." results in procedural mode </span>";
				$this->	displayCategoryButton();
				
				$this->displaypagebuttons('index.php?modepage=1&pagestart=',  $count);
				
		
			}
			
			//$this->s->Execute ($this->session , 'RESULTS.SelectBy','Act','>','1'); $this->getLastTime("'RESULTS.SelectBy','Act','>','1'");		
			//$this->s->Execute ($this->session , 'RESULTS.SortBy','Act','false'); $this->getLastTime("'RESULTS.SortBy','Act','false'");		
		}
	}	
  
  
  
	

 public  function searchLikeGoogleR($mysearch)
	{			
	echo "searchLikeGoogleR ";
		$checksem = false;
		$checksemlink = false; 
		if(isset($_GET['sem']))  {$checksem = true;}
		if(isset($_GET['semlink'])) $checksemlink = true; 
		echo "check: ".$checksem." ".$checksemlink;
		
	
		$mysearch = $this->dirtydebug($mysearch);
    $this->MVSearchSem($mysearch); $this->getLastTime("'MVSearchSem',$mysearch,'100'");
   /*
	   //on met la connaissance par d�faut de l'objet contexts de la session
	  
      $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');
	    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); 
	    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw ); 	    
	    $this->s->Execute ($this->session, 'CONTEXTS.NEW'); 
	    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100'); $this->getLastTime("'CONTEXTS.ADDELEMENT',$mysearch,'100'");
	    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true'); $this->getLastTime("'CONTEXTS.FILTERVOIDELEMENTS','true'");
	   
      if(isset($_GET['sem'])) {
	    	echo " sem ";
	    	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','0',''.$_GET['semfact'].'' );$this->getLastTime("'CONTEXTS.NEWFROMSEM','0','0',".$_GET["semlinkfact"].",'-1'");        	
	    }
	    if(isset($_GET['semlink'])) {
	    	echo " semlink ";
	    	$this->s->Execute ($this->session, 'CONTEXTS.SWAP'); $this->getLastTime("'CONTEXTS.SWAP ()'");
		    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','-1',''.$_GET["semlinkfact"].'' ); $this->getLastTime("'CONTEXTS.NEWFROMSEMLINKS','2','0',".$_GET["semlinkfact"].",'-1'");
			$this->s->Execute ($this->session, 'CONTEXTS.ROLLUP','3' ); $this->getLastTime("'CONTEXTS.ROLLUP' ,'3' ");
			$this->s->Execute ($this->session, 'CONTEXTS.UNION'); $this->getLastTime("'CONTEXTS.UNION'");
	    }
		$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');$this->getLastTime("'CONTEXTS.NORMALIZE'");

		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','33','true');$this->getLastTime("'CONTEXTS.CONTEXTS.FILTERACT','33','true'");

 
        $this->s->Execute ($this->session, 'CONTEXTS.UNION');$this->getLastTime("'CONTEXTS.UNION'");
 		if(isset($_GET['gen'])){ $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','true' );  $this->getLastTime("'CONTEXTS.SortByGenerality','true','true'");}
  
	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','100'); $this->getLastTime("'CONTEXTS.TORESULTS','false','-1'");
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','5'); $this->getLastTime("'RESULTS.SelectBy','Act','>','5'");
	    if(isset($_GET['act'])) {$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false'); $this->getLastTime("'RESULTS.SortBy','Act','false'");}
	    $this->s->Execute ($this->session , 'RESULTS.SHRINK','1000'); $this->getLastTime("'RESULTS.SHRINK','1000'");
	    
    */  
      $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount'); 
		$count = $this->s->KMResults;
		echo "<span class='ptextebig cadre4'>".$count[0]["results"][0][0]." results in semantic base mode </span>";
		$this->	displayCategoryButton();	
					
		
	$this->displaypagebuttons('index.php?modepage=2&sem=sem&semfact=15&semlinkfact=15&act=act&pagestart=',  $count);

			    
	} 
	
	
	public function displaypagebuttons( $urlpagebuttons, $count)
	{
	
		    $pager=1;	$pagerbr=0; $pagestart=1;
		echo '<div class="cadre2 displaytable">';	
		echo '<span class="textesmallbutton buttonpage2 cadrepagebuttons"><a href="'.$urlpagebuttons."1".'">'."1".'</a></span>';
			for($i=1;$i<$count[0]["results"][0][0];$i+=20)
			{		
				if(isset($_GET['pagestart'])) {$pagestart=$_GET['pagestart'];}						
				if($i > $pagestart && $i < $pagestart + 20*7)
				{
					echo '<span class="textesmallbutton buttonpage2 cadrepagebuttons"><a href="'.$urlpagebuttons.$i.'">'.$i.'</a></span>';
				}

					

				if ( $i >= $count[0]["results"][0][0]-20*1)
				{
				echo '<span class="textesmallbutton buttonpage2 cadrepagebuttons"><a href="'.$urlpagebuttons.($pagestart+20).'">'.">>>".'</a></span>';			
				echo '<span class="textesmallbutton buttonpage2 cadrepagebuttons"><a href="'.$urlpagebuttons.($pagestart-20).'">'."<<<".'</a></span>';			
					echo '<span class="textesmallbutton buttonpage2 cadrepagebuttons"><a href="'.$urlpagebuttons.$i.'">'.$i.'</a></span>';				
				}
				//if($pager==19){$pager=0; $pagerbr++; }
				$pager++;
			}			 
		echo '</div>';
		
	}
	
	
	public  function searchLikeGoogleR2($mysearch)
	{			
	echo "searchLikeGoogleR2 ";
		$checksem = false;
		$checksemlink = false; 
		if(isset($_GET['sem']))  {$checksem = true; echo $_GET['sem'];}
		if(isset($_GET['semlink'])) $checksemlink = true; 
		//echo "check: ".$checksem." ".$checksemlink;
		
		$this->initLasttime();
		$mysearch = $this->dirtydebug($mysearch);
	   //on met la connaissance par d�faut de l'objet contexts de la session
	    $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');
	    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); 
	    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw ); 
	    $this->s->Execute ($this->session, 'CONTEXTS.NEW'); 
	    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100'); $this->getLastTime("'CONTEXTS.ADDELEMENT',$mysearch,'100'");
	    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true'); $this->getLastTime("'CONTEXTS.FILTERVOIDELEMENTS','true'");
	    //$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE'); $this->getLastTime("'CONTEXTS.EVALUATE'");
	    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
	    //$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','0','-1' );$this->getLastTime("'CONTEXTS.NEWFROMSEM','0','0','-1' ");
	    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','-1','-1' ); $this->getLastTime("'CONTEXTS.NEWFROMSEMLINKS','2','0','-1','-1'");
	    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMATTRACTOR','0','-1' ); 
	    //$this->s->Execute ($this->session, 'CONTEXTS.UNION');$this->getLastTime("'CONTEXTS.UNION'");
	    $this->s->Execute ($this->session, 'CONTEXTS.UNION');$this->getLastTime("'CONTEXTS.UNION'");
	    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE'); $this->getLastTime("'CONTEXTS.EVALUATE'");
	    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','100','true');$this->getLastTime("'CONTEXTS.CONTEXTS.FILTERACT','100','true'");
	    $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','true' );  $this->getLastTime("'CONTEXTS.SortByGenerality','true','true'");   
	  //   $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','true' ); $this->getLastTime("'CONTEXTS.SortByGenerality','true','true'");  
	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1'); $this->getLastTime("'CONTEXTS.TORESULTS','false','-1'");
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','5'); $this->getLastTime("'RESULTS.SelectBy','Act','>','5'");
	    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false'); $this->getLastTime("'RESULTS.SortBy','Act','false'");
	    $this->s->Execute ($this->session , 'RESULTS.SHRINK','1000'); $this->getLastTime("'RESULTS.SHRINK','1000'");
	    $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount'); 
		$count = $this->s->KMResults;
		echo "<span class='ptextebig cadre4'>".$count[0]["results"][0][0]." results in semantic base mode</span>";
		$this->	displayCategoryButton();	
			
		$this->displaypagebuttons('index.php?modepage=1&pagestart=',  $count);
	  
			    
	}	
	
	public  function searchByRMX($mysearch) // categorize
	{	
	echo "searchByRMX ";
		$this->initLasttime();
		$seuil='1';
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');$pile=0;
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'CONTEXTS.NEW');$pile+=1;
		$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
		  $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); $pile+=1;
		  $this->s->Execute ($this->session, 'CONTEXTS.UNION');$pile-=1;
		  		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
		  $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); $pile+=1;
		  		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
		  $this->s->Execute ($this->session, 'CONTEXTS.UNION');	$pile-=1;	
	    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','1','100'); $pile+=1;
	    		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
	    $this->s->Execute ($this->session, 'CONTEXTS.GET','SIZE');
		$count = $this->s->KMResults;

		if($count[0]["results"][0][0]<2) {						
	    	 	$this->s->Execute ($this->session, 'CONTEXTS.DROP' );$pile-=1; 
				$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','0','-1' );$pile+=1; 
	    		$seuil='3';
		    }		
		$this->s->Execute ($this->session, 'CONTEXTS.PARTITION',$seuil,'0','0','32');	
//$this->getLastTime();			
		$this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');
		$count = $this->s->KMResults;
		
		echo "<span class='cadre4 ptextebig'> ".($count[0]["results"][0][0]-$pile)." Natural Categories</span>";	
		
		for($i=0; $i<$count[0]["results"][0][0]-$pile;$i++) {
			$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','30');	

			
			 $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );
			
			$tabcontext = $this->s->KMResults[0]["results"];	
			$this->tabcontents[0][$i] = $tabcontext;
			//echo " nb ".$countarticles[0]["results"][0][0];	
			
			$this->s->Execute ($this->session, 'CONTEXTS.DROP' );
			$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0');
			$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','0');
			$this->s->Execute ($this->session, 'RESULTS.NORMALIZE','RELATIVE');
			$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
			$countarticles = $this->s->KMResults;		
			//echo	$countarticles[0]["results"][0][0]." ";		
			$this->formatResult("8", "title act link_wikifr ROWID KNW_LANGAGE KNW_MEANING link_wikieng");
			$tabresult = $this->s->KMResults[0]["results"];			
			$this->tabcontents[1][$i] = $tabresult;
			$this->tabcontents[2][$i] = $countarticles[0]["results"][0][0];
			//$this->aff_tabl($this->tabcontents);
			$this->s->Execute ($this->session, 'RESULTS.DROP' );
		}
	}	
	
	public function searchByRMX2($mysearch){
		echo "searchByRMX2 ";
		$this->initLasttime();
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');$pile=0;
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'CONTEXTS.NEW');$pile+=1;
		$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');		
		$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1'); $pile+=1;
		$this->s->Execute ($this->session, 'CONTEXTS.UNION');$pile-=1;
		$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','0','-1' );$pile+=1; 
		//$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );$pile+=1; 
		$this->s->Execute ($this->session, 'CONTEXTS.UNION');$pile-=1;
		$this->s->Execute ($this->session, 'CONTEXTS.UNION');$pile-=1;
		$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');
		$this->partitionMultiContext('0','1','1', $pile, '50' );
	}
	
	public function searchNaturalCategory($mysearch){
		echo "searchNaturalCategoryXXX ";
	$this->initLasttime();
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );  $pile=1;
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','-1','-1' ); $pile+=1;
    $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
    $N = $this->s->KMResults[0]['results'][0][0]; 
    if ($N < 5)
    { 	 
	echo " $N < 5 ";
       $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );
       //$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1' ); $pile+=1;
       //$this->s->Execute ($this->session, 'CONTEXTS.SWAP' );
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' ); $pile+=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       //$this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','-1','-1' ); $pile+=1;
       $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
       $N = $this->s->KMResults[0]['results'][0][0]; 
	  
       if ($N < 1)
         {	
		 	echo " $N < 1 ";
           $this->s->Execute ($this->session, 'CONTEXTS.DROP' ); $pile-=1;
           $this->s->Execute ($this->session, 'CONTEXTS.NEW' );  $pile+=1;
           $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
           $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
           $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' ); $pile+=1;
           $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
           $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','1'); 
           $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
           $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' ); $pile+=1;
           $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
           $N = $this->s->KMResults[0]['results'][0][0]; 
         }
       
    }
    
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
		if ($N > 150)
		{
		   $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','1'); 
		}
    
		//$this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','true' );  $this->getLastTime("'CONTEXTS.SortByGenerality','true'");
		$this->partitionMultiContext('0', '1', '0' ,'16',$pile);
	}

	public function searchNaturalSemCategory($mysearch){	
		$this->initLasttime();
	  $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	  $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );  $pile=1;
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' ); $pile+=1;
    $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
    $N = $this->s->KMResults[0]['results'][0][0]; 
    if ($N < 5)
    { 
       $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );
      // $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1' ); $pile+=1;
      // $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' ); $pile+=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       //$this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' ); $pile+=1;
       
       $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
       $N = $this->s->KMResults[0]['results'][0][0]; 
       if ($N<1)
         {
           $this->s->Execute ($this->session, 'CONTEXTS.DROP' ); $pile-=1;
           $this->s->Execute ($this->session, 'CONTEXTS.NEW' );  $pile+=1;
           $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
           $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
           $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' ); $pile+=1;
           $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
           $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25'); 
           $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
           $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' ); $pile+=1;
           $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
           $N = $this->s->KMResults[0]['results'][0][0]; 
         }
       
    }
    
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
    if ($N > 150)
    {
       $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25'); 
    }
    
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','0','-1' );  $pile+=1;
    $this->s->Execute ($this->session, 'CONTEXTS.UNION'); $pile-=1;
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','15');
    $this->s->Execute ($this->session, 'CONTEXTS.UNION'); $pile-=1;
    
  	

		//$this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','true' );  $this->getLastTime("'CONTEXTS.SortByGenerality','true'");
		$this->partitionMultiContext('1', '1', '5' ,'16',$pile);
	}	
	
	public function searchMaxCategory($mysearch){
		echo "searchMaxCategory ";
		$this->initLasttime();
		$mysearch = $this->dirtydebug($mysearch);
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');$pile=0;
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'CONTEXTS.NEW');$pile+=1;
		$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100'); $this->getLastTime("'CONTEXTS.ADDELEMENT',$mysearch,'100'");
		//$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true'); $this->getLastTime("'CONTEXTS.FILTERVOIDELEMENTS','true'");

  	
    $this->MVContextExtend(); 
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');

    $this->MVContextExtend(); 
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');
    /*
    $this->MVContextExtend(); 
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');
    $this->MVContextExtend(); 
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');
    */

		$this->partitionMultiContext('1', '1', '5' ,'16',$pile);
	}
		
	public function partitionMultiContext($contextsize, $evaluate, $seuil, $nbmaxCat, $pile){
		echo "partitionMultiContext ";
//CONTEXTS.PARTITION',taille_mini du categorie 0 autoevaluation,'Evaluate false ou true','Seuil mini','nombre de categorie maxi');	
		$this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');
		$countCat1 = $this->s->KMResults;
		$this->s->Execute ($this->session, 'CONTEXTS.PARTITION',$contextsize,$evaluate,$seuil,$nbmaxCat);	
		$this->getLastTime("'CONTEXTS.PARTITION',$contextsize,$evaluate,$seuil,$nbmaxCat)");			
		$this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');
		$countCat = $this->s->KMResults;
		
		
		//CONTEXTS.PARTITION(<1 4/>, <1 1/>, <1 0/>, <2 -1/>)
		// $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );
		//echo "<div><span class='cadre4 ptextebig'>".($countCat[0]["results"][0][0]-$pile)." Categories</span><div>";	
		echo "<div><span class='cadre4 ptextebig'>".($countCat[0]["results"][0][0] - $countCat1[0]["results"][0][0])." Categories</span><div>";
		$index=0;
		$without=0;
		for($i=0; $i < ($countCat[0]["results"][0][0] - $countCat1[0]["results"][0][0]);$i++) {
			//$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');$this->getLastTime("'CONTEXTS.FILTERVOIDELEMENTS','true'");
			$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','1');
      		$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false', '100');$this->getLastTime("'CONTEXTS.TORESULTS','false'");
			//$this->s->Execute ($this->session, 'RESULTS.NORMALIZE','RELATIVE');$this->getLastTime("'CONTEXTS.NORMALIZE','RELATIVE'");	
			$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','1');$this->getLastTime("'RESULTS.SelectBy','Act','>','1'");	
			$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
			$countarticles = $this->s->KMResults;
			if ($countarticles[0]["results"][0][0] != "0")
			{
				//$this->aff_tabl($this->s->KMResults);
				$this->tabcontents[2][$index] = $countarticles[0]["results"][0][0];			
				$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );				
				$tabcontext = $this->s->KMResults[0]["results"];
				//$this->aff_tabl($this->s->KMResults);	
				$this->tabcontents[0][$index] = $tabcontext;	
				$this->s->Execute ($this->session, 'CONTEXTS.DROP' );				
				$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false');
	
				$this->formatResult("8", "title act link_wikifr texte ROWID KNW_LANGAGE KNW_MEANING link_wikieng");
				$tabresult = $this->s->KMResults[0]["results"];			
				$this->tabcontents[1][$index] = $tabresult;
				
				$index++;
				}
			else 
				{
					$without++;
					$this->s->Execute ($this->session, 'CONTEXTS.DROP' );

				}
					
			$this->s->Execute ($this->session, 'RESULTS.DROP' );
		}
		echo $without." Categories without article in procedural mode ";			
	}

	
	public function partition($seuil, $pile, $nbarticle){
		echo " partition ";
	//unset($tabcontext);
	//unset($tabresult);
	//unset($this->tabcontents);

		$this->s->Execute ($this->session, 'CONTEXTS.PARTITION',$seuil,'false','0','32');		
		// 	CONTEXTS.PARTITION',taille_mini du categorie 0 autoevaluation,'Evaluate false ou true','Seuil mini','nombre de categorie maxi');	
		$this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');
		$countCat = $this->s->KMResults;
//$this->aff_tabl($this->s->KMResults);
		echo "<div><span class='cadre4 ptextebig'>".($countCat[0]["results"][0][0]-$pile)." Categories</span><div>";							
		//$this->s->Execute ($this->session, 'CONTEXTS.DROP' );
		for($i=0; $i<$countCat[0]["results"][0][0]-$pile;$i++) {
		
			$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1'); // 30 changer aussi dans searchLikeGoogleCat
			$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
			$countarticles = $this->s->KMResults;
			//$this->aff_tabl($this->s->KMResults);
			$this->tabcontents[2][$i] = $countarticles[0]["results"][0][0];
				
			$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );			
			$tabcontext = $this->s->KMResults[0]["results"][0];	
			//$this->aff_tabl($this->s->KMResults[0]["results"][0]);			
			$this->tabcontents[0][$i] = $tabcontext;	
			//$this->aff_tabl($tabcontext);
			$this->s->Execute ($this->session, 'CONTEXTS.DROP' );
			$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','1');
			$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false');
			$this->s->Execute ($this->session, 'RESULTS.NORMALIZE','RELATIVE');
			$this->formatResult("8", "title act link_wikifr texte ROWID KNW_LANGAGE KNW_MEANING link_wikieng");
			$tabresult = $this->s->KMResults[0]["results"];			
			$this->tabcontents[1][$i] = $tabresult;
			//$this->aff_tabl($tabresult);
			//$this->tabcontents[2][$i] = $countarticles[0]["results"][0][0];
			
			$this->s->Execute ($this->session, 'RESULTS.DROP' );
			$this->s->Execute ($this->session, 'GET.TIME' );
			
		}
	//$this->aff_tabl($this->tabcontents);
	}
	
	public  function searchLikeGoogleCat($mysearch)
	{	
	echo " searchLikeGoogleCat ";
		$this->initLasttime();
		//echo "searchLikeGoogleCat";
		$mysearch = $this->dirtydebug($mysearch);
		$this->s->Execute ($this->session , 'CONTEXTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session , 'RESULTS.CLEAR'); //on vide la pile de RS
		$this->s->Execute ($this->session , 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session , 'CONTEXTS.NEW'); //on cr�e un nouveau contexte vide
		$this->s->Execute ($this->session , 'CONTEXTS.ADDELEMENT',$mysearch,'100'); $this->getLastTime("'CONTEXTS.ADDELEMENT',$mysearch,'100'");
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');$this->getLastTime("'CONTEXTS.FILTERVOIDELEMENTS','true'");
		$this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','false','30');$this->getLastTime("'CONTEXTS.TORESULTS','false','30'");
		$this->s->Execute ($this->session, 'RESULTS.NORMALIZE','RELATIVE');$this->getLastTime("'RESULTS.NORMALIZE','RELATIVE'");
		$this->s->Execute ($this->session , 'RESULTS.SelectBy','Act','>','1');		$this->getLastTime("'RESULTS.SelectBy','Act','>','1'");
		$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
		$count = $this->s->KMResults;
		//$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );		
		$this->s->Execute ($this->session , 'RESULTS.SortBy','Act','false'); $this->getLastTime("'RESULTS.SortBy','Act','false'");

	}	
	
		
	public function categorizationpages($mysearch) {
		echo "categorizationpages";
		$this->searchLikeGoogleCat($mysearch);
		$this->formatResult("8", "title act link_wikifr texte ROWID KNW_LANGAGE KNW_MEANING link_wikieng");
		$tab = $this->s->KMResults;
		return $tab;	
	}
	
	
	public function connexearticles ($rowid)
	{
		echo " similar article ";
		$this->initLasttime();$this->getLastTime("'function connexearticles'");
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'CONTEXTS.NEW');
		$this->s->Execute ($this->session, 'TABLE:wikimaster2.TOCONTEXT',$rowid);	$this->getLastTime("'TABLE:wikimaster2.TOCONTEXT',$rowid)");	

		
		$this->s->Execute ($this->session, 'CONTEXTS.DUP');                       $this->getLastTime("'CONTEXTS.DUP'");
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');                  $this->getLastTime("'CONTEXTS.EVALUATE'");
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25','true' );    $this->getLastTime("'CONTEXTS.FILTERACT','25'");
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','1','-1','-1' ); $this->getLastTime("'CONTEXTS.NEWFROMSEM','1','0','-1'");
		$this->s->Execute ($this->session, 'CONTEXTS.SWAP');                      $this->getLastTime("'CONTEXTS.SWAP'");	
		$this->s->Execute ($this->session, 'CONTEXTS.DROP');                      $this->getLastTime("'CONTEXTS.DROP'");
		$this->s->Execute ($this->session, 'CONTEXTS.SWAP');                      $this->getLastTime("'CONTEXTS.SWAP'");
		$this->s->Execute ($this->session, 'CONTEXTS.DUP');                       $this->getLastTime("'CONTEXTS.DUP'");
		$this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN','3');              $this->getLastTime("'CONTEXTS.ROLLDOWN','3'");
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');                     $this->getLastTime("'CONTEXTS.UNION'");	
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');                  $this->getLastTime("'CONTEXTS.EVALUATE'");
    $this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION');              $this->getLastTime("'CONTEXTS.INTERSECTION'");
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');                 $this->getLastTime("'CONTEXTS.NORMALIZE'");
    
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25','true' );    $this->getLastTime("'CONTEXTS.FILTERACT','25'");
   
		$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','25');	$this->getLastTime("'CONTEXTS.TORESULTS','false','50'");	

  	$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','10'); $this->getLastTime("'RESULTS.SelectBy','Act','>','10'");	
	 
    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false'); $this->getLastTime("'RESULTS.SortBy','Act','false'");		
	
		//$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' ); 
	    
	    
	    $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
		$count = $this->s->KMResults;
		

	    
		echo "<span class='ptextebig cadre4 toolTips' title='Search all articles with a same content 1. Very powerfull function !!!  '>".$count[0]["results"][0][0]." similar articles</span>";
		$this->	displayCategoryButton();
		$urltmp = 'index.php?modepage=1&rowid='.$rowid.'&pagestart=';
		$this->displaypagebuttons( $urltmp,  $count );     		
	 
		
	}
	
	public function displayCategoryButton() 
	{
		//echo '<span class="ptextebig button3"><a href="index.php?modepage=3&textboxsearch='.$_SESSION['strsearch'].'">Categorize Basic</a></span>';
		//echo '<span class="ptextebig button3"><a href="index.php?modepage=4&textboxsearch='.$_SESSION['strsearch'].'">Categorize with Semantique</a></span>';		
		//echo '<span class="ptextebig button3"><a href="index.php?modepage=7&textboxsearch='.$_SESSION['strsearch'].'">Categorize Max </a></span>';	
	}


	
	public function searchByRmxC($mysearch)
	{
		echo " searchByRmxC " ;
		$this->initLasttime();
		  //on met la connaissance par d�faut de l'objet contexts de la session
	    $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
	    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
	    $this->s->Execute ($this->session, 'CONTEXTS.NEW'); //on cr�e un nouveau contexte vide
	    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');   
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');	    
        $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMATTRACTOR','0','-1');
        $this->s->Execute ($this->session, 'CONTEXTS.UNION');
        $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
	    $this->s->Execute ($this->session, 'CONTEXTS.UNION');
	    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMATTRACTOR','0','-1');
	    $this->s->Execute ($this->session, 'CONTEXTS.UNION');
	    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1');
        $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE'); 
        $this->s->Execute ($this->session, 'CONTEXTS.UNION');
 	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1'); 
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false'); // on tri par Act descendante
	}
	
	public function searchSem($mysearch)
	{
		echo  " searchSem ";
		$this->initLasttime(); $this->getLastTime("'function searchSem'");
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
	    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
	    $this->s->Execute ($this->session, 'CONTEXTS.NEW'); //on cr�e un nouveau contexte vide
	    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');	    
	    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1');
	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1'); 
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante

	}
	
	public function searchSem2($mysearch)
	{	
			echo  " searchSem2 ";
		$this->initLasttime(); $this->getLastTime("'function searchSem2'");
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
	    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
	    $this->s->Execute ($this->session, 'CONTEXTS.NEW'); //on cr�e un nouveau contexte vide
	    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');	    
 	    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMATTRACTOR','0','-1');
	    $this->s->Execute ($this->session, 'CONTEXTS.UNION');   
	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1'); 
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante		
	}

	public function searchSem3($mysearch)
	{		
		$this->initLasttime(); $this->getLastTime("'function searchSem3'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');    
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );    
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile
	$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1'); 
	$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante	
	}	

	public function searchSem4($mysearch) //attractor link
	{		
		$this->initLasttime();$this->getLastTime("'function searchSem4'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMATTRACTOR','0','-1' );
    $this->s->Execute ($this->session, 'CONTEXTS.UNION' );
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );    
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	    
	}
	
	public function searchSem4Cat($mysearch) //SemanticLinks
	{		
		//echo "searchSem4Cat<br>"; searchSemSpaceCat
		$this->searchSem4($mysearch);
		$this->contextPartition();
	}
	
	public function searchSem4b($mysearch)
	{		
		$this->initLasttime();  $this->getLastTime("'function searchSem4b'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');    
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMATTRACTOR','0','-1' );
    $this->s->Execute ($this->session, 'CONTEXTS.UNION' );
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );    
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile
	$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1'); 
	$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante	
	}
	
	
	
	// serachforSemLink Semantic&Links
	public function searchSem5Simple($mysearch) //SemanticLinks
	{		
		$this->initLasttime(); $this->getLastTime("'function searchSem5simple'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');    
    //$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
    //$this->s->Execute ($this->session, 'CONTEXTS.UNION');	
	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','100' );    
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
	 if(isset($_GET['genact']) && $_GET['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}   
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile
	//$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false'); 
	//$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	//$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante	
	}

	public function searchSem5Ima($mysearch) //SemanticLinks
	{		
	$this->initLasttime();  $this->getLastTime("'function searchSem5ima'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
	$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');    
    //$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
    //$this->s->Execute ($this->session, 'CONTEXTS.UNION');	
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','1','-1','-1' );
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE' );
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','15' );
	//$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','100' );    
    //$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
    //$this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','true' );   
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile
	//$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false'); 
	//$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	//$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante	
	}
	
	public function searchFromSemTagsIma($mysearch)
	{		
	$this->initLasttime(); $this->getLastTime("'function searchFromSemTagsIma'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
	$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');    
	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');	
	  $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','1','-1','-1' );
	  $this->s->Execute ($this->session, 'CONTEXTS.UNION');	
  //$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','15','0','0','100' );    
	$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');     
	$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','15','true');////on s�lectionne les activit� >= 1%  
	// $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );  
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	
	
	public function searchSem5Cat($mysearch) //SemanticLinks
	{		
		//echo "searchSem5Cat<br>";
		$this->searchSem5($mysearch);
		$this->contextPartition();
	}
	

	
	public function contextPartition()
	{
		$pile=0;
		$this->tabcontents= '' ;
		$index=0;
		$this->s->Execute ($this->session, 'CONTEXTS.PARTITION','0', 'false', '1' ,'20');	
		$ctx = $this->s->KMResults [0]["results"][0][0]; 			
		//$this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');
		//$countCat = $this->s->KMResults;
		for($i=0; $i<$ctx;$i++) 
		{
		//$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
					$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );				
					$tabcontext = $this->s->KMResults;
						  
					//$this->aff_tabl($this->s->KMResults);	
					$this->tabcontents[0][$index] = $tabcontext;	
					$this->s->Execute ($this->session, 'CONTEXTS.DROP' );	
					$index++;
		}
		
		//$this->aff_tabl($this->s->KMResults);
	}
	
	
    public function searchSem5b($mysearch)
	{		
		$this->initLasttime(); $this->getLastTime("'function searchSem5b'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
	$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');    
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');	
	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','20' );    
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile
	$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1'); 
	$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante	
	}
	
	public function searchShape($mysearch)
	{		
		$this->initLasttime(); $this->getLastTime("'function searchShape'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
	$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');    
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','1','100' );    
    //$this->s->Execute ($this->session, 'CONTEXTS.UNION' );
    //$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );    
    //$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );} 
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	public function searchSemShapeSimple($mysearch)
	{		
		$this->initLasttime(); $this->getLastTime("'function searchSemShapeSimple'");
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1' );   
    		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    //$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    //$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' );
    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1' );  
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');     

    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	public function searchSemShape($mysearch)
	{		
	$this->initLasttime(); $this->getLastTime("'function searchSemShape'");
       $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
   $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
       $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
   $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
   $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
   $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');

  $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
  $N = $this->s->KMResults[0]['results'][0][0];  


  if ($N >1)
    { 
  //extension sem de la requete
   $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );   
   $this->s->Execute ($this->session, 'CONTEXTS.SWAP');
   $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );   
   $this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN', '3');
   $this->s->Execute ($this->session, 'CONTEXTS.UNION');
   $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');
	  $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1' );
	 $this->s->Execute ($this->session, 'CONTEXTS.SWAP');
	 $this->s->Execute ($this->session, 'CONTEXTS.DROP');
	 $this->s->Execute ($this->session, 'CONTEXTS.SWAP');
	 $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' );  
	 $this->s->Execute ($this->session, 'CONTEXTS.SWAP'); 
	 $this->s->Execute ($this->session, 'CONTEXTS.DROP');
	 $this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION');
	 $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');

 }

else
 {
  $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' ); 
}

   if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}
   $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );  

}


	public function searchAttractorCount($mysearch)
	{		
		$this->initLasttime();
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' ); 
    //$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true'); 
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','1','100' ); 
    //$this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );  
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );  	
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	  $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
	  $N = $this->s->KMResults[0]['results'][0][0];  
	  return $N;
	}

	
	public function searchAttractor($mysearch)
	{		
		$this->initLasttime();
	  $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	  $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' );
    $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
    $N = $this->s->KMResults[0]['results'][0][0]; 
    if ($N < 5)
    { 
       $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1' );
       $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' ); 
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' );
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' );
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' );
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' );
       
       $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
       $N = $this->s->KMResults[0]['results'][0][0]; 
       if ($N<1)
         {
           $this->s->Execute ($this->session, 'CONTEXTS.DROP' );
           $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
           $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
           $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
           $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' ); 
           $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
           $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25'); 
           $this->s->Execute ($this->session, 'CONTEXTS.UNION' );
           $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' );
           $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
           $N = $this->s->KMResults[0]['results'][0][0]; 
         }
       
    }
    
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
    if ($N > 150)
    {
       $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25'); 
    }
    
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	public function searchAttractorNew($mysearch)	
	{
		
    $this->initLasttime();
       $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
   $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
       $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
   $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
   $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
   $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');

  $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
  $N = $this->s->KMResults[0]['results'][0][0];  


  if ($N >1)
    { 
   //on �value l'ensemble des possibles locutions
   $this->s->Execute ($this->session, 'CONTEXTS.DUP' );
   $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' ); 
   $this->s->Execute ($this->session, 'CONTEXTS.UNION');
   $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' ); 
   $this->s->Execute ($this->session, 'CONTEXTS.SWAP');
   $this->s->Execute ($this->session, 'CONTEXTS.DROP');

  //extension sem de la requete
   $this->s->Execute ($this->session, 'CONTEXTS.SWAP');
   $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );   
   $this->s->Execute ($this->session, 'CONTEXTS.SWAP');
   $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );   
   $this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN', '3');
   $this->s->Execute ($this->session, 'CONTEXTS.UNION');
   $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );  
   $this->s->Execute ($this->session, 'CONTEXTS.UNION');
   $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');

  
  //restriction des locutions
   $this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN', '3'); 
   $this->s->Execute ($this->session, 'CONTEXTS.DUP' );
   $this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN', '3'); 
   $this->s->Execute ($this->session, 'CONTEXTS.DUP' );
   $this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN', '3'); 
   $this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION'); 
   $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');

  //evaluation grossi�re des activit�s
   $this->s->Execute ($this->session, 'CONTEXTS.UNION');
   $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
   $this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION'); 
   $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');
 }

else
 {
  $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' );  

}
		 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}
   $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile   

    

   	// strstr(tachaine,chaineatrouver) 
//	if ( strstr($mysearch,' ')!=true) { echo "xxxxxOK";$this->searchAttractorSimple($mysearch);  }   	
	}
	
	
	public function searchSemAttractor($mysearch){
		$this->initLasttime();
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    	$this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    	$this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    	$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    	$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMATTRACTOR','1','100' ); 
    	$this->s->Execute ($this->session, 'CONTEXTS.UNION' );
    	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','80' );   
    	$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );  
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}   
    	$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	public function searchSemAttractorNew($mysearch){
	$this->initLasttime();
    $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );

   $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
   $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');

   //on �value l'importance relative des termes de la requete
   $this->s->Execute ($this->session, 'CONTEXTS.DUP' );
   $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
   $this->s->Execute ($this->session, 'CONTEXTS.UNION');
  $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');

   //on �value les contextes dans la requete   
  $this->s->Execute ($this->session, 'CONTEXTS.PARTITION', '1', '0','0','-1'); 
// le retour donne le nombre de contextes : exemple SubClasses <1 2/> ;    soit NC
// ou alors on fait un  CONTEXTS.GET(<5 COUNT/>) pour avoir la taille finale de la pile  N, et NC = N-1
   	 $this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');		
     $NC = $this->s->KMResults[0]['results'][0][0]-1;     
     $i = $NC;
   // l� on fait une boucle sur NC genre
   //print_r($NC." ");
     while ($i >0)
     {
      $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' ); 
      $this->s->Execute ($this->session, 'CONTEXTS.UNION');
      $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );            
      $this->s->Execute ($this->session, 'CONTEXTS.SWAP');
            $this->s->Execute ($this->session, 'CONTEXTS.DROP');
            $this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN',$NC );    
            $i--;
    }

    $this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION', $NC-1);
    $this->s->Execute ($this->session, 'CONTEXTS.DUP' );
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');    
   $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT', '25','true');
   if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}   
   $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );		
	}
	
	
	
	public function searchSemAttractorCat($mysearch) //SemanticLinks
	{		
	//echo "searchSemAttractorCat<br>";
		$this->searchSemAttractor($mysearch);
		$this->contextPartition();
	}
	
	public function searchFromSem($mysearch)
	{		
		$this->initLasttime();
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' ); //CONTEXTS.NEWFROMSEM(<1 0/>, <2 -1/>, <2 -1/>) 
	/*
	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR' ); 
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1', '0','1' ); 
	$this->s->Execute ($this->session, 'CONTEXTS.SWAP');
    $this->s->Execute ($this->session, 'CONTEXTS.DROP' );
    $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); 
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' ); //CONTEXTS.NEWFROMSEM(<1 0/>, <2 -1/>, <2 -1/>) 
    $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); 
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
	    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');
	*/		
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' ); //CONTEXTS.NEWFROMSEM(<1 0/>, <2 -1/>, <2 -1/>) 
    $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); 	
	    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}       
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
       public function searchFromSemNew($mysearch)
       {                
               $this->initLasttime();
       $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
   $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
       $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
   $this->s->Execute ($this->session, 'CONTEXTS.NEW' );


   $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
   $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');

   //on �value l'importance relative des termes de la requete
   $this->s->Execute ($this->session, 'CONTEXTS.DUP' );
   $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
   $this->s->Execute ($this->session, 'CONTEXTS.UNION');
  $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');

   //on �value les contextes dans la requete   
   $this->s->Execute ($this->session, 'CONTEXTS.PARTITION', '1', '0','0','-1');
// le retour donne le nombre de contextes : exemple SubClasses <1 2/> ;    soit NC
// ou alors on fait un  CONTEXTS.GET(<5 COUNT/>) pour avoir la taille finale de la pile  N, et NC = N-1
  
   //$this->s->Execute ($this->session, 'CONTEXTS.GETCOUNT'); 
   // l� on fait une boucle sur NC genre
   	 $this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');		
     $NC = $this->s->KMResults[0]['results'][0][0]-1;     
     $i = $NC;
     //print_r($NC);
     //echo "xxxxxxxxxxxxxxxxxxxx $NC= ".$NC;
     
     while ($i >0)
     {
		//$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );
		$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );
		$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );
		//$this->s->Execute ($this->session, 'CONTEXTS.UNION');
		$this->s->Execute ($this->session, 'CONTEXTS.UNION');
		$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');
		//$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');
		
		$this->s->Execute ($this->session, 'CONTEXTS.SWAP');
		$this->s->Execute ($this->session, 'CONTEXTS.DROP');
		$this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN',$NC );
		$i--;
     	
    }

    $this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION', $NC-1);
    $this->s->Execute ($this->session, 'CONTEXTS.DUP' );
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');    
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT', '25','true');
    if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}    
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile        

   }

   	public function mixSem($mysearch)
   	{
   		echo "<pre>";
   		$this->searchSem5($mysearch);
   		$tab01=$this->readArrayKMserver();
		//print_r($tab01);
   		$this->searchFromSem($mysearch);
   		$tab02=$this->readArrayKMserver();
   		//print_r($tab02);
   		$this->searchSemAttractor($mysearch);
   		$tab03=$this->readArrayKMserver();
   		//echo "tab03";
   		//print_r($tab03);
   		$m01= array_merge($tab02[0]['results'],$tab03[0]['results']);
   		//echo "m01";
   		//print_r($m01);
   		$m02= array_merge_recursive($tab03,$m01);
   		
   		
   		$kmresults=$m02;
   		//echo "kmresults";
   		//print_r($kmresults);
   		echo "</pre>";
   		return $kmresults;
   		
   	}
   
   // Seamntic&Links
       public function searchSem5($mysearch)
       {                
        $this->initLasttime();
       	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
   		$this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
       	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
   		$this->s->Execute ($this->session, 'CONTEXTS.NEW' );
	   	$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
	   	$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');	
	   //on �value l'importance relative des termes de la requete
	   	$this->s->Execute ($this->session, 'CONTEXTS.DUP' );
	   	$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
	   	$this->s->Execute ($this->session, 'CONTEXTS.UNION');
	  	$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');

   //on �value les contextes dans la requete   
   $this->s->Execute ($this->session, 'CONTEXTS.PARTITION', '1', '0','0','-1');
// le retour donne le nombre de contextes : exemple SubClasses <1 2/> ;    soit NC
// ou alors on fait un  CONTEXTS.GET(<5 COUNT/>) pour avoir la taille finale de la pile  N, et NC = N-1
  
   //$this->s->Execute ($this->session, 'CONTEXTS.GETCOUNT'); 
   // l� on fait une boucle sur NC genre
   	 $this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');		
     $NC = $this->s->KMResults[0]['results'][0][0]-1;     
     $i = $NC;
     //print_r($NC);
     //echo "xxxxxxxxxxxxxxxxxxxx $NC= ".$NC;
     
     while ($i >0)
     {
     	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS', '2','0','0','-1' );     	
		$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );
		$this->s->Execute ($this->session, 'CONTEXTS.UNION');
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
		$this->s->Execute ($this->session, 'CONTEXTS.SWAP');
		$this->s->Execute ($this->session, 'CONTEXTS.DROP');
		$this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN',$NC );
		$i--;
     	
    }

    $this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION', $NC-1);
    $this->s->Execute ($this->session, 'CONTEXTS.DUP' );
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');
    $this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');    
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT', '25','true');
    if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}    
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile        

   }   
   
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function searchFromSemCat($mysearch) //SemanticLinks
	{		
		//echo "searchFromSemCat<br>";
		$this->searchFromSem($mysearch);
		$this->contextPartition();
	}
	
	public function searchFromSpaceold($mysearch)
	{		
		$this->initLasttime();
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '50' );
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSPACE','-1','50' ); //CONTEXTS.NEWFROMSEM(<1 0/>, <2 -1/>, <2 -1/>)
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}   
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}


	public function searchSemSpaceCat($mysearch) //SemanticLinks
	{		
		//echo "searchSem4Cat<br>"; searchSemSpaceCat
		$this->searchFromSpace($mysearch);
		$this->contextPartition();
	}
	
	


	

	public function searchTags($myrowid)
	{		
		$this->initLasttime();
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'TABLE:wikimaster2.TOCONTEXT',$myrowid);// on r�cup�re le knw_abstract de l'article 167723
	
		//$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');////�a se retrouve sur la pile de contexte, on met � jour les activit�s
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}		
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','40','true');////on s�lectionne les activit� >= 1%
		$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile			
	}
	
	public function searchTagsEval($myrowid)
	{
		$this->initLasttime();
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'TABLE:wikimaster2.TOCONTEXT',$myrowid);// on r�cup�re le knw_abstract de l'article 167723			
   		$this->s->Execute ($this->session, 'CONTEXTS.SORTBYGENERALITY', '0');
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');	
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','40','true');
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}		
		$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );		
	}
	
	
	
	
	public function searchTexteTags($mytext) {
		$this->initLasttime();
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'CONTEXTS.NEW' );
		$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mytext, '100' );
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');////�a se retrouve sur la pile de contexte, on met � jour les activit�s
    	$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );                        
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');////�a se retrouve sur la pile de contexte, on met � jour les activit�s
		$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');
		//$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','45','false');////on s�lectionne les activit� >= 1%
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','5','true');////on s�lectionne les activit� >= 1%
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}
		$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' ); //on r�cup�re le contexte en haut de la pile
	
	}
	
	public function searchFromSemTags($mysearch)
	{		
	$this->initLasttime();
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
	$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');    
	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');	
	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','5','0','0','100' );    
	$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');     
	$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','1','true');////on s�lectionne les activit� >= 1%  
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );} 
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	public function searchFromSemTagsCat($mysearch) //SemanticLinks
	{	
	//	echo "searchFromSemTagsCat<br>";	
		$this->searchFromSemTags($mysearch);
		$this->contextPartition();
	}
	
	public function searchFromAttractorTags($mysearch)
	{		
	$this->initLasttime();
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','-1','-1' );      
	$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');     
	$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','5','true');////on s�lectionne les activit� >= 1%  
	// $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );  
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	public function searchFromAttractorTagsCat($mysearch) //SemanticLinks
	{	
	//	echo "searchFromAttractorTagsCat<br>";	
		$this->searchFromAttractorTags($mysearch);
		$this->contextPartition();
	}
	
	/*
	 1 RESULTS.GET(<10 OWNERTABLE/>) // r�cuo�re la table li�e
1 1 1 1 8 0 OwnerTable <11 wikimaster2/> ;
1 RESULTS.GET(<11 RESULTCOUNT/>) //recupere le nombre de ligne ds le RS du haut
1 1 1 1 1 0 ResultCount <3 133/> ;
1 RESULTS.GET(<14 RESULTCAPACITY/>) // recupere la capacit� du RS du haut
1 1 1 1 1 0 ResultCapacity <3 133/> ;
1 RESULTS.GET(<9 FETCHSIZE/>) // r�cup�re la taille du fetch (20 par d�faut)
1 1 1 1 1 0 FetchSize <2 10/> ;
1 RESULTS.GET(<10 FETCHSTART/>) // r�cup�re l'indice du prochain fetch
1 1 1 1 1 0 FetchStart <1 1/> ;
1 RESULTS.GET(<7 FETCHID/>) //r�cup�re le prochain Id du fetch 
	 */
	
/*
 CONTEXTS.CLEAR()
CONTEXTS.NEW()
CONTEXTS.ADDELEMENT(<3 cat/>, <3 100/>)
CONTEXTS.NEW()
CONTEXTS.ADDELEMENT(<3 dog/>, <3 100/>)
CONTEXTS.NEWFROMSEM(<1 0/>, <1 0/>, <2 -1/>)
CONTEXTS.SWAP()
CONTEXTS.NEWFROMSEMLINKS(<1 2/>, <1 0/>, <1 0/>, <2 -1/>)
CONTEXTS.ROLLDOWN(<1 3/>)
CONTEXTS.UNION()
CONTEXTS.NORMALIZE()
CONTEXTS.ROLLDOWN(<1 3/>)f
CONTEXTS.NEWFROMSEM(<1 0/>, <1 0/>, <2 -1/>)
CONTEXTS.SWAP()
CONTEXTS.NEWFROMSEMLINKS(<1 2/>, <1 0/>, <1 0/>, <2 -1/>)
CONTEXTS.ROLLDOWN(<1 3/>)
CONTEXTS.UNION()
CONTEXTS.NORMALIZE()
CONTEXTS.ROLLDOWN(<1 3/>)
CONTEXTS.INTERSECTION()
CONTEXTS.NORMALIZE()
 */	
	public function searchSemIntersection($search01, $search02)
	{
	$this->initLasttime();
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $search01, '100' );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $search02, '100' );
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','0','-1' );
    $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );    
   
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );    
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');	 
    
    $this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN','3' );
	$this->s->Execute ($this->session, 'CONTEXTS.UNION' );
	$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
	$this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN','3' );   
	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','0','-1' );
    $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );   
	
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','-1' );    
    $this->s->Execute ($this->session, 'CONTEXTS.UNION');
    
    $this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN','3' );
	$this->s->Execute ($this->session, 'CONTEXTS.UNION' );
	$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
	$this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN','3' );
	$this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION' );
	$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' ); 
	
	$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','80','true');////on s�lectionne les activit� >= 1%  
	$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	
	public function formatResult($nblignes, $listfields)	// formatte le resultat $nbligne = 
	{
		if ($listfields=="") $listfields = 'title act link_wikifr texte ROWID KNW_LANGAGE KNW_MEANING link_wikieng';
		$this->s->Execute ($this->s->KMId, 'RESULTS.SET','FORMAT',$listfields);
		$this->getLastTime("'RESULTS.SET','FORMAT'");		
		if(isset($_GET['pagestart'])) {$pagestart=$_GET['pagestart'];} else {$pagestart=1;}
		$this->s->Execute ($this->s->KMId, 'RESULTS.FETCH',$nblignes, $pagestart);
	//$this->getLastTime("'RESULTS.FETCH'");			
	//$this->aff_lasttime();
	}
 
	public function formatResultNewSite($nblignes, $listfields, $pagestart)	// formatte le resultat $nbligne = 
	{
		if ($listfields=="") $listfields = 'title act link_wikifr texte ROWID KNW_LANGAGE KNW_MEANING link_wikieng';
		$this->s->Execute ($this->s->KMId, 'RESULTS.SET','FORMAT',$listfields);
		$this->getLastTime("'RESULTS.SET','FORMAT'");		
		$this->s->Execute ($this->s->KMId, 'RESULTS.FETCH',$nblignes, $pagestart);

	}	
	
	public function readArrayKMResultsMultiContent()
	{
				if(isset($this->tabcontents))
				{
				 return $this->tabcontents;
				}
		else
		{
			$taberreur[] = "Nothing Found";
				//return $this->s->KMResults;				
			return $taberreur;									
		}
	}
	
	public function readArrayKMserver() // renvoie le resultat de KmServer sous forme de array
	{
		if(isset($this->s->KMResults [0]["results"][0][0]))
		{
		//$str = $this->s->KMResults [0]["results"][0][0];
		//$str = htmlentities  ($str);
		//echo "first ".$str;
		//aff_tab($s->KMResults);
		return $this->s->KMResults;
		
		//return $str;
		}
		else
		{
			$taberreur[] = " Nothing ";
				//return $this->s->KMResults;				
			return $taberreur;									
		}
	} 
	
	public function readArrayNoControlKMserver() // renvoie le resultat de KmServer sous forme de array
	{		
		//$str = $this->s->KMResults [0]["results"][0][0];
		//$str = htmlentities  ($str);
		//echo "first ".$str;
		//aff_tab($s->KMResults);
		return $this->s->KMResults;
		//return $str;				
	} 
	
	public function DBselectbyRowId($rowid)
	{
		$this->s->Execute ($this->session, 'TABLE:wikichinese.SELECT','NEW', 'RowId',"=",$rowid);		
	}
	
	
	
	public function readStrKMResults($ind1,$field,$ind2,$ind3)
	{
		if(isset($this->s->KMResults [$ind1][$field][$ind2][$ind3]))
		{
		$str = $this->s->KMResults [$ind1][$field][$ind2][$ind3];
		$str = htmlentities  ($str);
		//echo "first ".$str;
		//aff_tab($s->KMResults);
		return $str;
		//return $str;
		}
		else
		{
			$str = "Null";
				//return $this->s->KMResults;				
			return $str;									
		}
	}

	// pour debug
	public static function aff_tabl($tab)
	{
		   echo "<ul>";
			  foreach($tab AS $cle => $val){
				 if( !is_array($val) )
					{
						if (is_string ($val))
						{
							$val = htmlentities ($val);
						}
						echo "<li>[$cle] => $val\n<br />";
					}
				 else
					{
						echo "<li>[$cle] => \n<br />";
						searchikm::aff_tabl($val);
					}
			  }
		echo "</ul>";
	}	
	
	public function GetAPI ()
	{
	    $this->s->Execute ($this->session, 'SERVER.GETAPI' );		
	}
	
	
	public function reindexation($rowid) {
		//$this->s->Execute ($this->session, ' table:wikimaster2.KUNINDEX',$rowid);
		$this->s->Execute ($this->session, ' table:wikimaster2.KREINDEX',$rowid);
	}
	
	
	public function publish()
	{
		$this->s->Execute ($this->session, ' KNOWLEDGE:wikiknw.PUBLISH');
	}

	public function save()
	{
		$this->s->Execute ($this->session, ' KNOWLEDGE:wikiknw.SAVE');
	}

	public function insertnewwikiLineDate($title, $texte, $link ,$date_maj, $date_enr)
	{
		$this->s->Execute ($this->session, ' table:wikimaster2.insert','title','texte','link_wikifr','date_maj','date_enr',$title, $texte, $link ,$date_maj, $date_enr);
	}
	public function insertnewwikiLine($title, $texte, $link ) // format lol , good format ...
	{
		$this->s->Execute ($this->session, ' table:wikimaster2.insert','title',$title,'texte', $texte , 'link_wikifr',$link );
	}
	
	// For chinese version
	
	public function insertnewwiki($title, $texte, $link ,$date_maj, $date_enr) // for Chinese
	{
		$this->s->Execute ($this->session, ' table:wikichinese.insert','title','texte','link_wikifr','date_maj','date_enr',$title, $texte, $link ,$date_maj, $date_enr);
	}
	
	public function recchinesetexte($str)
	{		
		$this->s->Execute ($this->session, ' table:wikichinese.insert','texte',$str);
	}	
	
	public function deleterowid($rowid)
	{
			$this->s->Execute ($this->session, ' table:wikichinese.delete',$rowid);
	}	

  public function matchsearch($search)
       {
          $this->s->Execute ($this->session, 'table:wikimaster2.select', 'new', 'title', '=', $search );
          return $this->s->KMResults[0]["results"][0][0];
       } 
  	  
// RMX CHALLENGE XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	public function MVEvaluate()
	{	
      //evalue un contexte
     $this->s->Execute($this->session, 'CONTEXTS.SORTBYGENERALITY', '1' );
     $this->s->Execute($this->session, 'CONTEXTS.PARTITION','1','0','0','-1');
     if (isset ($this->s->KMResults [0]["results"][0][0])  )
      {$ctx = $this->s->KMResults [0]["results"][0][0]; }
     else $ctx = 0;
     echo $ctx;	
     if ($ctx >1)
      {  
     $this->s->Execute($this->session, 'CONTEXTS.UNION', $ctx-1 );
     $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE');
      }
     
     if ($ctx >0)
      {
     $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
     $this->s->Execute($this->session, 'CONTEXTS.DROP' );
     }
      
  }



	public function MVMerge()
	{	
      //consolide 2 contextes, sans (trop de) r��valuation des activit�s
      //pile 2 contextes - > le resultat   stack -1
      //valid�e...a mettre dans l'aPI
      
      //on duplique les deux contextes 
      $this->s->Execute($this->session, 'CONTEXTS.DUP' );
      $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' );
      $this->s->Execute($this->session, 'CONTEXTS.DUP' );
      $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' );
      $this->MVDelta();
      $this->s->Execute($this->session, 'CONTEXTS.ROLLUP','3' );       
      $this->s->Execute($this->session, 'CONTEXTS.INTERSECTION' );
      $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );  
      $this->s->Execute($this->session, 'CONTEXTS.UNION' );  
      $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );
  }
  
 	public function MVDelta()
	{	
     //calcul (A U B) - (A inter B)
     //pile 2 contextes - > le resultat   stack -1
     //test�e..� modifier avec nouvelle aPI
      
      //on duplique les deux contextes 
      $this->s->Execute($this->session, 'CONTEXTS.DUP' );
      $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' );
      $this->s->Execute($this->session, 'CONTEXTS.DUP' );
      $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' ); 
      // A U B
      $this->s->Execute($this->session, 'CONTEXTS.UNION' ); 
      $this->s->Execute($this->session, 'CONTEXTS.ROLLUP','3' ); 
      // A inter B      
      $this->s->Execute($this->session, 'CONTEXTS.INTERSECTION' );
      $this->s->Execute($this->session, 'CONTEXTS.AMPLIFY','-1' );       
      //somme des deux et filtrage des activit�s n�gatives  
     
      $this->s->Execute($this->session, 'CONTEXTS.UNION' ); 
      $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );
      $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','1' ); 
      $this->s->Execute($this->session, 'CONTEXTS.AMPLIFY','2' ); 
      $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );      
  
  } 
 

  
 public function MVClear()
	{	
	   //initialisation de la session user IKM, des piles de contexte et de RS
     
      $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');  $this->getLastTime("'MVClear:CONTEXTS.CLEAR'");
	    $this->s->Execute ($this->session, 'RESULTS.CLEAR');   $this->getLastTime("'MVClear:RESULTS.CLEAR'");
	    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw ); $this->getLastTime("'MVClear:CONTEXTS.SET','KNOWLEDGE',$this->knw");
	}	 
	public function MVQuery($mysearch)
	{	
    $this->s->Execute($this->session, 'CONTEXTS.NEW' ); $this->getLastTime("'MVQuery:CONTEXTS.NEW'");
    $this->s->Execute($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );$this->getLastTime("'MVQuery:CONTEXTS.ADDELEMENT', $mysearch, '100'");
    $this->s->Execute($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true'); $this->getLastTime("'MVQuery:CONTEXTS.FILTERVOIDELEMENTS','true'");
    
	}	
	
	public function MVPartition($size,$nb)
	{	       
     //partitionne une requete, et renvoie le nombre de sous-contextes cr�es
     $this->s->Execute($this->session, 'CONTEXTS.PARTITION',$size,'1','0',$nb);
     $ctx = $this->s->KMResults [0]["results"][0][0]; 
     return $ctx ;
	}
	
	public function MVQueryExt($mysearch)
	{	
    //traite une requete sous forme contextuelle
    //la pile contient le contexte de la requete, et les sous contextes
    //renvoie le nombre de sous-contextes en haut de la pile
    $this->MVQuery($mysearch) ;
    $N = $this->MVPartition(1,-1) ;
    return $N ;
    
	}	
  
	public function MVContextExtend()
	{	
 
     $this->s->Execute($this->session, 'CONTEXTS.NEWFROMSHAPE', '0', '-1' );
     $this->s->Execute($this->session, 'CONTEXTS.SWAP' );  
     $this->s->Execute($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' );
     $this->s->Execute($this->session, 'CONTEXTS.SWAP' ); 
     $this->s->Execute($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );      
     $this->s->Execute($this->session, 'CONTEXTS.UNION');
     $this->s->Execute($this->session, 'CONTEXTS.UNION');
     $this->s->Execute($this->session, 'CONTEXTS.UNION');
     $this->s->Execute($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR', '0', '-1' );
     $this->s->Execute($this->session, 'CONTEXTS.UNION');
     $this->s->Execute($this->session, 'CONTEXTS.EVALUATE' ); 
     
  }  
     
     	

	
	
	public function MVSearch($mysearch)
	{	
     $this->MVClear();
     $N=$this->MVQueryExt($mysearch);
    
      for ($i=1;$i<=$N;$i++)
      {    
        $this->s->Execute ($this->session , 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1');  $this->getLastTime("'MVSearch:CONTEXTS.NEWFROMSUBATTRACTOR'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.EVALUATE');                $this->getLastTime("'MVSearch:CONTEXTS.EVALUATE'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','true','-1');  $this->getLastTime("'MVSearch:CONTEXTS.TORESULTS','true','-1'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.DROP');                    $this->getLastTime("'MVSearch:CONTEXTS.DROP'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','false','-1'); $this->getLastTime("'MVSearch:CONTEXTS.TORESULTS','false','-1'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.DROP');                    $this->getLastTime("'MVSearch:CONTEXTS.DROP'"); 
        $this->s->Execute ($this->session , 'RESULTS.UNION');                  $this->getLastTime("'MVSearch:RESULTS.UNION'");  
        $this->s->Execute ($this->session , 'RESULTS.AMPLIFY','.5');            $this->getLastTime("'MVSearch:CONTEXTS.AMPLIFY','0.5'"); 
      }                                                                          
      for ($i=1;$i<$N;$i++)
      {
        $this->s->Execute ($this->session , 'RESULTS.INTERSECTION');         $this->getLastTime("'MVSearch:RESULTS.INTERSECTION'");  
      }   


     $this->s->Execute ($this->session , 'RESULTS.SortBy','Act','false');     $this->getLastTime("'MVSearch:RESULTS.SortBy','Act','false'");
     //$this->s->Execute ($this->session , 'RESULTS.SelectBy','Act','>','25') ;  $this->getLastTime("'MVSearch:RESULTS.SelectBy','Act','>','0'");     
	}	
		
	
	public function MVSearchSem($mysearch)
	{	
     $this->MVSearch($mysearch);
     $this->s->Execute ($this->session , 'RESULTS.NORMALIZE','RELATIVE');
     $this->s->Execute ($this->session , 'CONTEXTS.DROP');                      $this->getLastTime("'MVSearchSem:CONTEXTS.DROP'");
     
     $N=$this->MVQueryExt($mysearch);
     
    
      for ($i=1;$i<=$N;$i++)
      {
        $this->MVContextExtend() ; 
        $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','25' );
        //$this->s->Execute ($this->session , 'CONTEXTS.NORMALIZE');              $this->getLastTime("'MVSearchSem:CONTEXTS.NORMALIZE'");
        $this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','false','50'); $this->getLastTime("'MVSearchSem:CONTEXTS.TORESULTS','false','-1'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.DROP');                   $this->getLastTime("'MVSearchSem:CONTEXTS.DROP'");
        //$this->s->Execute ($this->session , 'CONTEXTS.DROP');                   $this->getLastTime("'MVSearchSem:CONTEXTS.DROP'");
        //$this->s->Execute($this->session, 'CONTEXTS.UNION' );
        //$this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN',$N );
         
        //$this->s->Execute ($this->session , 'CONTEXTS.SWAP');
        //$this->s->Execute ($this->session , 'CONTEXTS.DROP');
        //$this->s->Execute($this->session, 'CONTEXTS.EVALUATE' );         
        //this->s->Execute($this->session, 'CONTEXTS.FILTERACT','25' );
        //$this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','false','50');
        //$this->s->Execute ($this->session , 'CONTEXTS.DROP');
      }
      for ($i=1;$i<$N;$i++)
      {
        $this->s->Execute ($this->session , 'RESULTS.INTERSECTION'); $this->getLastTime("'MVSearchSem:RESULTS.INTERSECTION'");
      }      

     //$this->s->Execute($this->session, 'CONTEXTS.EVALUATE' );
     //$this->s->Execute($this->session, 'CONTEXTS.FILTERACT','25' );
     //$this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','false','50');
     
     //$this->s->Execute ($this->session , 'RESULTS.NORMALIZE','ABSOLUTE','100');
     $this->s->Execute ($this->session , 'RESULTS.UNION');                      $this->getLastTime("'MVSearchSem:CONTEXTS.UNION'");
     $this->s->Execute ($this->session , 'RESULTS.SortBy','Act','false');       $this->getLastTime("'MVSearchSem:CONTEXTS.SortBy'");
     $this->s->Execute ($this->session , 'RESULTS.SelectBy','Act','>','5') ;    $this->getLastTime("'MVSearchSem:CONTEXTS.SelectBy'");
    
	}	
	
	
	public function MVGetCategories()
	{	
     $this->s->Execute($this->session, 'CONTEXTS.NEWFROMSHAPE', '0', '-1' );  
     $this->s->Execute($this->session, 'CONTEXTS.UNION' );
     $this->s->Execute($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' );   
     $this->s->Execute($this->session, 'CONTEXTS.SWAP' );  
     $this->s->Execute($this->session, 'CONTEXTS.DROP' );  
 
	}	
	
		public function MVGetCategoriesExt()
	{	 
     
     $this->s->Execute($this->session, 'CONTEXTS.DUP' );
     $this->s->Execute($this->session, 'CONTEXTS.DUP' );
     $this->MVGetSem (5);
     $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
     $this->MVGetCategories();
     $this->s->Execute($this->session, 'CONTEXTS.INTERSECTION' );
     $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );
 
	}	
	
	
	
		public function MVColorize()
	{	
     //evalue les activit�s du contexte 2 en fonction du contexte 1
     //le nouveau contexte est laiss� sur le pile
     //pile -1
     
      $this->s->Execute($this->session, 'CONTEXTS.SWAP' );       // p
      $this->s->Execute($this->session, 'CONTEXTS.EVALUATE' );   // p
      $this->s->Execute($this->session, 'CONTEXTS.DUP' );        // p+1
      $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' ); // p+1
      $this->s->Execute($this->session, 'CONTEXTS.UNION' );        // p
      $this->s->Execute($this->session, 'CONTEXTS.EVALUATE' );     // p
      $this->s->Execute($this->session, 'CONTEXTS.SWAP' );         // p
      $this->s->Execute($this->session, 'CONTEXTS.DUP' );          // p+1
      $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' );   // p+1
      
      $this->s->Execute($this->session, 'CONTEXTS.INTERSECTION' );   //p

      $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
      $this->s->Execute($this->session, 'CONTEXTS.AMPLIFY','-1' );
      $this->s->Execute($this->session, 'CONTEXTS.INTERSECTION' );     //p-1
      $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' ); 
 
	}	
	
		public function MVProp($seuilprop)
	{	
    //propagation dans le r�seau sem  uniquement
    // prend le contexte, propage, et rend le differentiel de prop suivit du container
    // de propagation
    // valid�e. qq fonctions API pour augmenter la pr�cision, et gros gain en vitesse
    

     $this->s->Execute($this->session, 'CONTEXTS.NEWFROMSEM','0',-1,'-1' );
             
     $this->s->Execute($this->session, 'CONTEXTS.FILTERACT',$seuilprop );
        
      $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
      $this->s->Execute($this->session, 'CONTEXTS.DROP' );
      $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
      $this->s->Execute($this->session, 'CONTEXTS.DUP' );
      $this->s->Execute($this->session, 'CONTEXTS.AMPLIFY','-1' );
      $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' );
      $this->MVMerge(); 
      $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );
      $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','1' );
        

        $this->s->Execute($this->session, 'CONTEXTS.DUP' );
        $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' );
        $this->MVMerge(); 
        $this->s->Execute($this->session, 'CONTEXTS.EVALUATE' );
        //$this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );
        //$this->s->Execute($this->session, 'CONTEXTS.FILTERACT','1' );
        $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
        $this->s->Execute($this->session, 'CONTEXTS.SELECTBYACT','1' );

      

 
	}		
	
	
	
	public function MVGetSem($level)
	{	
    //propagation dans le r�seau sem  uniquement
    // valid�e. qq fonctions API pour augmenter la pr�cision, et gros gain en vitesse
    
     $stop = 0;
     $this->s->Execute($this->session, 'CONTEXTS.DUP' );
     $seuilprop = 10;
     for ($i=1;$i<=$level;$i++)
      {
        $this->s->Execute($this->session, 'CONTEXTS.NEWFROMSEM','0',-1,'-1' );
   
        if ($i == 1)
          {
             if (isset ($this->s->KMResults [0]["results"][0][1] )   )
              {$max = 5 * $this->s->KMResults [0]["results"][0][1]; } 
              else
              {$max = 100; $stop = 1;}             
          }    
        
        $this->s->Execute($this->session, 'CONTEXTS.FILTERACT',$seuilprop );
        $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
        $this->s->Execute($this->session, 'CONTEXTS.DROP' );
        $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
        $this->s->Execute($this->session, 'CONTEXTS.DUP' );
        $this->s->Execute($this->session, 'CONTEXTS.AMPLIFY','-1' );
        $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' );
        $this->MVMerge();
        //$this->s->Execute($this->session, 'CONTEXTS.UNION' );
        $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );
        $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','1' );
        if (isset($this->s->KMResults [0]["results"][0][1] ))  
         {$nb = $this->s->KMResults [0]["results"][0][1];} 
         else
          {$nb = 0;}
        
        echo $nb.' ';
        //si rien du tout on arrete
        if ($nb > $max)
         {
           
           $this->s->Execute($this->session, 'CONTEXTS.FILTERSIZE',$max );
          
         }
         
         echo $nb.' ' ;

        $this->MVEvaluate();
        $this->s->Execute($this->session, 'CONTEXTS.DUP' );
        $this->s->Execute($this->session, 'CONTEXTS.ROLLDOWN','3' );
        $this->MVMerge(); 
        $this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );
        $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','1' );
        $this->s->Execute($this->session, 'CONTEXTS.SWAP' );
        
        $seuilprop += 1;
        if ($seuilprop >75) {$seuilprop = 75;}
        if ($stop == 1)  {break;} 
      
      }
     
    $this->MVMerge(); 
    //$this->s->Execute($this->session, 'CONTEXTS.UNION' );
    //$this->s->Execute($this->session, 'CONTEXTS.NORMALIZE' );
    $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','1');
    if (isset($this->s->KMResults [0]["results"][0][1] ))  
         {$nb = $this->s->KMResults [0]["results"][0][1];} 
         else
          {$nb = 0;}
     return $nb;     

 
	}	
	
   public function searchFromSpace($mysearch)
	{	
     $this->initLasttime(); 
     $this->MVClear();
     $N=$this->MVQueryExt($mysearch);  
    
    
     for ($i=1;$i<=$N;$i++)
      {
        $this->MVContextExtend(); 
        $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','5' ); 
        //$this->MVContextExtend(); 
        //$this->s->Execute($this->session, 'CONTEXTS.FILTERACT','25' );           
        $this->s->Execute ($this->session , 'CONTEXTS.ROLLUP',$N);
         
      }
       
      for ($i=1;$i<$N;$i++)
      {
        $this->s->Execute ($this->session , 'CONTEXTS.UNION');
      } 
   
     $this->s->Execute($this->session, 'CONTEXTS.EVALUATE' );         
     $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','5' ); 
    

	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}   
    $this->s->Execute($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	  	 	  	  
	  
   
	
	/*
	  $tabresults = $this->s->KMResults;	  	  
	  echo '<pre>';
	   print_r($tabresults);
	   echo '</pre>';
	*/  
	
  }	




}
?>