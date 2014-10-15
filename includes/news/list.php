<?php
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$limit = 10;
	$maxRecords = $SQL->fetchRow("SELECT
			COUNT(id) AS pageCount
			FROM news
			WHERE showing = 1
			ORDER BY show_date DESC, date DESC");
	
						
	$maxRecords = $maxRecords['pageCount'];
	
	$maxPages = ceil($maxRecords/$limit);
	
	$offset = $page == '1' ? 0 : $page*$limit;
	
	$all_news = $SQL->fetchAssoc("SELECT
			*
			FROM news
			WHERE showing = 1
			ORDER BY show_date DESC, date DESC
			LIMIT $offset, 10");

	foreach($all_news as $news){
?>
<div class="news-article">
	<div class="article-title clearfix">
		<h3><?php echo date('M', $news['date']); ?><span><?php echo date('d', $news['date']); ?></span></h3>
		<h2><a href="/news/<?php echo $news['id']; ?>"><?php echo $news['title']; ?></a></h2>
	</div>
	<div class="article-body">
		<?php echo strip_tags(nl2br($news['summary'])); ?>
	</div>
</div>
<?php } ?>

<?php

     // determine page (based on <_GET>)
    $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;

    // instantiate; set current page; set number of records
    $pagination = (new Pagination());
    $pagination->setCurrent($page);
    $pagination->setRPP(10);
    $pagination->setTotal($maxRecords);
    $pagination->setTarget('/news/page');

    // grab rendered/parsed pagination markup
    $markup = $pagination->parse();
    echo $markup;

?>