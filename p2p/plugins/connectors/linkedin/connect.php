<?php


if ( !class_exists('linkedin') ) {
	if (session_id() == "") {
		@session_start();
	}
	class linkedin {
		var $API_KEY = '';
		var $API_SECRET = '';
		var $SCOPE = "";
		var $ETC = "";
		var $access_token = "";
		var $expires_at = 0;
		var $expires_in = 0;
		var $state = "";
		var $service = "linkedin";
		var $json_raw = "";

		function linkedin ($api_key = "", $secret = "", $scope = "", $etc = "../../../etc/", $die_on_error = false) {

			$this->API_KEY = $api_key;
			$this->API_SECRET = $secret;
			$this->SCOPE = $scope;
			$this->ETC = $etc;

			$this->die_on_error = $die_on_error;
			$this->service = "linkedin";
			//Find the PHP version and store it for future reference
			$this->php_version = explode("-", phpversion());
			$this->php_version = explode(".", $this->php_version[0]);
		}
		
		function etc($etc) {
			$this->ETC = $etc;
		}
		function set($access_token, $expires_in, $expires_at, $state) {
			$this->access_token = $access_token;
			$this->expires_in = $expires_in;
			$this->expires_at = $expires_at;
			$this->state = $state;
		}
		
		function save() {
			$obj = array(
				"API_KEY" => $this->API_KEY,
				"API_SECRET" => $this->API_SECRET,
				"access_token" => $this->access_token,
				"expires_in" => $this->expires_in,
				"expires_at" => $this->expires_at,
				"state" => $this->state
			);
			file_put_contents($this->ETC.$this->service.".json", json_encode($obj,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE));
		}

		function filename() {
			$this->filename = $this->ETC.$this->service.".json";
		}
		
		function load() {
			try {
				$this->filename = $this->ETC.$this->service.".json";
				$json = file_get_contents($this->filename);
				$ob = json_decode($json);

				$this->API_KEY = $ob->API_KEY;
				$this->API_SECRET = $ob->API_SECRET;
				$this->access_token = $ob->access_token;
				$this->expires_in = $ob->expires_in;
				$this->expires_at = $ob->expires_at;
				$this->state = $ob->state;
				
			} catch (Exception $e)  {
				print $e;
			}
		}

		function clear() {
			$this->set('',0,0,'');
		}
		function check() {
			$this->load();
		    if ((empty($this->expires_at)) || (time() > $this->expires_at)) {
		        // Token has expired, clear the state
		        $this->clear();
		        return false;
		    }
		    if (empty($this->access_token)) {	
		    	return false;
		    }
		    return true;
			
		}
		function fetch($method, $resource, $body = '') {
//			if ($this->check() == false) return false;
			
    		$params = array('oauth2_access_token' => $this->access_token,
    		                'format' => 'json',
        		      );
     
    		// Need to use HTTPS
		    $url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);

		    $this->url = $url;
		    
		    // Tell streams to make a (GET, POST, PUT, or DELETE) request
		    $context = stream_context_create(
		                    array('http' => 
		                        array('method' => $method,
		                        )
		                    )
		                );
 
		    // Hocus Pocus
		    $response = file_get_contents($url, false, $context);
 			
		    // Native PHP object, please
		    return json_decode($response);
		}		

	}
	
}







?>