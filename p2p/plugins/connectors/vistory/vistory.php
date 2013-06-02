<?php



if ( !class_exists('vistory_connect') ) {
	if (session_id() == "") {
		@session_start();
	}
	class vistory_connect {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://site1504.glimworm.com/ext_chunk.jsp";		
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		
		function vistory_connect ($api_key, $secret = NULL, $die_on_error = false) {
			//The API Key must be set before any calls can be made.  You can
			//get your own at http://www.flickr.com/services/api/misc.api_keys.html
			$this->api_key = $api_key;
			$this->secret = $secret;
			$this->die_on_error = $die_on_error;
			$this->service = "vistory";

			//Find the PHP version and store it for future reference
			$this->php_version = explode("-", phpversion());
			$this->php_version = explode(".", $this->php_version[0]);
		}
		
		
		
		function request ($action, $args = array(), $nocache = false)
		{
			//Sends a request to Flickr's REST endpoint via POST.

			//Process arguments, including method and login data.
			
			$flds = "?chunk=stproc:apps4nl:".$action;
			
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
				for ($i=0; $i < count($this->parsed_response); $i++) {
					$rec = $this->parsed_response[$i];

					$obj = new obj();
					$obj->title = $rec->title;
					$obj->subtitle = $rec->description;
					$obj->description = $rec->description;
					$obj->identifier = $rec->identifier;

					/*
					$obj->object_number = $rec["fields"]["object_number"];
					$obj->location = $rec["fields"]["location"];
					$obj->place = $rec["fields"]["place"];
					$obj->artist = $rec["fields"]["artist"];
					$obj->date_text = $rec["fields"]["date_text"];
					$obj->collection_code = $rec["fields"]["collection_code"];
					$obj->museum_number = $rec["fields"]["museum_number"];
					*/
					$obj->image = $rec->thumb;
					$obj->image320 = $rec->thumb;
					$obj->thumb2 = $rec->thumb;
					$obj->summary = new obj();
					$obj->summary->lines = array(
						"img:".$obj->thumb2,
						"h1:".$obj->title,
						"p:".$obj->subtitle,
						"p:  "
					);
					$obj->details = new obj();
					$obj->details->lines = array(
						"h1:".$obj->title,
						"movie:".$rec->mp4small,
						"p:".$obj->description
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
		
		
		function search ($text = "") {
			// http://site1504.glimworm.com/ext_chunk.jsp?chunk=stproc:apps4nl:script&action=search&srch=amsterdam
			$this->request();
			$this->request("search", array("srch" => $text));
			$this->makesimpleitems();
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		function getclip ($id = "oai:openimages.eu:1703") {
			// http://site1504.glimworm.com/ext_chunk.jsp?chunk=stproc:apps4nl:script&action=getclip&identifier=oai:openimages.eu:1703
			$this->request("getclip", array("identifier" => $id));
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		
	}


}


?>