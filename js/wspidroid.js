/* */
function msgattente() {
	$("#loader").show();
}

/* Déconnexion de la session via Ajax */
function deconnect(varLien) {
	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'action=deconnect',
		beforeSend: function() { $("#loader").show(); },
		success: function(msg){
	  	window.location="./index.php";
	 	},
	 	error: function() {
	 		alert('erreur');
	 	}
	});
}

/* Enregistrement des données de configuration dans le fichier config.inc.php */
function majConfigInc(varLien, varObjID_msg) {
	var varSecure_key =  document.getElementById('secure_key').value;
	var varWS_adresse =  document.getElementById('WS_adresse').value;
	var varToken =  document.getElementById('Token').value;
	var varlireEtat =  document.getElementById('lireEtat').checked;
	if (varlireEtat == true) { varlireEtat = 1; } else { varlireEtat = 0; }
	var varAutoConnect =  document.getElementById('AutoConnect').checked ;
	if (varAutoConnect == true) { varAutoConnect = 1; } else { varAutoConnect = 0; }
	var varlogin =  document.getElementById('login').value;
	var varpwd =  document.getElementById('pwd').value;
	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'action=majConfig&secure_key='+varSecure_key + '&WS_adresse='+varWS_adresse + '&Token='+varToken + '&lireEtat='+varlireEtat + '&AutoConnect='+varAutoConnect + '&login='+varlogin + '&pwd='+varpwd,
		beforeSend: function() { $("#loader").show(); },
		success: function(msg){
			/* msg est une chaine contenant : la couleur - le message */
			var result = msg.split("-"); // Conversion de msg en tableau.
			$("#info_enrg").css('background-color', '#'+result[0]).css('border', '5px solid #'+result[0]).slideDown(800);
			document.getElementById(varObjID_msg).innerHTML = result[1];
			$("#info_enrg").slideUp(2000);
		}
	});
}

/* Affichage du bandeau d'information lors de l'Enregistrement des données via Ajax (All) */
function majDonneesAjax(varLien, varAction, varObjID_msg, varSecure_key) {
	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'action=' + varAction + '&ObjID_msg=' + varObjID_msg + '&secure_key=' + varSecure_key,
		beforeSend: function() { $("#loader").show(); },
		success: function(msg){
			/* msg est une chaine contenant : la couleur - le message */
			var result = msg.split("-"); // Conversion de msg en tableau.
			$("#info_enrg").css('background-color', '#'+result[0]).css('border', '5px solid #'+result[0]).slideDown(800);
			document.getElementById(varObjID_msg).innerHTML = result[1];
			$("#info_enrg").slideUp(2000);
		}
	});
}

/* Action sur les GPIO via Ajax */
function majGPIO(varLien, varWiringPi, varIDInfo, varIcon, varSecure_key) {
	if (varIcon != 'text') {
		/* Si on est pas en mode texte */
		var reg = new RegExp("(')", "g");
		var optequip = varIcon.replace(reg,'"');
		optequip = JSON.parse(optequip);
		var iconon = optequip.iconon;
		var iconoff = optequip.iconoff;
		var texton = optequip.texton;
		var textoff = optequip.textoff;
	}
	
	var varAction = 0;
	var varEtat =  document.getElementById(varIDInfo).className;
	if(varEtat == 'eteind' || varEtat.substring(0,3) == 'off') { varAction = 1; } 

	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'envoi=GPIO&wiringpi='+varWiringPi+'&action='+varAction+'&token='+varSecure_key,
		beforeSend: function() { $("#loader").show(); },
		success: function(msg){
	  	if (msg == 1) {
				if (texton == null) { texton = 'Allumé'; }
	  		if (varIcon != 'text') {
					if (iconon == null) { iconon = 'jeedom-lumiere-on'; }
					document.getElementById(varIDInfo).className = 'on icon '+iconon;
					document.getElementById('info'+varIDInfo).innerHTML = texton;
				} else {
					document.getElementById(varIDInfo).className = 'allume';
		 			document.getElementById(varIDInfo).innerHTML = texton;
				}
	 		} else {
				if (textoff == null) { textoff = 'Eteind'; }
				if (varIcon != 'text') {
					if (iconoff == null) { iconoff = 'jeedom-lumiere-off'; }
					document.getElementById(varIDInfo).className = 'off icon '+iconoff;
					document.getElementById('info'+varIDInfo).innerHTML = textoff;
				} else {
					document.getElementById(varIDInfo).className = 'eteind';
					document.getElementById(varIDInfo).innerHTML = textoff;
				}
	 		}
	 	},
	 	error: function() {
	 		alert('erreur');
	 	},
		complete: function() { $("#loader").fadeOut(); }
	});
}

/* Demande un relevé de température */
function maj1Wire(varLien, varNomWire, varIDRetour, varSecure_key) {
	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'envoi=Wire&nomwire='+varNomWire+'&token='+varSecure_key,
		beforeSend: function() { $("#loader").show(); },
		success: function(msg){
			document.getElementById(varIDRetour).innerHTML = msg+' °C';
		},
	 	error: function() {
	 		document.getElementById(varIDRetour).innerHTML = 'Erreur !';
	 	},
		complete: function() { $("#loader").fadeOut(); }
	});
}

/* Demande d'envoi d'une commande RF433 */
function setRF(varLien, varValCmd, varIDRetour, varSecure_key) {
	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'envoi=Wire&nomwire='+varNomWire+'&token='+varSecure_key,
		beforeSend: function() { $("#loader").show(); },
		success: function(msg){
			document.getElementById(varIDRetour).innerHTML = msg+' °C';
		},
	 	error: function() {
	 		document.getElementById(varIDRetour).innerHTML = 'Erreur !';
	 	},
		complete: function() { $("#loader").fadeOut(); }
	});
}

/* Information sur le système via psutil */
function psutil(varLien, varSecure_key) {
	var element = "sel-psutil";
	var commande = document.getElementById(element).value;
	var idx = document.getElementById(element).selectedIndex;
	var libelle = document.getElementById(element)[idx].innerHTML;
	$.ajax({
		type: 'POST',
		async: false,
		url: varLien,
		data: 'envoi=psutil&commande='+commande+'&token='+varSecure_key,
		beforeSend: function() { $("#loader").show(); },
		success: function(msg){
			var temp = libelle.toLowerCase();
			if(parseInt(msg) > 100000 && temp.indexOf("temps") == -1 && temp.indexOf("date") == -1) { 
				var msg = msg / 1024;
				msg = format(msg, 0, " ") + " Ko";
			} else if (libelle.indexOf("%") != -1) {
				msg = parseFloat(msg) + " %";
			} else if (temp.indexOf("date") != -1) {
				msg = new Date(msg * 1000);
				var jour = msg.getDate();
				if (jour < 10) { jour = "0" + jour; }
				var mois = msg.getMonth()+1;
				if (mois < 10) { mois = "0" + mois; }
				var heure = msg.getHours();
				if (heure < 10) { heure = "0" + heure; }
				var minute = msg.getMinutes();
				if (minute < 10) { minute = "0" + minute; }
				msg = "Le "+jour+"-"+mois+"-"+msg.getFullYear() +" à "+ heure+":"+minute;
			} else if (temp.indexOf("temps") != -1) {
				/* msg = formatduree(parseInt(msg)/100); */
				msg = new Date(msg * 1000);
				msg = new Date() - msg;
				msg = formatduree(msg);
			} else {
				msg = msg.replace('[','');
				msg = msg.replace(']','');
				msg = msg.replace('), ','),\n');
			}
	  	alert(libelle + '\n\n' + msg);
	 	},
	 	error: function() {
	 		alert('erreur');
	 	}
	});
}


/* Fonction Javascript permettant de formater un nombre */
/* Valeur = La valeur à formater */
/* decimal = Nombre de décimale */
/* separateur = signe utilisé pour la séparation des milliers */
function format(valeur,decimal,separateur) {
// formate un chiffre avec 'decimal' chiffres après la virgule et un separateur
	var deci=Math.round( Math.pow(10,decimal)*(Math.abs(valeur)-Math.floor(Math.abs(valeur)))) ; 
	var val=Math.floor(Math.abs(valeur));
	if ((decimal==0)||(deci==Math.pow(10,decimal))) {val=Math.floor(Math.abs(valeur)); deci=0;}
	var val_format=val+"";
	var nb=val_format.length;
	for (var i=1;i<4;i++) {
		if (val>=Math.pow(10,(3*i))) {
			val_format=val_format.substring(0,nb-(3*i))+separateur+val_format.substring(nb-(3*i));
		}
	}
	if (decimal>0) {
		var decim=""; 
		for (var j=0;j<(decimal-deci.toString().length);j++) {decim+="0";}
		deci=decim+deci.toString();
		val_format=val_format+"."+deci;
	}
	if (parseFloat(valeur)<0) {val_format="-"+val_format;}
	return val_format;
}

/* Fonction permettant de convertir une durée en jour, heure, minute, seconde */
function formatduree(duree){
	var diff = {}																// Initialisation du retour
	duree = Math.floor(duree/1000);							// Nombre de secondes entre les 2 dates
	diff.sec = duree % 60;											// Extraction du nombre de secondes

	duree = Math.floor((duree-diff.sec)/60);		// Nombre de minutes (partie entière)
	diff.min = duree % 60;											// Extraction du nombre de minutes
	if (diff.min > 0) { diff.min = diff.min + " minutes et "; } else { diff.min = ""; }
	
	duree = Math.floor((duree-diff.min)/60);		// Nombre d'heures (entières)
	diff.hour = duree % 24;											// Extraction du nombre d'heures
	if (diff.hour > 0) { diff.hour = diff.hour + " heures, "; } else { diff.hour = ""; }
	
	duree = Math.floor((duree-diff.hour)/24);		// Nombre de jours
	diff.day = duree;
	if (diff.day > 0) { diff.day = diff.day + " jours, "; } else { diff.day = ""; }

	var temps = diff.day + diff.hour + diff.min + diff.sec+" secondes"
	return temps;
}


