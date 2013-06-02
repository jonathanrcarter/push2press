<?php



if ( !class_exists('artsholland') ) {
	if (session_id() == "") {
		@session_start();
	}
	class artsholland {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://api.artsholland.com/rest/";		
		var $rest_endpoint_data = "http://http://data.artsholland.com/";		
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		
		function artsholland ($api_key, $secret = NULL, $die_on_error = false) {
			//The API Key must be set before any calls can be made.  You can
			//get your own at http://www.flickr.com/services/api/misc.api_keys.html
			$this->api_key = $api_key;
			$this->secret = $secret;
			$this->die_on_error = $die_on_error;
			$this->service = "artsholland";

			//Find the PHP version and store it for future reference
			$this->php_version = explode("-", phpversion());
			$this->php_version = explode(".", $this->php_version[0]);
		}
		
		


		function requestdata ($action, $args = array(), $nocache = false)
		{
			//Sends a request to Flickr's REST endpoint via POST.

			//Process arguments, including method and login data.



			//	http://api.artsholland.com/rest/venue.json
			//		&locality=amsterdam
			//		&pretty=true
			//		&lang=en
			//		&per_page=15
			//		&apiKey=9cbce178ed121b61a0797500d62cd440	

			
			$flds = $action."&apiKey=" . $this->api_key;
			
			foreach ($args as $key => $value) {
				$flds = $flds . "&".$key."=".$value;
			}
			$this->url = $this->rest_endpoint_data. $flds;
			
			
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
			$this->response = json_decode($result,true);
			$this->parsed_response = json_decode($result,true);
			return $this->response;
		}	
		
		
		function request ($action, $args = array(), $nocache = false)
		{
			$flds = $action."&apiKey=" . $this->api_key;

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