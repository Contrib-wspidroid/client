<?php

/**
 * Classe permettant de créer les équipements
 *
 * @auteur Dominique PAUL
 */

class equipement {
	
	private $dyncss;
	private $icon;
	private $iconon;
	private $iconoff;
	private $texton;
	private $textoff;
	private $equip;
	private $Token;
	protected $valEquip;


	/* Constructeur */
	public function __construct() {
		// Code constructeur.
	}

	/**
	 * Création du bloc correspondant à un équipement.
	 * @param string $nom						= Libellé de l'objet vu par l'ihm
	 * @param type string $Token		= Clé de sécurité
	 * @return string = Variable texte contenant l'objet équipement créé dans une DIV.
	 */
	public function objet($equip, $Token='') {
		$this->equip = $equip;
		$this->Token = $Token;
		
		/* On vérifie qu'il s'agit bien d'un équipement */
		if ($this->equip['eq_nom'] != null) {
			/* Suivant l'équipement on attribut un CSS */
			switch ($this->equip['eq_type_interface_id']) {
				case 1: $this->dyncss = 'equipGpio'; break;		// Gpio
				case 2: $this->dyncss = 'equipX10'; break;		// X10
				case 3: $this->dyncss = 'equipRF'; break;			// RadioFrequence
				case 4: $this->dyncss = 'equipTemp'; break;		// 1-Wire
				default: $this->dyncss = 'equipAutre'; break;	// Autre Equipement
			}
			$this->valEquip = json_decode($this->equip['eq_configuration'], true);
						
			/* Si icon et/ou texte personnalisé, on les enregistre */
			$this->iconon = $this->valEquip['iconon'];
			$this->iconoff = $this->valEquip['iconoff'];
			$this->texton = $this->valEquip['texton'];
			$this->textoff = $this->valEquip['textoff'];
			
			/* Met a jour les information ou action demandées par Ajax */
			switch ($this->valEquip['type']) {
				case 'on-off':			// Cas d'écriture On/Off sur GPIO.
					$this->icon = $this->creeDivGpio();
					break;
				
				case 'temperature': // Lecture de Capteur de température.
					$this->icon = $this->CreeDivTemp();
					break;
				
				case 'radio':				// Envoi d'une commande On/Off sur RF433
					$this->icon = $this->creeDivRF();
					break;
				
				default: 
					$this->icon = '';
					break;
			}
		}
		return $this->icon;
	}
	
	/**
	 * Fonction Privée qui permet de créer la DIV d'affichage de l'état du GPIO.
	 * @return string = Code HTML de l'objet GPIO
	 */
	private function creeDivGpio() {
		$textjson = preg_replace('/"/', '\\\'', $this->equip['eq_configuration']);
		$valHtml = '<div class="equipement '.$this->dyncss.'">';
		$valHtml .= $this->equip['eq_nom'].'<hr class="iconinfo">';
		$valHtml .= '<span class="iconSel">';
		if ($this->equip['eq_valeur'] == 'on') { 
			if ($this->iconon == '') $this->iconon = 'jeedom-lumiere-on';
			$valHtml .= '<a href="" onclick="majGPIO(\'update_ajax.php\', \''.$this->equip['eq_code_equip'].'\', \'led_'.$this->equip['eq_code_equip'].'\', \''.$textjson.'\', \''.$this->Token.'\');return false;" class="iconaction">';
			$valHtml .= '<i id="led_'.$this->equip['eq_code_equip'].'" class="on icon '.$this->iconon.'"></i></a></span>'; // allumée
			$valHtml .= '<br><span id="infoled_'.$this->equip['eq_code_equip'].'" class="iconValeur">'.($this->texton!='' ?$this->texton :'Allumé');
		} else {
			if ($this->iconoff == '') $this->iconoff = 'jeedom-lumiere-off';
			$valHtml .= '<a href="" onclick="majGPIO(\'update_ajax.php\', \''.$this->equip['eq_code_equip'].'\', \'led_'.$this->equip['eq_code_equip'].'\', \''.$textjson.'\', \''.$this->Token.'\');return false;" class="iconaction">';
			$valHtml .= '<i id="led_'.$this->equip['eq_code_equip'].'" class="off icon '.$this->iconoff.'"></i></a></span>'; // eteind
			$valHtml .= '<br><span id="infoled_'.$this->equip['eq_code_equip'].'" class="iconValeur">'.($this->textoff!='' ?$this->textoff :'Eteind');
		}
		$valHtml .= '</span></div>';
		/* On retourne la DIV */
		return $valHtml;
	}
	
	/**
	 * Fonction Privée qui permet de créer la DIV d'affichage des températures.
	 * @return string = Code HTML de l'objet Température
	 */
	private function CreeDivTemp() {
		$valHtml = '<div class="equipement '.$this->dyncss.'">';
		$valHtml .= $this->equip['eq_nom'].'<hr class="iconinfo">';
		$valHtml .= '<span class="iconSel">';
		$valHtml .= '<a href="" onclick="maj1Wire(\'update_ajax.php\', \''.$this->equip['eq_code_equip'].'\', \'wire_'.$this->equip['eq_code_equip'].'\', \''.$this->Token.'\');return false;" class="iconaction">';
		$valHtml .= '<i class="'.($this->iconon!='' ?$this->iconon :'jeedom-thermometre-celcius').'"></i></a></span>'; // Température
		$valHtml .= '<br><span id="wire_'.$this->equip['eq_code_equip'].'" class="iconValeur">'.$this->equip['eq_valeur'].' °C';
		$valHtml .= '</span></div>';
		/* On retourne la DIV */
		return $valHtml;
	}
	
	/**
	 * Fonction Privée qui permet de créer la DIV d'affichage de Commande RF433.
	 * @return string = Code HTML de l'objet RF433
	 */
	private function creeDivRF() {
		/* Si icones d'action personnalisés, on les enregistre */
		$actionon = $this->valEquip['actionon'];
		$actionoff = $this->valEquip['actionoff'];
		
		/* Création du code HTML */
		$valHtml = '<div class="equipement '.$this->dyncss.'">';
		$valHtml .= $this->equip['eq_nom'].'<hr class="iconinfo">';
		$valHtml .= '<div class="rf-groupe"><div class="rf-icon"><span class="iconSel">';
		if ($this->equip['eq_valeur'] == 'on') { 
			$valHtml .= '<i id="led_'.$this->equip['eq_code_equip'].'" class="icon '.($this->iconon!='' ?$this->iconon :'jeedom-lumiere-on').'"></i></span></div>'; // allumée
		} else {
			$valHtml .= '<i id="led_'.$this->equip['eq_code_equip'].'" class="icon '.($this->iconoff!='' ?$this->iconoff :'jeedom-lumiere-off').'"></i></span></div>'; // eteind
		}
		$valHtml .= '<div class="rf-grp-action"><div class="rf-action"><span class="rf-iconSel">';
		$valHtml .= '<a href="" onclick="return false;" class="btnaction"><i class="fa '.($actionon!='' ?$actionon :'fa-check').'"></i></a>';
		$valHtml .= '</span></div>';
		$valHtml .= '<div class="rf-action"><span class="rf-iconSel">';
		$valHtml .= '<a href="" onclick="return false;" class="btnaction"><i class="fa '.($actionoff!='' ?$actionoff :'fa-times').'"></i></a>';
		$valHtml .= '</span></div></div></div>';
		if ($this->equip['eq_valeur'] == 'on') { 
			$valHtml .= '<div class="rf-text"><span id="infoRf_'.$this->equip['eq_code_equip'].'" class="iconValeur">'.($this->texton!='' ?$this->texton :'Allumé').'</div>';
		} else {
			$valHtml .= '<div class="rf-text"><span id="infoRf_'.$this->equip['eq_code_equip'].'" class="iconValeur">'.($this->textoff!='' ?$this->textoff :'Eteind').'</div>';
		}
		$valHtml .= '</span></div>';
		
		/* On retourne la DIV */
		return $valHtml;
	}

}

?>
