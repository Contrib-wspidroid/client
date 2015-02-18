<?php

?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>WsPiDroid</title>
	<meta name="description" content="">
	<meta name="author" content="Dominique">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0" />
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="js/wspidroid.js"></script>
	<link rel="stylesheet" type="text/css" href="style/style.css" media="screen">
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
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
			<a href="?page=action">Actions</a>
			<a href="?page=objet">Objets</a>
			<a href="?page=configuration">Configuration</a>
		</nav>
	</header>
	
	<div class="site-pusher">

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
<script type="text/javascript" src="js/ouvre_menu.js"></script>
</body>
</html>
