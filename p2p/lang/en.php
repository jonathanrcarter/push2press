<?php

/** This file is part of push2press
  *
  *      @desc English Language file
  *   @package push2press
  *    @author Jonathan Carter <jc@glimworm.com>, Paul Manwaring <paul@glimworm.com>
  * @copyright 2013 glimworm IT BV
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://www.push2press.com
  */

function L($phrase) {
	static $lang = array(
		"runsetup" => "<div>%s</div>Please run <a href='setup.php'>Setup</a>",
		"ADD" => "ADD",
		"Title" => "Title",
		"Pagename" => "Pagename",
		"Caption" => "Caption",
		"Instructions" => "Instructions",
		"All devices registered for push notes" => "All devices registered for push notes",
		"timestamp" => "Last changed on:",
		"osn" => "OS name",	/* values will be : android , iphone, ios, ipad */
		"username" => "username",
		"un" => "username",
		"uid" => "Unique ID",
		"gid" => "Group ID",
		"group-id" => "Member of:",
		"group-name" => "group-name",
		"gname" => "gname",
		"Send Push Notification" => "Send Push Notification",
		"Device" => "Device",
		"Name of os" => "Name of os",
		"Notification" => "Notification",
		"Mesage Description" => "Mesage Description",
		"Recent messages" => "Recent messages",
		"time" => "time",
		"Text" => "Text",
		"Status" => "Status",
		"List of Cats" => "List of Cats",
		"image" => "image",
		"New cat" => "New cat",
		"DELETED" => "DELETED",
		"Showing Cat #" => "Showing Cat #",
		"List of Config" => "List of Config",
		"New" => "New",
		"UPDATED" => "UPDATED",
		"Showing domain #" => "Showing domain #",
		"New Message" => "New Message",
		"Showing message #" => "Showing message #",
		"image" => "image",
		"bodytext" => "bodytext",
		"List of Pages" => "List of Pages",
		"Volgorde" => "Volgorde",
		"Cat" => "Cat",
		"New page" => "New page",
		"Showing Page #" => "Showing Page #",
		"template" => "template",
		"type" => "type",
		"extraData" => "extraData",
		"bodytext" => "bodytext",
		"CatID" => "CatID",
		"ADD" => "ADD",
		"EDIT" => "EDIT",
		"SEND" => "send",
		"OK" => "OK",
		"preview" => "preview",
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
		"Browse" => "Browse",
		"EDIT" => "EDIT"
	);
	return $lang[$phrase];
}
?>