<?php



if ( !class_exists('rijksmuseum_muse') ) {
	if (session_id() == "") {
		@session_start();
	}
	class rijksmuseum_muse {
		var $api_key;
		var $secret;
		
//		var $rest_endpoint = "http://jon651.glimworm.com/europeana/rijksmuseum.php";		
		var $rest_endpoint = "http://localhost/europeana/rijksmuseum.php";		
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		
		function rijksmuseum_muse ($api_key, $secret = NULL, $die_on_error = false) {
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
			
			$flds = "?action=".$action;
			
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

		function search ($text = "") {
			// http://site1504.glimworm.com/ext_chunk.jsp?chunk=stproc:apps4nl:script&action=search&srch=amsterdam
			$this->request();
//			$this->request("search", array("srch" => $text));
			return $this->parsed_response ? $this->parsed_response : false;
		}
		function getgame ($type = "") {
			// http://jon651.glimworm.com/europeana/rijksmuseum.php?action=json-get-game
			$this->request();
			$this->request("json-get-game", array("type" => $type));
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		
		
		
		
	}


}


?>