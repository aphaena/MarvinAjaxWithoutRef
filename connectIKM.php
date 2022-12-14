<?php 
include_once 'class_km_server.php';


	
		
class searchikm {
				
	 public $s;
	 public $session;
	 public $currentsession;
	 public $knw = "wikiknw";
	 public $mastertable= "wikimaster2";
	 
	 public	$tabcontext;
	 public $tabresult;
	 public $tabcontents;
	 public $tablasttimes;
	 public $totalasttime;
	 	
	function __construct() 
	{
		$this->s = new KMServer;
		$this->session = $this->s->KMId;
		//$this->knw = $this->knw;

	 //$this->tmptabcontext = $this->tmptabcontext + $this->tmptabcontext + 1;
	}
	
	public function connectIKM_TEST()
	{
			echo "echo TEST....";
			$this->s->IP = '127.0.0.1';
			$this->s->Port = '1255'; //52
			$this->s->connect();
			////$this->knw = $knw;
			$this->session = $this->s->KMId;
	}
	
	public function connectIKM_ENG()
	{
			//$this->s->IP = '192.168.0.14';
			//$this->s->IP = '192.168.0.26';
			//$this->s->IP = '5.118.47.15';
			//$this->s->IP = '90.31.162.245';
			$this->s->IP = '127.0.0.1';
			$this->s->Port = '1255'; //52
			//$this->s->Port = '1256'; //52
			$this->s->connect();
			//$this->knw = $knw;
			$this->session = $this->s->KMId;
			//$this->currentsession = $this->s->KMCurrentId;		
	}

	public function connectIKM_FR()
	{
			//$this->s->IP = '88.189.240.38';
			//$this->s->IP = '127.0.0.1';
			//$this->s->IP = '192.168.0.12';
			$this->s->IP = '127.0.0.1';
			//$this->s->Port = '1254';//59
			$this->s->Port = '1255';//59
			$this->s ->connect();
			//$this->knw = $knw;
			$this->session = $this->s->KMId;
			//$this->currentsession = $this->s->KMCurrentId;			
	}
		
	public function connect($ip, $port)
	{
		$this->s->IP = $ip;
			$this->s->Port = $port;
			$this->s ->connect();
			//$this->knw = $knw;
			$this->session = $this->s->KMId;	

	}
	
	// lit les champs string en steamming
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
	public function getSession(){return $session; }
	
	public function readTOTALSEM(){
	$this->s->Execute ($this->session , 'KNOWLEDGE'.$knw.'.GET','TOTALSEM');
	}		
	
	public function readTOTALREFS(){
	$this->s->Execute ($this->session , 'KNOWLEDGE '.$knw.'.GET','TOTALREFS');
	}
	
	public function readTOTALKEYS(){
	$this->s->Execute ($this->session , 'KNOWLEDGE'.$knw.'.GET','TOTALKEYS');
	}
	
	
	public function readINDEXATIONCACHEUSED(){
	$this->s->Execute ($this->session , 'KNOWLEDGE'.$knw.'.GET','INDEXATIONCACHEUSED');
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
		$this->totallastime = (double)$this->totallastime +  (double)$lasttime;
		$this->tablasttimes[] = $message.": \t\t".$lasttime." \t-> ".$this->totallastime." ms ";
		return $this->totallastime;
		
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
	    //$this->s->Execute ($this->session , 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1');	   
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');	   
		//$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');  
		$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	   

		 $filtered = $this->s->KMResults;
		 return $filtered;
	}
	

	public function FILTERVOIDELEMENTS2String($mysearch)
	{
		$this->initLasttime();
		$mysearch = $this->mixword( $mysearch);
		  //on met la connaissance par d�faut de l'objet contexts de la session
	    $this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
	    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	    $this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
	    $this->s->Execute ($this->session, 'CONTEXTS.NEW'); //on cr�e un nouveau contexte vide
	    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');   
	    // CONTEXTS.NEWFROMSUBATTRACTOR(<1 0/>, <2 -1/>)
	    //$this->s->Execute ($this->session , 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1');	   
		$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');	   
		//$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');  
		$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	   

		 $filtered = "";
		 
		 foreach ($this->s->KMResults[0]['results'] as $element)
		 {
			 $strtmp = str_replace( "'", " ", $element[0]) ;
 			 $strtmp = str_replace( "/", " ", $strtmp ) ;
 			 $strtmp = str_replace( ",", " ", $strtmp ) ;
  			 $strtmp = str_replace( "/*", " ", $strtmp ) ;
   			 $strtmp = str_replace( "*/", " ", $strtmp ) ;
			 $strtmp = str_replace( "�", " ", $strtmp ) ;
 			 $strtmp = str_replace( ".", " ", $strtmp ) ;
			 $strtmp = str_replace( "_", " ", $strtmp ) ;
			  
			 if(strlen($strtmp)>2 ) { $filtered .= $strtmp." ";}
		 }
		 $filtered = str_replace( "_", " ", $filtered );
		 $filtered = $this->mixword( $filtered);
		 
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
		foreach ( $table AS $str2)
		{			
			$strmixed = $strmixed.$str2." ";
		}		
		return $strmixed;
	}
	
	public  function searchLikeGooglefornewsite2($mysearch)
	{	
			//$mysearch = trim($mysearch);
		$this->initLasttime();
		//$mysearch = $this->FILTERVOIDELEMENTS2String($mysearch);
		//echo $mysearch;
		//$this->getbestresults($mysearch,"true");
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );		$this->getLastTime("");		
		$this->s->Execute ($this->session, 'CONTEXTS.GETBESTRESULTS',$mysearch,true ); 	$this->getLastTime("");
		$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
		if(isset($this->s->KMResults)) $count = $this->s->KMResults;
	
	//echo "search get Best Results 2 ";
	
	}
	
	public  function searchLikeGooglefornewsite($mysearch)
	{	
			//$mysearch = trim($mysearch);
		//$this->initLasttime();
		//$mysearch = $this->FILTERVOIDELEMENTS2String($mysearch);
		//echo $mysearch;
		$this->getbestresults($mysearch);
		//var_dump($mysearch)."<br><br>";
		$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
		//if(isset($this->s->KMResults)) $count = $this->s->KMResults;
		//var_dump($this->s->KMResults)."<br><br>";
	
	//echo "search get Best Results ";
	/*
		$this->initLasttime();  
		//$mysearch = $this->dirtydebug($mysearch);
		
		$this->MVSearch($mysearch) ; 
		
    $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount'); 
	if (isset($this->s->KMResults))
		{
			$count = $this->s->KMResults;
			$N = $count[0]["results"][0][0];
			if ($count[0]["results"][0][0]<1) { 
				$this->searchLikeGoogleR($mysearch); //echo '<div class="smalltexte cadre3">mode changed</div>'; 
			}			
			
		}
		*/
	}
	
 public  function searchLikeGoogleR($mysearch)
	{			
	$this->initLasttime();  
		$checksem = false;
		$checksemlink = false; 
		if(isset($_GET['sem']))  {$checksem = true;}
		if(isset($_GET['semlink'])) $checksemlink = true; 

    $this->MVSearchSem($mysearch); $this->getLastTime("'MVSearchSem',$mysearch,'100'");

      $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount'); 
											    
	} 	

// procedural

	public function searchStandardbest($mysearch)
	{
		//$mysearch = trim($mysearch);		
		$this->initLasttime();
		$this->getbestresults($mysearch);
		$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
		$count = $this->s->KMResults;
		
	}

	public function searchStandardxx($mysearch){	
		$mysearch = trim($mysearch);
		$this->initLasttime();
		if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch);	
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');  $this->getLastTime("");
		$this->s->Execute ($this->session, 'RESULTS.CLEAR'); $this->getLastTime("");
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );							$this->getLastTime("");
		$this->s->Execute ($this->session, 'CONTEXTS.NEW');													$this->getLastTime("");
		$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');							$this->getLastTime("");
		//$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0', '-1' ,'0', '1' ); 				$this->getLastTime("");	
		$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' );  							$this->getLastTime("");
		//$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMSHAPE','0','-1'); 
		//$this->s->Execute ($this->session, 'CONTEXTS.UNION');	
		//$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','0','20' );    
		//$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
		//$mysearch = $this->FILTERVOIDELEMENTSok($mysearch);		
		//$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMLINKS','2','0','-1','-1' ); $this->getLastTime("'CONTEXTS.NEWFROMSEMLINKS','2','0','-1','-1'");
	    //$this->s->Execute ($this->session, 'CONTEXTS.UNION');   
		$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEMATTRACTOR','0','-1' ); 
		//$this->s->Execute ($this->session, 'CONTEXTS.UNION');   
		//$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');$this->getLastTime("");
		//$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');$this->getLastTime("");		
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','1','true');								$this->getLastTime("");
		
	    //$this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );  					 $this->getLastTime("");
		//$this->s->Execute ($this->session, 'CONTEXTS.SortByActivity','true','false' );  					 $this->getLastTime("");
	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','1500');							$this->getLastTime("");
		$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false');									$this->getLastTime("");
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); 								$this->getLastTime("");
		$this->s->Execute ($this->session, 'RESULTS.NORMALIZE','RELATIVE'); 
		//echo "<pre>";
		//print_r($this->s->KMResults);
		//echo "</pre>";
		$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
		//$count = $this->s->KMResults;
	}		
	
	
	// procedural
		public function searchStandard($mysearch){	
		$mysearch = trim($mysearch);
		$this->initLasttime();
		//if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch);	
		
		// ex: de requete "probo panda" "probo koala chimiquier"
		
		/*
		$kmresults = $this->FILTERVOIDELEMENTSok($mysearch);
		$mysearch ="";
		foreach ($kmresults[0]['results'] as $word)
		{
				$mysearch = $mysearch ." ".$word[0];				
		}
		
		$mysearch = str_replace("_", " ", $mysearch);
		*/
		
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');  $this->getLastTime("");
		$this->s->Execute ($this->session, 'RESULTS.CLEAR'); $this->getLastTime("");
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );$this->getLastTime("");
		$tabwordstmp = explode(" ",$mysearch);
		//echo $mysearch." ";
		//print_r($tabwordstmp);
		$wordscount=0;
		foreach ( $tabwordstmp as $word)
		{
			//echo "_> ".$word." ";
			$this->s->Execute ($this->session, 'CONTEXTS.NEW');$this->getLastTime("");
			$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$word,'100');$this->getLastTime("");
			
			//$this->filtervoidelements();
			//$mysearch = $this->FILTERVOIDELEMENTSok($mysearch);
				
			$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');$this->getLastTime("");
			$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');$this->getLastTime("");		
	
			//$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','1','true');$this->getLastTime("");
			
			//$this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','true' );   
			$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true'); $this->getLastTime("");
			$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','-1');$this->getLastTime("");
			$this->s->Execute ($this->session, 'RESULTS.SWAP', '1');$this->getLastTime("");
			$this->s->Execute ($this->session, 'RESULTS.DUP');$this->getLastTime("");
			if ( $this->getLastTime("") > 500) {echo "up to 500ms, search aborted at ".$this->getLastTime("")."ms "; break; }
			$wordscount++;

			//$this->s->Execute ($this->session, 'RESULTS.INTERSECTION');$this->getLastTime("");
			
		}
		//echo "wordscount".$wordscount;
		$stackcount = $this->getStack();
		
		for ( $i=0; $i < $wordscount; $i++)
		{

			$this->s->Execute ($this->session, 'RESULTS.INTERSECTION');$this->getLastTime("");
			//$this->s->Execute ($this->session, 'RESULTS.INTERSECTION');$this->getLastTime("");
			//$this->s->Execute ($this->session, 'RESULTS.DUP');$this->getLastTime("");
			
			$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
			$count = $this->s->KMResults[0]["results"][0][0];
			//$this->s->Execute ($this->session, 'RESULTS.INTERSECTION');$this->getLastTime("");
		
			//echo "count:".$count." ";
			if($count==0) 
			{				
			$this->s->Execute ($this->session, 'RESULTS.SWAP', '1'); $this->getLastTime("");
				//$this->s->Execute ($this->session, 'RESULTS.DROP', '1'); $this->getLastTime("");
				//$this->s->Execute ($this->session, 'RESULTS.INTERSECTION');$this->getLastTime("");
				//$this->s->Execute ($this->session, 'RESULTS.DROP', '1');$this->getLastTime("");
			}
				
		}
		//$this->s->Execute ($this->session, 'RESULTS.INTERSECTION');$this->getLastTime("");
		
		
		$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false');$this->getLastTime("");
	    //$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); $this->getLastTime("");
		//$this->s->Execute ($this->session, 'RESULTS.NORMALIZE','RELATIVE'); 
		//echo "<pre>";
		//print_r($this->s->KMResults);
		//echo "</pre>";
		$this->getStack();
		
		$this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
		$count = $this->s->KMResults;
		//echo "count xxxx ".$count[0]["results"][0][0];
		
			
		if($count[0]["results"][0][0]==0) { $this->searchStandardxx($mysearch); echo "search standard actived ";}
	}		
	
	public function getStack()
	{
		$this->s->Execute ($this->session, 'RESULTS.GET','count');
			$countstack = $this->s->KMResults[0]["results"][0][0];
			//echo "stack ".$countstack." ";
	}

	public function connexearticles ($rowid)
	{
	//	echo " similar article ";
		$this->initLasttime();$this->getLastTime("'function connexearticles'");
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'CONTEXTS.NEW');
		$this->s->Execute ($this->session, 'table:'.$this->mastertable.'.TOCONTEXT',$rowid);	$this->getLastTime("'table:.$this->mastertable.TOCONTEXT',$rowid)");			
		$this->s->Execute ($this->session, 'CONTEXTS.DUP');                       	$this->getLastTime("'CONTEXTS.DUP'");
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');                  	$this->getLastTime("'CONTEXTS.EVALUATE'");
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25','true' );    	$this->getLastTime("'CONTEXTS.FILTERACT','25'");
    	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','1','-1','-1' ); 	$this->getLastTime("'CONTEXTS.NEWFROMSEM','1','0','-1'");
		$this->s->Execute ($this->session, 'CONTEXTS.SWAP');                      	$this->getLastTime("'CONTEXTS.SWAP'");	
		$this->s->Execute ($this->session, 'CONTEXTS.DROP');                      	$this->getLastTime("'CONTEXTS.DROP'");
		$this->s->Execute ($this->session, 'CONTEXTS.SWAP');                      	$this->getLastTime("'CONTEXTS.SWAP'");
		$this->s->Execute ($this->session, 'CONTEXTS.DUP');                       	$this->getLastTime("'CONTEXTS.DUP'");
		$this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN','3');              	$this->getLastTime("'CONTEXTS.ROLLDOWN','3'");
		$this->s->Execute ($this->session, 'CONTEXTS.UNION');                     	$this->getLastTime("'CONTEXTS.UNION'");	
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');                  	$this->getLastTime("'CONTEXTS.EVALUATE'");
		$this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION');              	$this->getLastTime("'CONTEXTS.INTERSECTION'");
		$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');                 	$this->getLastTime("'CONTEXTS.NORMALIZE'");		
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25','true' );    	$this->getLastTime("'CONTEXTS.FILTERACT','25'");	   
		$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','25');	  	$this->getLastTime("'CONTEXTS.TORESULTS','false','50'");	
		$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','40');    	$this->getLastTime("'RESULTS.SelectBy','Act','>','5'");			 
		$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false');       	$this->getLastTime("'RESULTS.SortBy','Act','false'");		
		
	    $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount');
		$count = $this->s->KMResults;	 
		
	}
	
	
	/* trouver les articles de contextedifferent et lister les titre des articles.
	
		 stocker le titre du premier article ( context plus �lev�)
			
		 supprimer tous les articles qui sont dans le meme context que le premier
		
		pour chaque article restant  recommecer la meme op�ration
		*/
	function listContextsArticles($mysearch)
	{
		$tabContexts = array();
		
		$this->searchStandard($mysearch);
				
		$listfields = 'ROWID title act link_wikifr KNW_LANGAGE KNW_MEANING';
		$this->s->Execute ($this->s->KMId, 'RESULTS.SET','FORMAT',$listfields);	
		
		$this->formatResult("1", "ROWID title");
		$this->s->Execute ($this->session, 'RESULTS.FETCH',"1","1");					
		$ret = $this->readArrayKMserver();
		$rowid = $ret[0]["results"][0][0];
		$title = $ret[0]["results"][0][1];
		array_push($tabContexts,$title );
	

				//$this->formatResultNewSite("20", "ROWID title act link_wikifr KNW_LANGAGE KNW_MEANING", 1);
for($i=0; $i<30; $i++) {	
		//echo "$rowid";
					
				//echo "<pre>";
				//print_r($ret);
				//echo "</pre>";					
				//$this->connexearticles ($rowid);
				
		//$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'CONTEXTS.NEW');
		$this->s->Execute ($this->session, 'table:'.$this->mastertable.'.TOCONTEXT',$rowid);	$this->getLastTime("'TABLE:'.$this->mastertable.'.TOCONTEXT',$rowid)");			
		$this->s->Execute ($this->session, 'CONTEXTS.DUP');                       	$this->getLastTime("'CONTEXTS.DUP'");
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');                  	$this->getLastTime("'CONTEXTS.EVALUATE'");
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25','true' );    	$this->getLastTime("'CONTEXTS.FILTERACT','25'");
    	$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','1','-1','-1' ); 	$this->getLastTime("'CONTEXTS.NEWFROMSEM','1','0','-1'");
		$this->s->Execute ($this->session, 'CONTEXTS.SWAP');                      	$this->getLastTime("'CONTEXTS.SWAP'");	
		$this->s->Execute ($this->session, 'CONTEXTS.DROP');                      	$this->getLastTime("'CONTEXTS.DROP'");
		$this->s->Execute ($this->session, 'CONTEXTS.SWAP');                      	$this->getLastTime("'CONTEXTS.SWAP'");
		$this->s->Execute ($this->session, 'CONTEXTS.DUP');                       	$this->getLastTime("'CONTEXTS.DUP'");
		$this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN','3');             	$this->getLastTime("'CONTEXTS.ROLLDOWN','3'");
		$this->s->Execute ($this->session, 'CONTEXTS.UNION');                     	$this->getLastTime("'CONTEXTS.UNION'");	
		$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');                  	$this->getLastTime("'CONTEXTS.EVALUATE'");
		$this->s->Execute ($this->session, 'CONTEXTS.INTERSECTION');              	$this->getLastTime("'CONTEXTS.INTERSECTION'");
		$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE');                 	$this->getLastTime("'CONTEXTS.NORMALIZE'");		
		$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25','true' );    	$this->getLastTime("'CONTEXTS.FILTERACT','25'");	   
		$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','25');	  	$this->getLastTime("'CONTEXTS.TORESULTS','false','50'");	
		$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','95');    	$this->getLastTime("'RESULTS.SelectBy','Act','>','5'");			 
		$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false');       	$this->getLastTime("'RESULTS.SortBy','Act','false'");
						

						
				$this->s->Execute($this->session, 'RESULTS.DUP' );    			
				$this->s->Execute($this->session, 'RESULTS.AMPLIFY','-5' );   

				$this->s->Execute($this->session, 'RESULTS.ROLLDOWN','3' );    		
 
				$this->s->Execute($this->session, 'RESULTS.UNION' ); 
 //$this->s->Execute ($this->session, 'RESULTS.GET','Count'); $count = $this->s->KMResults;	print_r( $count[0]["results"][0][0]."UNION<br>");	
				$this->s->Execute ($this->session,'RESULTS.SelectBy','Act','>','0');
						
				$this->s->Execute($this->session, 'RESULTS.SWAP');  
					
		$listfields = 'ROWID title act link_wikifr KNW_LANGAGE KNW_MEANING';
		$this->s->Execute($this->s->KMId, 'RESULTS.SET','FORMAT',$listfields);	
		
			$this->s->Execute ($this->session, 'RESULTS.FETCH',"10","1");
			

				
			$this->s->Execute($this->session, 'RESULTS.DROP', '1');  
			$this->s->Execute($this->s->KMId, 'RESULTS.SET','FORMAT',$listfields);	
			$this->s->Execute ($this->session, 'RESULTS.FETCH',"1","1");	
				$ret = $this->readArrayKMserver();  
				//echo "<pre>";
				//print_r($ret);
				//echo "</pre>";
				$rowid = $ret[0]["results"][0][0];
				$title = $ret[0]["results"][0][1];
				echo "<div>".$title."</div>";
				 flush();
				 ob_get_contents();
				array_push($tabContexts,$title );
				
				  
		}

		
		
	echo "<span class='querytime'> in ".$this->getTotalLastTime()." ms (".number_format(($this->getTotalLastTime()/1000),3)." seconde)</span>";	

				echo "<pre>";
				print_r($tabContexts);
				echo "</pre>";
		
	}
	
	function querylocutization( $mysearch ) 
	{
			$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');  
			$this->s->Execute ($this->session, 'RESULTS.CLEAR'); 
			$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
			$this->s->Execute ($this->session, 'CONTEXTS.NEW');
			$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100');
			$this->s->Execute ($this->session, 'CONTEXTS.DUP' );
			$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.UNION' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0', '-1' ,'0', '1' ); 			
			$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.EVALUATE' , 'activity' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.AMPLIFY','-3' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.UNION' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.SWAP' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.DROP' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.DUP' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.ROLLDOWN','3' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.UNION' ); 
			$this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','0','true' ); 	
			$this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true'); 
			$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );	

			$ret = $this->readArrayKMserver();  	
			$tabret = array();
			
			$strret="";
			if(isset($ret[0]["results"][0]))
			{
				foreach ( $ret[0]["results"]as $locution )
				{
					$strret .= $locution[0]." ";
					array_push($tabret , $locution);
					
				}
			}
			else 
			{
				$strret= $mysearch;
			}
			
				return $strret;
	}
	
	
	
	public function KMResultsIsEmpty()
	{
		if (isset($this->s->KMResults))
		{
			$count = $this->s->KMResults;			
			if ($count[0]["results"][0][0]<1) { 
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
/*	
	public  function searchLikeGoogle($mysearch)
	{	
	echo "searchLikeGoogle ";
		$this->initLasttime();  
		//$mysearch = $this->dirtydebug($mysearch);
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
  
  */
	
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
	
/*	
	public  function searchLikeGoogleR2($mysearch)
	{			
	echo "searchLikeGoogleR2 ";
		$checksem = false;
		$checksemlink = false; 
		if(isset($_get['sem']))  {$checksem = true; echo $_get['sem'];}
		if(isset($_get['semlink'])) $checksemlink = true; 
		//echo "check: ".$checksem." ".$checksemlink;
		
		$this->initLasttime();
		//$mysearch = $this->dirtydebug($mysearch);
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
	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','800'); $this->getLastTime("'CONTEXTS.TORESULTS','false','-1'");
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','5'); $this->getLastTime("'RESULTS.SelectBy','Act','>','5'");
	    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false'); $this->getLastTime("'RESULTS.SortBy','Act','false'");
	    $this->s->Execute ($this->session , 'RESULTS.SHRINK','1000'); $this->getLastTime("'RESULTS.SHRINK','1000'");
	    $this->s->Execute ($this->session, 'RESULTS.GET','ResultCount'); 
		$count = $this->s->KMResults;
		echo "<span class='ptextebig cadre4'>".$count[0]["results"][0][0]." results in semantic base mode</span>";
		$this->	displayCategoryButton();	
			
		$this->displaypagebuttons('index.php?modepage=1&pagestart=',  $count);
	  
			    
	}	
*/
/*	
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
		$this->partitionMultiContext('1', $pile, '50' );
	}
	
	public function searchNaturalCategory($mysearch){
		echo "searchNaturalCategory ";
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
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1' ); $pile+=1;
       $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' ); $pile+=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMATTRACTOR','0','-1' ); $pile+=1;
       $this->s->Execute ($this->session, 'CONTEXTS.GETSIZE' );
       $N = $this->s->KMResults[0]['results'][0][0]; 
	  
       if ($N < 1)
         {	
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
		$this->partitionMultiContext('1', '1', '1' ,'16',$pile);
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
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1' ); $pile+=1;
       $this->s->Execute ($this->session, 'CONTEXTS.SWAP' );
       $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSHAPE','0','-1' ); $pile+=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
       $this->s->Execute ($this->session, 'CONTEXTS.UNION' ); $pile-=1;
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
	*/
	
	/*
	public function searchMaxCategory($mysearch){
		echo "searchMaxCategory ";
		$this->initLasttime();

		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');$pile=0;
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'CONTEXTS.NEW');$pile+=1;
		$this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$mysearch,'100'); $this->getLastTime("'CONTEXTS.ADDELEMENT',$mysearch,'100'");


  	
    $this->MVContextExtend(); 
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');

    $this->MVContextExtend(); 
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');
  
		$this->partitionMultiContext('1', '1', '5' ,'16',$pile);
	}
*/
/*
		
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
      		$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','1');$this->getLastTime("'CONTEXTS.TORESULTS','false','1'");
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

*/

/*	
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
*/

/*	
	public  function searchLikeGoogleCat($mysearch)
	{	
	echo " searchLikeGoogleCat ";
		$this->initLasttime();
		//echo "searchLikeGoogleCat";
		//$mysearch = $this->dirtydebug($mysearch);
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
*/
/*	
		
	public function categorizationpages($mysearch) {
		echo "categorizationpages";
		$this->searchLikeGoogleCat($mysearch);
		$this->formatResult("8", "title act link_wikifr texte ROWID KNW_LANGAGE KNW_MEANING link_wikieng");
		$tab = $this->s->KMResults;
		return $tab;	
	}
	
/*	

	
	public function displayCategoryButton() 
	{
		//echo '<span class="ptextebig button3"><a href="index.php?modepage=3&textboxsearch='.$_SESSION['strsearch'].'">Categorize Basic</a></span>';
		//echo '<span class="ptextebig button3"><a href="index.php?modepage=4&textboxsearch='.$_SESSION['strsearch'].'">Categorize with Semantique</a></span>';		
		//echo '<span class="ptextebig button3"><a href="index.php?modepage=7&textboxsearch='.$_SESSION['strsearch'].'">Categorize Max </a></span>';	
	}

*/
/*
	
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
 	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','800'); 
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','false'); // on tri par Act descendante
	}

/*
	
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
	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','800'); 
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante

	}
*/

/*	
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
	    $this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','800'); 
	    $this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	    $this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante		
	}
*/
/*
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
	$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','800'); 
	$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante	
	}	
*/
/*
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
*/
/*	
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
	$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','800'); 
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
	
*/
/*	
	
	public function searchSem5Cat($mysearch) //SemanticLinks
	{		
		//echo "searchSem5Cat<br>";
		$this->searchSem5($mysearch);
		$this->contextPartition();
	}
	
*/

	public function contextPartition()
	{
		$pile=0;
		$this->tabcontents= '' ;
		$index=0;
		$this->s->Execute ($this->session, 'CONTEXTS.PARTITION','0', 'false', '1' ,'100');	
		$ctx = $this->s->KMResults [0]["results"][0][0]; 			
		//$this->s->Execute ($this->session, 'CONTEXTS.GET','COUNT');
		//$countCat = $this->s->KMResults;
		for($i=0; $i<$ctx;$i++) 
		{
		//$this->s->Execute ($this->session, 'CONTEXTS.NORMALIZE' );
					$this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );				
					$tabcontext = $this->s->KMResults;
						  
					//$this->aff_tabl($this->s->KMResults);	
					//$this->tabcontents[0][$index] = $tabcontext;	
					$this->s->Execute ($this->session, 'CONTEXTS.DROP' );	
					$index++;
		}
		
		//$this->aff_tabl($this->s->KMResults);
	}

/*	
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
	$this->s->Execute ($this->session, 'CONTEXTS.TORESULTS','false','800'); 
	$this->s->Execute ($this->session, 'RESULTS.SelectBy','Act','>','0'); //on �limine les act = 0
	$this->s->Execute ($this->session, 'RESULTS.SortBy','Act','true'); // on tri par Act descendante	
	}
*/
	
	public function searchShape($mysearch)
	{
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 			
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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

/*
	
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
*/
	// calcul le nombre de composite
	public function searchAttractorCount($mysearch)
	{		
		$this->initLasttime();
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 		
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
	 //$this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	public function searchAttractorNew($mysearch)	
	{		
    $this->initLasttime();
if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 	
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 		
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 	
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
	//echo "searchSemAttractorCat<br>";
		$this->searchSemAttractor($mysearch);
		$this->contextPartition();
	}
	
	public function searchFromSem($mysearch)
	{		
	$this->initLasttime();
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
	$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
    $this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
    $this->s->Execute ($this->session, 'CONTEXTS.NEW' );
    $this->s->Execute ($this->session, 'CONTEXTS.ADDELEMENT', $mysearch, '100' );
    $this->s->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS','true');
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' ); //CONTEXTS.NEWFROMSEM(<1 0/>, <2 -1/>, <2 -1/>) 
    $this->s->Execute ($this->session, 'CONTEXTS.NEWFROMSEM','0','-1','-1' ); //CONTEXTS.NEWFROMSEM(<1 0/>, <2 -1/>, <2 -1/>) 
	$this->s->Execute ($this->session, 'CONTEXTS.UNION');
    $this->s->Execute ($this->session, 'CONTEXTS.EVALUATE');
    $this->s->Execute ($this->session, 'CONTEXTS.FILTERACT','25');
	 if(isset($_SESSION['genact']) && $_SESSION['genact'] == "gen"){  $this->s->Execute ($this->session, 'CONTEXTS.SortByGenerality','true','false' );}       
    $this->s->Execute ($this->session, 'CONTEXTS.GETELEMENTS' );   //on r�cup�re le contexte en haut de la pile	
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
       public function searchFromSemNew($mysearch)
       {                
       $this->initLasttime();
	   if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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


	public function getbestwords($query , $max_single_words="5", $max_compose_word="5", $arr_context=array(), $arr_inhibited=array(), $user_query_history=array())
	{
		 	
		//echo $query." ".$max_single_words." ".$max_compose_word."<br>";
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
   		$this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
       	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );				
		
		$retf = $this->s->Execute ($this->session, 'CONTEXTS.GETBESTWORDS',$query , $max_single_words, $max_compose_word, 
		count($arr_context), implode(',',$arr_context), 
		count($arr_inhibited), implode(',',$arr_inhibited), 
		count($user_query_history),implode(',', $user_query_history ));
		//echo "KMErrorMsg:".$this->s->KMErrorMsg." KMError:".$this->s->KMError." ";


				
		/*
		$kmr = $this->s->KMResults;
		
		echo "<pre>";
		print_r($kmr);
		echo "</pre>";
		*/
		
	}

public function getbestresults( $mysearch )
	{
		 	
		//echo $query." ".$max_single_words." ".$max_compose_word."<br>";
		//$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
   		//$this->s->Execute ($this->session, 'RESULTS.CLEAR'); //on vide la pile de RS
       	$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );			$this->getLastTime("");	
		
		$this->s->Execute ($this->session, 'CONTEXTS.GETBESTRESULTS',$mysearch ); $this->getLastTime("");
		//echo "KMErrorMsg:".$this->s->KMErrorMsg." KMError:".$this->s->KMError." ";
						
		$kmr = $this->s->KMResults;
		/*
		echo "<pre>";
		print_r($kmr);
		echo "</pre>";
		*/
		
	}


   	public function mixSem($mysearch)
   	{
		if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
   		//echo "<pre>";
   		$this->searchFromSpace($mysearch);
   		$tab01=$this->readArrayKMserver();
		//print_r($tab01);
   		$this->searchFromSem($mysearch);
   		$tab02=$this->readArrayKMserver();
   		//print_r($tab02);
   		$this->searchAttractor($mysearch);
   		$tab03=$this->readArrayKMserver();
   		//echo "tab03";
   		//print_r($tab03);
		if(isset($tab02[0]['results'][0]) && isset($tab03[0]['results'][0])) $m01= array_merge($tab02[0]['results'],$tab03[0]['results']);
   		//echo "m01";
   		//print_r($m01);
		if(isset($m01))		$m02= array_merge_recursive($tab03,$m01);
   		
   		
   		if(isset($m02)) $kmresults=$m02;
		else return "";
   		//echo "kmresults";
   		//print_r($kmresults);
   		//echo "</pre>";
   		return $kmresults;
   		
   	}
   
   // Seamntic&Links
       public function searchSem5($mysearch)
       {                
        $this->initLasttime();
		if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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
		if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
		$this->searchFromSem($mysearch);
		$this->contextPartition();
	}
	
	public function searchFromSpaceold($mysearch)
	{		
		$this->initLasttime();
		if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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
		if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
		$this->searchFromSpace($mysearch);
		$this->contextPartition();
	}
	
	


	

	public function searchTags($myrowid)
	{		
		$this->initLasttime();
		$this->s->Execute ($this->session, 'CONTEXTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session, 'RESULTS.CLEAR');//on vide la pile de contextes
		$this->s->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$this->knw );
		$this->s->Execute ($this->session, 'TABLE:'.$this->mastertable.'.TOCONTEXT',$myrowid);// on r�cup�re le knw_abstract de l'article 167723
	
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
		$this->s->Execute ($this->session, 'TABLE:'.$this->mastertable.'.TOCONTEXT',$myrowid);// on r�cup�re le knw_abstract de l'article 167723			
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
		$this->searchFromSemTags($mysearch);
		$this->contextPartition();
	}
	
	public function searchFromAttractorTags($mysearch)
	{		
	$this->initLasttime();
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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
		//var_dump($this->s->KMResults);

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
		//var_dump($this->s->KMResults );
		if(isset($this->s->KMResults [0]["results"][0][0]))
		{
		//$str = $this->s->KMResults [0]["results"][0][0];
		//$str = htmlentities  ($str);
		//echo "first ".$str;
		//aff_tab($s->KMResults);
		//echo "<pre>";
		//print_r($this->s->KMResults);
		//echo "<pre>";
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
						aff_tab($val);
					}
			  }
		echo "</ul>";
	}	
	
	
	public function reindexation($rowid) {
		//$this->s->Execute ($this->session, ' table'.$this->mastertable.'.KUNINDEX',$rowid);
		$this->s->Execute ($this->session, ' table:'.$this->mastertable.'.KREINDEX',$rowid);
	}
	
	
	public function publish()
	{
		$this->s->Execute ($this->session, ' KNOWLEDGE'.$knw.'.PUBLISH');
	}

	public function save()
	{
		$this->s->Execute ($this->session, ' KNOWLEDGE'.$knw.'.SAVE');
	}

	public function insertnewwikiLineDate($title, $texte, $link ,$date_maj, $date_enr)
	{
		$this->s->Execute ($this->session, ' table:'.$this->mastertable.'.insert','title','texte','link_wikifr','date_maj','date_enr',$title, $texte, $link ,$date_maj, $date_enr);
	}
	public function insertnewwikiLine($title, $texte, $link ) // format lol , good format ...
	{
		$this->s->Execute ($this->session, ' table:'.$this->mastertable.'.insert','title',$title,'texte', $texte , 'link_wikifr',$link );
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
          $this->s->Execute ($this->session, 'table:'.$this->mastertable.'.select', 'new', 'title', '=', $search );
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
     $this->MVClear();
     $N=$this->MVQueryExt($mysearch);
    
      for ($i=1;$i<=$N;$i++)
      {    
        $this->s->Execute ($this->session , 'CONTEXTS.NEWFROMSUBATTRACTOR','0','-1');  $this->getLastTime("'MVSearch:CONTEXTS.NEWFROMSUBATTRACTOR'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.EVALUATE');                $this->getLastTime("'MVSearch:CONTEXTS.EVALUATE'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','true','1500');  $this->getLastTime("'MVSearch:CONTEXTS.TORESULTS','true','-1'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.DROP');                    $this->getLastTime("'MVSearch:CONTEXTS.DROP'"); 
        $this->s->Execute ($this->session , 'CONTEXTS.TORESULTS','false','2500'); $this->getLastTime("'MVSearch:CONTEXTS.TORESULTS','false','-1'"); 
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
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
	if($_SESSION['locutization']=="ok") $mysearch = $this->querylocutization($mysearch); 
     $this->initLasttime(); 
     $this->MVClear();
     $N=$this->MVQueryExt($mysearch);  
    
    
     for ($i=1;$i<=$N;$i++)
      {
        $this->MVContextExtend(); 
        $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','-1' ); 
        //$this->MVContextExtend(); 
        //$this->s->Execute($this->session, 'CONTEXTS.FILTERACT','25' );           
        $this->s->Execute ($this->session , 'CONTEXTS.ROLLUP',$N);
         
      }
       
      for ($i=1;$i<$N;$i++)
      {
        $this->s->Execute ($this->session , 'CONTEXTS.UNION');
      } 
   
     $this->s->Execute($this->session, 'CONTEXTS.EVALUATE' );         
     $this->s->Execute($this->session, 'CONTEXTS.FILTERACT','-1' ); 
    

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