<?
	/*
	
			redirect
	
	*/
	function redirect($location) {
		header('Location: ' . $location);
		exit;
	}
	
	
	
	/*
	
			is logged in
	
	*/
	function is_logged_in() {
		if ($_SESSION['logged_in_id']) {
			return true;
		}
		
		return false;
	}
	
	
	
	
	/*
	
			is admin
	
	*/
	function is_admin() {
		if (get_one('SELECT `is_admin` FROM `users` WHERE `id` = \'' . $_SESSION['logged_in_id'] . '\' LIMIT 1')) {
			return true;
		}
		
		return false;
	}
	
	
	
	
	/*
	
			validate
	
	*/
	function validate($field, $required = true, $options = array()) {
		$value = $_REQUEST[$field];
		$_SESSION['form_data'][$field] = $value;
		$name = isset($options['label']) ? $options['label'] : ucwords(str_replace('_',' ',$field));
		
		
		//required
		if ($required && !$value && $field != "photo") {
			$_SESSION['form_error'][$field] = '"' . $name . '" - This field is required.';
		}
		
		
		//md5
		if (is_option('md5',$options)) {
			$_SESSION['form_data'][$field] = md5($value);
		}
		
		
		//datetime
		if (is_option('datetime',$options) && is_array($value)) {
			$_SESSION['form_data'][$field] = date('Y-m-d H:i:s', strtotime(
				$value['month'] . '/' . $value['day'] . '/' . $value['year'] . ' ' . $value['hour'] . ':' . $value['minute'] . ':' . $value['second'] . ' ' . $value['ampm']
			));
		}
      
      
      //date
      if (is_option('date',$options) && is_array($value)) {
			$_SESSION['form_data'][$field] = date('Y-m-d', strtotime(
				$value['month'] . '/' . $value['day'] . '/' . $value['year']
			));
		}
		
	//time
	 if (is_option('time',$options) && is_array($value)) {
			$_SESSION['form_data'][$field] = date('H:i:s', strtotime(
				$value['hour'] . ':' . $value['minute'] . ':' . $value['second'] . ' ' .$value['ampm']
			));
		}
		
		
		//implode
		if (is_option('implode',$options) && is_array($value)) {
			$_SESSION['form_data'][$field] = implode($options['implode']['glue'], $value);
		}
		
		
		//upload
		if (is_option('upload',$options)) {
			if (!is_uploaded_file($_FILES[$field]['tmp_name'])) {
				unset($_SESSION['form_data'][$field]);
				return;
			}
			
			$value = $_FILES[$field]['name'];
			$new_value = ereg_replace('([^A-Za-z0-9_\. -]+)', '_', $value);
			
			$_SESSION['form_data'][$field] = $new_value;
			
			if (is_array($options['upload']) && isset($options['upload']['id'])) {
				$folder = '../uploads/' . section . '/' . $options['upload']['id'];
				
				if (mkdir_recursive($folder)) {
					move_uploaded_file($_FILES[$field]['tmp_name'], $folder . '/' . $new_value);
				} else {
					die('Could not make folder "' . $folder . '"');
				}
				
				
				if (is_option('thumbnails',$options)) {
					foreach ($options['thumbnails'] as $thumb_name => $thumb) {
						make_thumbnail($folder . '/' . $new_value,$folder . '/' . $thumb_name . '_' . $new_value,$thumb['width'],$thumb['height'],$thumb['scale_type']);
					}
				}
			}
		}
	}
	
	
	
	
	/*
	
			mkdir recursive
	
	*/
	function mkdir_recursive($pathname, $mode = 0755) {
		is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname), $mode);
		return is_dir($pathname) || @mkdir($pathname, $mode);
	}	
	
	
	
	
	/*
	
			is option
	
	*/
	function is_option($option, $options) {
		if (isset($options[$option]) || in_array($option,$options)) {
			return true;
		}
		
		return false;
	}
	
	
	
	
	/*
	
			generate fields
	
	*/
	function generate_fields($table) {
		$no_add = array('id','created','modified');
		
		if (get_rows('DESCRIBE `' . $table . '`')) {
			while ($row = get_rows()) {
				if (!in_array($row['Field'], $no_add)) {
					$_listing_fields[] = $row['Field'];
				}
			}
		}
		
		return $_listing_fields;
	}
	
	
	
	
	/*
	
			set times
	
	*/
	function set_times($new = false) {
		$date = date('Y-m-d H:i:s');
		
		$_SESSION['form_data']['modified'] = $date;
		
		if (!$new) {
			$_SESSION['form_data']['created'] = $date;
		}
		
		return $arr;
	}
	
	
	
	
	/*
	
			dump
	
	*/
	function dump($arr) {
		print '<pre style="border: 1px #000 solid; padding: 5px; margin: 5px;">';
		var_dump($arr);
		print '</pre>';
	}
	
	
	
	
	/*
	
			shorten
	
	*/
	function shorten($str, $len, $end = '...') {
		if (strlen($str) > $len) {
			return substr($str, 0, $len) . $end;
		} else {
			return $str;
		}
	}
	
	
	
	
	/*
			
			_array combine
	
	*/
	function _array_combine($keys, $values) {
		$count = min(count($keys),count($values));
		$new_arr = array();
		
		for ($i = 0; $i < $count; $i++) {
			$new_arr[$keys[$i]] = $values[$i];
		}
		
		return $new_arr;
	}
	
	
	
	
	/*
	
			pagination
	
	*/
	function pagination($total, $per_page = 5, $page_path = '', $page_text = '') {
		if ($total <= $per_page) {
			return;
		}
		
		
		$pages = array();
		
		for ($i = 0; $i < $total; $i++) {
			if ($i % $per_page == 0) {
				$page = ($i / $per_page) + 1;
				
				$pages[] = $page;
			}
		}
		
		
		$num_pages = count($pages);
		
		if ($current_page > 1 && $num_pages > 1) {
			print '<a href="' . $page_path . ($current_page - 1) . '">Prev</a> ';
		}
		
		for ($i = 0; $i < $num_pages; $i++) {
			$active = ($pages[$i] == $current_page) ? ' class="active"' : '';
			print '<a' . $active . ' href="' . $page_path . $pages[$i] . '">' . $page_text . $pages[$i] . '</a> ';
		}
	
		if ($num_pages > 1 && $current_page < $num_pages) {
			print '<a href="' . $page_path . ($current_page + 1) . '">Next</a> ';
		}
	}
	
	function recent_activity($id, $title, $content, $type, $created, $modified)
	{
		if($type == 'photo')
		{
		
		}
	}
?>