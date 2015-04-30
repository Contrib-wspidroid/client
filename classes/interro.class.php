<?php

/**
 * Description de interro
 *
 * @auteur dpaul
 */

class interro {
	
	/* Constructeur */
	function __construct() {
		// Code constructeur.
	}

	/* Destructeur */
	function __destruct() {
		// Code destructeur
	}
	
	/* ******************************************* */
	/* Fonction test si Web-Service est accessible */
	/* ******************************************* */
	/**
	 * Fonction qui fait une simple interrogation au Web-Service afin de vérifier qu'il est accessible
	 * ***********************************************************************************************
	 * @param type soap $client			= nusoap_client
	 * @param type string $Token		= Clé de sécurité
	 * @return type string					= 'ok' si le web-service répond correctement.
	 */
	function Hello($client, $Token) {
		return $client->call('getHello',array('cle' => $Token));
	}
	
	/* ************************************************************ */
	/* Fonctions Agissant en lecture/écriture sur un élément unique */
	/* ************************************************************ */
	/**
	 * Fonction cliente permettant de changer l'état d'un port GPIO
	 * ************************************************************
	 * @param type soap $client			= nusoap_client
	 * @param type integer $pin			= N° WiringPi
	 * @param type integer $valeur	= 0 pour etteindre, 1 pour allumer
	 * @param type string $Token		= Clé de sécurité
	 * @return type integer					= Valeur de l'état du GPIO (0 ou 1)
	 */
	function changeEtatGpio($client, $pin, $valeur, $Token) {
		$parametres = array('pin'=>$pin, 'valeur'=>$valeur, 'cle' =>$Token);
		return $client->call('setPin', $parametres);
	}
	/**
	 * Fonction cliente permettant de lire l'état d'un port GPIO
	 * *********************************************************
	 * @param type soap $client			= nusoap_client
	 * @param type integer $pin			= N° WiringPi
	 * @param type string $Token		= Clé de sécurité
	 * @return type integer					= Valeur de l'état du GPIO (0 ou 1)
	 */
	function litEtatGpio($client, $pin, $Token) {
		$parametres = array('pin'=>$pin, 'cle' =>$Token);
		return $client->call('getPin', $parametres);
	}
	

	/* ***************************************** */
	/* Fonctions retournant un tableau en retour */
	/* ***************************************** */
	/** 
	 * Fonction qui interroge les températures et retourne un Tableau de tous les 1-wire
	 * *********************************************************************************
	 * @param type soap $client			= nusoap_client
	 * @param type string $Token		= Clé de sécurité
	 * @return type array						= Tableau contenant le nom du capteur, et la température en °C
	 */	 
	public function temperatureTab($client, $Token) {
		$parametres = array('cle' =>$Token);
		return $client->call('get1WireTab', $parametres);
	}
	/**
	 * Fonction cliente utilisant un tableau en retour d'information
	 * *************************************************************
	 * @param type soap $client			= nusoap_client
	 * @param type string $Token		= Clé de sécurité
	 * @param type $litEtat					= Lit l'état du Gpio après action et retourne résultat (1 = oui, 0 = pas de lecture)
	 * @return type array						= Tableau contenant le Nom, le N° WiringPi et l'état du Gpio si $litEtat = 1
	 */
	public function listeMaterielTab($client, $Token, $litEtat=0) {
		$parametres = array('cle' =>$Token, 'litEtat' =>$litEtat);
		return $client->call('getMaterielTab', $parametres);
	}
	
	
	/* ********************************************* */
	/* Fonctions retournant un fichier XML en retour */
	/* ********************************************* */
	/** 
	 * Fonction qui interroge les températures et retourne un fichier XML
	 * ******************************************************************
	 * @param type soap $client			= nusoap_client
	 * @param type string $Token		= Clé de sécurité
	 * @return type XML							= XML contenant le Nom, le N° WiringPi et l'état du Gpio si $litEtat = 1
	 */
	public function temperatureXml($client, $Token) {
		$parametres = array('cle' =>$Token);
		return $client->call('get1WireXml', $parametres);
	}
	
	
	
	/* *********************** */
	/* Execution d'une requete */
	/* *********************** */
	/**
	 * Fonction qui permet d'exécuter une requete sur le Web-Service
	 * *************************************************************
	 * @param type soap $client			= nusoap_client
	 * @param type string $requete	= Le requete à executer.
	 * @param type string $Token		= Clé de sécurité
	 * @return type Tableau					= Tableau contenant le retour de la requete.
	 */
	public function reqExecute($client, $requete, $Token) {
		$parametres = array('query' => $requete, 'cle' => $Token);
		return $client->call('execute', $parametres);
	}
	
}

?>
