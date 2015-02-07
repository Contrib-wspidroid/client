<?php

/* Minimum necessaire 
*/
// $Token = 'Votre_cle_personnalisée';
// include('lib/nusoap.php');
// ini_set("soap.wsdl_cache_enabled", "1");
// $client = new nusoap_client('http://192.168.0.5/pidroid/wspi.php?wsdl');
//
// * Pour ecrire sur un pin :
// $parametres = array('pin'=>1, 'valeur'=>0, 'cle' =>$Token);
// echo $client->call('setPin', $parametres);
//
// * Pour lire sur un pin :
// $parametres = array('pin'=>$pin, 'cle' =>$Token);
// echo $client->call('getPin', $parametres);
/* 
Fin du minimum */

/* Initialisation des variables de configuration */
require("config.inc.php");

/* Déclaration du webService */
include('lib/nusoap.php');
ini_set("soap.wsdl_cache_enabled", "0");
$client = new nusoap_client($WS_adresse.'wspi.php?wsdl');
	
	
/* Fonction cliente permettant de changer l'état d'un port GPIO */
/* Valeur envoyée : $pin = N° WiringPi : $valeur = 0 pour etteindre, 1 pour allumer */
/* La valeur de retour est l'état réel : 0 (éteind), ou 1 (allumé), lu sur le GPIO après action demandée */
function clientEcritWeb($pin, $valeur) {
	global $client, $Token;
	$parametres = array('pin'=>$pin, 'valeur'=>$valeur, 'cle' =>$Token);
	return $client->call('setPin', $parametres);
}

/* Fonction cliente permettant de lire l'état d'un port GPIO */
/* Valeur envoyée : $pin = N° WiringPi */
/* La valeur de retour est l'état réel : 0 (éteind), ou 1 (allumé) */
function clientLitWeb($pin) {
	global $client, $Token;
	$parametres = array('pin'=>$pin, 'cle' =>$Token);
	return $client->call('getPin', $parametres);
}

/* Fonction cliente utilisant un tableau en retour d'information */
/* Valeur envoyée : $litEtat = donne la valeur du GPIO oui (1) ou non (0) */
/* La valeur de retour est un tableau contenant le numéro WiringPi, le nom du Gpio, et l'état si $litEtat = 1 */
function listeMateriel($litEtat=0) {
	global $client, $Token;
	$parametres = array('cle' =>$Token, 'litEtat' =>$litEtat);
	return $client->call('getMaterielTab', $parametres);
}

/* Fonction de clignotement aléatoire */
function noel() {
	for ($i = 1; $i <= 40; $i++) {
		$aleatoire = rand(0, 7);
		if ($aleatoire == 7) $aleatoire = 21;
		$valeur = clientLitWeb($aleatoire);
		if ($valeur == 0) $valeur = 1; else $valeur = 0;
		clientEcritWeb($aleatoire, $valeur);
	}
	
	/* On eteind tout */
	for ($i = 0; $i <= 7; $i++) {
		clientEcritWeb($i, 0);
	}
}

/* Fonction qui commande le Stop du Raspberry */
function commande($commande) {
	global $client, $Token;
	$parametres = array('commande' =>$commande, 'cle' =>$Token);
	return $client->call('setCommande', $parametres);
}

/* Fonction qui interroge les températures et retourne un fichier XML */
function temperature() {
	global $client, $Token;
	$parametres = array('cle' =>$Token);
	return $client->call('get1WireXml', $parametres);
}

/* Fonction qui interroge les températures et retourne un Tableau */
/* La valeur de retour est un tableau contenant le nom du capteur, et la température en °C */
function temperatureTab() {
	global $client, $Token;
	$parametres = array('cle' =>$Token);
	return $client->call('get1WireTab', $parametres);
}


/* Script necessaire pour la partie qui interroge les GPIO Actifs */
/* ************************************************************** */
echo '<h1 class="titre1">Allumage et Extinction des ports GPIO</h1>';
if ($_GET['envoi'] == "OK") {
	/* On envoi la commande si elle est demandée */
	clientEcritWeb($_GET['wiringpi'], $_GET['action']);
}
$materiels = listeMateriel($lireEtat); 
echo '<div class="wrap">';
if (is_array($materiels)) {
	foreach ($materiels as $materiel) {
		echo '<div class="donnees"><p class="titre_rel">'. $materiel['nom'] . '</p><p>WiringPi N° : '. $materiel['pin'] . '<br>';
		if ($lireEtat == 1) {
			echo '<a href="?envoi=OK&wiringpi='.$materiel['pin'].'&action=';
			if ($materiel['etat']==1) {
				echo '0" class="allume"><span class="allume">Allumé</span>';
			} else {
				echo '1" class="eteind"><span class="eteind">Eteind</span>';
			} 
			echo '</a>';
		}
		echo '</p></div>';
	}
} else echo "<p>Pas de retour, soit il n'existe aucun Pin configuré dans le WebService, soit le WebService ne répond pas</p>";
echo '</div>';
/* *************** Fin de la partie inférieure ******************** */
/* **************************************************************** */

echo '<p style="clear:both">&nbsp;</p><hr /><p>&nbsp;</p>';


/* ***************** Relevé des températures ********************** */
/* **************************************************************** */
echo '<h1 class="titre1">Relevé des températures</h1>';
$releves = temperatureTab();
echo '<div class="wrap">';
if (is_array($releves)) {
	foreach ($releves as $releve) 
		echo '<div class="donnees"><p class="titre_rel">'. $releve['nom'] . '</p><p>'. $releve['valeur'] . ' °C</p></div>';
} else echo "<p>Pas de retour, soit il n'existe aucun capteur de température, soit le WebService ne répond pas</p>";
echo '<p style="clear:both"><br /><a class="button" href="?envoi=temperature">Mise à jour des températures</a></p>';
echo '</div>';
echo '<hr /><p>&nbsp;</p>';
/* ************** Fin de Relevé des températures ******************* */
/* ***************************************************************** */


/* Actions Shell */
echo '<h1 class="titre1">Autres actions</h1>';
echo '<div class="wrap">';

/* ***************** Script Guirlande de noel ********************** */
/* ***************************************************************** */
if ($_GET['envoi'] == "noel") noel();
echo '<p><a href="?envoi=noel">Guirlande de Noël</a></p>';
/* ***************** Fin de Guirlande de noel ********************** */
/* ***************************************************************** */


/* ********** Commande d'arret ou de Reboot du Raspberry *********** */
/* ***************************************************************** */
if ($_GET['envoi'] == "cde") commande($_GET['commande']);
echo '<p><a class="button" href="?envoi=cde&commande=halt">Arrêt du Raspberry</a></p>';
echo '<p><a class="button" href="?envoi=cde&commande=reboot">Reboot du Raspberry</a></p>';
/* ****************** Fin de Commande d'arrêt ********************** */
/* ***************************************************************** */
echo '</div>';

echo'
	</body>
</html>';

?>
