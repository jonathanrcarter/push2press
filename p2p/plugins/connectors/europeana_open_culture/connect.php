<?php



if ( !class_exists('europeana_connect') ) {
	if (session_id() == "") {
		@session_start();
	}
	class europeana_connect {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://jon632.glimworm.com/europeana/euv2.php";		
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		
		function europeana_connect ($api_key, $secret = NULL, $die_on_error = false) {
			//The API Key must be set before any calls can be made.  You can
			//get your own at http://www.flickr.com/services/api/misc.api_keys.html
			$this->api_key = $api_key;
			$this->secret = $secret;
			$this->die_on_error = $die_on_error;
			$this->service = "europeana";

			//Find the PHP version and store it for future reference
			$this->php_version = explode("-", phpversion());
			$this->php_version = explode(".", $this->php_version[0]);
		}
		
		
		
		function request ($action, $args = array(), $nocache = false)
		{
			//Sends a request to Flickr's REST endpoint via POST.

			//Process arguments, including method and login data.
			
			$flds = "?action=".$action."&lang=en&query=&mls=n&page=0";
			
			foreach ($args as $key => $value) {
				$flds = $flds . "&".$key."=".$value;
			}
			$this->url = $this->rest_endpoint. $flds;
			
			
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL,  $this->url);
			curl_setopt( $ch, CURLOPT_POST, false );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

//			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
//			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

			// Execute post
			$result = curl_exec($ch);

			// Close connection
			curl_close($ch);

			$this->raw = $result;
			$this->response = json_decode($result);
			$this->parsed_response = json_decode($result);
			return $this->response;
		}

		function makesimpleitems() {
		
			$timthumb = str_replace("/plugins/connectors/vistory/","/timthumb.php",urlpath());
			$p2parray = array();
			
			if ($this->parsed_response) {
				for ($i=0; $i < count($this->parsed_response->data->items); $i++) {
					$rec = $this->parsed_response->data->items[$i];

					$obj = new obj();
					$obj->title = $rec->title;
					$obj->subtitle = $rec->description;
					$obj->description = $rec->description;
					$obj->identifier = $rec->id;

					$obj->image = $rec->image;
					$obj->dataProvider = $rec->dataProvider;
					$obj->rights = $rec->rights;
					$obj->dcCreator = $rec->dcCreator;
//					$obj->image = $rec->enclosure;
					$obj->summary = new obj();
					$obj->summary->lines = array(
						"p:  ",
						"img:".$obj->image
//						"h1:".$obj->title,
//						"p:".$obj->subtitle,
//						"p:  "
					);
					$obj->details = new obj();
					$obj->details->lines = array(
						"p:  ",
						"p:  ",
						"img:".$rec->enclosure,
						"h1:".$obj->title,
						"p:".$obj->description,
						"p:".$obj->dataProvider,
						"p:".$obj->dcCreator,
						"p:".$obj->rights,
						"p:  "
//						"xmovie:".$obj->image320 .",".$rec->mp4small,
					);
					
					array_push($p2parray, $obj);
				
				}
				$this->parsed_response = array(
					"raw" => $this->parsed_response,
					"p2p" => $p2parray
				);
			
			}
		
		
		}




		function search ($text = "",$theme = "") {
			// http://jon632.glimworm.com/europeana/euv2.php?action=json-srch&lang=en&query=&mls=n&page=0&srch=vermeer&type=
			$this->request();
			if ($theme === "art") {
				$theme = "1";
			} else {
				$theme = "";
			}
			$this->request("json-srch", array("srch" => $text, "query" => $theme));
			$this->makesimpleitems();
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		function getclip ($id = "oai:openimages.eu:1703") {
			// http://jon632.glimworm.com/europeana/euv2.php?action=json-get&lang=en&identifier=/2021608/dispatcher_aspx_action_search_database_ChoiceCollect_search_priref_2205
			$this->request("json-get", array("identifier" => $id));
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		
	}


}


?>