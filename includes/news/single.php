<?php
	$article = $SQL->fetchAssoc("SELECT * FROM news WHERE id = '{$_GET['article']}' AND show_date <= NOW() LIMIT 1");
	$article = $article[0];
?>
	<h2 class="page-title">NEWS //</h2>
	<div class="news-article">
		<div class="article-title clearfix">
			<h3><?php echo date('M', $article['date']); ?><span><?php echo date('d', $article['date']); ?></span></h3>
			<h2><a href="/news/<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></h2>
		</div>
		<div class="article-body">
			<?php echo $article['content']; ?>
		</div>
	</div>
	<a href="/" class="btn btn-default">&laquo; more news</a>
	<br />
	<br />