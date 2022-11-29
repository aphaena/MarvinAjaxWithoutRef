<?php

function ScanDirectory($Directory){

  $MyDirectory = opendir($Directory) or die('Erreur');
  error_reporting(0);
	while($Entry = @readdir($MyDirectory))
	{
		if(is_dir($Directory.'/'.$Entry)&& $Entry != '.' && $Entry != '..') {
                         echo '<ul>'.$Directory;
			ScanDirectory($Directory.'/'.$Entry);
                        echo '</ul>';
		}
		else 
		{
				$str =  '<li>'.$Entry.' ____ '.number_format((filesize($Directory.'/'.$Entry)/1000),1).'Ko ___ '.date('h:i:s', filemtime($Directory.'/'.$Entry)).'</li>';
				echo '<a href="connections/'.$Entry.'">'.$str.'</a>';
        }
	}
  closedir($MyDirectory);
}


ScanDirectory('connections');

?>

