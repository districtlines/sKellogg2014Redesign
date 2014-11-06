<?php
	$active = 'home';
	$pageTitle = 'Photos';
	$curr_page = 'photos';
	$layout = 'large-left-col';
	include('./includes/header.php');
	
	$photos = $SQL->fetchAssoc("SELECT * FROM photos WHERE album_id = 21 ORDER BY RAND()");
	

?>

<h2 class="page-title">PHOTOS //</h2>

<div class="photos-page">
	<div id="container">
<?php	foreach($photos as $p){ 
	
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/photos/".$p['id']."/".$p['thumbnail']) && file_exists( $_SERVER['DOCUMENT_ROOT']."/uploads/photos/".$p['id']."/".$p['photo']) ){
?>
		
			<div class="item">
				<img class="thumb" width="100%" height="100%" src="<?=ROOT?>/uploads/photos/<?=$p['id'];?>/<?=$p['thumbnail'];?>" />
				<img class="full hide" width="100%" height="100%" src="<?=ROOT?>/uploads/photos/<?=$p['id'];?>/<?= str_replace('full_', '', $p['photo']);?>" />
			</div>
		

<?php 	} } ?>
	
	</div>
</div>
</div>

<script type="text/javascript">

/*
$(function(){
  var $container = $('#container'),
      $items = $('.item');
  
  $container.isotope({
    itemSelector: '.item',
    layoutMode : 'fitRows',
    masonry: {
      columnWidth: 60
    },
    resizesContainer : false,
    getSortData : {
      fitOrder : function( $item ) {
        var order,
            index = $item.index();
        
        if ( $item.hasClass('large') && index % 2 ) {
          order = index + 1.5;
        } else {
          order = index;
        }
        return order;
      }
    },
    sortBy : 'selected'

  });
  
  $items.click(function(){
    var $this = $(this);
    // nothing to change if this already has large class
    if ( $this.hasClass('large') ) {
      return;
    }
    var $previousLargeItem = $items.filter('.large');
    
    $previousLargeItem.removeClass('large');
    $this.addClass('large');

    $container
      // update sort data on changed items
      .isotope('updateSortData', $this )
      .isotope('updateSortData', $previousLargeItem )
      // trigger layout and sort
      .isotope();
  });
});
*/

$(function(){
  
  
  var $container = $('#container'),
      $items = $('.item');
  
  $container.isotope({
    itemSelector: '.item',
    masonry: {
      columnWidth: 160
    },
    getSortData : {
      selected : function( $item ){
        return ($item.hasClass('selected') ? -1000 : 0 ) + $item.index();
      }
    },
    sortBy : 'selected'
  })
  
  $items.click(function(){
    var $this = $(this);

    var $previousSelected = $('.large');
    
    if ( !$this.hasClass('large') ) {
		$this.addClass('large');
	
	   $this.find('.thumb').toggleClass("hide");
	   $this.find('.full').toggleClass("hide");
    }
    
	$previousSelected.find('.thumb').toggleClass("hide");
	$previousSelected.find('.full').toggleClass("hide");	

    $previousSelected.removeClass('large');
	
	var p = $this.position();
	$(window).scrollTop(p.top);
	
    $container
      .isotope( 'updateSortData', $this )
      .isotope( 'updateSortData', $previousSelected )
      .isotope();
    
  });

});

</script>

<?php include('./includes/footer.php'); ?>