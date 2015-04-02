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
$client = new nusoap_client($WS_adresse.'requete.php?wsdl');

/* Liste des zones actives */
if ($WS_OK) {
	/* On liste les zones */
	$requete = "select zone_nom, zone_parent,zone_icone from zones where zone_valide = 1 order by zone_position asc";
	$parametres = array('query' => $requete, 'cle' => $Token);
	$varRetZones = $client->call('execute', $parametres);
	/* On liste les équipements */
	$requete = "select zone_nom, zone_parent,zone_icone, eq_nom, eq_code_equip, eq_type_interface_id, eq_configuration
							from zones
							left join equipements on (eq_zone_id = zone_id and eq_valide = 1 and eq_visible = 1)
							where zone_valide = 1 
							order by zone_position asc, eq_type_interface_id";
	$parametres = array('query' => $requete, 'cle' => $Token);
	$varRetequip = $client->call('execute', $parametres);
	/* On décode les tableaux retournés */
	if ($client->getError()) $WS_OK = false;
	$varRetZones = json_decode($varRetZones,true);
	$varRetequip = json_decode($varRetequip,true);
	/* On boucle sur les zones */
	if (!empty($varRetZones)) { // En cas d'erreur et que la requete ne retourne pas un tableau.
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
				foreach ($elementFiltre as $elementEquip) {
					$equip = new equipement();
					echo $equip->objet($elementEquip['eq_nom']);
				}
				echo '</div>';
					
			}
//				$groupFiltre = array_filter($group,"test");
//				var_dump($groupFiltre);
				
					//var_dump(array_filter($varRetour,"test")); die;
//	foreach ( $varRetour as $value ) {
//    $group[$value['zone_nom']][] = $value;
//	} 	
//	//var_dump($group);
//	if (!empty($group)) { // En cas d'erreur et que la requete ne retourne pas un tableau.
//		foreach ($group as $key=>$tab1) {
//			echo '<div class=zone>';
//			echo $key;
//			echo '</div>';
//		}
//		
//	}
//	
//	die;
//foreach ($group)
//				echo '<div>';

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
