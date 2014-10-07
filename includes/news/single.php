<?php
	$sql = "SELECT
			*
			FROM news WHERE id = '{$_GET['article']}' AND show_date <= NOW() LIMIT 1";
	$row = mysql_fetch_assoc(mysql_query($sql));
?>
	<h2 class="page-title">NEWS //</h2>
	<div class="news-article">
		<div class="article-title clearfix">
			<h3><?php echo date('M', $row['date']); ?><span><?php echo date('d', $row['date']); ?></span></h3>
			<h2><a href="/news/<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></h2>
		</div>
		<div class="article-body">
			<?php echo $row['content']; ?>
		</div>
	</div>
	<a href="/" class="btn btn-default">&laquo; more news</a>
	<br />
	<br />