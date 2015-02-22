# Client WsPiDroid

Le client WsPiDroid est un client Web Php pour le Web-Service du même nom.

Le client WsPiDroid permet d'interagir avec les ports GPIO d'un Raspberry Pi, sur lequel est installé le Web-Service WsPiDroid. Il est également possible de lire des sondes de température de type DS18b20, ainsi que de rebooter ou arreter proprement le Raspberry Pi.

Le client Web écrit en Php est de type Responsive. Cet technologie permet ainsi d'être utilisé et de rester convivial aussi bien sur un navigateur d'ordinateur que sur tablette ou smartphone. L'affichage s'adapte en fonction du navigateur.

# Fonctionnalités du client
## Constitution du client Php
 - La bibliothèque NuSoap. Permet d'invoquer le Web-service WsPiDroid.
 - La page "**index.php**" : Page principale du site.
 - La page de configuration "**config.inc.php**".
 - Les pages de contenues : "**action.php**", "**configuration.php**" et "**objet.php**". Cette dernière n'étant pas encore implantée.
 - La feuille de style CSS du site.
 - Deux fichiers Java-Script, une pour les mises à jour via la technologie Ajax, l'autre pour la gestion du menu.
 - Un fichier "**update-ajax.php**" utilisé pour les mises à jours via Ajax.
 - Un fichier "**config.mod.php**", qui est un fichier modéle permettant la mise à jour du fichier "**config.inc.php**", depuis la page de configuration du site.

## Fonctionnalités Utilisateurs
 - Lecture des ports GPIO déclarés dans le Web-Service
 - Ecriture sur les ports GPIO (marche ou arrêt)
 - Lecture des sondes de température de type DS18b20 utilisant le bus "**1-wire**" du Raspberry Pi.
 - Arrêt "propre" (shutdown) ou Reboot du Raspberry Pi
 - Identification ou pas pour accèder à l'interface.

## Fonctionnalités Technique
 - Ecrit en PHP
 - Utilise la technologie Ajax
 - Théme Responsive

# licence
<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">WsPiDroid</span> est mis à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">licence Creative Commons Attribution - Pas d&#39;Utilisation Commerciale - Pas de Modification 4.0 International</a>.

