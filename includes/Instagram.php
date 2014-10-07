<?php
	class Instagram {
		
		public $apiUrl = 'https://api.instagram.com/v1/users/356683473/media/recent/?client_id=11a49a1c2dba429c8576ae60d751d8b5';
		public $feed;
		
		public function __construct() {
			$this->feed = $this->quick_curl();
		}
		
		private function quick_curl($params = '',$debug = true) {
			$ch = curl_init();    // initialize curl handle
			
			curl_setopt($ch, CURLOPT_URL, 'https://api.instagram.com/v1/users/356683473/media/recent/?client_id=11a49a1c2dba429c8576ae60d751d8b5'); // set url to post to
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch); // run the whole process
			if ($debug && curl_errno($ch)) {
				print curl_errno($ch) . ': ' . curl_error($ch);
			}

			curl_close($ch);
			return json_decode($result);
		}
		
	}
?>