<?php



if ( !class_exists('googlecal') ) {
	if (session_id() == "") {
		@session_start();
	}
	class googlecal {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://www.google.com/calendar/feeds/";
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		
		function googlecal ($api_key, $secret = NULL, $die_on_error = false) {
			//The API Key must be set before any calls can be made.  You can
			//get your own at http://www.flickr.com/services/api/misc.api_keys.html
			$this->api_key = $api_key;
			$this->secret = $secret;
			$this->die_on_error = $die_on_error;
			$this->service = "googlecal";

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
			$this->response = json_decode($result,true);
			$this->parsed_response = json_decode($result,true);
			return $this->response;
		}		

		function search ($text = "") {
			//	http://www.google.com/calendar/feeds/2a805oltro8qj9i0eklanved9o%40group.calendar.google.com/public/full?alt=json

			$this->request($text."/public/full?", array(
				"alt" => "json"
			));
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		
	}


}


?>