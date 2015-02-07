<?php

$Col_OK = "B5E655-";
$Col_NOOk = "FF5B2B-";
$MAJ_OK = $Col_OK."Mise à jour effectuée";
$MAJ_NOOK = $Col_NOOk."Erreur lors de la mise à jour !";

/* Mise à jour des critères de sélection */
	if ($_POST['action'] == 'maj') {
		$retour = true;
		if ($retour == true) echo $MAJ_OK; else echo $MAJ_NOOK ;
		return;
	}
	
?>
