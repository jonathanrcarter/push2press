<?php


if ( !class_exists('victoria_and_albert') ) {
	if (session_id() == "") {
		@session_start();
	}
	class victoria_and_albert {
		var $api_key;
		var $secret;
		
		var $rest_endpoint = "http://www.vam.ac.uk/api/json/museumobject";		
		
		var $req;
		var $response;
		var $parsed_response;
		var $last_request = null;
		var $die_on_error;
		var $error_code;
		Var $error_msg;
		var $token;
		var $php_version;
		
		function victoria_and_albert ($api_key, $secret = NULL, $die_on_error = false) {
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

			foreach ($args as $key => $value) {
				$flds = $flds . "&".$key."=".$value;
			}
			
			//	http://www.vam.ac.uk/api/json/museumobject/search?objectnamesearch=necklace			
			
			$this->url = $this->rest_endpoint. $flds;
			
			$result = $this->get($this->url);

			$this->raw = $result;
			$this->response = json_decode($result,true);
			$this->parsed_response = json_decode($result,true);
			return $this->response;
		}		
		function makesimpleitems() {
//			var_dump($this->parsed_response["meta"]);


			$timthumb = str_replace("/plugins/connectors/victoria_and_albert/","/timthumb.php",urlpath());


			$this->parsed_response["meta"]["a"] = "aaaaa";
			if ($this->parsed_response && $this->parsed_response && $this->parsed_response["records"]) {
				for ($i=0; $i < count($this->parsed_response["records"]); $i++) {
					$rec = $this->parsed_response["records"][$i];
					$obj = new obj();
					$obj->title = $rec["fields"]["object"];
					$obj->subtitle = $rec["fields"]["slug"];
					$obj->identifier = $rec["pk"];
					$obj->object_number = $rec["fields"]["object_number"];
					$obj->location = $rec["fields"]["location"];
					$obj->place = $rec["fields"]["place"];
					$obj->artist = $rec["fields"]["artist"];
					$obj->lat = $rec["fields"]["latitude"];
					$obj->lon = $rec["fields"]["longitude"];
					$obj->date_text = $rec["fields"]["date_text"];
					$obj->collection_code = $rec["fields"]["collection_code"];
					$obj->museum_number = $rec["fields"]["museum_number"];
					$obj->image = sprintf("http://media.vam.ac.uk/media/thira/collection_images/%s/%s.jpg",substr($rec["fields"]["primary_image_id"],0,6), $rec["fields"]["primary_image_id"]);
					$obj->image320 = $timthumb . "?w=320&a=t&zc=1&src=" . $obj->image;
					$obj->thumb2 = $timthumb . "?h=160&w=320&a=t&zc=1&src=" . $obj->image;
					
					$obj->summary = new obj();
					$obj->summary->lines = array(
						//"img:".$obj->image320,
						"img:".$obj->thumb2,
						"h1:".$obj->title,
						"p:".$obj->subtitle,
						"p:  "
					);


					$obj->details = new obj();
					$obj->details->lines = array(
						"h1:".$obj->title,
						"zimg:".$obj->image320 . "," . $obj->image,
						"p:".$obj->subtitle
					);
					
					
					$this->parsed_response["records"][$i]["p2pitem"] = $obj;
					$this->parsed_response["meta"]["b"] = $i;
				
				}
			
			}
		
		
		}

		function search ($text = "") {
			$this->request();
			$this->request("/search?", array("q" => $text));
			$this->makesimpleitems();
			return $this->parsed_response ? $this->parsed_response : false;
		}

		function search_object_name ($text = "") {
			$this->request();
			$this->request("/search?", array("objectnamesearch" => $text));
			$this->makesimpleitems();
			return $this->parsed_response ? $this->parsed_response : false;
		}

		function search_name ($text = "") {
			$this->request();
			$this->request("/search?", array("namesearch" => $text));
			$this->makesimpleitems();
			return $this->parsed_response ? $this->parsed_response : false;
		}

		function search_place ($text = "") {
			$this->request();
			$this->request("/search?", array("objectnamesearch" => $text));
			$this->makesimpleitems();
			return $this->parsed_response ? $this->parsed_response : false;
		}
		
	}


}


?>

<?php





?>