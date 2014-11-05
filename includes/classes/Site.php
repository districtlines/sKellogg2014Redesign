<?php
	class Site {
		public $Merch;
		public $Instagram;
		public $Slides;
		private $SQL;
		
		public function __construct() {
			$this->SQL = new SQL('akthosting.com','stephenk_user','xRG=QOAPQ]mskbdNX%', 'stephenk_site');
			$this->Merch = new Merch();
			$this->Instagram = new Instagram();
			$this->Slides = $this->getSliderData();
		}
		
		public function youtube(){
			$youtube = $this->SQL->fetchRow("SELECT * FROM `youtube` WHERE `sort` = 0 ");
			
			$data = array(
						'name'	=>	$youtube['name'],
						'url'	=>	$youtube['url']	
					);
					
			if(empty($youtube['photo']) || is_null($youtube['photo'])){
				$data['photo'] = $youtube['photo'];
			}else{
				$data['photo'] = $youtube['youtube_poster'];
			}
			
			
			return $data;
		}
		
		public function getSliderData() {
			$slides = $this->SQL->fetchAssoc("SELECT * FROM slideshow ORDER BY sort ASC LIMIT 21");
			if( is_array($slides) && count($slides) > 0 ) {
				return $slides;
			} else {
				return false;
			}
		}
		
	}
	
	include('Instagram.php');
	include('Merch.php');
?>