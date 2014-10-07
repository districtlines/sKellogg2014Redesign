<?
	/*
			html functions
	*/
	function get_value($name, $value, $only_row = false) {
		if ($value || $only_row) {
			return $value;
		} else {
			if ($_POST[$name]) {
				return $_POST[$name];
			} else {
				return $_SESSION['form_data'][$name];
			}
		}
	}
	
	function label($display, $name = '') {
		$error = ($_SESSION['error'][$name]) ? ' class="error"' : '';
		print '<label' . $error . '>' . $display . '</label>';
	}
	
	function input_text($name, $value = '', $extra = '') {
		global $global_extra;
		$value = get_value($name,$value);
		if($name == 'date' && $value != "") {
			$value = date('Y-m-d', $value);
		}
		$value = htmlentities($value);
		print '<input type="text" name="' . $name . '" value="' . $value . '"' . $extra . $global_extra . ' />';
	}
	
	
	function input_password($name, $extra = '') {
		global $global_extra;
		print '<input type="password" name="' . $name . '"' . $extra . $global_extra . ' />';
	}
	
	
	function input_hidden($name, $value = '') {
		$value = get_value($name,$value);
		print '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
	}
	
	
	function input_submit($name, $value = '', $extra = '') {
		$value = get_value($name,$value);
		print '<input type="submit" name="' . $name . '" value="' . $value . '" ' . $extra . ' />';
	}
	
	
	function input_checkbox($name, $val = '', $disp = '', $value = '', $extra = '') {
		global $global_extra;
		$value = get_value($name,$value);
		$checked = ($value == $val) ? ' checked="checked"' : '';
		print '<input type="checkbox" name="' . $name . '" value="' . $val . '" id="checkbox_' . $name . '_' . $val . '" ' . $extra . $global_extra . $checked . ' />';
		if ($disp) {
			print '<label for="checkbox_' . $name . '_' . $val . '">' . $disp . '</label>';
		}
	}
	
	
	function input_radio($name, $val, $disp = '', $value = '', $extra = '') {
		global $global_extra;
		$value = get_value($name,$value);
		$checked = ($value == $val) ? ' checked="checked"' : '';

		print '<input type="radio" name="' . $name . '" value="' . $val . '" id="radio_' . $name . '_' . $val . '"' . $checked . $extra . $global_extra . ' />';

		if ($disp) {
			print '<label for="radio_' . $name . '_' . $val . '">' . $disp . '</label>';
		}
	}
	
	
	function input_file($name, $value = '', $path = '') {
		global $global_extra;
		$value = get_value($name,$value, true);
		
		if (is_file($path . $value)) {
			print '<a href="' . $path . $value . '">' . $value . '</a><br />';
		} elseif ($value) {
			print $value . '<br />';
		}
		print '<input type="file" name="' . $name . '" />';
	}
	
	
	function textarea($name, $value = '', $extra = '') {
		global $global_extra;
		$value = get_value($name,$value);
		print '<textarea name="' . $name . '" ' . $extra . $global_extra . '>' . $value . '</textarea>';
	}
	
	function select($name, $options, $value = '', $extra = '', $extra_options = array()) {
		global $global_extra;
		$value = get_value($name,$value);
		
		if (!is_array($options) && @is_file($_SERVER['DOCUMENT_ROOT'] . '/includes/dropdowns/' . $options . '.php')) {
			include($_SERVER['DOCUMENT_ROOT'] . '/includes/dropdowns/' . $options . '.php');
		} elseif (!is_array($options)) {
			switch ($options) {
				case 'month':
					$options = array();
					
					if (!$value) {
						$value = date('m');
					}
					
					for ($i = 1; $i <= 12; $i++) {
						$options[sprintf('%02d',$i)] = sprintf('%02d',$i);
					}
					break;
				case 'year':
					$options = array();
					
					for ($i = date('Y') - 100; $i <= date('Y') + 100; $i++) {
						$options[$i] = $i;
					}
					break;
				case 'day':
					$options = array();
					
					if (!$value) {
						$value = date('j');
					}
					
					for ($i = 1; $i <= 31; $i++) {
						$options[sprintf('%02d',$i)] = sprintf('%02d',$i);
					}
					break;
				case 'hour':
					$options = array();
					
					if (!$value) {
						$value = date('g');
					}
					
					$min = isset($extra_options['min']) ? $extra_options['min'] : 1;
					$max = isset($extra_options['max']) ? $extra_options['max'] : 12;
					
					for ($i = $min; $i <= $max; $i++) {
						$options[sprintf('%02d',$i)] = sprintf('%02d',$i);
					}
					break;
				case 'minute':
					$options = array();
					
					if (!$value) {
						$value = date('i');
					}
					
					for ($i = 0; $i <= 59; $i++) {
						$options[sprintf('%02d',$i)] = sprintf('%02d',$i);
					}
					break;
				case 'second':
					$options = array();
					
					if (!$value) {
						$value = date('s');
					}
					
					for ($i = 0; $i <= 59; $i++) {
						$options[sprintf('%02d',$i)] = sprintf('%02d',$i);
					}
					break;
				case 'ampm':
					$options = array(
						'AM' => 'AM',
						'PM' => 'PM',
					);
					break;
			}
		}
		
		print '<select name="' . $name . '"' . $extra . $global_extra . '>';
		foreach ($options as $val => $disp) {
			$selected = ($value == $val) ? ' selected="selected"' : '';
			print '	<option value="' . $val . '"' . $selected . '>' . $disp . '</option>';
		}
		print '</select>';
	}
	
	
	
	function select_date($name, $value = '') {
		$value = get_value($name,$value);
		$timestamp = strtotime($value . date(' H:i:s'));
		
		print '<div class="select_date">';

		select($name . '[month]', 'month', date('m', $timestamp));
		print '<div class="select_separater">/</div>';
		select($name . '[day]', 'day', date('d', $timestamp));
		print '<div class="select_separater">/</div>';
		select($name . '[year]', 'year', date('Y', $timestamp));

		print '</div>';
	}
	
	
	

	function select_time($name, $value = '', $show_ampm = true) {
		$value = get_value($name,$value);
		$timestamp = strtotime(date('m/d/Y ') . $value);
		
		print '<div class="select_time">';

		select($name . '[hour]', 'hour', date('h', $timestamp));
		print '<div class="select_separater">:</div>';
		select($name . '[minute]', 'minute', date('i', $timestamp));
		print '<div class="select_separater">:</div>';
		select($name . '[second]', 'second', date('s', $timestamp));

		if ($show_ampm) {
			print '<div class="select_separater">&nbsp;</div>';
		
			select($name . '[ampm]', 'ampm', date('A', $timestamp));
		}

		print '</div>';
	}
	
	
	
	function select_time_duration($name, $value = '') {
		$value = get_value($name,$value);
		$value = explode(':',$value);
		
		list($value['hour'], $value['minute'], $value['second']) = $value;
		
		
		print '<div class="select_time">';

		select($name . '[hour]', 'hour', $value['hour'], null, array('min' => 0, 'max' => 99));
		print '<div class="select_separater">:</div>';
		select($name . '[minute]', 'minute', $value['minute']);
		print '<div class="select_separater">:</div>';
		select($name . '[second]', 'second', $value['second']);

		print '</div>';
	}
	
	
	
	function select_datetime($name, $value = '', $extra = '') {
		$value = get_value($name,$value);
		$timestamp = strtotime($value);
		
		print '<div class="select_datetime">';
		
		select($name . '[month]', 'month', date('m', $timestamp));
		print '<div class="select_separater">/</div>';
		select($name . '[day]', 'day', date('d', $timestamp));
		print '<div class="select_separater">/</div>';
		select($name . '[year]', 'year', date('Y', $timestamp));

		print '<div class="select_separater">&nbsp;</div>';
		
		select($name . '[hour]', 'hour', date('h', $timestamp));
		print '<div class="select_separater">:</div>';
		select($name . '[minute]', 'minute', date('i', $timestamp));
		print '<div class="select_separater">:</div>';
		select($name . '[second]', 'second', date('s', $timestamp));

		print '<div class="select_separater">&nbsp;</div>';

		select($name . '[ampm]', 'ampm', date('A', $timestamp));
		
		print '</div>';
	}
	
	
	
	function checkboxes($name, $options, $value = array()) {
		foreach ($options as $option) {
			list($val, $disp) = $option;
			if (in_array($val, $value)) {
				input_checkbox($name, $val, $disp, $val);
			} else {
				input_checkbox($name, $val, $disp);
			}
		}
	}
	
		
	function radios($name, $options, $value = array()) {
		foreach ($options as $option) {
			list($val, $disp) = $option;
			if (in_array($val, $value)) {
				input_radio($name, $val, $disp, $val);
			} else {
				input_radio($name, $val, $disp);
			}
		}
	}
?>