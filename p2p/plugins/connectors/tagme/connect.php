<?php


if ( !class_exists('tagme') ) {
	if (session_id() == "") {
		@session_start();
	}
	class tagme {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://tagme.di.unipi.it/";		
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		function tagme ($api_key, $secret = NULL, $die_on_error = false) {
			//The API Key must be set before any calls can be made.  You can
			//get your own at http://www.flickr.com/services/api/misc.api_keys.html
			$this->api_key = $api_key;
			$this->secret = $secret;
			$this->die_on_error = $die_on_error;
			$this->service = "ramm";

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
			$flds = $flds . "&key=".$this->api_key;

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

		function search ($q = "") {
			$this->request("tag?", array(
				"lang"=>"en",
				"text"=>$q,
				"rho"=>"50"
			));
			
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
	}


}


?>

<?php





?>