<?php

?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>WsPiDroid</title>
	<meta name="description" content="">
	<meta name="author" content="Dominique">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="js/wspidroid.js"></script>
	<link rel="stylesheet" type="text/css" href="style/style.css" media="screen">
<script>
jQuery(document).ready(function(){
	$(window).scroll(function () {
		var rupture = $('#header').outerHeight() ;
		
		if( $(window).scrollTop() > rupture ) {
			$('#nav').addClass('fixed');
		} else {
			$('#nav').removeClass('fixed');
		}
	});
    
});
</script>
</head>
<body>
<div class="page">
<section>
	<div id="wrap">
		<header id="header" class="header">
			<h1 class="titre_haut">WsPiDroid</h1>
		</header>

		<nav id="nav" class="nav">
			<ul>
				<li><a href="?page=action">Actions</a></li>
				<li><a href="?page=objet">Objets</a></li>
				<li><a href="?page=configuration">Configuration</a></li>
			</ul>
		</nav>
	
		<article id="article" class="article">
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
		</article>
	</div>
</section>
	
	<footer>
    Mon pied de page ici
	</footer>

<div id="info_enrg"><h2 id="text_enrg"></h2>
</div>
</body>
</html>
