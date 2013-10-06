<?php



if ( !class_exists('rss') ) {
	if (session_id() == "") {
		@session_start();
	}
	class rss {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://www.compendiumvoordeleefomgeving.nl/rss/clo-feed.xml";
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		
		function rss ($api_key, $secret = NULL, $die_on_error = false) {
			//The API Key must be set before any calls can be made.  You can
			//get your own at http://www.flickr.com/services/api/misc.api_keys.html
			$this->api_key = $api_key;
			$this->secret = $secret;
			$this->die_on_error = $die_on_error;
			$this->service = "rss";

			//Find the PHP version and store it for future reference
			$this->php_version = explode("-", phpversion());
			$this->php_version = explode(".", $this->php_version[0]);
		}
		
		

		function get($url = "") {
		
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL,  $url);
			curl_setopt( $ch, CURLOPT_POST, false );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
			
		}
		
		function request ($action, $args = array(), $nocache = false)
		{
			$flds = $action;

			foreach ($args as $key => $value) {
				$flds = $flds . "&".$key."=".$value;
			}
			
			$this->url = $this->rest_endpoint. $flds;
			
			$result = $this->get($this->url);

			$this->raw = $result;
			$this->response = simplexml_load_string($result);
			$this->parsed_response = $this->response;
			return $this->response;
		}		

		function search ($text = "") {
			// http://www.compendiumvoordeleefomgeving.nl/rss/clo-feed.xml?i=1

			$this->request($text, array());
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
	}


}


?>