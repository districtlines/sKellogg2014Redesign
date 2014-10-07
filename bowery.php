<? $curr_page = 'music'; ?>
<? $pageTitle = 'Music'; ?>
<? $active = 'home'; ?>
<? include('./includes/header.php'); ?>



	<div class="music">
	
		<h2 class="heading">Forgotten Bowery: SK6ERS Live 2009</h2>
		
			</div>
<br><br>
Click <a href="http://www.sk6ers.com/bowerydownload.zip">here</a> to download.<br><br>

Recorded, Mixed and Mastered by Jeremy Miller<br>
Bowery Ballroom, November 2009<br><br>

Track listing from "Forgotten Bowery: SK6ERS Live 2009" <br><br>

1) Born in the Spring<br>
2) Such a Way<br>
3) Sweet Sophia<br>
4) Start the Day Early<br>
5) Southern State of Mind<br>
6) You Win<br>
7) Cork Story <br>
8) In Front of the World<br>
9) Big Easy (featuring Carbon Leaf)<br>
10) The Bear<br>
11) Glassjaw Boxer<br>
12) 4th of July<br>
13) Milwaukee<br>
14) Satisfied Man<br>
15) See You Later, See You Soon<br>




</div><!-- end leftCntr -->
				
<? include('./includes/sidebar.php'); ?>

<script type="text/javascript">

	$(document).ready(function(){
	
		$('.listen_btn').click(function(){
		
			var id = $(this).attr('href').substr(7);
			
			if ($('#listen-'+id).css('display') == 'none') {
		
				$('#tracks-'+id).hide();
			
				$('#listen-'+id).show();
			
			} else {
			
				$('#tracks-'+id).show();
				
				$('#listen-'+id).hide();
			
			}
		
			return false;
		
		});
	
	});

</script>

<? include('./includes/footer.php'); ?>