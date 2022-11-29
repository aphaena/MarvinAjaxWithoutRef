<?php
include_once 'class_km_server.php';

class ikmInterface {
				
	 public $ikm;
	 public $session;
	 
	
	function __construct() 
	{
		$this->ikm = new KMServer;
	}
	
	public function connectdefault() 
	{
		echo "connecting...";
		$this->connect($ip_connect_default,"1254");
		$this->knowledge("wikiknw");	
		$this->KMId();
	}
	
	/**
	 * 
	 * @param string $_ip ex: "127.0.0.1"
	 * @param unknown_type $_port ex: 1254
	 * @return A valid sessionId, and a possible challenge for password identification
	 * @see -1 CONNECT ( null ) ;
	 * returns
	 * 623 1 1 1 8 0 CHALLENGE <0 \>
	 * 623  is the new valid session Id
	 * password  é rights checking is presently unactivated
	 */
	public function connect($_ip, $_port)
	{
		$this->ikm->IP = $_ip;
		$this->ikm->Port = $_port;
		$sessionID = $this->ikm ->connect();
		return $sessionID;
	}
	
	/**
	 * return ip
	 * @return string IP
	 */
	public function KMServerGetIp()
	{
			return $this->ikm->IP;
	}
	
	/**
	 * KMServer Set Ip
	 * @param string $_ip
	 * @return unknown_type
	 */
	public function  KMServerSetIp($_ip="127.0.0.1")
	{
		return $this->ikm->IP = $_ip;
	}
	
	/**
	 * return Port
	 * @return string Port
	 */
	public function KMServerGetPort()
	{
			return $this->ikm->Port;
	}
	
	/**
	 * KMServer Set Port 
	 * @param string Port ex "1254"
	 * @return unknown_type
	 */
	public function  KMServerSetPort($_port="1254")
	{
		return $this->ikm->Port = $_port;
	}
	
	/**
	 * return Sock
	 * @return string Sock
	 */
	public function KMServerGetSock()
	{
			return $this->ikm->Sock;
	}
	
	/**
	 * KMServer Set Sock 
	 * @param string Port 
	 * @return unknown_type
	 */
	public function  KMServerSetSock($_Sock)
	{
		return $this->ikm->Sock = $_Sock;
	}
	
	/**
	 * return isConnected
	 * @return string isConnected
	 */
	public function KMServerGetisConnected()
	{
			return $this->ikm->isConnected;
	}
	
	/**
	 * KM connection state
	 * return isValid
	 * @return string isValid
	 */
	public function KMServerGetisValid()
	{
			return $this->ikm->isValid;
	}
	
	/**
	 * error flag true or false
	 * return isError
	 * @return string isError
	 */
	public function KMServerGetisError()
	{
			return $this->ikm->isError;
	}
	
	
	/**
	 * Socket bloking mode flag (default blocking)
	 * return isBlocking
	 * @return string isBlocking
	 */
	public function KMServerGetisBlocking()
	{
			return $this->ikm->isBlocking;
	}
	
	
	/**
	 * Socket error message
	 * return ErrorMsg
	 * @return string ErrorMsg
	 */
	public function KMServerGetErrorMsg()
	{
			return $this->ikm->ErrorMsg;
	}
	
	
	/**
	 * Connection time out in s
	 * return TimeLimit
	 * @return string TimeLimit
	 */
	public function KMServerGetTimeLimit()
	{
			return $this->ikm->TimeLimit;
	}
	
	/**
	 * Connection time out in s
	 * return TimeLimit
	 * @return string TimeLimit
	 */
	public function KMServerSetTimeLimit($_TimeLimit )
	{
			return $this->ikm->TimeLimit = $_TimeLimit;
	}
	
	
	
	
	/**
	 * Data to send to the server
	 * return toSend
	 * @return string toSend
	 */
	public function KMServerGettoSend()
	{
			return $this->ikm->toSend;
	}
	
	
	/**
	 * Data to send to the server
	 * return toSend
	 * @return string toSend
	 */
	public function KMServerSettoSend($_toSend)
	{
			return $this->ikm->toSend=$_toSend;
	}

	/**
	 * Data received from the server
	 * return Received
	 * @return string Received
	 */
	public function KMServerGetReceived()
	{
			return $this->ikm->Received;
	}
	
	/**
	 * KM session Id array
	 * return KMSessions
	 * @return string KMSessions
	 */
	public function KMServerGetKMSessions()
	{
			return $this->ikm->KMSessions;
	}
		
	/**
	 * Session Id of the current script
	 * return KMScriptSession
	 * @return string KMScriptSession
	 */
	public function KMServerGetKMScriptSession()
	{
			return $this->ikm->KMScriptSession;
	}	
	
	
	/**
	 * Number of command lines of the script
	 * return KMScriptSize
	 * @return string KMScriptSize
	 */
	public function KMServerGetKMScriptSize()
	{
			return $this->ikm->KMScriptSize;
	}	
	
	/**
	 * Boolean. Error state returned by a KM  function call
	 * return KMError
	 * @return string KMError
	 */
	public function KMServerGetKMError()
	{
			return $this->ikm->KMError;
	}	
	
	/**
	 * KM error code
	 * return $KMErrorMsg
	 * @return string $KMErrorMsg
	 */
	public function KMServerGetKMErrorMsg()
	{
			return $this->ikm->$KMErrorMsg;
	}	
	
	/**
	 * Session Id of a KMServer object
	 * return KMId
	 * @return string KMId
	 */
	public function KMServerGetKMId()
	{
			return $this->ikm->KMId;
	}	
	
	/**
	 * Session Id of the last request
	 * return KMCurrentId
	 * @return string KMCurrentId
	 */
	public function KMServerGetKMCurrentId()
	{
			return $this->ikm->KMCurrentId;
	}	
	
	// SERVER xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	
	/**
	 * 
	 * @return Array
	 */
	public function serverGetName()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','Name');
		 return $this->ikm->KMResults;
	}
	
	/**
	 * 
	 * @return Array
	 */	
	public function serverSetName($_Name)
	{
		 return $this->ikm->Execute ($this->session, 'SERVER.SET','Name',$_Name);		
	}

	/**
	 * 
	 * @return Array
	 */	
	public function serverGetType()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','Type');
		 return $this->ikm->KMResults;
	}
	
	/**
	 * 
	 * @return Array
	 */	
	public function serverGetModel()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','Model');
		 return $this->ikm->KMResults;
	}
	
	/**
	 * 
	 * @return Array
	 */
	public function serverGetVersion()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','Version');
		 return $this->ikm->KMResults;
	}
	/**
	 * 
	 * @return Array
	 */		
	public function serverGetBuild()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','Build');
		 return $this->ikm->KMResults;
	}
	/**
	 * 
	 * @return Array
	 */
	public function serverGetClusterPort()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','ClusterPort');
		 return $this->ikm->KMResults;
	}
	/**
	 * 
	 * @return Array
	 */	
	public function serverGetClustermembers()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','Clustermembers');
		 return $this->ikm->KMResults;
	}
	
	/**
	 * Max number of system threads allowed to the server
	 * 
	 */
	public function serverGetCommandThreads()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','CommandThreads');
		 return $this->ikm->KMResults;
	}
	
	/**
	 * Max number of system threads allowed to the server
	 * 
	 */
	public function serverSetCommandThreads($_CommandThreads)
	{
		 return $this->ikm->Execute ($this->session, 'SERVER.SET','CommandThreads',$_CommandThreads);		 
	}
	/**
	 * HH:MM:SS:ms  ex:  17:11:26:890
	 * @return string
	 */
	public function serverGetCommandLocalDate()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','LocalDate');
		 return $this->ikm->KMResults;
	}
	/**
	 * In seconds ex : 395426.44090625
	 * @return string
	 */
	public function serverGetCommandGMTTime()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','GMTTime');
		 return $this->ikm->KMResults;
	}
	
	/**
	 * 
	 * @return Array
	 */
	public function serverGetCommandUPTime()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','UPTime');
		 return $this->ikm->KMResults;
	}
	/**
	 * 
	 * @return Array
	 */
	public function serverGetCommandIdleTime()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','IdleTime');
		 return $this->ikm->KMResults;
	}
	/**
	 * 
	 * @return Array
	 */	
	public function serverGetCacheSize()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','CacheSize');
		 return $this->ikm->KMResults;
	}	
	
/**
 * CacheSize
 * @param string $_CacheSize ex: 4096
 * @return Array
 */	
	public function serverSetCacheSize($_CacheSize)
	{		 
		 $this->ikm->Execute ($this->session, 'SERVER.SET','CacheSize',$_CacheSize);		 
		 return $this->ikm->KMResults;
	}

	/**
	 * CacheUsed
	 * @return Array
	 */
	public function serverGetCacheUsed()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','CacheUsed');
		 return $this->ikm->KMResults;
	}	
/**
 * CacheHits
 * @return Array
 */	
	public function serverGetCacheHits()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','CacheHits');
		 return $this->ikm->KMResults;
	}	
/**
 * State
 * @return Array
 */
	public function serverGetState()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','State');
		 return $this->ikm->KMResults;
	}	
/**
 * ExecTimeOutDefault
 * @return Array
 */
	public function serverGetExecTimeOutDefault()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','ExecTimeOutDefault');
		 return $this->ikm->KMResults;
	}	
/**
 * ExecTimeOutDefault
 * @param string $_ExecTimeOutDefault ex:5000
 * @return Array
 */	
	public function serverSetExecTimeOutDefault($_ExecTimeOutDefault)
	{
		 $this->ikm->Execute ($this->session, 'SERVER.SET','ExecTimeOutDefault',$_ExecTimeOutDefault);
		 return $this->ikm->KMResults;		 
	}
/**
 * SessionTimeOutDefault
 * @return Array
 */
	public function serverGetSessionTimeOutDefault()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','SessionTimeOutDefault');
		 return $this->ikm->KMResults;
	}	
/**
 * SERVER.SET SessionTimeOutDefault
 * @param string $_SessionTimeOutDefault
 * @return string
 */	
	public function serverSetSessionTimeOutDefault($_SessionTimeOutDefault)
	{
		 $this->ikm->Execute ($this->session, 'SERVER.SET','SessionTimeOutDefault',$_SessionTimeOutDefault);		 
		 return $this->ikm->KMResults;
	}		
/**
 * Get Connected
 * @return Array
 */
	public function serverGetConnected()
	{
		 $this->ikm->Execute ($this->session, 'SERVER.GET','Connected');
		 return $this->ikm->KMResults;
	}	
	
	// SESSION property xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	
	/**
	 * Instances
	 * @return array
	 */
	public function sessionGetInstances()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','Instances');
		 return $this->ikm->KMResults;
	}

	/**
	 * 
	 * @return string
	 */
	public function sessionGetLastTime()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','LastTime');
		 return $this->ikm->KMResults[0]["results"][0][0];
	}
	/**
	 * 
	 * @return string
	 */
	public function sessionGetId()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','Id');
		 return $this->ikm->KMResults;
	}		
	
	/**
	 * 
	 * @return string
	 */
	public function sessionGetKnowledge()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','Knowledge');
		 return $this->ikm->KMResults[0]["results"][0][0];
	}	
	/**
	 * 
	 * @param string $_Knowledge
	 * @return Array
	 */
	public function sessionSetKnowledge($_Knowledge)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.SET','Knowledge',$_Knowledge);
		 return $this->ikm->KMResults;		 
	}	

	/**
	 * 
	 * @return string
	 */
	public function sessionGetOwnerIP()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','OwnerIP');
		 return $this->ikm->KMResults;
	}		
	
	/**
	 * 
	 * @return string
	 */
	public function sessionGetOwnerPort()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','OwnerPort');
		 return $this->ikm->KMResults;
	}	
	

	/**
	 * 
	 * @return string
	 */
	public function sessionGetPriority()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','Priority');
		 return $this->ikm->KMResults;
	}	
	
	public function sessionSetPriority($_Priority)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.SET','Priority',$_Priority);
		 return $this->ikm->KMResults;		 
	}	

	/**
	 * 
	 * @return string
	 */
	public function sessionGetExecTimeOut()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','ExecTimeOut');
		 return $this->ikm->KMResults;
	}	
	
	public function sessionSetExecTimeOut($_ExecTimeOut)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.SET','ExecTimeOut',$_ExecTimeOut);
		 return $this->ikm->KMResults;		 
	}

	/**
	 * 
	 * @return string
	 */
	public function sessionGetSessionTimeOut()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.GET','SessionTimeOut');
		 return $this->ikm->KMResults;
	}	
	
	public function sessionSetSessionTimeOut($_ExecTimeOut)
	{
		  $this->ikm->Execute ($this->session, 'SESSION.SET','SessionTimeOut',$_SessionTimeOut);
		  return $this->ikm->KMResults;		
				 		 
	}		
	
	
	// SESSION methode xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	
	/**
	 * Null, or a RW mode
	 * global method
	 * 
	 * @see -1 CONNECT ( null ) ;
	 * returns
	 * 623 1 1 1 8 0 CHALLENGE <0 \>
	 * 623  is the new valid session Id
	 * password  & rights checking is presently unactivated
	 * @return array A valid sessionId, and a possible challenge for password identification
	 */
	public function sessionConnect()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.CONNECT','null');		 
		 return $this->ikm->KMResults;
	}	
		
	/**
	 * For admin purpose Kills a session warning !! do not kill the present the session
	 * @param string $_sessionId
	 * @return array
	 */
	public function sessionKill($_sessionId)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.Kill',$_sessionId);		 
		 return $this->ikm->KMResults;
	}	

	/** For admin purpose gets the las executed script of a session
	* @param string $_sessionId	
	*/
	public function sessionBreak($_sessionId)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.Break',$_sessionId);
		  return $this->ikm->KMResults;				 		
	}

	public function sessionScript($_sessionId)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.Script',$_sessionId);		 
		 return $this->ikm->KMResults;
	}		 		 
		
	public function sessionClear()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.Clear');		 		 		 
	}		

	public function sessionProps()
	{
		 $this->ikm->Execute ($this->session, 'SESSION.Props');		 
		 return $this->ikm->KMResults;		 
	}	
	
	/**
	 * @param $_PropertyAdd ex "toto"
	 * @param string $_PropertyAdd
	 * 
	 */
	
	public function sessionPropertyAdd($_PropertyName)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.PropertyAdd',$_PropertyName);		 		 		 
	}		
	
	public function sessionPropertyDelete($_PropertyName)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.PropertyDelete',$_PropertyName);		 		 		 
	}		
	
	public function sessionPropertySet($_PropertyName, $_PropertyValue)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.PropertySet',$_PropertyName, $_Propertyvalue);		 		 		 
	}		

	public function sessionPropertyGet($_PropertyName)
	{
		 $this->ikm->Execute ($this->session, 'SESSION.PropertyGet',$_PropertyName);		 		 		 
	}			

	
	// knowledge xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	
	
	public function knowledge($_knw)
	{
		$this->knw = $_knw;
	}
	
	public function knowledgeGetInstances()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE.GET','Instances');
		 return $this->ikm->KMResults;
	}		

	public function knowledgeGetOwner()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','Owner');
		 return $this->ikm->KMResults;
	}	
	
	public function knowledgeGetType()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','Type');
		 return $this->ikm->KMResults;
	}		

	public function knowledgeGetFilter()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','Filter');
		 return $this->ikm->KMResults;
	}

	//????????????????????????????????????
	public function knowledgeSetFilter($_data)
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.SET','Filter',$_data);				  
	}

	//???????????????????????????????????
	public function knowledgeGetKey($_key, $_nbKey)
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','Keys',$_key, $_nbKey);
		 return $this->ikm->KMResults;
	}

	public function knowledgeGetMasterTable()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','MasterTable');
		 return $this->ikm->KMResults;
	}

	public function knowledgeGetTotalSem()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','TotalSem');
		 return $this->ikm->KMResults;
	}	

	public function knowledgeGetTotalKeys()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','TotalKeys');
		 return $this->ikm->KMResults;
	}		

	public function knowledgeGetTotalRefs()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','TotalRefs');
		 return $this->ikm->KMResults;
	}	

	public function knowledgeGetIndexationCache()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','IndexationCache');
		 return $this->ikm->KMResults;
	}	
	
	public function knowledgeSetIndexationCache($_cacheSize)
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.SET','IndexationCache',$_cacheSize);
	}	
	
	public function knowledgeGetIndexationCacheUsed()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','IndexationCacheUsed');
		 return $this->ikm->KMResults;
	}	

	public function knowledgeGetIndexationTimeOut()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','IndexationTimeOut');
		 return $this->ikm->KMResults;
	}	
	
	public function knowledgeSetIndexationTimeOut($_time)
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.SET','IndexationTimeOut',$_time);
	}

	public function knowledgeGetSafeMode()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','SafeMode');
		 return $this->ikm->KMResults;
	}	
	
	public function knowledgeSetSafeMode($_time)
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.SET','SafeMode',$_time);
	}

	public function knowledgeGetSensitivity()
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','Sensitivity');
		 return $this->ikm->KMResults;
	}	
	
	public function knowledgeSetSensitivity($_sensitivity)
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.SET','Sensitivity',$_sensitivity);
	}

	public function knowledgeGetLocalIndexation()//Not yet active
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.GET','LocalIndexation');
		 return $this->ikm->KMResults;
	}	
	
		// knowledge Method xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
 	/**
 	 * knowledge Create
 	 * @see Name : the name of the knowledge to create
 	 * owner :  the name of the owner,  may be another knowledge, but generally NULL (default owner is the server)
 	 * location : the path of the knowledge files. Generally  NULL,  eg the files are stored inside the server's repository.
 	 * In .../data/knowledge/wikiknw/wikiknw.knw
 	 * type :  'auto'  only for this version
 	 * filter : the name of a lookup table object.
 	 * Usually NULL, and then will use the default lookup table with only control character filtering.
 	 * 5 knw,create (<6 marvin/>), <1 0/>, <4 auto/>,<1 0/>)
 	 * or 5 knw:create (<6 marvin/>),NULL, <4 auto/>,NULL)
 	 * @param string $name
 	 * @param string $owner
 	 * @param string $location
 	 * @param string $type
 	 * @param string $filter
 	 */
	public function knowledgeCreate($name,$owner="NULL",$location="NULL",$type="AUTO",$filter="NULL")
	{
		 $this->ikm->Execute ($this->session, 'KNOWLEDGE.CREATE',$name,$owner,$location,$type,$filter);
		 
	}

	
	/**
	 * 5 knw:wikiknw.save ( )
	 * saves the neural net to the disk.
	 * @see Warning,  indexation operations,  PUBLISH ( ),  CLEAR ( ),  REBUILD ( ), usually does not save the knowledge
	 * after an indexation session,  if new sem links have  been learned,  a SAVE () must be issued in order to make this new knowledge persistant.
	 * @return unknown_type
	 */
	public function knowledgeSave()
	{
		$this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.SAVE');		 		 
	}	
	
	public function knowledgeKillRef()
	{
		$this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.KillRef');		 		 
	}	

	public function knowledgeClear()
	{
		$this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.Clear');		 		 
	}		
	
	/**
	 * @see If mode = ''  learn semantics from the parameters column from lines rowId1 to rowId2 of the mastertable
	 * if mode =  'ref'  proceed to indexation too
	 * 5 knw:wikiknw.rebuild (< 11 title texte/> , <1 0/>, <3 999/>, <3 ref/>) ;
	 * reindex and reads all documents from rowid = 0 to rowId = 999.
	 * if mode = 'abstract'   rebuilds all the references of the knowledge according to the field  KNW_ABSTRACT of  the mastertable. 
	 * This is equivalent of reconstructing the reverse index.
	 * Generally it is preceeded by a CLEAR () command
	 * @param string $_col fields names ex: "title texte" 
	 * @param string $_row1 
	 * @param string $_row2
	 * @param string $_mode ref or abstract or learn
	 * 
	 */
	public function knowledgeRebuild($_col,$_row1,$_row2,$_mode)
	{
		$this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.Rebuild',$_col,$_row1,$_row2,$_mode);		 		 
	}		

	/**
	 * @see Learn	
	 * String <text>,
	 * uint32 <KeyOrigin>
	 *  [[int32 <DBId>],
	 *  [uint32 <RefOrigin>,
	 *  string <mode>,
	 *  int <threshold>,
	 *  int <activity min>],
	 *  int <activity max>],
	 *     	Int32 <langage>,
	 *     int32 <meaning>
	 *     these indicators have values between 0-100%
	 *     they rate the quality of the indexation according to the present knowledge state.
	 *     Langage is a measure of the rate of known patterns int the submitted text
	 *     Meaning is a measure of the activity and the rate detection of contexts in the texte
	 *      	Text : the text that is to be read and learned by the neural net
	 *      keyorigin : non active yet, use NULL or <0>
	 *      DBId : a rowid of  line of the master table.
	 *      If dbid is >= 0 the document is also indexed according to an internal semantic evaluation algorithm.
	 *      If DBId = -1, no indexation occurs,  only the semantic training part of the process occurs.
	 *      Ref origin :inactive yet, use NULL or  <0>
	 *      mode : allways  'auto' for this version
	 *      threshold : the minimum activity for a key in order to be used as an acces key to the document. Default 1, all patterns.
	 *      activity min / activity max  :  if an indexation is asked, eg DBId >0,
	 *       sets the range of outputted activity for each pattern indexing the document. By default the range is 0  to 100.
	 *       training without indexation
	 *       5 knw:wikiknw.learn (<xxx  the text to learn/>, NULL,<2 -1/>) ;
	 *       training with indexation
	 *       5 knw:wikiknw.learn (<xxx  the text of  line 12 in the table/>,NULL,<2 12/> ) ;
	 *   
	 *       Int32 <langage>,int32 <meaning> these indicators have values between 0-100%
	 *       they rate the quality of the indexation according to the present knowledge state.
	 *       Langage is a measure of the rate of known patterns int the submitted text
	 *       Meaning is a measure of the activity and the rate detection of contexts in the texte
	 * @param $_text
	 * @param $_KeyOrigin
	 * @param $_DBId
	 * @param $_RefOrigin
	 * @param $_mode
	 * @param $_threshold
	 * @param $_actMin
	 * @param $_actMax
	 * @return unknown_type
	 */
	public function knowledgeLearn($_text,$_KeyOrigin,$_DBId,$_RefOrigin, $_mode, $_threshold, $_actMin, $_actMax)
	{
		$this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.Learn',$_text,$_KeyOrigin,$_DBId,$_RefOrigin, $_mode, $_threshold, $_actMin, $_actMax);		 		 
	}

	/**
	 * 
	 * @see 5 knw:wikiknw.publish ( )
	 * empties the indexation cache  and updates all new indexation. Once published, 
	 * an indexed document is visible via queries to the indexation system (the RESULTS   object)
	 * 
	 */
	public function knowledgePublish()
	{
		$this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.Publish');		 		 
	}

	/**
	 * 5 knw:wikiknw.keygen ( <5 queen/>,  ) 
	 * 5 1 1 2 10 0 RelativeGen 2 0 AbsoluteGen
	 * <18 0.0107888584191838/> <4 6405/>  ;
	 * 'queen'  is referenced by 6405 neural connections (dendritis) in the neural net
	 * @param string $_pattern ex: "queen"
	 * @return unknown_type
	 */
	public function knowledgeKeyGen($_pattern)
	{
		$this->ikm->Execute ($this->session, 'KNOWLEDGE:'.$this->knw.'.KeyGen',$_pattern);	
		return $this->ikm->KMResults;	 		 
	}		
	
	// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	/*
	 * reccord the actice session in $session
	 */
	public function  KMId()
	{
		$this->session = $this->ikm->KMId;	
		return $this->session;
		
	}
	
	/*
	 * close active session of ikm
	 */
	public function closeSession() 
	{
		$this->ikm->CloseKMSession($this->ikm->KMCurrentId);
	}
	
	/*
	 * return session id
	 */
	public function getSession(){
		return $session; 
	}
	
	
// context property xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function contextGetKnowledge()
	{
		 $this->ikm->Execute ($this->session, 'CONTEXTS.GET','Knowledge');
	}	
	
	/**
	 * set the context knowledge
	 */
	public function contextSetKnowledge($_knw)
	{
		 $this->ikm->Execute ($this->session, 'CONTEXTS.SET','Knowledge',$_knw);
	}		
	
	public function contextGetCount()
	{
		 $this->ikm->Execute ($this->session, 'CONTEXTS.GET','COUNT');
	}	
// context methode xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	/**
	 * Clears the stack, and releases all ressources
	 * see : handling the context stack
	 */
	public function contextClear()
	{
		 $this->ikm->Execute ($this->session, 'CONTEXTS.CLEAR');
	}	
	

	/*
	public function contextSetKnowledge($_knw)
	{
		 $this->ikm->Execute ($this->session, 'CONTEXTS.SET','KNOWLEDGE',$_knw );
	}
	*/
	
	
	public function contextNew() // return stackcount
	{
		 $stackcount = $this->ikm->Execute ($this->session, 'CONTEXTS.NEW');
		 return $stackcount;
	}
	
	/**
	 * 
	 * Adds the known  shapes of the text to the top context.  There is no learning with that function
	 * @param $_mysearch
	 * @param {$_activity] default 100 
	 * @return array stack count
	 * @see <textual data>, [<activity>]
	 */
	public function contextAddElement($_mysearch, $_activity="100" ) // $_activity -1 100
	{
		$stackcount = $this->ikm->Execute ($this->session, 'CONTEXTS.ADDELEMENT',$_mysearch,$_activity);
		return $stackcount;  
	}
	
	/*
	 * Reads and learns the textual before putting them in a context. 
	 * The resulting context contains all the shapes that composed the text.
	 */
	public function contextAddText($_mysearch ) // $_activity -1 100
	{
		$stackcount = $this->ikm->Execute ($this->session, 'CONTEXTS.ADDTEXT',$_mysearch);
		return $stackcount;  
	}
		

	
	/*
	 * GetElements
	 * returns all the shapes, 
	 * with activity and generality,
	 * composing the top context.
	 */
	public function contextGetElements()  
	{
		$list = $this->ikm->Execute ($this->session , 'CONTEXTS.GETELEMENTS');
		return $list;	   
	}
	
	
	/**
	 * return array of the relsultset of KMResult
	 */
	public function KMResults()
	{
		if(isset($this->ikm->KMResults))	 return $this->ikm->KMResults;
		else return array();
	} 
	
	/*
	 * Normalizes all activities of the top context to in the range 0-100%, and sorts the atoms by activity.
	 * 
	 * param: [<act threshold>],[<# of  elements>][<upper range>] defaults :0, -1,100 
	 * optional parameters shapes with act < threshold will be eliminated from context.
	 * The context will be restricted to this number of elements
	 * 
	 * return <stack count>, <# of elements of the top context>
	 */
	public function contextNormalize($_actThreshold="0", $_ofelements="-1", $_upperrange="100")
	{
		
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NORMALIZE',$_actThreshold,$_ofelements,$_upperrange);
		return $stackcount;	 
	}
	
	
	/*
	 * Assuming that one context was on the stack.
	 * 5 contexts.drop (  )
	 *  5 1 1 2 1 0 StackCount 1 0 Elements <1 0/> <2 -1/>  ;
	 *  see : handling the context stack
	 */
	public function contextDrop()
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.DROP');
		return $stackcount;	 
	}
	
	/*
	 * Dup makes a copy of the top context of the stack	none	<stack count>,
	 * <# of elements of the top context>	Assuming that one context was on the stack.
	 * 5 contexts.dup (  )
	 * 5 1 1 2 1 0 StackCount 1 0 Elements <1 2/> <1 0/>  ;
	 * see : handling the context stack 
	 */
	public function contextDup()
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.DUP');
		return $stackcount;	 
	}
	
	/*
	 * Assuming that 2 context s were on the stack.5 
	 * contexts.swap (  )
	 * 5 1 1 2 1 0 StackCount 1 0 Elements <1 2/> <1 0/>  ;
	 * see : handling the context stack

	 */	
	public function contextSwap()
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.SWAP');
		return $stackcount;	 
	}
	
	
	/*
	 * Performs a circular permutation one step up, of the  <range> 
	 *  contexts on top of the stack. 
	 *  Context #2 slides to position #range, and context 
	 *  #range slides on the top of the stack
	 *  5 contexts.rollup ( <1 3/>,  )
	 *  5 1 1 2 1 0 StackCount 1 0 Elements <1 3/> <1 0/>  ;
	 *   see : handling the context stack
	 */
	public function contextRollUp($_range="1")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.ROLLUP',$_range);
		return $stackcount;	 
	}
	
	/*
	 * Performs a circular permutation one step down of the  <range>  contexts on top of the stack.
	 *   Context #1 slides to position 32, and context #range slides on the top of the stack
	 *   5 contexts.rolldown ( <1 3/>,  )
	 *   5 1 1 2 1 0 StackCount 1 0 Elements <1 3/> <1 0/>  ;
	 *    see : handling the context stack
	 */
	
	public function contextRollDown($_range="1")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.ROLLDOWN',$_range);
		return $stackcount;	 
	}
	
	/*
	 * proceeds to the union of  the  upper contexts of the stack. The result is on  top of the stack.  
	 * The range+1 operands has been killed
	 * param: by default range = 1, eg only one union is performed
	 * return: <stack count>, <# of elements of the top context>
	 * 5 contexts.union (  )
	 * 5 1 1 2 1 0 StackCount 1 0 Elements <1 3/> <1 0/>  ;
	 * see : combining contexts
	 */
	public function contextUnion($_range="1")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.UNION',$_range);
		return $stackcount;	 
	}
	
	
	/*
	 * merges  the 2 upper contexts of the stack. The result is on  top of the stack.  
	 * The 2 operands has been killed
	 * return <stack count>, <# of elements of the top context>
	 * 5 contexts.merge (  )5 1 1 2 1 0 StackCount 1 0 Elements <1 3/> <1 0/>  ;
	 * see : combining contexts
	 * obsolete method...probably
	 */
	public function contextMerge()
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.MERGE');
		return $stackcount;	 
	}
	
	/*
	 * proceeds to the intersection of  the  upper contexts of the stack. The result is on  top of the stack.  
	 * The range+1 operands has been killed
	 * param: [<range>] by default range = 1, eg only one intersection is performed
	 * 5 contexts.intersection (  )
	 * 5 1 1 2 1 0 StackCount 1 0 Elements <1 3/> <1 0/>  ;
	 * see : combining contexts
	 * 
	 */
	public function contextIntersection($_range="1")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.INTERSECTION',$_range);
		return $stackcount;	 
	}
	
	
	/*
	 * param: $id <index of the element to delete> the index is in base 1
	 * 5 contexts.getelements (  )'gamma_ray' 
	 *  'stars'5 contexts.deleteelement ( <1 2/>,  )
	 * StackCount 1 0 Elements <1 1/> <1 1/>  ;
	 * 5 contexts.getelements (  )
	 * 'gamma_ray'
	 */
	public function contextDeleteElement($_id)
	{
		$this->ikm->Execute ($this->session , 'CONTEXTS.DELETEELEMENT',$_id);
	}
	
	
	/**
	 * return:context activity> 
	 * the global activity of the top context after a treatment.
	 *  It is the mean value of all the activities of its contituing atoms (shapes)
	 *  @return context Activity
	 *  Ex:
	 *  5 contexts.getact (  ) 
	 *  5 1 1 1 1 0 Act <2 -1/> ;
	 *  in such a case, -1 means that no activity has been evaluated for the context
	 *  let's evaluate the context
	 * 5 contexts.evaluate (  )
	 * 5 contexts.getact (  ) 
	 * 5 1 1 1 1 0 Act <2 48/> ;
	 * 5 contexts.getelements (  )
	 * ' gamma_ray 31'  'nucleus 17'  : 31+17=48
	 */
	public function contextGetAct()
	{
		$contextActivity = $this->ikm->Execute ($this->session , 'CONTEXTS.GETACT');
		return$contextActivity; 
	}	
	
	
	/*
	 * return The number of atoms (shapes) in the top context of the stack
	 * Ex:
	 * 5 contexts.new  ( )
	 * 5 contexts.addelement ( <17 gamma ray nucleus/>,  )
	 * 5 contexts.getsize (  )
	 * 5 1 1 1 1 0 Atoms <1 2/> ;
	 */
	public function contextGetSize()
	{
		$shape = $this->ikm->Execute ($this->session , 'CONTEXTS.GETSIZE');
		return $shape;
	}	
	
	/*
	 * kills the atoms in the top context whose activities are less (strictly) than threshold ( act < threshold) if mode is true
	 * if mode is false, kills the atoms whose activities are greater or equal than threshold (act >= threshold)
	 * 
	 * param: <threshold value>[<boolean mode>]
	 * by default the boolean mode is true (or <1 1/>)
	 * 
	 * return : <stack count>, 
	 * <# of elements of the top context>
	 * 
	 * Ex:
	 * FilterAct
	 * kills the atoms in the top context whose activities are less (strictly) than threshold ( act < threshold) if mode is true
	 * if mode is false, kills the atoms whose activities are greater or equal than threshold (act >= threshold)
	 * <threshold value>
	 * [<boolean mode>]
	 * by default the boolean mode is true (or <1 1/>)	<stack count>,
	 * <# of elements of the top context>	5 contexts.getelements (  )
	 * ' gamma_ray 31'  'nucleus 17'
	 * 5 contexts.filterAct ( <2 18/>,  )
	 * 5 contexts.getElements (  )
	 * ' gamma_ray 31'
	 * 5 contexts.addelement ( <7 nucleus/>, <2 17/>,  )
	 * 5 contexts.getElements (  )
	 * ' gamma_ray 31'  'nucleus 17'
	 * 5 contexts.filterAct ( <2 18/>,  <1 0/>)  or
	 * 5 contexts.filteract ( <2 18/>, <5 false/>,  )
	 * 5 contexts.getelements (  )
	 * 'nucleus 17'
	 */
	public function contextFilterAct($_threshold, $_mode="true")
	{
		$stack = $this->ikm->Execute ($this->session , 'CONTEXTS.FILTERACT',$_threshold,$_mode);
		return $stack;
	}	
	
	
	/*
	 * params: <#number of atoms to keep in the context>
	 *
	 * EX:
	 * 5 contexts.getelements (  )
	 * ' gamma_ray 31 204'  'nucleus 17 1286'
	 * 5 contexts.filtersize ( <1 1/>,  )
	 * 5 contexts.getelements (  )
	 * ' gamma_ray 31 204'
	 * 
	 */
	public function contextFilterSize($_nbAtoms)
	{
		$stack = $this->ikm->Execute ($this->session , 'CONTEXTS.FILTERSIZE',$_nbAtoms);
		return $stack;
	}


	/*
	 * kills the atoms in the top context whose generalities are less (strictly) than threshold ( act < threshold) if mode is true
	 * if mode is false, kills the atoms whose generality are greater or equal than threshold (act >= threshold)
	 *
	 * <threshold value> 
	 * [<boolean mode>]
	 * by default the boolean mode is true (or <1 1/>)
	 * 
	 * ex:
	 *  * 5 contexts.getelements (  )
	 *  ' gamma_ray 31 204'  'nucleus 17 1286'
	 *   contexts.filterGen( <2 300/>,  )
	 *   5 contexts.getelements (  )
	 *    'nucleus 17 1286'
	 *    5 contexts.addelement ( <7 nucleus/>, <2 17/>,  )
	 *    5 contexts.getelements (  )
	 *    ' gamma_ray 31 204'  'nucleus 17 1286'
	 *    5 contexts. filterGen ( <2 18/>,  <1 0/>)  or 5 contexts. filterGen ( <2 18/>, <5 false/>,  )
	 *    5 contexts.getelements (  )
	 *    ' gamma_ray 31 204'
	 */
	public function contextFilterGen($_threshold, $_mode="true")
	{
		$stack = $this->ikm->Execute ($this->session , 'CONTEXTS.FILTERGEN',$_threshold,$_mode);
		return $stack;
	}	
	
	
	/*
	 * mode true or false
	 * kills the atoms in the top context whose generalities is to high to be significant (eg 'the', 'a', 'it' etc...)
	 * if mode is false, kills all the significant atoms
	 * 
	 * params:
	 * [<boolean mode>]
	 * by default the boolean mode is true (or <1 1/>)
	 * 
	 * 5 contexts.FilterVoidElements (  )  or
	 * 5 contexts.FilterVoidElements ( <1 1/>)  or
	 * 5 contexts.FilterVoidElements ( <4 true/>)
	 * 	 */
	public function contextFiltervoidelements($_mode="true")  
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.FILTERVOIDELEMENTS',$_mode);
		return $stackcount;	   
	}
	
	
	/*
	 * sorts  the atoms in the top context according to their generality
	 * @param $_sortorder "true" or "false"
	 * @return $stackcount;
	 * 
	 * params:[<sort order>] by default the sort order is true (or <1 1/>), descending order
	 * return: <stack count>, <# of elements of the top context>
	 * EX:
	 * 5 contexts.getelements (  ) 
	 * ' gamma_ray 31 204'  'nucleus 17 1286'
	 * 5 contexts.sortbyGenerality (  )
	 * 5 contexts.getelements (  )
	 * 'nucleus 17 1286' ' gamma_ray 31 204'
	 * 5 contexts.sortbyGenerality ( <1 0/> )  or 
	 * 5 contexts.sortbyGenerality ( <5 false/> )
	 * 5 contexts.getelements (  )
	 * ' gamma_ray 31 204'  'nucleus 17 1286'
	 */	
	public function contextSortByGenerality( $_sortorder="true")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.SORTBYGENERALITY',$_sortorder);
		return $stackcount;	   
	}

	
	/*
	 * Smart reducing the # of elements of the top context. The shapes with the less generality will remain
	 * 
	 */
	public function contextShrink()
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.SHRINK');
		return $stackcount;	   
	}
	
	/*
	 * Amplyfing all activities of the atoms of the top context. The amplification factor is a flloating point number, which can be negative
	 * <act amplification, [<max value>] all activities are amplified by this FP factor 
	 * if max value is used, all activities will be thresholded. 
	 * <stack count>,<# of elements of the top context>
	 * EX:
	 * 5 contexts.getelements (  )
	 * 5 1 2 2 8 0 Key 1 0 Act
	 *  'book 100'   'thriller 35'  ;
	 *  5 contexts.amplify ( <5 -1.25/>,  )
	 *  5 contexts.getelements (  )
	 *  5 1 2 2 8 0 Key 1 0 Act
	 *   'book -125'   'thriller -43'  ;
	 * 
	 */
	public function contextAmplify($_actAmplification, $_maxvalue="100")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.AMPLIFY',$_actAmplification,$_maxvalue);
		return $stackcount;	   
	}
	
	
	/* Major routine
	 * gets the elements of the top context and extract all relation links of each cel to a new context that will be pushed on the stack
	 *  The parameter context remains
	 *  params: [<relation flag>], [<min activity>],[<max # of atoms>],
	 *  defaults :0,0,-1
	 *  Note:
	 *  If relation  = true (or <1 1/>), only bidirectionnal links will be selected
	 *  see semantic programming below
	 */
	
	public function contextNewFromSem($_relationflag="false", $_minAct="0", $_maxAct="-1" )
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NEWFROMSEM',$_relationflag,$_minAct,$_maxAct);
		return $stackcount;	 
	}
	
	/**
	 * gets the semantic neighbour of the atoms of the top context	 * The result is stored in a new context on top of the stack * The parameter context remains  
	 * 
	 * @param $_depth = iteration 1-3 $_mode = 1,2,3,4 or 5 $_minAct = 0-100 $_maxAct = 0-100
	 * <Mode>, <min activity>,<max # atoms> 
	 * defaults : 1,0,0,-1
	 * 
	 * @see
	 * Propagates depth level through the neural net.
	 * If mode = 0, full propagation
	 * if mode = 1, propagation through low generality pathes
	 * if mode = 2, propagation through hi generality pathes
	 * see semantic programming below
	 */
	public function contextNewFromSemLinks($_depth="1", $_mode="0", $_minAct="0", $_maxAct="-1" )
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NEWFROMSEMLINKS',$_depth,$_mode,$_minAct,$_maxAct);
		return $stackcount;	 
	}
	
	/** Major routine
	 * gets the neighbour aggregates (phrases)  of the atoms of the top context.
	 * The result is stored in a new context on top of the stack. 
	 * The parameter context remains
	 */
	public function contextNewFromAttractor()
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NEWFROMATTRACTOR');
		return $stackcount;	
	}
	
	/**
	 * gets the nearest shapes  of the atoms of the top context. 
	 * The result is stored in a new context on top of the stack. The parameter context remains
	 * 
	 * @param $actThreshold=1-100 ,$_maxResult = 1-100
	 */
	public function contextNewFromShape($_actThreshold="1", $_maxResult="100")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NEWFROMSHAPE',$_actThreshold,$_maxResult);
		return $stackcount;	
	}
	
	/**
	 * same as NewFromAttractor except that the results are restricted to atoms having a link with at least one atom of the top context
	 * 
	 * @param $_actThreshold (
	 * @param $_maxResult
	 * @return Array <stack count>, <# of elements of the top context>
	 */
	public function contextNewFromSemAttractor($_actThreshold="1", $_maxResult="100")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NEWFROMSEMATTRACTOR',$_actThreshold,$_maxResult);
		return $stackcount;	
	}
	
	/**
	 * same as NewFromShape except that the results are restricted to atoms having a link with at least one atom of the top context
	 * 
	 * @param string $_actThreshold (1-100)
	 * @param string_type $_maxResult (1-100)
	 * @return array
	 */
	public function contextNewFromSemShape($_actThreshold="1", $_maxResult="100")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NEWFROMSEMSHAPE',$_actThreshold,$_maxResult);
		return $stackcount;	
	}
	/**
	 * NewFromSubAttractor
	 * splits shapes  of the atoms of the top context into their own atoms. Aggregates will be splited into their components.
	 *  The result is stored in a new context on top of the stack. The parameter context remains
	 * @param string $_actThreshold
	 * @param string_type $_maxResult
	 * @return array
	 */
	public function contextNewFromSubAttractor($_actThreshold="1", $_maxResult="100")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NEWFROMSUBATTRACTOR',$_actThreshold,$_maxResult);
		return $stackcount;	
	}
	
	
	/**
	 * NewFromSemSubAttractor same as NewFromSubCategory except that the results are restricted to atoms having a link with at least one atom of the top context
	 * @param string $_actThreshold
	 * @param string $_maxResult
	 * @return array
	 */	
	public function contextNewFromSemSubAttractor($_actThreshold="0", $_maxResult="-1")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.NEWFROMSEMSUBATTRACTOR',$_actThreshold,$_maxResult);
		return $stackcount;	
	}
	
	/**
	 *  Partition builds all subcontexts of the top context of the stack. Major routine
	 * @see if context min size = 0 the minimum size required for a valid subcontext will be evaluated automatically according to the size (in # of atoms) of the top context of the stack.
	 *  If evaluation is true,  the contexts are all evaluated
	 *  each valid subcontext will be stored on top of the stack, sorted by context activity.see semantic programming below
	 * @param string $_context
	 * @param string $_evaluation
	 * @param string $_actThreshold
	 * @param string $_maxResult
	 * @return array
	 */
	
	public function contextPartition($_context="2",$_evaluation="false",$_actThreshold="1", $_maxResult="-1")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.PARTITION',$_context,$_evaluation,$_actThreshold,$_maxResult);
		return $stackcount;	
	}
	
	/**
	 * Evaluate propagation routine in the neural net   
	 * The top context will be evaluated according to its atoms : The result is sorted by activity 
	 * Major routine
	 * @return array 
	 */
	public function contextEvaluate()
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.EVALUATE');
		return $stackcount;	
	}
	
	/**
	 * for indexation only : If indexation has occurred,  the top context will generate a new result set on the RESULTS object stack.
	 * @param string $_modeflag false or true default true
	 * @return ARRAY
	 */
	public function contextToResults($_modeflag="true")
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.TORESULTS');
		return $stackcount;	
	}
	
	/**
	 * Pushes the top context to the context  stack of the  session described by the 'session Id' parameter 
	 * The owner IP of both sessions must be the same.
	 * @param string $_SessionId
	 * @return array
	 */
	public function contextPush($_SessionId)
	{
		$stackcount = $this->ikm->Execute ($this->session , 'CONTEXTS.PUSH',$_SessionId);
		return $stackcount;	
	}
	
	
// RESULT xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx	
	public function resultClear()
	{
		 $this->ikm->Execute ($this->session, 'RESULTS.CLEAR');
	}
// Knowledge xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	
	public function resultsGetOwnerTable()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','Ownertable');
		return $this->ikm->KMResults;
		
	}
	public function resultsSetOwnerTable($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','Ownertable',$_value);
			
	}	

	public function resultsGetMaxStackSize()
	{
		$this->ikm->Execute ($this->session , 'RESULTS.GET','MaxStackSize');
		return $this->ikm->KMResults;	
	}

	public function resultsGetCount()
	{
		$this->ikm->Execute ($this->session , 'RESULTS.GET','Count');
		return $this->ikm->KMResults;	
	}	

	public function resultsGetResultCount()
	{
		$this->ikm->Execute ($this->session , 'RESULTS.GET','ResultCount');
		return $this->ikm->KMResults;	
	}	

	public function resultsGetResultResultCapacity()
	{
		$this->ikm->Execute ($this->session , 'RESULTS.GET','ResultCapacity');
		return $this->ikm->KMResults;	
	}

	public function resultsGetFetchSize()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','FetchSize');
		return $this->ikm->KMResults;		
	}
	
	public function resultsSetFetchSize($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','FetchSize',$_value);			
	}	

	public function resultsGetFetchStart()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','FetchStart');
		return $this->ikm->KMResults;	
	}
	
	public function resultsSetFetchStart($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','FetchStart',$_value);			
	}	

	public function resultsGetFetchID()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','FetchID');
		return $this->ikm->KMResults;	
	}	
	
	public function resultsGetGroupClass()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','GroupClass');
		return $this->ikm->KMResults;	
	}	
	public function resultsGetGroupSort()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','GroupSort');
		return $this->ikm->KMResults;	
	}	
	public function resultsGetGroupValue()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','GroupValue');
		return $this->ikm->KMResults;	
	}
	public function resultsGetGroupBreak()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','GroupBreak');
		return $this->ikm->KMResults;	
	}	
	public function resultsGetEmbedded()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','Embedded');
		return $this->ikm->KMResults;	
	}
	
	public function resultsSetEmbedded($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','Embedded',$_value);			
	}	
	
	public function resultsGetColSep()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','ColSep');
		return $this->ikm->KMResults;	
	}	
	public function resultsSetColSep($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','ColSep',$_value);			
	}		

	public function resultsGetLineSep()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','LineSep');
		return $this->ikm->KMResults;	
	}	
	public function resultsSetLineSep($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','LineSep',$_value);			
	}		
	
	public function resultsGetMaxAct()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','MaxAct');
		return $this->ikm->KMResults;	
	}	

	public function resultsGetMinAct()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','MinAct');
		return $this->ikm->KMResults;	
	}	
	
	public function resultsGetSteps()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','Steps');
		return $this->ikm->KMResults;	
	}	

	public function resultsGetSortedBy()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','SortedBy');
		return $this->ikm->KMResults;	
	}	
	
	public function resultsGetSortOrder()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','SortOrder');
		return $this->ikm->KMResults;	
	}		
	
	public function resultsGetAutoNormalize()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','AutoNormalize');
		return $this->ikm->KMResults;	
	}	
	public function resultsSetAutoNormalize($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','AutoNormalize',$_value);			
	}	
	
	public function resultsGetAutoLock()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','AutoLock');
		return $this->ikm->KMResults;	
	}	
	public function resultsSetAutoLock($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','AutoLock',$_value);			
	}	
	
	public function resultsGetFormat()
	{
		 $this->ikm->Execute ($this->session , 'RESULTS.GET','Format');
		return $this->ikm->KMResults;	
	}	
	public function resultsSetFormat($_value)
	{
		$this->ikm->Execute ($this->session , 'RESULTS.SET','Format',$_value);			
	}		
	
// results method xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function resultsNew($capacity="256")
	{
		 return $this->ikm->Execute ($this->session , 'RESULTS.NEW',$capacity);
	}	
	
	
	public function resultsClear()
	{
		 return $this->ikm->Execute ($this->session , 'RESULTS.CLEAR');
	}	

	public function resultsDrop()
	{
		 return $this->ikm->Execute ($this->session , 'RESULTS.Drop');
	}	
	
	public function resultsDup()
	{
		 return $this->ikm->Execute ($this->session , 'RESULTS.Dup');
	}		
	
	public function resultsSwap()
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.Swap');		 	
	}		

	public function resultsNot()
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.Not');		 	
	}	
	
	public function resultsPush($_id)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.Push',$_id);		 	
	}	

	public function resultsRollUp($_range)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.RollUp',$_range);		 	
	}		
	
	public function resultsRollDown($_range)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.RollDown',$_range);		 	
	}		

	public function resultsIntersection($_average="true")
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.Intersection',$_average);		  	 	
	}	
	
	public function resultsUnion()
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.Union');		  	 	
	}
	
	public function resultsSelectToTable($_column, $_table)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.SelectToTable',$_column, $_table);		  	 	
	}

	public function resultsSelectBy($_critere, $_operator, $_op1, $_op2)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.SelectBy',$_critere, $_operator, $_op1, $_op2);		  	 	
	}	
	
	public function resultsDeleteBy($_critere, $_operator, $_op1, $_op2)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.DeleteBy',$_critere, $_operator, $_op1, $_op2);		  	 	
	}

	public function resultsSortBy($_critere, $_ascending="true")
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.SortBy',$_critere, $_ascending);		  	 	
	}	

	public function resultsGroupBy($_critere, $_sort="true")
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.GroupBy',$_critere, $_sort);		  	 	
	}		
	
	public function resultsUniqueBy($_critere)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.UniqueBy',$_critere);		  	 	
	}
	
	public function resultsRelevanceBy($_column, $_mode, $_coef, $_min, $_max)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.RelevanceBy',$_column, $_mode, $_coef, $_min, $_max);		  	 	
	}	
	
	public function resultsFetch($_line, $_start)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.Fetch',$_line, $_start);		  	 	
	}
	
	/**
	 * 
	 * @param string $_datas "'123','100','2', '222','95','2', '812','74','2'"
	 * @return string  <1>; <1>; <1> <3>
	 */
	public function resultsAdd($_datas)
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.add',$_datas);		  	 	
	}

	/**
	 * 
	 * @param string $_mode "absolute" "relative"
	 * @return unknown_type
	 */
	public function resultsNormalize($_mode="absolute")
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.Normalize',$_mode);		  	 	
	}	
	
	public function resultsLockRead()
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.LockRead');		  		  	 
	}	
	public function resultsUnlockRead()
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.UnlockRead');		  		  	 
	}		
	public function resultsLockWrite()
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.LockWrite');		  		  	 
	}	
	public function resultsUnLockWrite()
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.UnLockWrite');		  		  	 
	}	
	public function resultsToContext()
	{
		  return $this->ikm->Execute ($this->session , 'RESULTS.ToContext');		  		  	 
	}

// TABLE xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

	public function tableGetInstances()
	{
		$this->ikm->Execute ($this->session , 'TABLE.GET','Instances');	
		return $this->ikm->KMResults;	  		  	 
	}

	public function tableGetLines($_Instance="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_Instance.'.GET','Lines');	
		return $this->ikm->KMResults;	  		  	 
	}	

	public function tableGetOwner($_Instance="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_Instance.'.GET','Owner');	
		return $this->ikm->KMResults;	  		  	 
	}
	public function tableGetStructure($_Instance="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_Instance.'.GET','Structure');	
		return $this->ikm->KMResults;	  		  	 
	}
	public function tableGetBindexes($_Instance="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_Instance.'.GET','Bindexes');	
		return $this->ikm->KMResults;	  		  	 
	}	
	public function tableGetKindexes($_Instance="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_Instance.'.GET','Kindexes');	
		return $this->ikm->KMResults;	  		  	 
	}		
	// TABLE Methods xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
   public function tableCreate($_tableName , $_linkedKnw, $_param1="", $_param2="", 
                                        $_type="MASTER", $_field="id INT64 , title STRING , texte STRING , type STRING") 
  { 
    $this->ikm->Execute ($this->session , 'TABLE.CREATE',$_tableName, $_linkedKnw, $_param1, $_param2, $_type , $_field);   
    return $this->ikm->KMResults;              
  } 

	public function tableKill($_tableName)
	{
		$this->ikm->Execute ($this->session , 'TABLE.KILL',$_tableName);	
		return $this->ikm->KMResults;	  		  	 
	}	
	
	public function tableRename($_tableName, $_newName)
	{
		$this->ikm->Execute ($this->session , 'TABLE.Rename',$_tableName, $_newName);	
		return $this->ikm->KMResults;	  		  	 
	}

	/**
	 * 
	 * @param string $_datas ex: "23 8" delete 23 et 8
	 * @return Array KMResults
	 */
	public function tableDelete($_datas,$_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.Delete',$_data);	
		return $this->ikm->KMResults;	  		  	 
	}	
	/**
	 * 
	 * @param string $_datas ex:  
	 * @return Array KMResults
	 */
	
	public function tableUpdate($_rowId,$_data,$_table="myMasterTable")
	{
		
		try {
		$tabtmp = array( 1=> $this->session, 'TABLE:'.$_table.'.update', $_rowId);
		$tabtmp2 = explode(";", $_data );
		$result = array_merge((array)$tabtmp, (array)$tabtmp2);	
		echo "<pre>";
		print_r($result);	
		echo "</pre>";
		$this->ikm->Execute ($result);		
		return $this->ikm->KMResults;	  		  	 
		}
	catch (Exception $e) {
		echo $this->KMServerGetKMError();
		echo $this->KMServerGetKMErrorMsg();
		}
		
		//$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.Update',$_rowId,$_datas);	
		//return $this->ikm->KMResults;	  		  	 
	}	
	
	/**
	 * @see  ex: tableSelect('new', 'title', 'between', 'ab', 'ac', 'wikimaster2');
	 * @param $_mode
	 * @param $_column
	 * @param $_operation
	 * @param $_op1
	 * @param $_op2
	 * @param $_table
	 * @return Array
	 */
	public function tableSelect($_mode, $_column, $_operation, $_op1, $_op2="", $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.Select',$_mode, $_column, $_operation, $_op1, $_op2);	
		return $this->ikm->KMResults;	  		  	 
	}
	
	/**
	 * 
	 * @param string $_data ex: "medid,1235, title,titre1,abtract,qsd qsd qsdq sdqsdqsd,type,med" 
	 * @param string $_table
	 * @return Array KMResults
	 */
	public function tableInsert( $_data, $_table="myMasterTable" )
	{		
	try {
		$tabtmp = array( 1=> $this->session, 'TABLE:'.$_table.'.Insert');
		$tabtmp2 = explode(";", $_data );
		$result = array_merge((array)$tabtmp, (array)$tabtmp2);		
		$this->ikm->Execute ($result);		
		return $this->ikm->KMResults;	  		  	 
		}
	catch (Exception $e) {
		echo $this->KMServerGetKMError();
		echo $this->KMServerGetKMErrorMsg();
		}
	}
	
	public function tableReadBlock($_rowId,$_column, $_position="",$_size="", $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.ReadBlock',$_rowId,$_column, $_position,$_size);	
		return $this->ikm->KMResults;	  		  	 
	}	

	/**
	 * ex: tableReadLine("1456","title texte");
	 * @param string $_rowid
	 * @param string $_data
	 * @param string $_table
	 * @return Array
	 */
	public function tableReadLine($_rowid, $_data, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.ReadLine', $_rowid, $_data);	
		return $this->ikm->KMResults;	  		  	 
	}	
	/**
	 * ex: tableReadFirstLine("title rowid");
	 * 
	 */
	public function tableReadFirstLine( $_data, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.ReadFirstLine', $_data);	
		return $this->ikm->KMResults;	  		  	 
	}

	/**
	 * ex: tableReadFirstLine("title rowid");
	 * 
	 */
	public function tableReadNextLine( $_data, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.ReadNextLine', $_data);	
		return $this->ikm->KMResults;	  		  	 
	}
/**
 * @see For a master table only returns the context of a line, if it exists (the content of the field knw_abstract)
 * the oprional threshold parameter filters the output to the keys which activity is over it
 * returns lines of couple key, activity 
 * 0 tbl:wikimaster2.ReadContext ( <2 23/>, <2 75/>,  )
 * 0 1 14 2 8 0 Key 1 0 Activity <18 Eve_of_destruction/> <3 100/> <5 X_Men/> 
 * <3 100/> <3 Eve/> <3 100/> <3 Men/> <3 100/> <11 destruction/> <3 100/> <1 X/>
 *  <3 100/> <8 longshot/> <2 95/> <6 xavier/> <2 89/> <7 dazzler/> <2 85/> <7 magneto/> 
 *  <2 83/> <5 tells/> <2 82/> <7 cargill/> <2 78/> <8 plotline/> <2 78/> <9 inability/> <2 76/>  ;
 * @param unknown_type $_rowid
 * @param unknown_type $_threshold
 * @param unknown_type $_table
 * @return unknown_type
 */
	public function tableReadContext($_rowid, $_threshold="", $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.ReadContext',$_rowid, $_threshold);	
		return $this->ikm->KMResults;	  		  	 
	}	
/**
 * Creates an image of the context of a line of a master table upon the context stack of the CONTEXTS object
 * @param unknown_type $_rowid
 * @param unknown_type $_table
 * @return unknown_type
 */
	public function tableToContext($_rowid, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.ToContext',$_rowid);	
		return $this->ikm->KMResults;	  		  	 
	}		
/**
 * Creates a Btree over a given column.
 * Optionally with an attribute unique (caution maybe bugged in this version)
 * @param unknown_type $_column
 * @param unknown_type $_unique
 * @param unknown_type $_table
 * @return unknown_type
 */
	public function tableBIndexCreate($_column, $_unique="", $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.BIndexCreate',$_column, $_unique);	
		return $this->ikm->KMResults;	  		  	 
	}		

	public function tableBIndexDelete($_column, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.BIndexDelete',$_column);	
		return $this->ikm->KMResults;	  		  	 
	}	
	
	public function tableBIndexRebuild($_column, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.BIndexRebuild',$_column);	
		return $this->ikm->KMResults;	  		  	 
	}	

	public function tableKIndexCreate($_column, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.KIndexCreate',$_column);	
		return $this->ikm->KMResults;	  		  	 
	}	
	public function tableKIndexDelete($_column, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.KIndexDelete',$_column);	
		return $this->ikm->KMResults;	  		  	 
	}		

	public function tableKIndexRebuild($_column, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.KIndexRebuild',$_column);	
		return $this->ikm->KMResults;	  		  	 
	}	
	
	public function tableKReIndex($_rowid, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.KReIndex',$_rowid);	
		return $this->ikm->KMResults;	  		  	 
	}

	public function tableKUnIndex($_rowid, $_table="myMasterTable")
	{
		$this->ikm->Execute ($this->session , 'TABLE:'.$_table.'.KUnIndex',$_rowid);	
		return $this->ikm->KMResults;	  		  	 
	}		
}

?>