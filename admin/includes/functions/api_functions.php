<?
	
	function curl_load($url,$params = '',$debug = false) {
		$result = quick_curl($url,$params,$debug);
		$parser = new ParseXML;
		$rows = $parser->parse($result);
		if ($debug) {
			var_dump($rows);
		}
		return $rows['DATA'][0]['ROWS'][0]['ROW'];
	}
	
	
	
	function quick_curl($url,$params = '',$debug = false) {
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
	
	
	
	function image($id,$image = null,$prefix = '') {
		return str_replace('%IMAGE%',$prefix . $image,str_replace('%ID%',$id,img_path));
	}
	
	
	
	function product($id) {
		if (is_array($id)) {
			$path = product_path;
			
			foreach ($id as $var => $val) {
				$path = str_replace('%' . strtoupper($var) . '%',$val,$path);
			}
			
			return $path;
		} else {
			return str_replace('%ID%',$id,product_path);
		}
	}
