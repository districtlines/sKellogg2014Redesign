<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?= title ?> Admin: <?= clean_section ?></title>
	
	<style type="text/css">
	<!--
		HTML,BODY {
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
			text-align: center;
			
			font-family: Helvetica, Aria, Tahoma, Verdana;
		}
		
		DIV#container {
			width: 100%;
			margin: 0 auto;
			text-align: left;
		}
	-->
	</style>
	
	<link rel="stylesheet" type="text/css" href="./css/layout.css" />
	<link rel="stylesheet" type="text/css" href="./css/datepicker.css" />
	<link rel="stylesheet" type="text/css" href="./js/fancybox/source/jquery.fancybox.css" />
	
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="./js/date.js"></script>
	<script type="text/javascript" src="./js/jquery.datepicker.js"></script>
	<script type="text/javascript" src="./js/tablednd.js"></script>
	
<!--	<script type="text/javascript" src="./js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="./js/default_rte.js"></script> -->
	
	<script type="text/javascript" src="./js/nicEdit.js"></script>
	<script type="text/javascript" src="./js/fancybox/source/jquery.fancybox.js"></script>
	<script type="text/javascript">
		bkLib.onDomLoaded(function() {
			if($('#ignore_tf').length > 0) {
				var tarea = new nicEditor({fullPanel : true, iconsPath : '/admin/js/nicEditorIcons.gif'}).panelInstance('ignore_tf',{hasPanel : true});
			}
			
			if($('#ignore_tf2').length > 0) {
				var tarea2 = new nicEditor({fullPanel : true, iconsPath : '/admin/js/nicEditorIcons.gif'}).panelInstance('ignore_tf2',{hasPanel : true});
			}
		});
		//bkLib.onDomLoaded(function() { nicEditors.findEditor('ignore_tf')});
	</script>
</head>
<body>

<div id="container">
	<div id="header">
		<h1><?= title ?></h1>
		<h3><?= clean_section ?></h3>
	</div>
	
	<? if (is_logged_in()) { ?>
	<div id="menu">
		<ul>
			<? if (is_array($_sections)) foreach ($_sections as $section) { ?>
			<li><a href="./<?= $section ?>.php"><?= ucwords(str_replace('_',' ',$section)) ?></a></li>
			<? } ?>
			<li><br /></li>
			<li><a href="./log_out.php">Log Out</a></li>
		</ul>
	</div>
	<? } ?>
	
	<div id="content">
		<?
			if ($_SESSION['message']) {
		?>
		<div id="message">
			<?= $_SESSION['message'] ?>
		</div>
		<?
				$_SESSION['message'] = '';
			}
		?>
