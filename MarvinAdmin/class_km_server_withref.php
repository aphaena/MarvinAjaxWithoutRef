<?php

 /*
  
  Classe d'interface avec un serveur KM.
  
 $arr = array("foo" => "bar", 12 => true);
 fonctionnalités , mode d'emploi prévisionnel:
 
 $fonction = "SERVER.GET";
 $params  = array ("Connected");
 
 $serveur = new KnowledgeServer;
 $serveur->IP = "192.168.0.0";   //ou 127.0.0.1 par défaut 
 $serveur->Port =  1253;     	//1254 par défaut
 $serveur->Connect();           //retourne true si ok
 
 $serveur->$KM_Open_Script();
 $serveur->AddFonction( $fonction, $params); // params est une array contenant la liste des parametre
 // autres appels de fonction éventuellement  
  
 $serveur->$KM_Execute_Script();   //retourne true si ok 
 $serveur->$KM_Close_Script();
 
 le résultat est ds le tableau 
 								$serveur->$KMResults   // un tableau array
  
    erreur d'éxécution :			$serveur->$KMerror		// contient true si ça s'est bien passé, false autrement
  									$serveur->$KMerrorMsg   // contient le message d'erreur
  
  */
 
 class KMServer
{
   	
	public static function ToGPString(&$str) 
	{ 
		//transforme une chaine en GPString
		$len = (string)strlen($str);
		$str = "<".$len." ".$str."/>";
		
	}//end function ToGPString
	
	public static function ToProtocol(&$str) 
	{ 
		//transforme une chaine en chaine protocolaire
		$len = (string)strlen($str);
		$len2 =(string)strlen($len); 
		$str = "#".$len2."#".$len." ".$str;
		
	}//end function ToProtocol
	
	public static function FromProtocol(&$str) 
	{ 
		//transforme une chaine de protocole en chaine
		//rajouter le traitement d'erreur au cas ou, strlen($str) = $len2 + $len1 + 3; 
		$len1 = (int)substr($str,1,1);
		$len2 = (int)substr($str,3,$len1);		
		$str = substr($str,4+$len1,$len2);		
		
	}//end function ToProtocol	
	

	public static function FromGPString(&$str) 
	{ 
		//transforme une GPString en chaine
		$len = strlen($str);
		$idx = strpos($str, ' ', 1);
		$i = (int)substr($str,1,$idx-1); //taille de la chaine standard
		$str = substr($str,$idx+1,$i);		
		
	}//end function FromProtocol	
	
   public static function GetNextString(&$str,&$idx1) 
	{
		// positionne les index de recherche de mots pour encadrer le mot suivant
		// tient compte des GPString
		//idx1 contient la prochaine position d'analyse, ou -1 si fin de chaine 
		// retourne la chaine située au début de l'analyse
		
		$len = strlen($str);
		if ($idx1 < 0) $idx1 = 0;		
		if ($idx1 >= $len)
		{
			$idx1=-1;
			return '';			
		}
		
		//on recherche le premier caractère non espace
		while ($str[$idx1]== ' ')
		{
			$idx1 +=1;
			if ($idx1 >= $len){$idx1=-1;return;}
		}		
		//est ce une GPString ?
		if ($str[$idx1]== '<')
		{
			// on décode la taille de la GPString
			$idx1 +=1;
			$idx2 = $idx1;
			while ($str[$idx2]!= ' ') {$idx2 +=1;}		     
			$size = (int) substr ($str,$idx1,$idx2-$idx1);
			//$idx2 += 1;			
			$idx1 = $idx2+ $size + 3 ;
			if ($idx1 >= $len) {$idx1 = -1;} 
			if ($size == 0) return "";
			$idx2 += 1;
			return substr ($str,$idx2,$size);
		}
		
		//sinon on avance tant que le curseur n'est pas un espace 
		$idx2 = $idx1;
		while ($str[$idx2]!= ' ') {$idx2 +=1;}
		$idx3 = $idx1;
		$idx1 = $idx2;
		return substr ($str,$idx3,$idx2-$idx3);	
		
	}//end get next string  
	


	
	// déclaration des propriétés
    public $IP;  //adresse IP defaut 127.0.0.1
    public $Port; // Port défaut 1254
    public $Sock; // La socket cliente vers le serveur KM
    public $isConnected; //connexion km
    public $isValid;      //socket IP valide
    public $isError;
    public $isBlocking;
    public $ErrorMsg;
    public $TimeLimit; //time out de connexion en s
    public $toSend ;	
    //public $toReceive;
    
    
    //propriétés liés à KM
    public $KMSessions;	    	//tableau des id de connexion crées par l'objet
    public $KMScriptSession;	//contient l'id de session pour le script
    public $KMScriptSize;		//nombre de lignes de scripts en retour
    public $KMResults;    		//tableau des résultats
    public $KMTransactions; 	//liste des résultats d'une transaction de plusieurs lignes
    
    public $KMError ;     // booléen, retour d'erreur d'un appel de fonction KM
    public $KMErrorMsg ;  // booléen, retour d'erreur d'un appel de fonction KM
    public $KMId; 		  //Id de session KM de l'objet KMServer
    public $KMCurrentId;  //Id de session KM de la dernière requete
    
    public $KMFunction;   // fonction IKM à appeler
    public $KMParams;     // liste des parametres de la fonction KM    
    public $KMTypeLabel; 
    
    
	function __construct() 
	{
        $this->IP = 			"127.0.0.1";
        $this->Port = 			1254;
        $this->Sock =			NULL;
        $this->isConnected = 	false; // état de la connexion au serveur IKM
    	$this->isValid = 		false; //la connection socket TCP est valide
   		$this->isError = 		true;
    	$this->isBlocking = 	true;
    	$this->Errormsg = 		"TCP Socket not created";
    	$this->TimeLimit =		10; 	//10 s de time out par défaut, 0 pas de time out
    	$this->KMId = 			-1;		//pas d'Id de session IKM
    	$this->toSend 	=		"";     //chaine à envoyer au serveur
    	$this->Received =		"";     //chaine de retour du serveur
    	
  		
   		$this->KMScriptSize = 0;  
   		$this->KMScriptSession = -1;
   		$this->KMSessions = array();
       
    	$this->KMTypeLabel [0] = "string"; 			$this->KMTypeLabel [1] = "int32";		$this->KMTypeLabel [2] = "uint32";
    	$this->KMTypeLabel [3] = "int8"; 			$this->KMTypeLabel [4] = "uint8";		$this->KMTypeLabel [5] = "char";
    	$this->KMTypeLabel [6] = "int64"; 			$this->KMTypeLabel [7] = "uint64";		$this->KMTypeLabel [8] = "string";
    	$this->KMTypeLabel [9] = "float"; 			$this->KMTypeLabel [10] = "double";		$this->KMTypeLabel [11] = "bool";
    	$this->KMTypeLabel [12] = "simpledate"; 	$this->KMTypeLabel [13] = "rowid";		$this->KMTypeLabel [14] = "sessionid";
       }
    
	function __destruct() 
	{
        //destruction des sessions KM crées par l'objet
        
		if (isset($this->KMSessions))
		{
		    foreach ($this->KMSessions as $value) 
		    {
    			$this->CloseKMSession ($value);
		    }	
		}
		
	//fin de la liaison physique	
		if (isset($this->Sock)) 
          {
          	 socket_close($this->Sock);
          	 $this->Sock = NULL;
          }
    }// end destruct 

    public function ExecuteKMCommand ($str)
    { 
       //execute une commande déjà formatée, un script par exemple
       $this->toSend = $str;
       return $this->KM_Execute_Script ();
    }
    
    public function Execute ()
    { 
        $this->toSend ='';
		$params = func_get_args(); 
		$numargs = func_num_args();
		//correctif pour transmettre un tableau xb
		if(is_array($params[0])==true ) 
		{
			$params = func_get_arg(0); 
		}
       
        $idx = 0;
        $str = (string) $params [$idx];
        //1er parametre = id de session ?        
        if (is_numeric($str) === true ) { $session = $str; $idx +=1;}
        else							{ $session = $this->KMId;}        
    	$function = (string) $params [$idx];
    	$idx +=1;
    	$this->toSend = $session;
    	$this->toSend .= ' ';
    	$this->toSend  .= (string) $function;
    	$this->toSend  .= " ( ";
    	for ($i=$idx ; $i < count($params); $i++ ) 
    	{
    		$str = $params [$i];
    		if (strcasecmp($str,'null') !== 0 && strcasecmp($str,'default') !== 0)
    		{
    			KMServer::ToGPString($str);
    		}

    		$this->toSend  .= $str;
    		$this->toSend  .= ' ';
    		if ($i< count($params)-1)
    		{
    			$this->toSend  .= ', ';
    		}		
    		
    	}// end boucle $i
    	
    	$this->toSend  .= ')';    	
    	return $this->KM_Execute_Script ();
        
    }
    
    
    public function KM_Open_Script ($session = null)
    {
    	//utilise un numéro de seeion, ou l'id de session par défaut de l'objet connecté si pas d'argument
    	if ($session === null) 	{$session = $this->KMId;} 
    	if ($session < 0) 		{$session = $this->KMId;} 
    	$this->KMScriptSession = $session;    	
    	if (isset($this->toSend)) {$this->toSend= '';} 
    	
    }// end KM_Open_Script
    
    
    public function KM_Execute_Script ()
    {
    	$this->Send();
        $this->Receive();
        $this->Analyse();

        if ($this->KMError == '0') { echo $this->KMErrorMsg;  return false;}
    	return true ;
    	
    }// end KM_Execute_Script
    
    public function AddFunction($function)
    {
    	//on va fabriquer la chaine à destination du serveur
    	$params = func_get_args();
    	 
    	$this->toSend  = (string)$this->KMScriptSession . " ";
    	$this->toSend  .= (string) $function;
    	$this->toSend  .= " ( ";
    	for ($i=1 ; $i < count($params); $i++ ) 
    	{
    		$str = $params [$i];
    		if (strcasecmp($str,'null') !== 0 && strcasecmp($str,'default') !== 0)
    		{
    			KMServer::ToGPString($str);
    		}

    		$this->toSend  .= $str;
    		$this->toSend  .= ' ';
    		if ($i< count($params)-1)
    		{
    			$this->toSend  .= ', ';
    		}		
    		
    	}// end boucle $i
    	
    	$this->toSend  .= ') ;';
    	
    }// end AddFonction  

    public function KM_Close_Script ()
    {
    	$this->KMScriptSession = -1;
    	if (isset($this->toSend)) {$this->toSend ='';}	
    	
    }// end KM_Close_Script     
    
    
    
    public function AnalyseLine(&$idx) 
	{
		//analyse la chaine Received, pour la formater sous forme de tableau					

		// on extrait le nb de lignes et de colonnes du résultat
		$lines = 	(int)KMServer::GetNextString ($this->Received,$idx);
		$columns = 	(int)KMServer::GetNextString ($this->Received,$idx);
		
		$this->KMResults [$this->KMScriptSize]['lines'] = 		$lines;
		$this->KMResults [$this->KMScriptSize]['columns'] = 	$columns;
		
		//on lit les types, tailles et noms des colonnes
		$types = array();
		$sizes = array();
		$names = array();
		
		for ($i = 0; $i < $columns; $i++)
		{
			$types[]= 	$this->KMTypeLabel [(int)KMServer::GetNextString ($this->Received,$idx)];
			$sizes[]= 	(int)KMServer::GetNextString ($this->Received,$idx);
			$names[]= 	KMServer::GetNextString ($this->Received,$idx);
		}
		
		$this->KMResults [$this->KMScriptSize]['types'] = 		$types;
		$this->KMResults [$this->KMScriptSize]['sizes'] = 		$sizes;
		$this->KMResults [$this->KMScriptSize]['names'] = 		$names;
		
		//on lit les données
		$results = array();
			for ($i = 0; $i < $lines; $i++)
			{
				for ($j = 0; $j < $columns; $j++)
				{
					$str = KMServer::GetNextString ($this->Received,$idx);
					$results[$i][$j]= $str;
				}
			}
		$this->KMResults [$this->KMScriptSize]['results'] = $results;
	}// end analyse line
	
	
    public function Analyse() 
	{    
      //analyse les différentes valeurs de retour de la chaine Received
	  
	  if ($this->KMScriptSize >= 0)
	  {
	  	//on vide les tableaux de résultat
	  	if (isset ($this->KMResults)) 	unset($this->KMResults);    //tableau des résultats    	
	    $this->KMScriptSize = 0;
	  }
	  	
		// on va analyser la chaine de retour
		$idx = 0;
		//identifiant de session
		$this->KMCurrentId = 		KMServer::GetNextString ($this->Received,$idx);
		//indicateur d'erreur
		$this->KMError = 	KMServer::GetNextString ($this->Received,$idx);		
		if ($this->KMError == '0')
		{
			//erreur en retour
			$this->KMErrormsg = KMServer::GetNextString ($this->Received,$idx);
			return;
		}
		// pas d'erreur, on poursuit
		// on appelle AnalyseLine
		$this->AnalyseLine(&$idx);
		$len = strlen ($this->Received);
		if ($idx == -1)							return;	
		if ($idx >= $len)						return;	
			
		while ($idx < $len && $idx!= -1 )
		{
			while ($this->Received[$idx] == ' ')
			{
				$idx  += 1;
				if ($idx >= $len)				return;	
				
			}
			
			if ($this->Received[$idx] != ';')  return;
			$idx  += 1;
			if ($idx >= $len-1)				   return;
			$this->KMScriptSize += 1;
			$this->AnalyseLine(&$idx);	
		}	
		
	}// end analyse
	
	public function OpenKMSession() 
	{
		//ouvre une nouvelle session KM
		//retourne -1 en cas d'erreur, un numéro de session sinon
		//la liste des n° de session est mise à jour
		$this->toSend = "-1 CONNECT (NULL);";
		$this->KM_Execute_Script ();
		if ($this->KMError == '0') return -1;	
		$this->KMSessions[]= $this->KMCurrentId  ;	
        return $this->KMCurrentId;
		
	}// end OpenKMSession
	
	public function CloseKMSession($id) 
	{
		//détruit la session $id. attention, ne jamais utiliser le numéro de session de l'objet $KMId
		
		$key = array_search($id, $this->KMSessions); 
		if ($key === false) return false;		
		unset ($this->KMSessions[$key]); //on vire la référence
		return $this->Execute ($this->KMId,"session.KILL",$id);
		
	}//CloseKMSession
	
	
	public function Connect() 
	{
        if ($this->isValid === true)
        {
        	//erreur, la connexion existe déjà
        	$this->Errormsg = 		"socket already exists : " ;
        	$this->isError = 		true;
        	$this->isValid = 		false;
        	return false;         	
        	
        }
		
		//on initialise le time out de connexion
		//set_time_limit($this->TimeLimit); // à voir comment ça se comporte le truc
		set_time_limit(0); //pour debuggage
        //creation de la socket cliente, et connection au niveau IP
        //sur Linux, remplacer AF_INET, par AF_UNIX plus efficace
        if (($this->Sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false)
        {
        	//en cas d'erreur
        	$this->Errormsg = "socket creation failure : " . socket_strerror(socket_last_error());
        	$this->isError = 		true;
        	$this->isValid = 		false;
        	return false;        
        }
        
        $result = socket_connect($this->Sock, $this->IP, $this->Port);
        if ($this->Sock === NULL)
        {
        	close_socket($this->Sock);
        	$this->Sock = NULL;
        	return false;
        }
        

        //ici, la connection TCP a été établie
        	$this->isValid = 		true;
        	$this->isError = 		false;
        	$this->Errormsg = 		"ok";
        	
        //maintenant on va tenter une connexion KM
        $this->toSend = "-1 CONNECT (NULL);";
        $this->Send();
        $this->Receive();
        $this->Analyse();		
        if ($this->KMError == '0') return false;
        $this->isConnected = true;
        $this->KMId = $this->KMCurrentId;
		return true; 
		
    }// end connect
    
    public function Send() 
	{ 
		//traitement d'erreur à finir
		$ok = false;
		if ($this->isValid === false) return $ok;		
		KMServer::ToProtocol($this->toSend);

		$len = strlen($this->toSend);
		$offset = 0;
		while ($offset < $len) 
			{
    			$sent = socket_write($this->Sock, substr($this->toSend, $offset), $len-$offset);
    			if ($sent === false) 
    				{
        				// erreur
        				return $ok;
    				}
    			$offset += $sent;
			}// end while
			
		if ($offset < $len) 
			{
    		//$errorcode = socket_last_error();
    		//$errormsg = socket_strerror($errorcode);
    		//echo "SENDING ERROR: $errormsg";
    		return $ok;
			} 

		$ok = true;
		return $ok;
	}//end function send
	
	public function Receive() 
	{ 
		//traitement d'erreur à finir
		$ok = false;
		if ($this->isValid === false) return $ok;
		
		$ByteToReceive = 1024;
		$ByteReceived  = 0;
		$isHeaderOk = false;		
		$this->Received = "";
		
		while($ByteToReceive>0)
    		{
    			$recv = "";
    			$recv = socket_read($this->Sock, '65536');
    			$this->Received .= $recv;
    			$ByteReceived  += strlen($recv);
                $ByteToReceive -= strlen($recv);
    			if ($isHeaderOk === false)
    			{
    				if (strlen($this->Received)>2)
    				{
    					if ($this->Received[0]!=='#') {	return false;}
    					$len1 = (int)substr($this->Received,1,1); 
    					if ($len1 <= 0) {	return false;}
    					if (strlen($this->Received)> 5 + $len1)
    					{    						
    						if ($this->Received[2] !== '#') 		{return false;}
    						if ($this->Received[3+$len1] !== ' ') 	{return false;}
    						$len2 = (int)substr($this->Received,3,$len1);
							$this->Received = substr ($this->Received,4+$len1);
    						$ByteReceived = strlen($this->Received);
    						$ByteToReceive = $len2-$ByteReceived;
    						$isHeaderOk = true;
    					}
    					
    				}// end header
    				
    			if ($isHeaderOk === true)
            		{
                		if ($ByteToReceive == 0) {return true;}  
    				}
    			
    			
    		}
    	}

		return true;	
		
	}//end function receive
	   

	
	
} // end class KMServer


 
	?>
