<?php
	if($_POST['redeem_code']) {
		$code = trim(addslashes($redeem_code));
		$data = array('coupon_code' => $code);
		
		$is_valid_code = api_call('coupon_exists',$data);
			
		if($is_valid_code == 'true') {
			if(!$_SESSION['dl_order_id']) {
				$order_id = api_call('create_cart');			
				if(is_numeric($order_id)) {
					$_SESSION['dl_order_id'] = $order_id;
				}
			}
			$_SESSION['coupon_code'] = $code;
			$order_id = $_SESSION['dl_order_id'];
			$_SESSION['authenticated'] = true;
		} else if($is_valid_code == 'false_used'){
			$_SESSION['invalid_coupon'] = 'This redeem code has already been used.';
		} else {
			$_SESSION['invalid_coupon'] = 'That is not a valid code. Please try again.';
		}
	} else {
		$_SESSION['invalid_coupon'] = 'Please enter a redeem code.';
	}
	header('Location: /redeem');
	exit;
?>