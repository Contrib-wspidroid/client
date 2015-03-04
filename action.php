<?php

/* Minimum necessaire 
*/
// $Token = 'Votre_cle_personnalisée';
// include('lib/nusoap/nusoap.php');
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
$WS_OK = true;

/* Déclaration du webService */
include('lib/nusoap/nusoap.php');
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
$materiels = listeMateriel($lireEtat); 
if ($client->getError()) $WS_OK = false;
echo '<div class="wrap">';
if (is_array($materiels)) {
	foreach ($materiels as $materiel) {
		echo '<div class="donnees"><p class="titre_rel">'. $materiel['nom'] . '</p><p>WiringPi N° : '. $materiel['pin'] . '<br>';
		if ($lireEtat == 1) {
			// echo '<a href="?envoi=OK&wiringpi='.$materiel['pin'].'&action=';
			echo '<a href="" onclick="majGPIO(\'update_ajax.php\', \''.$materiel['pin'].'\', \''.$Token.'\');return false;" ';
			if ($materiel['etat']==1) {
				echo 'class="allume"><span id="led_'.$materiel['pin'].'" class="allume">Allumé</span>';
			} else {
				echo 'class="eteind"><span id="led_'.$materiel['pin'].'" class="eteind">Eteind</span>';
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
if ($WS_OK) $releves = temperatureTab();
echo '<div class="wrap">';
if (is_array($releves)) {
	foreach ($releves as $releve) 
		echo '<div class="donnees"><p class="titre_rel">'. $releve['nom'] . '</p><p>'. $releve['valeur'] . ' °C</p></div>';
} else echo "<p>Pas de retour, soit il n'existe aucun capteur de température, soit le WebService ne répond pas</p>";
echo '<p style="clear:both"><br />'.($WS_OK ? '<a class="button" href="?envoi=temperature">Mise à jour des températures</a>':'').'</p>';
echo '</div>';
echo '<hr /><p>&nbsp;</p>';
/* ************** Fin de Relevé des températures ******************* */
/* ***************************************************************** */


/* Actions Shell */
echo '<h1 class="titre1">Autres actions</h1>';
echo '<div class="wrap">';

/* ************ Envoyer des commandes PSUTIL - Python ************** */
/* ***************************************************************** */
if ($WS_OK) {
	echo '<p>';
	echo '<p>';
	echo '<select id="sel-psutil" name="sel-psutil" class="cbo-psutil">
					<optgroup label="CPU">
						<option value="psutil.cpu_count()">Nombre de processeur Physique</option>
						<option value="psutil.cpu_times_percent()[0]">% CPU pour processus de l’utilisateur</option>
						<option value="psutil.cpu_times_percent()[2]">% CPU pour noyau et ses processus</option>
						<option value="psutil.cpu_times_percent()[1]">% CPU pour processus de l’utilisateur qui ont été "nicés"</option>
						<option value="psutil.cpu_times_percent()[3]">% CPU non sollicité</option>
						<option value="psutil.cpu_times_percent()[4]">% CPU pour attente I/O</option>
						<option value="psutil.cpu_times_percent()[5]">% CPU pour les interruptions matérielles</option>
						<option value="psutil.cpu_times_percent()[6]">% CPU pour les interruptions logicielles</option>
						<option value="psutil.cpu_times_percent()">Toutes les informations de consommation CPU</option>
					</optgroup>
					<optgroup label="Mémoire">
						<option value="psutil.virtual_memory()[2]">Mémoire Utilisée en %</option>
						<option value="psutil.virtual_memory()[0]">Mémoire totale</option>
						<option value="psutil.virtual_memory()[3]">Mémoire utilisée</option>
						<option value="psutil.virtual_memory()[4]">Mémoire libre</option>
						<option value="psutil.virtual_memory()[7]">Mémoire tampon</option>
						<option value="psutil.virtual_memory()">Toutes les informations sur la mémoire</option>
					</optgroup>
					<optgroup label="Swap">
						<option value="psutil.swap_memory()[3]">Swap Utilisée en %</option>
						<option value="psutil.swap_memory()[0]">Swap totale</option>
						<option value="psutil.swap_memory()[2]">Swap utilisée</option>
						<option value="psutil.swap_memory()[4]">Swap libre</option>
						<option value="psutil.swap_memory()">Toutes les informations sur le Swap</option>
					</optgroup>
					<optgroup label="Disques">
						<option value="psutil.disk_partitions()">Partitionnement</option>
						<option value="psutil.disk_usage(\'/\')[0]">Espace total Disque depuis le "/"</option>
						<option value="psutil.disk_usage(\'/\')[1]">Espace Disque utilisé depuis le "/"</option>
						<option value="psutil.disk_usage(\'/\')[2]">Espace Disque libre depuis le "/"</option>
						<option value="psutil.disk_usage(\'/\')[3]">% d\'utilisation du Disque depuis le "/"</option>
						<option value="psutil.disk_usage(\'/\')">Toutes les informations Disque depuis le "/"</option>
					</optgroup>
					<optgroup label="Autres informations Système">
						<option value="psutil.users()">Utilisateurs connectés (vide si personne)</option>
						<option value="psutil.boot_time()">Date et heure du dernier reboot</option>
						<option value="psutil.boot_time()">Temps écoulé depuis le dernier reboot</option>
					</optgroup>
				</select>';
	echo '<a class="button" href="" onclick="psutil(\'update_ajax.php\',\''.$Token.'\');return false;">Commandes Psutil</a></p>';
} else echo "<p>Le WebService ne répond pas</p>";
/* ************** Fin des commandes PSUTIL - Python **************** */
/* ***************************************************************** */


/* ***************** Script Guirlande de noel ********************** */
/* ***************************************************************** */
if ($WS_OK) {
	if ($_GET['envoi'] == "noel") noel();
	echo '<p>'.($WS_OK ? '<a href="?envoi=noel">Guirlande de Noël</a>':'').'</p>';
}	
/* ***************** Fin de Guirlande de noel ********************** */
/* ***************************************************************** */


/* ********** Commande d'arret ou de Reboot du Raspberry *********** */
/* ***************************************************************** */
if ($_GET['envoi'] == "cde") commande($_GET['commande']);
if ($WS_OK) {
	echo '<p><a class="button" href="?envoi=cde&commande=halt">Arrêt du Raspberry</a></p>';
	echo '<p><a class="button" href="?envoi=cde&commande=reboot">Reboot du Raspberry</a></p>';
} //else echo "<p>Le WebService ne répond pas</p>";
/* ****************** Fin de Commande d'arrêt ********************** */
/* ***************************************************************** */
echo '</div>';

echo'
	</body>
</html>';

?>
