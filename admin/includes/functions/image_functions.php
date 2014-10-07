<?
	function make_thumbnail($original,$thumbnail,$max_width,$max_height,$resize_type = 'scale',$quality = 100) {
		//get the image information from the original image
		list ($src_width, $src_height, $type, $w) = getimagesize($original);
		
		
		//open image based on type
		switch ($type) {
			case '1': //gif
				$srcImage = @imagecreatefromgif($original);
				break;
			case '2': //jpeg
				$srcImage = @imagecreatefromjpeg($original);
				break;
			case '3': //png
				$srcImage = @imagecreatefrompng($original);
				break;
		}

		//if image container failed to create, return
		if (!$srcImage) {
			print 'source image failed';
			return false;
		}
		
		
		
		switch ($resize_type) {
			case 'scale-crop':
				if ($src_width > $src_height) {
					$thumb_height = $max_height;
					$thumb_width = $src_width * ($max_height / $src_height);
				} else {
					$thumb_width = $max_width;
					$thumb_height = $src_height * ($max_width / $src_width);
				}
		
				$off_w = - ($thumb_width - $max_width) / 2;
				$off_h = - ($thumb_height - $max_height) / 2;
				break;
				
			case 'scale':
				if ($src_width > $src_height) {
					$thumb_height = $max_height;
					$thumb_width = $src_width * ($max_height / $src_height);
				} else {
					$thumb_width = $max_width;
					$thumb_height = $src_height * ($max_width / $src_width);
				}

				$max_width = $thumb_width;
				$max_height = $thumb_height;
				break;
				
			case 'scale-height'://<--------
				//try like this
				$thumb_width = $max_width;
				$thumb_height = $src_height * ($max_width / $src_width);

				$max_width = $thumb_width;
				$max_height = $thumb_height;
				break;
				
			case 'scale-crop2':
				$offset_w = $max_width - ($src_width * ($max_height / $src_height));
				$offset_h = $max_height - ($src_height * ($max_width / $src_width));
				
				$offset_w = $offset_w > 0 ? $offset_w : 0;
				$offset_h = $offset_h > 0 ? $offset_h : 0;
				
				if ($src_width > $src_height) {
					$thumb_height = $max_height + $offset_h;
					$thumb_width = $src_width * ($thumb_height / $src_height) + $offset_w;	
				} else {
					$thumb_width = $max_width + $offset_w;
					$thumb_height = $src_height * ($thumb_width / $src_width) + $offset_h;
				}
				
				$off_w = - ($thumb_width - $max_width) / 2;
				$off_h = - ($thumb_height - $max_height) / 2;
				
				break;
		}
		
		
		
/*
		print '<strong>Max Width</strong>: ' . $max_width . '<br />';
		print '<strong>Max Height</strong>: ' . $max_height . '<br /><br />';
		print '<strong>Max Ratio</strong>: ' . ($max_width / $max_height) . '<br /><br /><br />';

		print '<strong>Thumb Width</strong>: ' . $thumb_width . '<br />';
		print '<strong>Thumb Height</strong>: ' . $thumb_height . '<br /><br />';
		print '<strong>Thumb Ratio</strong>: ' . ($thumb_width / $thumb_height) . '<br /><br /><br />';

		print '<strong>Source Width</strong>: ' . $src_width . '<br />';
		print '<strong>Source Height</strong>: ' . $src_height . '<br /><br />';
		print '<strong>Source Ratio</strong>: ' . ($src_width / $src_height) . '<br /><br /><br />';

		print '<strong>Off Width</strong>: ' . $off_w . '<br />';
		print '<strong>Off Height</strong>: ' . $off_h . '<br /><br />';
*/
		
		
		//create thumbnail container, if it fails, return
		if (!@$destImage = imagecreatetruecolor($max_width, $max_height)) {
			print 'destination image failed';
			return false;
		}
		
		
		//copy our new image into the thumbnail container
		if (!@imagecopyresampled($destImage, $srcImage, $off_w, $off_h, 0, 0, $thumb_width, $thumb_height, $src_width, $src_height)) {
			return false;
		}
		
		
		//destroy original image container
		@imagedestroy($srcImage);

		//antialias thumbnail image, if it fails, return
		if (!@imageantialias($destImage, true)) {
			return false;
		}
		
		
		//save thumbnail image based on type
		switch ($type) {
			case '1': //gif
				$image_saved = @imagegif($destImage, $thumbnail);
				break;
			case '2': //jpeg
				$image_saved = @imagejpeg($destImage, $thumbnail, $quality);
				break;
			case '3': //jpeg
				$image_saved = @imagepng($destImage, $thumbnail);
				break;
		}
		
		
		//if it fails, return
		if (!$image_saved) {
			return false;
		}
		
		//destroy thumbnail container
		@imagedestroy($destImage);
	}
?>