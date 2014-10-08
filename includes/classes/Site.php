<?php
	class Site {
		public $Merch;
		public $Instagram;
		
		public function __construct() {
			$this->Merch = new Merch();
			$this->Instagram = new Instagram();
		}
		
		public function youtube(){
			$SQL = new SQL('akthosting.com','stephenk_user','xRG=QOAPQ]mskbdNX%', 'stephenk_site');
			$youtube = $SQL->fetchRow("SELECT * FROM `youtube` WHERE `sort` = 0 ");
			
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
		
	}
	
	include('Instagram.php');
	include('Merch.php');
?>