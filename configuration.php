<?php

/* Test si le fichier "config.inc.php" peut être modifié en écriture par Php */
$filename = "config.inc.php";
if(!is_writable($filename)) {
	echo '<p style="font-weight:bold;color:red;">ATTENTION : Le fichier "config.inc.php" n\'est pas accessible en écriture par le serveur Web.<br />';
	echo 'Veuillez modifier les droits du fichier afin de pouvoir utiliser cette page de configuration.';
}

/* Initialisation des variables de configuration */
require("config.inc.php");

echo '<div class="wrap">';
echo '
		<form action="'.$_SERVER['REQUEST_URI'].'" id="formConfig" name="formConfig" method="post">
			<input type="hidden" name="secure_key" id="secure_key" value="'.$Token.'" />';
			
		echo '<h1 class="titre1">Configuration de l\'application</h1>';
		echo '
			<label>Adresse du serveur WsPiDroid</label>
			<div class="margin-form">
				<input size="80" type="text" name="WS_adresse" id="WS_adresse" value="'.$WS_adresse.'"></div>
			<label for="Token">Clé de sécurité du Web-Service</label>
			<div class="margin-form">
				<input size="40" type="text" name="Token" id="Token" value="'.$Token.'"><br />
				Attention : Si vous changez cette clé il faut également la changer sur le Web-Service.</div>
			<div class="divchecked">Lecture des valeurs GPIO au chargement de page <input type="checkbox" id="lireEtat" name="lireEtat" value="1" '.($lireEtat==1 ? ' checked ="checked "' :'').' /><br />&nbsp;</div>
			';
		
		echo '<h1 class="titre1">Identification à l\'application</h1>';
		echo '
			<div class="divchecked">Auto-connexion (pas de sécurité) : <input type="checkbox" id="AutoConnect" name="AutoConnect" value="1" '.($AutoConnect==1 ? ' checked ="checked "' :'').' /><br />&nbsp;</div>
			<div>
				<div class="divlabel"><label>Votre login : </label></div><div class="divinput"><input type="text" id="login" name="login" value="'.($login!="" ? $login :'').'" /></div>
				<div class="divlabel"><label>Votre mot de passe : </label></div><div class="divinput"><input type="password" id="pwd" name="pwd" value="'.($pwd!="" ? $pwd :'').'" /></div>
			</div>';
		
		echo '
			<div class="btn_center">
				<a class="button" href="javascript:void(0)" name="submitformConfig" id="submitformConfig" style="cursor: pointer; margin-left: 20px" 
					onClick="javascript:majConfigInc(\'config.mod.php\', \'text_enrg\'); return;" />
					&nbsp;Valider&nbsp;</a>
			</div>
		</form>';
		
echo '</div>';
?>
