<?php
	include('includes/ParseXML.php');
	class Merch {
		public $img_path;
		public $product_path;
		public $api_url;
		public $merchItems;
		
		public function __construct() {
			$this->img_path = 'http://d3eum8lucccgeh.cloudfront.net/designs/%ID%/%IMAGE%';
			$this->product_path = 'http://www.districtlines.com/%PRODUCT_ID%-%PRODUCT_CLEAN%/%VENDOR_CLEAN%';
			$this->api_url = 'http://www.districtlines.com/api/';
			
			$this->merchItems = $this->curl_load($this->api_url,'action=products&q_vendor_id=1886&q_featured=1&o_date_added=DESC&limit=4&q_album_id=0&o_random=1');
		}
		
		public function image($id,$image = null,$prefix = '') {
			return str_replace('%IMAGE%',$prefix . $image,str_replace('%ID%',$id,$this->img_path));
		}
		
		public function product($id) {
			if (is_array($id)) {
				$path = $this->product_path;
		
				foreach ($id as $var => $val) {
					$path = str_replace('%' . strtoupper($var) . '%',$val,$path);
				}
		
				return $path;
			} else {
				return str_replace('%ID%',$id,$this->product_path);
			}
		}
		
		private function quick_curl($url,$params = '',$debug = false) {
			$ch = curl_init();    // initialize curl handle
		
			curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
			//curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects (security issue i guess)
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
			curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 5s
			curl_setopt($ch, CURLOPT_POST, 1); // set POST method
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params); // add POST fields
			$result = curl_exec($ch); // run the whole process
			
			if ($debug && curl_errno($ch)) {
				print curl_errno($ch) . ': ' . curl_error($ch);
			}
			
			curl_close($ch);
			return $result;
		}
		
		private function curl_load($url,$params = '',$debug = false) {
			$result = $this->quick_curl($url,$params,$debug);
			$parser = new ParseXML;
			$rows = $parser->parse($result);
			if ($debug) {
				var_dump($rows);
			}
			return $rows['DATA'][0]['ROWS'][0]['ROW'];
		}
		
	}