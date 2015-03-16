<?php

$Col_OK = "B5E655-";
$Col_NOOk = "FF5B2B-";
$MAJ_OK = $Col_OK."Mise à jour effectuée";
$MAJ_NOOK = $Col_NOOk."Erreur lors de la mise à jour !";

/* Déclaration du webService */
	require("config.inc.php");
	include('lib/nusoap/nusoap.php');
	ini_set("soap.wsdl_cache_enabled", "0");
	$client = new nusoap_client($WS_adresse.'wspi.php?wsdl');

/* Mise à jour des critères de sélection */
	if ($_POST['action'] == 'maj') {
		$retour = true;
		if ($retour == true) echo $MAJ_OK; else echo $MAJ_NOOK ;
		return;
	}
	
/* Mise à jour du port GPIO */
	if ($_POST['envoi'] == 'GPIO') {
		$parametres = array('pin'=>$_POST['wiringpi'], 'valeur'=>$_POST['action'], 'cle' =>$_POST['token']);
		echo $client->call('gpio.setPin', $parametres);
		return;
	}
	
/* Envoi d'un commande psutil */
	if ($_POST['envoi'] == 'psutil') {
		$parametres = array('commande'=>$_POST['commande'], 'cle' =>$_POST['token']);
		echo $client->call('wspi.setPsutil', $parametres);
		return;
	}
	
?>
