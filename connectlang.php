<?php
if(isset($_GET['lang'])) {	
if ($_GET['lang']=="") $sikm->connectIKM_FR();
if ($_GET['lang']=="fr") $sikm->connectIKM_FR();
if ($_GET['lang']=="fr2") $sikm->connectIKM_FR2();
if ($_GET['lang']=="eng") $sikm->connectIKM_ENG();
if ($_GET['lang']=="es") $sikm->connectIKM_ES();
if ($_GET['lang']=="de") $sikm->connectIKM_DE();
if ($_GET['lang']=="it") $sikm->connectIKM_IT();
if ($_GET['lang']=="cancer") $sikm->connectIKM_cancer();
if ($_GET['lang']=="cardio") $sikm->connectIKM_cardio();
if ($_GET['lang']=="med") $sikm->connectIKM_MED();
if ($_GET['lang']=="test") $sikm->connectIKM_TEST();

}

else {
	$sikm->connectIKM_FR();
}
?>
