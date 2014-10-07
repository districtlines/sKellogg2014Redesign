<?php
	
	function is_authenticated() {
		$data = array('coupon_code' => $_SESSION['coupon_code']);
		$exists = api_call('coupon_exists',$data);
		
		if($exists == 'true') {
			return true;
		} else {
			return false;
		}
	}
	
	function redeem_errors() {
		$errors = '';
		if($_SESSION['invalid_coupon']) { 
			$errors = $_SESSION['invalid_coupon'];
			unset($_SESSION['invalid_coupon']);
		}
		if($_SESSION['checkout_errors']) {
			$errors = $_SESSION['checkout_errors'];			
			unset($_SESSION['checkout_errors']);
		}
		if($errors) {
			echo '<p class="redeem_errors">' . $errors . '</p>';
		}
	}
	
	function styles($data = null) {
		if(!$data) {echo '';}
		echo '<style>';
		foreach($data as $selector => $attributes) {
			echo $selector . " { \n";
			foreach($attributes as $k => $v) { echo "\t$k: $v; \n";}
			echo "}\n\n";
		}
		echo '</style>';
	}
	
	function form($data = null, $form_attrs = null){
		if(!$data){return false;}
		
		$form = '';
		$skips = array('label','type','value','selected');
		
		if(is_array($data)) {
			foreach($data as $item) {
				$item_str = '';
				
				if($item['label']) { 
					$label_attrs = '';
					foreach($item['label'] as $k => $v) {
						if($k != 'text') {
							$label_attrs .= " $k = \"$v\"";
						}
					}
					$item_str .= "<label{$label_attrs}>" . $item['label']['text'] . '</label> ';
				}
				
				$value = '';
				$attrs = array();
				
				foreach($item as $k => $v) { 
					if(in_array($k, $skips)) {continue;}
					$attrs[] = "$k=\"$v\"";
				}
				$attrs = implode(' ', $attrs);
				
				switch($item['type']) {
					case 'text':
					case 'radio':
					case 'checkbox':
					case 'password':
					case 'hidden':
					case 'submit':
						$value = 'value="' . $item['value'] . '"';
						$item_str .= '<input type="' . $item['type'] . '" ' . $attrs . ' ' . $value . ' />';
						break;
					case 'textarea':
						$value = $item['value'];
						$item_str .= '<textarea ' . $attrs . '>' . $value . '</textarea>';
						break;
					case 'select':
						if(is_array($item['value'])) {
							foreach($item['value'] as $val => $display) {
								$sel = '';
								if($item['selected'] == $val) { $sel = 'selected="selected"';}
								$value .= '<option value="' . $val . '" ' . $sel . '>' . $display . '</option>';
							}
						} else {
							echo 'Error occurred: ';
							print('<pre>');
							print_r($item);
							print('</pre>');
							exit;
						}
						$item_str .= '<select ' . $attrs . '>' . $value . '</select>';
						break;
					case 'html':
						$item_str .= $item['value'];
						break;
				}
				$form .= $item_str;
			}
		} else {
			switch($data) {
				case 'start':
					$form_meta = array();
					foreach($form_attrs as $k => $v) { $form_meta[] = "$k = \"$v\"";}
					$form_meta = implode(' ', $form_meta);
					$form .= "<form $form_meta>";
					break;
				case 'end':
					$form .= '</form>';
					break;
			}
		}
		echo $form;
	}

?>