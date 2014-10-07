<?php
	if($_SESSION['success']) {
		include('includes/redeem/success.php');
		exit;
	}
	
	if(!is_authenticated() || !$_SESSION['authenticated']) {
		$_SESSION = array();
		include('includes/redeem/expired.php');
		exit;
	}

	/****** Pull back products associated with this vendor... ******/
	$products = explode(', ', DL_PRODUCT_IDS);
	$product_data = array();
	
	$data = array('d_id' => $products, 'test' => true);
	$dl_products = api_call('products',$data);
	$dl_products = json_decode($dl_products,true);

	$styles = array(
		'.form_left' => array(
			'float' => 'left',
			'margin-right' => '10px',
			'width' => '290px'
		),
		'.form_right' => array(
			'float' => 'left',
			'width' => '266px'
		),
		'.form_right, .form_left' => array(
			'margin-top' => '15px'
		),
		'.clear' => array(
			'clear' => 'both'
		),
		'.shipping_info_submit' => array(
			'cursor' => 'pointer'
		),
		'.product' => array(
			'border' => '5px solid transparent',
			'padding' => '10px',
			'width' => '250px',
			'float' => 'left',
			'margin-right' => '10px'
		),
		'.product h4' => array(
			'text-align' => 'center',
			'background-color' => '#F00000',
			'color' => '#EBEBEB',
			'font-size' => '15px',
			'padding' => '5px',
		),
		'.product a' => array(
			'text-decoration' => 'none',
			'position' => 'relative',
		),
		'.product a:hover' => array(
			/* 'text-decoration' => 'underline' */
			'opacity' => '0.8'
		),
		'.product.selected' => array(
			'border-color' => '#f00'
		),
		'.form_left input, .form_right input, .form_right select' => array(
			'width' => '155px',
			'display' => 'block',
			'float' => 'left',
		),
		'.form_left label, .form_right label' => array(
			'width' => '100px',
			'display' => 'block',
			'float' => 'left',
			'clear' => 'both'
		),
		'.form_right input.shipping_info_submit' => array(
			'float' => 'right',
			'width' => '177px',
			'margin-top' => '5px'
		),
		'.selected-msg' => array(
			'display' => 'none'
		),
		'#redeem-section .selected p.selected-msg' => array(
			'display' => 'block',
			'position' => 'absolute',
			'bottom' => '0',
			'text-align' => 'center',
			'color' => '#EBEBEB',
			'font-size' => '15px',
			'background-color' => '#FF0000',
			'width' => '100%',
			'padding' => '10px 0',
			'font-weight' => 'bold',
			'left' => '0'
		),
	);
	
	styles($styles);
	
/* 	echo '<h3>Products (Click a product)</h3>'; */
	
	echo '<h3><span class="step">Step 1:</span> Click a product to redeem</h3><br>';	
	
	form('start',array('method'=>'post','action'=>'/redeem'));
	
	foreach($dl_products['items'] as $product) {		
		$sizes = array();
		echo '<div class="product" id="product_' . $product['product_id'] . '">
				<a href="#" class="product_click" id="product_click_' . $product['product_id'] . '">
					<h4>' . $product['product'] . '</h4>
					<div class="hidden_radio">
						<input type="radio" class="product_radio" id="product_radio_' . $product['product_id'] . '" name="product" value="' . $product['product_id'] . '" />
					</div>
					<img src="' . DL_IMG_PATH . $product['product_id'] . '/' . $product['image'] . '" width="250" />
					<p class="selected-msg">Selected</p>
					</a>';
		
					/****** Sizes ******/
					$sizes = $product['inventory'];
					
					if($sizes) {
						echo '<strong>Item Size: </strong>';
						echo '<select name="size[' . $product['product_id'] . ']">';
						foreach($sizes as $size) {
							echo '<option value="' . $size['size_id'] . '">' . $size['size'] . '</option>';
						}
						echo '</select>';
												
						echo '<input type="hidden" name="color[' . $product['product_id'] .']" value="' . $sizes[0]['color_id'] . '" />';
					}
					
					
					
		echo '</div>';
	}
	echo '<div class="clear"></div>';
	
	redeem_errors();
	
	echo '<br /><br />';

	echo '<h3><span class="step">Step 2:</span> Enter your shipping information</h3>';
		
	form(array(
			array(
				'type' => 'html',
				'value' => '<div class="form_left">'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'First Name',
				),
				'name' => 'first_name',
				'value' => $_SESSION['order_info']['first_name']
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br />'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'Last Name',
				),
				'name' => 'last_name',
				'value' => $_SESSION['order_info']['last_name']
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br />'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'Email',
				),
				'name' => 'email',
				'value' => $_SESSION['order_info']['email']
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br />'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'Phone',
				),
				'name' => 'phone',
				'value' => $_SESSION['order_info']['phone']
			),
			array(
				'type' => 'html',
				'value' => '</div>'
			),
			array(
				'type' => 'html',
				'value' => '<div class="form_right">'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'Address',
				),
				'name' => 'address',
				'value' => $_SESSION['order_info']['address']
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br />'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'Address 2',
				),
				'name' => 'address2',
				'value' => $_SESSION['order_info']['address2']
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br />'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'City',
				),
				'name' => 'city',
				'value' => $_SESSION['order_info']['city']
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br />'
			),
			array(
				'type' => 'html',
				'value'  => '<div class="state_province">'
			),
			array(
				'type' => 'html',
				'value' => '<div class="state_container">'
			),
			array(
				'type' => 'select',
				'label' => array(
					'text' => 'State',
				),
				'name' => 'state',
				'class' => 'state_select',
				'value' => json_decode(api_call('states'),true),
				'selected' => $_SESSION['order_info']['state']
			),
			array(
				'type' => 'html',
				'value' => '</div>'
			),
			array(	
				'type' => 'html', 
				'value' => '<div class="province_container" style="display:none;">'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'Province'
				),
				'name' => 'province',
				'class' => 'province_select',
				'selected' => $_SESSION['order_info']['province']
			),
			array(
				'type' => 'html',
				'value' => '</div>'
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br />'
			),
			array(
				'type' => 'html',
				'value' => '</div>'
			),
			array(
				'type' => 'select',
				'label' => array(
					'text' => 'Country',
				),
				'name ' => 'country',
				'class' => 'country_select',
				'value' => json_decode(api_call('countries'),true),
				'selected' => $_SESSION['order_info']['country']
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br />'
			),
			array(
				'type' => 'text',
				'label' => array(
					'text' => 'Zip',
				),
				'name' => 'zip',
				'value' => $_SESSION['order_info']['zip']
			),
			array(
				'type' => 'html',
				'value' => '<br /><br />'
			),
	
			array(
				'type' => 'html',
				'value' => '</div>'
			),
			array(
				'type' => 'html',
				'value' => '<div class="clear"></div>'
			),
			array(
				'type' => 'html',
				'value' => '<br><h3><span class="step">Step 3:</span> Finalize</h3><br>'
			),
			array(
				'type' => 'submit',
				'name' => 'process',
				'value' => '',
				'class' => 'shipping_info_submit'
			),
		));
	form('end');
?>
<br /><br /><br /><br />
<!-- <a href="/redeem?done=1">Done</a> -->

</div>

<script>
	$(document).ready(function(){
		
		
		
		$('.product_click').live('click',function(){
			$('.product_radio').removeAttr('checked');
			var $id = $(this).attr('id').replace('product_click_','');
			$('#product_radio_' + $id).attr('checked','checked');
			$('.product').removeClass('selected');
			$('#product_' + $id).addClass('selected');
			return false;
		})
		
		<?php
			if($_SESSION['order_info']['product']) {
		?>
				$('#product_click_<?=$_SESSION['order_info']['product']?>').trigger('click');
		<?php
			}
		?>
			
		$('.country_select').live('change',function(){
			if($(this).val() == 'US') {
				$('.state_container').show();
				$('.province_container').hide();
			} else {
				$('.state_container').hide();
				$('.province_container').show();
			}
		})
		
		$('.country_select').trigger('change');
		
	})
</script>