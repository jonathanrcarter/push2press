<?php

/** This file is part of push2press
  *
  *      @desc French Language file
  *   @package push2press
  *    @author Jonathan Carter <jc@glimworm.com>, Paul Manwaring <paul@glimworm.com>, Jeroen van der Linde (jeroenvdl@glimworm.com)
  * @copyright 2013 glimworm IT BV
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://www.push2press.com
  */

function L($phrase) {
	static $lang = array(
		"runsetup" => "<div>%s</div>Exécutez le <a href='setup.php'>Setup</a>",
		"ADD" => "AJOUTER",
		"Title" => "Titre",
		"Pagename" => "Nom de la page",
		"Caption" => "Légende",
		"Instructions" => "Instructions",
		"All devices registered for push notes" => "Tous les appareils enregistrés pour pushnotes",
		"timestamp" => "Dernier modification:",
		"osn" => "nom de SO",
		"username" => "Nom d'utilisateur",
		"un" => "Nom d'utilisateur",
		"uid" => "Unique ID",
		"gid" => "Group ID",
		"group-id" => "Member of:",
		"group-name" => "Nom de groupe",
		"gname" => "gname",
		"Send Push Notification" => "Envoyez notification push",
		"Device" => "Appareil",
		"Name of os" => "Name of SO",
		"Notification" => "Notification",
		"Mesage Description" => "Descriptif des messages",
		"Recent messages" => "Messages récents",
		"time" => "Heure",
		"Text" => "Texte",
		"Status" => "Statut",
		"List of Cats" => "List des catégories",
		"image" => "Image",
		"New cat" => "Nouvelle catégorie",
		"DELETED" => "Effacé",
		"Showing Cat #" => "Montrant catégorie #",
		"List of Config" => "Liste des configurations",
		"New" => "Nouveau",
		"UPDATED" => "Mis  à jour",
		"Showing domain #" => "Montrant domaine #",
		"New Message" => "Nouveau message",
		"Showing message #" => "Montrant message #",
		"bodytext" => "Texte",
		"List of Pages" => "Liste des pages",
		"Volgorde" => "Ordre",
		"Cat" => "Cat",
		"New page" => "Nouvelle page",
		"Showing Page #" => "Montrant page #",
		"template" => "template",
		"type" => "type",
		"extraData" => "extraData",
		"bodytext" => "bodytext",
		"CatID" => "CatID",
		"ADD" => "Ajouter",
		"EDIT" => "Editer",
		"SEND" => "Envoyer",
		"OK" => "OK",
		"preview" => "Exemple",
		"draft_messages" => "Ebauches des messages",
		"sent_messages" => "Messages envoyés",
		"all_messages" => "Tous les messages",
		"Date" => "Date",
		"options" => "Options",
		"DELETE" => "Effacez",
		"PREVIEW" => "PExemple",
		"List_conf" => "Configuration du système",
		"log_phone" => "Appareils enregistrés",
		"groupz" => "Liste des groupes",
		"catz" => "Catégories",
		"mesz" => "Messages",
		"pagz" => "Pages",
		"Check_devices" => "Vérifiez les appareils",
		"List_cats" => "Montrez toutes les catégories",
		"List_pages" => " Montrez toutes les pages",
		"STATS" => "Montrez les statistiques",
		"COMPOSE" => "Composer",
		"SS" => "Sauvegarder et envoyer",
		"Browse" => "Parcourir",
		"EDIT" => "Editer"
	);
	return $lang[$phrase];
}
?>
