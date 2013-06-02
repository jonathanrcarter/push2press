<?php



if ( !class_exists('ramm_museum') ) {
	if (session_id() == "") {
		@session_start();
	}
	class ramm_museum {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://rammcollections.org.uk/api";		
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		
		function ramm_museum ($api_key, $secret = NULL, $die_on_error = false) {
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
		
		function login ($username = "", $password = "") {
			$this->username = $username;
			$this->password = $password;
			$response = $this->get($this->$rest_endpoint."/GetUserToken?username=".$username."&Password=".$password);
			$this->login = $response;
			
		}
		
		function request ($action, $args = array(), $nocache = false)
		{
			$flds = $action."&apiKey=" . $this->api_key;

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



		function search_locality ($text = "") {

			//	http://api.artsholland.com/rest/venue.json
			//		&locality=amsterdam
			//		&pretty=true
			//		&lang=en
			//		&per_page=15

			$this->request();
			$this->request("venue.json?", array(
				"locality" => $text,
				"pretty" => "true",
				"lang" => "en",
				"per_page" => "100"
			));
			return $this->parsed_response ? $this->parsed_response : false;
		}

		function search ($text = "") {
			// http://site1504.glimworm.com/ext_chunk.jsp?chunk=stproc:apps4nl:script&action=search&srch=amsterdam
			$this->request();
			$this->request("search", array("srch" => $text));
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		function production ($id = "") {
		
			//	http://api.artsholland.com/rest/production/0006816f-426c-4e43-8ac4-c8376f4dc3b4.json?
			//		&lang=any
			//		&apiKey=9cbce178ed121b61a0797500d62cd440	
			/*
			{

			results: [
			{
				uri: "http://data.artsholland.com/production/0006816f-426c-4e43-8ac4-c8376f4dc3b4",
				attachment: "http://data.artsholland.com/production/0006816f-426c-4e43-8ac4-c8376f4dc3b4/attachment/1",
				cidn: "0006816f-426c-4e43-8ac4-c8376f4dc3b4",
				genre: "http://purl.org/artsholland/1.0/GenrePopmusic",
				languageNoProblem: "false",
				productionType: "http://purl.org/artsholland/1.0/ProductionTypePerformance",
				created: "2012-03-09T21:53:29Z",
				modified: "2012-03-09T21:53:29Z",
				type: "http://purl.org/artsholland/1.0/Production",
				sameAs: "http://resources.uitburo.nl/productions/0006816f-426c-4e43-8ac4-c8376f4dc3b4",
				homepage: "http://www.primalscream.net"
			}]
			}

			*/
				
			$this->requestdata("production/".$id.".json?", array(
				"lang" => "any"
			));
		}
			
		function nearby_venues($lat ="52.3729", $lon = "4.8931", $dist = "2500") {
		
			//	http://api.artsholland.com/rest/venue.json?
			//		&nearby=POINT(4.8931 52.3729)
			//		&distance=2500
			//		&apiKey=9cbce178ed121b61a0797500d62cd440
			$this->request("venue.json?", array(
				"nearby" => "POINT(".$lon."%20".$lat.")",
				"distance" => $dist,
				"per_page" => "100"
			));
		
		}
		
		function getclip ($id = "oai:openimages.eu:1703") {
			// http://site1504.glimworm.com/ext_chunk.jsp?chunk=stproc:apps4nl:script&action=getclip&identifier=oai:openimages.eu:1703
			$this->request("getclip", array("identifier" => $id));
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
		
	}


}


?>

<?php





?>