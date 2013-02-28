<?php
/** This file is part of push2press
  *
  *      @desc English Language file
  *   @package push2press
  *    @author Jonathan Carter <jc@glimworm.com>
  * @copyright 2013 glimworm IT BV
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://www.push2press.com
  */


function L($phrase) {
	static $lang = array(
		"runsetup" => "<div>%s</div>Please run <a href='setup.php'>Setup</a>",
		"ADD" => "TOEVOEGEN",
		"Title" => "Titel",
		"Pagename" => "Paginanaam",
		"Caption" => "Onderschrift",
		"Instructions" => "Instructies",
		"All devices registered for push notes" => "Alle apparaten geregistreerd voor push notities",
		"timestamp" => "tijdstempel",
		"osn" => "osn",
		"username" => "gebruikersnaam",
		"un" => "gebruikersnaam",
		"uid" => "uid",
		"gid" => "gid",
		"group-id" => "groep-id",
		"group-name" => "groep-naam",
		"gname" => "gnaam",
		"Send Push Notification" => "Stuur Push Notificatie",
		"Device" => "Apparaat",
		"Name of os" => "Naam van de os",
		"Notification" => "Notificatie",
		"Mesage Description" => "Berecht Beschrijving",
		"Recent messages" => "Recente beschrijving",
		"time" => "tijd",
		"Text" => "Tekst",
		"Status" => "Toestand",
		"List of Cats" => "Lijst van Cats",
		"image" => "beeld",
		"New cat" => "Nieuw cat",
		"DELETED" => "VERWIJDERD",
		"Showing Cat #" => "Tonen Cat #",
		"List of Config" => "Lijst met Config",
		"New" => "Nieuw",
		"UPDATED" => "BIJGEWERKT",
		"Showing domain #" => "Tonen domein #",
		"New Message" => "Nieuw Bericht",
		"Showing message #" => "Tonen Bericht #",
		"image" => "beeld",
		"bodytext" => "hoofdtekst",
		"List of Pages" => "Lijst van Pagina's",
		"Volgorde" => "Volgorde",
		"Cat" => "Cat",
		"New page" => "Neiuwe Pagina",
		"Showing Page #" => "Tonen Pagina #",
		"template" => "sjabloon",
		"type" => "type",
		"extraData" => "extraData",
		"bodytext" => "hoofdtekst",
		"CatID" => "CatID",
		"ADD" => "TOEVOEGEN",
		"EDIT" => "BEWERKEN",
		"SEND" => "send",	//pm
		"OK" => "OK",
		"preview" => "voorbeeld",
		/* all new */
		"draft_messages" => "Draft messages",
		"sent_messages" => "Sent messages",
		"all_messages" => "All messages",
		"Date" => "Created",
		"options" => "Options",
		"DELETE" => "Delete",
		"PREVIEW" => "Preview",
		"List_conf" => "System configuration",
		"log_phone" => "Registered devices",
		"groupz" => "List of groups",
		"catz" => "Categories",
		"mesz" => "Messages",
		"pagz" => "Pages",
		"Check_devices" => "Check devices",
		"List_cats" => "List all categories",
		"List_pages" => "List all pages",
		"STATS" => "Show statistics",
		"COMPOSE" => "Send",
		"SS" => "Save & Send",
		/* to here */
		"Browse" => "Bladeren",
		"EDIT" => "BEWERKEN"
	);
	return $lang[$phrase];
}
?>