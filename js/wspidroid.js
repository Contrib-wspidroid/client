/* Enregistrement des données de configuration dans le fichier config.inc.php */
function majConfigInc(varLien, varObjID_msg) {
	var varSecure_key =  document.getElementById('secure_key').value;
	var varWS_adresse =  document.getElementById('WS_adresse').value;
	var varToken =  document.getElementById('Token').value;
	var varlireEtat =  document.getElementById('lireEtat').value;
	var varAutoConnect =  document.getElementById('AutoConnect').value;
	var varlogin =  document.getElementById('login').value;
	var varpwd =  document.getElementById('pwd').value;
	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'action=majConfig&secure_key='+varSecure_key + '&WS_adresse='+varWS_adresse + '&Token='+varToken + '&lireEtat='+varlireEtat + '&AutoConnect='+varAutoConnect + '&login='+varlogin + '&pwd='+varpwd,
		success: function(msg){
			/* msg est une chaine contenant : la couleur - le message */
			var result = msg.split("-"); // Conversion de msg en tableau.
			$("#info_enrg").css('background-color', '#'+result[0]).css('border', '5px solid #'+result[0]).slideDown(800);
			document.getElementById(varObjID_msg).innerHTML = result[1];
			$("#info_enrg").slideUp(2000);
		}
	});
}

/* Enregistrement des données via Ajax (All) */
function majDonneesAjax(varLien, varAction, varObjID_msg, varSecure_key) {
	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'action=' + varAction + '&ObjID_msg=' + varObjID_msg + '&secure_key=' + varSecure_key,
		success: function(msg){
			/* msg est une chaine contenant : la couleur - le message */
			var result = msg.split("-"); // Conversion de msg en tableau.
			$("#info_enrg").css('background-color', '#'+result[0]).css('border', '5px solid #'+result[0]).slideDown(800);
			document.getElementById(varObjID_msg).innerHTML = result[1];
			$("#info_enrg").slideUp(2000);
		}
	});
}
