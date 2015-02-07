<?php

$Col_OK = "B5E655-";
$Col_NOOk = "FF5B2B-";

/* Sécurité du scipt */
include("config.inc.php");
if ($_POST['secure_key'] != $Token) {
	echo $Col_NOOk.'Erreur de sécurité !';
	return;
}

/* ouverture en écriture du fichier config.inc.php */
if(!$fichier = @fopen('config.inc.php', 'w')) {
	/* si erreur on retourne l'erreur */
	echo $Col_NOOk.'Erreur lors de l\'écriture du fichier config.inc.php !';
	return;
}

/* ********************* */
/*  Ecriture du fichier  */
/* ********************* */
/*       TAG Php         */
fwrite($fichier, "<?php\n\n");
/*   en-tête du fichier  */
/* ********************* */
fwrite($fichier, "/* Paramètres générés par ".$_SERVER['PHP_SELF']." */\n\n");
fwrite($fichier, "/* Page de configuration */\n/* ********************* */\n");
fwrite($fichier, "/*\n\t\$WS_adresse = Adresse du WebService.\n\t\$Token \t\t\t= Clé de sécurité du WebService.\n");
fwrite($fichier, "\t\$lireEtat \t= Mettre 1 en parametre pour lire les valeurs en même temps que les retours d'informations du matériel.\n*/\n\n");
/* écriture de variables */
/* ********************* */
fwrite($fichier, "/* Initialisation des variables */\n");
fwrite($fichier, "\$WS_adresse = '".$_POST['WS_adresse']."';\n");
fwrite($fichier, "\$Token = '".$_POST['Token']."';\n");
fwrite($fichier, "\$lireEtat = ".$_POST['lireEtat'].";\n");

/*
// écriture des variables du formulaire
foreach($_POST as $key=>$val) {
	// passer certaines entrées de formulaire (préfixées par 'f_')
	if(strstr($key,"f_")) continue;
	// traitement des constantes (en majuscule)
	elseif(strstr($key,"DB")) fwrite($fichier, "define(\"$key\", \"$val\");\n");
	// traitement des variables numériques ou booléennes en valeur
	elseif(is_numeric($val) || preg_match("/true|false/",$val)) fwrite($fichier, "\$$key = $val;\n");
	// sinon entre guillemets
	elseif(!empty($val)) fwrite($fichier, "\$$key = \"".preg_replace("/[\n|\r|\r\n]+/", " ", trim($val))."\";\n");
}
*/

/*       TAG Php         */
/* ********************* */
fwrite($fichier, "\n?>");

/* Fermeture du fichier  */
fclose($fichier);

/* retour à l'édition */
echo $Col_OK.'Enregistrement du fichier effectué.';
return;

?>
