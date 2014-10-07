<?php
	
	if(!is_authenticated()) {
		header('Location: redeem.php');
		exit;
	}
	
	$coupon_code = $_SESSION['coupon_code'];
	$order_id = $_SESSION['dl_order_id'];
	$prepend = 'shipping_information_';
	$prepend_b = 'billing_information_';
	$billing_fields = array('phone','email');

	if($_POST['first_name'] == 'apiTestAKTDL'){
		$debug = true;
	}else{
		$debug = false;
	}
	$debug = false;
	
	$fields = array(
		'first_name',
		'last_name',
		'address',
		'city',
		'state',
		'zip',
		'province',
		'country',
		'phone',
		'email'
	);
	
	$required_fields = array(
		'first_name',
		'last_name',
		'address',
		'city',
		'zip',
		'country',
		'phone',
		'email'
	);
	
	if(!$_POST['product']) {
		$_SESSION['checkout_errors'] = 'Please select a product';
	}
	
	if($_POST['country']) {
		if($_POST['country'] == 'US') {
			$required_fields[] = 'state';
			unset($_POST['province']);
		} else {
			$required_fields[] = 'province';
			unset($_POST['state']);
		}
	}
	
	$product_id = $_POST['product'];
	$size_id 	= $_POST['size'][$product_id];
	$color_id 	= $_POST['color'][$product_id];
	
	$api_data = array();
	foreach($fields as $field) {
		if(!$_POST[$field] && in_array($field, $required_fields)) { $_SESSION['checkout_errors'] = "All fields are required";}
		
		if(!in_array($field, $billing_fields)) {
			$api_data[$prepend . $field] = $_POST[$field];
		}
		$api_data[$prepend_b . $field] = $_POST[$field];
	}
	
	/****** Coupon Order Information ******/
	$api_data['coupon_used'] = 1;
	$api_data['coupon_information_coupon_code'] = $coupon_code;
	$api_data['coupon_adjustment'] = 0;
	
	foreach($_POST as $k => $v) {
		$_SESSION['order_info'][$k] = $v;
	}
	
	if($_SESSION['checkout_errors']) {
		header('Location: /redeem');
		exit;
	}
	
	$selected_package_items = $product_packages[$product_id];
		
	/****** INSERT PRODUCT ******/
	$cart_data = array(
		'order_id' => $order_id,
		'design_id' => $product_id,
		'size_id' => '',
		'color_id' => '',
		'quantity' => 1,
		'is_package' => '1',
		'part_of_package' => '0',
		'test' => 1
	);
	
	
	$rs = api_call('cart_add',$cart_data, $debug);
		
	$package_item = json_decode($rs,true);
		
	$package_item_id = $package_item['item_id'];
	
	foreach($selected_package_items as $id => $package_data) {
		$package_data['part_of_package'] = $package_item_id;
		$package_data['design_id'] = $id;
		$package_data['test'] = 1;
		$package_data['order_id'] = $order_id;

		$rs = api_call('cart_add',$package_data, $debug);
	}
		
	//$selected_package_items
			
	/****** INSERT ORDER ******/
	$data = array(
		'order_id' => $order_id,
		'user_info' => $api_data,
		'type' => '',
		'api_order_id' => ''
	);
	
	$rs = api_call('insert_order',$data, $debug);
	
	/****** ADJUST COUPON ******/
	$coupon_data = array(
		'order_id' => $order_id,
		'coupon_code' => $coupon_code,
		'total' => 0
	);
	
	$rs = api_call('apply_coupon_code',$coupon_data, $debug);
	
	$_SESSION['success'] = true;
	
	if($_POST['first_name'] == 'garthTest'){
//		die(var_dump("end"));
	}

	
	header('Location: redeem.php');
	exit;
?>