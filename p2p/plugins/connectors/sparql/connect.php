<?php


if ( !class_exists('sparql') ) {
	if (session_id() == "") {
		@session_start();
	}
	class sparql {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://api.artsholland.com/sparql?query=";		
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		function sparql ($api_key, $secret = NULL, $die_on_error = false) {
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
			curl_setopt( $ch , CURLOPT_HTTPHEADER, array (
				"Content-Type: application/x-www-form-urlencoded",
				"Accept: application/sparql-results+json"
			));
			
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
			
		}
		
		function sparql_request ($query, $nocache = false)
		{
			
			//	http://www.vam.ac.uk/api/json/museumobject/search?objectnamesearch=necklace			
			$flds = urlencode($query);
			$flds .= "&api_key=85715d4734ee8a22571c6b69a789d8ac";
//			$flds .= "&selectoutput=json";
			
			$this->url = $this->rest_endpoint. $flds;
			
			$result = $this->get($this->url);

			$this->raw = $result;
			$this->response = json_decode($result,true);
			$this->parsed_response = json_decode($result,true);
			return $this->response;
		}		


		function search ($text = "") {
			$this->sparql_request($text);
			return $this->parsed_response ? $this->parsed_response : false;
		}

	}


}


?>

<?php





?>