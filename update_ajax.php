<?php
session_start();

$Col_OK = "B5E655-";
$Col_NOOk = "FF5B2B-";
$MAJ_OK = $Col_OK."Mise à jour effectuée";
$MAJ_NOOK = $Col_NOOk."Erreur lors de la mise à jour !";

/* Déconnection de l'application */
if ($_POST['action'] == 'deconnect') {
	$_SESSION['connect'] = 0;
	return;
}

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
	echo $client->call('setPin', $parametres);
	return;
}

/* Mise à jour d'u port GPIO'une donnée 1-wire */
if ($_POST['envoi'] == 'Wire') {
	$parametres = array('nom'=>$_POST['nomwire'], 'cle' =>$_POST['token']);
	echo $client->call('get1WireUnique', $parametres);
	return;
}
	
/* Envoi d'un commande psutil */
if ($_POST['envoi'] == 'psutil') {
	$parametres = array('commande'=>$_POST['commande'], 'cle' =>$_POST['token']);
	echo $client->call('setPsutil', $parametres);
	return;
}
	
?>
