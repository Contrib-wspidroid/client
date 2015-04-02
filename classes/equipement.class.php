<?php

/**
 * Classe permettant de créer les équipements
 *
 * @auteur Dominique PAUL
 */

class equipement {
	
	private $varObjet;
	
	/* Constructeur */
	public function __construct() {
		// Code constructeur.
	}

	/**
	 * Créateur d'objet 
	 * @param string $nom = Libellé de l'objet vu par l'ihm
	 * @return string $varObjet = Variable texte contenant l'objet équipement créé dans une DIV.
	 */
	public function objet($nom) {
		$varObjet ="";
		/* On vérifie qu'il s'agit bien d'un équipement */
		if ($nom != null) {
			$varObjet .= '<div class="equip">';
			$varObjet .= $nom;
			$varObjet .= '</div>';
		}
		return $varObjet;
	}
	
}

?>
