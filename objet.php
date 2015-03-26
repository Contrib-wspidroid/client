<?php

// On interdit l'appel direct à cette page */
if (basename($_SERVER['PHP_SELF']) != 'index.php' || !isset($_SESSION['connect']) || $_SESSION['connect'] == 0) { 
	header('Location: ./index.php');
	die();
}

echo '<h1 class="titre1">Configuration des Objets</h1>';

// echo "<p>Option furure : à développer.</p>";


/* Initialisation des variables de configuration */
require("config.inc.php");
$WS_OK = true;

/* Déclaration du webService */
include('lib/nusoap/nusoap.php');
ini_set("soap.wsdl_cache_enabled", "0");
$client = new nusoap_client($WS_adresse.'requete.php?wsdl');

function xcaract($nombre,$caractere) {
	$xcar = "";
	for($i=1;$i<=(int)$nombre;$i++){
		$xcar .= $caractere;
	}
	return $xcar;
}

/* Fonction qui liste les zones */
function zone($action,$donnees,$cle) {
	global $client, $Token;
	$parametres = array('action' =>$action, 'donnees' =>$donnees, 'cle' =>$Token);
	return $client->call('tbZone', $parametres);
}

/* Liste des zones actives */
if ($WS_OK) $testRetour = zone('select','{"zone_valide":"1","order by":"zone_position asc"}',$Token);
if ($client->getError()) $WS_OK = false;
if ($WS_OK) {
	$testRetour = json_decode($testRetour,true);
	foreach ($testRetour as $tab1) {
		if ($tab1['zone_parent'] == '') echo '<i class="fa '.$tab1['zone_icone'].'"></i> ' . $tab1['zone_nom'] . '<br />';
		else {
			echo xcaract((int)$tab1['zone_parent'],'&nbsp;') . ' ' . '<i class="fa '.$tab1['zone_icone'].'"></i> ' . $tab1['zone_nom'] . '<br />';
		}
	}
} else echo "<p>Pas de retour, le WebService n'est pas configuré, ou celui ci ne répond pas</p>";


/* Pour test */
//echo '<pre>';
//print_r($testRetour);
//echo '</pre>';
//foreach ($testRetour as $tab1) {
//	echo '<br />enr : <br />';
//	foreach ($tab1 as $tab2 => $tab3) {
//		echo $tab2.'=>'.$tab3 .' ----- ';
//	}
//	
//}

//if ($client->getError()) $WS_OK = false;
?>
