<?php



if ( !class_exists('meetup') ) {
	if (session_id() == "") {
		@session_start();
	}
	class meetup {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://api.meetup.com/";
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		
		function meetup ($api_key, $secret = NULL, $die_on_error = false) {
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

		function search ($group_urlname = "", $offset = "0", $text = "") {
			//http://api.meetup.com/2/events?
			//	status=upcoming
			//	&order=time
			//	&limited_events=False
			//	&group_urlname=Appsterdam
			//	&desc=false
			//	&offset=0
			//	&format=json
			//	&page=20
			//	&fields=
			//	&sig_id=705093
			//	&sig=364a5e8613c47dcc0283fc2d370ee291e27963c4
		
//https://api.meetup.com/events.json?key=ABDE12456AB2324445&group_urlname=ny-tech&sign=true		

https://api.meetup.com/2/events?&sign=true&group_urlname=Appsterdam&page=20&offset=2&key=162d67f467f223e68724c3266627767

		
			$this->request($text."2/events?", array(
				"status" => "upcoming",
				"order" => "time",
				"limited_events" => "false",
				"group_urlname" => $group_urlname,
				"desc" => "false",
				"fields" => "",
				"page" => "20",
				"offset" => $offset,
				"sign" => "true",
				"key" => $this->api_key,
				"format" => "json"
			));
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		
	}


}


?>