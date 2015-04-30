<?php
session_start();

/* Microtime du debut */
$temps_debut = microtime(true);

/* Chargement de la configuration */
$erreur = 0;
include('./config.inc.php');
if ($AutoConnect == "1") $_SESSION['connect'] = 1;

/* On vérifie si la variable de session existe. */
if (isset($_SESSION['connect'])) {
	/* On récupère la valeur de la variable de session. */
	$connect = $_SESSION['connect'];
} else {
	/* Si $_SESSION['connect'] n'existe pas, on donne la valeur "0". */
	$connect = 0;
}
/* On vérifie si la page est appelée par le formulaire d'identification */
if (isset($_POST['login'])) {
	/* Validation du formulaire et connexion non valide */
	if ($_POST['login'] == $login && $_POST['mot_de_passe'] == $pwd) { 
		$connect = 1; 
		$_SESSION['connect'] = 1;
	} else $erreur = 1;
}

/* Affichage de l'entête du site */
echo '
	<!doctype html>
	<html lang="fr">
	<head>
		<title>WsPiDroid</title>
		<meta name="description" content="">
		<meta name="author" content="Dominique">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="style/icones/jeedom/style.css">
		<link href="http://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" type="text/css" />
		<script src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script src="js/wspidroid.js"></script>
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<!--
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
		-->
	</head>
	<body>';

/* Affichage du contenu de la page principale si Identifié */    
if ($connect == 1) {
	/* Identification OK, Affichage du contenu de la page principale */
?>

	<div class="site-container">

		<header class="header">
			<a href="#" class="header__icon" id="header__icon"></a>
			<span class="header__logo">
			<?php 
				switch ($_GET['page']) {
					case "action":
							echo "Actions";
							break;
					case "objet":
							echo "Objets";
							break;
					case "configuration":
							echo "Configuration";
							break;
					default:
						echo "Actions";
				}
			?>
			</span>
			<nav class="menu" id="menu">
				<a href="?page=action" onclick="msgattente();">Actions</a>
				<a href="?page=objet" onclick="msgattente();">Objets</a>
				<a href="?page=configuration" onclick="msgattente();">Configuration</a>
			</nav>
			<?php 
				if ($AutoConnect != 1) { 
					echo '<a href="" onclick="deconnect(\'update_ajax.php\');return false;" class="icoDeconnect"><i class="icoDeconnect fa fa-sign-out fa-2x"></i></a>';
				} ?>
		</header>
		
		<div class="site-pusher">
		<div id="loader"><div class="overlay"></div><i class="fa fa-refresh fa-spin img"></i></div>
			<div class="site-content">
				<div class="container">
					<?php 
						switch ($_GET['page']) {
							case "action":
									require("action.php");
									break;
							case "objet":
									require("objet.php");
									break;
							case "configuration":
									require("configuration.php");
									break;
							default:
								require("action.php");
						}
					?>
				</div>
			</div>
			
			<div class="site-cache" id="site-cache"></div>
			
		</div>
		<div class="info_enrg" id="info_enrg"><h2 class="text_enrg" id="text_enrg"></h2>
		</div>
	</div>
	<script type="text/javascript" src="js/ouvre_menu.js"></script>

	<?php
	} else { 
		/* Identification NON-OK, Affichage de la page de connexion */
		echo '<div id="loader"><div class="overlay"></div><i class="fa fa-refresh fa-spin img"></i></div>';
		include('include/header-id.php');
		echo '
			<div class="divopaque">
				<div class="centre">
				<div class="titre">Veuillez vous identifier</div>
	 
				<form class="formConnect" action="index.php" method="post">
					<div class="divinput"><input type="text" name="login" autocomplete="off" value="" placeholder="Nom d\'utilisateur" /></div>
					<div class="divinput"><input type="password" name="mot_de_passe"  autocomplete="off" value="" placeholder="Mot de passe" /></div>
					<div class="btn_center"><input class="btnConnect" type="submit" onclick="msgattente();" value="Valider"/></div>';
		if ($erreur == 1) echo '<div class="diverreur">Erreur de Login ou Mot de passe lors de votre identification !</div>';
		echo'
				</form>
			</div>
        </div>';
	}

	/* Microtime de fin */
	$temps_fin = microtime(true);
	/* Calcul */
	$calcul = $temps_fin - $temps_debut;
	/* Arrondi (ici 3 décimales) */
	$calcul = round($calcul, 3);
	/* Affichage */
	//echo '<footer><div class="piedpage">Page générée en ' . $calcul . ' seconde(s)</div></footer>';

	/* Fin du site */
	echo '</body></html>';

?>
