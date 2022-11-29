
<?php

class display
{
	public $results;

	public function display( $_results)
	{
		$this->results = $_results;
	}
	
	
	public function getbestwords_elements()
	{		
		if( !isset($this->results[0]['results'][0])) {echo "no sugestion";return "";}
			foreach( $this->results[0]['results'] as $shape )
			{
				$id = $shape[0];
				//$locution = $shape[1];
				$locution = $this->cleanlocution($shape[1]);
				$tag = $shape[2];
				
				//echo $id." ".$locution." ".$tag.", ";
				
				//echo $tag;
				if ($tag == "validated") { echo "<span class='red'>".$locution."</span>, "; }
				else if ($tag == "predicted") { echo "<span class='green'>".$locution."</span>, "; }
				else if ($tag == "suggested") { echo "<span class='blue'>".$locution."</span>, "; }
				else if ($tag == "context") { echo "<span class='orange'>".$locution."</span>, "; }
				else if ($tag == "unknow") { echo "<span class='grey'>".$locution."</span>, "; }
				else if ($tag != "unknow") { echo "<span class='red'>".$locution."</span>, "; } // pour le bug de validated
				//echo "".$locution.", ";
			}			
	}	
	
	
	
	public function comma_elements()
	{		
		if( !isset($this->results[0]['results'][0])) {echo "no sugestion";return "";}
			foreach( $this->results[0]['results'] as $shape )
			{
				$locution = $this->cleanlocution($shape[0]);
				
				echo "".$locution.", ";
			}			
	}
	
	
	
	public function horizontal_elements()
	{
		
		if( !isset($this->results[0]['results'][0])) {echo "no sugestion";return "";}
			foreach( $this->results[0]['results'] as $shape )
			{
				$locution = $this->cleanlocution($shape[0]);
				
				echo "<span class='smalltext italic button'>".$locution."</span> ";
			}
			
	}
	
	public function vertical_elements()
	{
			
			foreach( $this->results[0]['results'] as $shape )
			{
				$locution = $this->cleanlocution($shape[0]);
				
				echo "<div class='smalltext italic button'>".$locution."</div> ";
			}	
	}
	
	public function aff_nb_articles()
	{
		if(isset($this->results[0]['results'][0]))
		{
			echo "<span class='nb_articles'>".$this->results[0]['results'][0][0]." articles found on Wikipedia</span>";
			return $this->results[0]['results'][0][0];
		}
		else
		{
			echo "<div>no article found</div>";
		}
		
	}
	
	
	public function displaypagebuttons( $urlpagebuttons, $count)
	{
	
		    $pager=1;	$pagerbr=0; $pagestart=1;			
		echo '<div class="displaypager"><span><a href="http://www.marvinbot.com/" target="_blank"><img src="images/logomarvinbot.png" width="250" height="80" alt="logo" /></a></span><div class="displaypagerin">';	
		echo '<span  value="1" class="smalltextbutton buttonpages link "><a value="1" xhref="'.$urlpagebuttons."1".'">'.'<img class="imgpager" src="images/first.png" width="20" height="20" />'.'</a></span>';
			for($i=1;$i<$count;$i+=20)
			{		
				if(isset($_GET['pagestart'])) {$pagestart=$_GET['pagestart'];}						
				if($i > $pagestart && $i < $pagestart + 20*7)
				{
					echo '<span value="'.$i.'" class=" smalltextbutton buttonpages link"><a value="'.$i.'" xhref="'.$urlpagebuttons.$i.'">'.$i.'</a></span>';
				}					

				if ( $i >= $count-20*1)
				{
					if($pagestart+20<$count)
					{
						echo '<span value="'.($pagestart+20).'" class="smalltextbutton buttonpages displaypagerima"><a xhref="'.$urlpagebuttons.($pagestart+20).'"><img class="imgpager" src="images/next.png" width="20" height="20" alt="'.($pagestart+20).'" /></a></span>';			
					}
					
					if(($pagestart-20) > 0)
					{
						echo '<span  value="'.($pagestart-20).'" class="smalltextbutton buttonpages displaypagerima"><a xhref="'.$urlpagebuttons.($pagestart-20).'"><img class="imgpager" src="images/prev.png" width="20" height="20" alt="'.($pagestart-20).'" /></a></span>';			
					}
				//echo '<span class="smalltext buttonpages link"><a xhref="'.$urlpagebuttons.($pagestart-20).'">'."<<<".'</a></span>';			
					echo '<span  value="'.$i.'" class="smalltextbutton buttonpages displaypagerima"><a xhref="'.$urlpagebuttons.$i.'"><img class="imgpager" src="images/last.png" width="20" height="20" /></a>'.$i.'</span>';				
				}
				//if($pager==19){$pager=0; $pagerbr++; }
				$pager++;
			}			 
		echo '</div></div>';
		
	}

	public function aff_articles()
	{
		if(!isset($this->results[0]['results'][0])) { return "";}
		
			foreach( $this->results[0]['results'] as $shape )
			{
				$title = $shape[0];
				$activity = $shape[1];
				$link = $shape[2];
				$rowid = $shape[3];
				$KNW_LANGAGE = $shape[4];
				$KNW_MEANING = $shape[5];
				$link_wikieng = $shape[6];
				$texte = $shape[7];				
				
				echo "<div class='bloc_article'>";
				//echo "<span class='title blue'>".$title."</span>";
				echo  "<span value='".$rowid."'  title='all similar articles' class='similar_articles'>SA</span>";
				echo '<span class="link_article"  value="'.trim($title).'" title="Display Wikipedia Page" > W </span>';
				echo '<span class="title blue">'.$title.'</span>'; //<a target="_blank" xhref="'.$link.'">'.$title.'</a>
				echo "<span class='smalltext orangeflash bold'>pertinence: ".$activity."%</span>";
				//echo "<span class='smalltext link green'>".$link."</span>";
				if($_GET['lang']=='eng') $link=str_replace("fr.","en.",$link); // jauge de couillon à corriger dans la base
				echo '<div class="smalltext link green" ><a target="_blank" href="'.$link.'">'.$link.'</a>   </div>';
				
				echo "<div class='block_info'>";
				
				// echo "<span class='smalltext orange'>langage: ".$KNW_LANGAGE."%</span>";
				//echo "<span class='smalltext orange'>quality: ".str_replace("-", "*", $KNW_MEANING)."%</span>";
				echo "</div>";
				echo "<span value='".$rowid."'class='text_abstract smalltext italic text grey'>".$texte."</span>";
				echo "<span  id='preview_".$rowid."' class='preview'></span>";
				echo "</div>";
								
			}
	}

	public function aff_articles_preview()
	{
		
	if(!isset($this->results[0]['results'][0])) { return "";}
		
		echo "<div><h3>SIMILAR ARTICLES</h3></div>";

			foreach( $this->results[0]['results'] as $shape )
			{
				$title = $shape[0];
				$activity = $shape[1];
				$link = $shape[2];
				$rowid = $shape[3];
				$KNW_LANGAGE = $shape[4];
				$KNW_MEANING = $shape[5];
				$link_wikieng = $shape[6];
				$texte = $shape[7];				
				
				echo "<div class='bloc_article papier'>";
				
				//echo "<span class='title blue'>".$title."</span>";
				echo '<span class="title blue">'.$title.'</span>'; //<a target="_blank" xhref="'.$link.'">'.$title.'</a>
				//echo "<span class='smalltext link green'>".$link."</span>";
				if($_GET['lang']=='eng') $link=str_replace("fr.","en.",$link); // jauge de couillon à corriger dans la base
				//echo '<span class="smalltext link green"><a target="_blank" href="'.$link.'">'.$link.'</a></span>';
				echo "<div class='block_info tinytext'>";
				echo "<span class='smalltext orange'>pertinence: ".$activity."%</span>";
				//echo "<span class='smalltext orange'>langage: ".$KNW_LANGAGE."%</span>";
				//echo "<span class='smalltext orange'>quality: ".str_replace("-", "*", $KNW_MEANING)."%</span>";
				echo "</div>";
				echo "<span value='".$rowid."'class='text_abstract tinytext italic text grey'>".$texte."</span>";
				//echo "<span  id='preview_".$rowid."' class='preview'></span>";
				echo "</div>";
				
				
			}
	}


	public function cleanlocution($locution) 
	{
		return str_replace("_", " ", $locution);
		 
	}
	
	public function aff_partition()
	{
	$ccolor = true;	
	if(	isset($this->results[0]))
	{
		foreach($this->results[0] as $context )
		{
			echo "<span class='cadrecontext button hightlight'>"; 
	
				$tab_context_sens = array();			
				$acttmp=0;
				$gentmp=0;
				$i=0;
				echo '<span class="contextclick smalltext" title="';
					reset( $tab_context_sens );
					foreach ($context[0]['results'] as $word)
					{ 
					$tab_context_sens[$i] = $word;
					$i++;
					if ( $gentmp < $word[2] ) { $strMajorGen = $this->cleanlocution($word[0]); $gentmp = $word[2]; }
					if ( $acttmp < $word[1] && $word[0]!=$strMajorGen ) { $strMajorAct = $this->cleanlocution($word[0]); $acttmp = $word[1]; }
																
						echo $this->cleanlocution($word[0])." ";	// stocke le context complet dans  title	
					}
					
				
					if( $strMajorGen == $strMajorAct) 
					{
						 $strMajorGenTab = $tab_context_sens[array_rand($tab_context_sens,1)];
	 					 $strMajorGen = $this->cleanlocution($strMajorGenTab[0]);
						 $wexit = 0;
						 while ( $strMajorGen == $strMajorAct )
						 {
							 $strMajorActTab= $tab_context_sens[array_rand($tab_context_sens,1)]; 
	 	   					 $strMajorAct =  $this->cleanlocution($strMajorActTab[0]);
							 $wexit++;
							 if($wexit > 5) {break;}
						 }
					}	
				
				echo '"></span>';
				
				
				
				if(isset($strMajorGen) && isset($strMajorAct)  )
				{
					if(strpos($strMajorGen,$strMajorAct)) { $strMajorAct="";}
					if(strpos($strMajorAct,$strMajorGen)) { $strMajorGen="";}
					echo "<span class='smalltext_suggestion '>".$strMajorGen." </span>"; 
					echo "<span class='smalltext_suggestion '>".$strMajorAct."</span>"; 		
				}
		echo "</span>";			
		}
		
	}
	else 
	{
		echo "</span>";
	}
		/*
		echo "<pre>";
		print_r($this->results);
		echo "</pre>";
		*/
	}

	

}
?>
