<?php

// On interdit l'appel direct à cette page */
if (basename($_SERVER['PHP_SELF']) != 'index.php' || !isset($_SESSION['connect']) || $_SESSION['connect'] == 0) { 
	header('Location: ./index.php');
	die();
}

/* Chargement en dynamique des classes à l'aide d'un autoloader */
require 'classes/autoloader.php';
autoloader::register();

/* Titre de la page */
echo '<h1 class="titre1">Configuration des Objets</h1>';

/* Initialisation des variables de configuration */
require("config.inc.php");
$WS_OK = true;

/* Déclaration du webService */
include('lib/nusoap/nusoap.php');
ini_set("soap.wsdl_cache_enabled", "0");
$matos = new nusoap_client($WS_adresse.'wspi.php?wsdl');

/* On charge le classe des interrogations */
$interroge = new interro();

/* met à jour les valeurs de données matériel en base de données */
$varRetour = $interroge->Hello($matos, $Token);
if (!$matos->getError() && (int)$varRetour != 9999) {
	/* Lecture données, n'utilise pas le retour mais permet d'enregistrer la valeur dans la base de données */
	/* **************************************************************************************************** */
	/* Lecture des valeurs de GPIO */
	$valRetour = $interroge->listeMaterielTab($matos, $Token, 1);
	
	/* Lecture des valeurs de température */
	$valRetour = $interroge->temperatureTab($matos, $Token);
} 
	

/* Lecture des informations */
$client = new nusoap_client($WS_adresse.'requete.php?wsdl');
$varRetour = $interroge->Hello($client, $Token);
if ($client->getError() || (int)$varRetour == 9999) $WS_OK = false;


if ($WS_OK) {
	/* Liste des zones actives */
	/* *********************** */
	/* On liste les zones */
	$requete = "select zone_nom, zone_parent,zone_icone from zones where zone_valide = 1 order by zone_position asc";
	$varRetZones = $interroge->reqExecute($client, $requete, $Token);
	
	/* On liste les équipements */
	$requete = "select zone_nom, zone_parent,zone_icone, eq_nom, eq_code_equip, eq_type_interface_id, eq_configuration, eq_valeur
							from zones
							left join equipements on (eq_zone_id = zone_id and eq_valide = 1 and eq_visible = 1)
							where zone_valide = 1 
							order by zone_position asc, eq_type_interface_id";
	$varRetequip = $interroge->reqExecute($client, $requete, $Token);
		
	/* On décode les tableaux retournés */
	if ($client->getError()) $WS_OK = false;
	$varRetZones = json_decode($varRetZones,true);
	$varRetequip = json_decode($varRetequip,true);
	/* On boucle sur les zones */
	if (!empty($varRetZones) && is_array($varRetZones)) { // En cas d'erreur et si la requete ne retourne pas un tableau.
		foreach ($varRetZones as $elementZone) {
			if ($elementZone['zone_parent'] == '') {
				echo '<div class=zone>';
				echo '<i class="fa '.$elementZone['zone_icone'].'"></i> ' . $elementZone['zone_nom'] . '<br />';
				echo '</div>';
			} else {
				echo '<div class=zone>';
				$tabul = (int)$elementZone['zone_parent'] * 15;
				echo '<span style="position: relative; left:'.$tabul.'px;"><i class="fa '.$elementZone['zone_icone'].'"></i> ' . $elementZone['zone_nom'] . '<br /></span>';
				echo '</div>';

				$elementFiltre = array_filter($varRetequip, function($var){ global $elementZone; return $var['zone_nom'] == $elementZone['zone_nom']; });
				echo '<div class="element" style="position: relative; left:'.$tabul.'px;">';
				/* Lit la valeur de chaque Equipement */
				$equip = new equipement();
				foreach ($elementFiltre as $elementEquip) {
					echo $equip->objet($elementEquip, $Token);
				}
				echo '</div>';
					
			}
		}
	}
} else {
	/* Informe l'utilisateur si la clé n'est pas valide */
	if ((int)$varRetour == 9999) echo 'Erreur : Clé de sécurité non valide !!!';
	else echo "<p>Pas de retour, le WebService n'est pas configuré, ou celui ci ne répond pas</p>";
}



//echo base_convert('500015',16,10).'<br>'.  base_convert('500014', 16, 10);

?>
