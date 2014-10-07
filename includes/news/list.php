<?php
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$limit = 10;
	$sql = "SELECT
			COUNT(id) AS pageCount
			FROM news
			WHERE showing = 1
			ORDER BY show_date DESC, date DESC";
	$maxRecords = mysql_fetch_assoc(mysql_query($sql));
	$maxRecords = $maxRecords['pageCount'];
	$maxPages = ceil($maxRecords/$limit);

	$offset = $page*$limit;
	
	$sql = "SELECT
			*
			FROM news
			WHERE showing = 1
			ORDER BY show_date DESC, date DESC
			LIMIT $offset, 10";

	$query = mysql_query($sql);
	while($row = mysql_fetch_assoc($query)) {
?>
<div class="news-article">
	<div class="article-title clearfix">
		<h3><?php echo date('M', $row['date']); ?><span><?php echo date('d', $row['date']); ?></span></h3>
		<h2><a href="/news/<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></h2>
	</div>
	<div class="article-body">
		<?php echo strip_tags(nl2br($row['summary'])); ?>
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