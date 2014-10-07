<?
	$_SESSION['form_data'] = array();
	$_SESSION['form_error'] = array();
?>
	</div>
	
	<div id="footer">
	</div>
</div>


<script type="text/javascript">

	$(document).ready(function(){
		$('#listing_sort').change(function(){
			var classer = $(this).attr('class').replace('_sort','');
			
			window.location = './' + classer + '.php?sort=' + $(this).val();
		});
		
		
		/*
$('.datepick').datePicker();
		
		
		$('#add_form,#form_table INPUT,#form_table INPUT').each(function(){
			$(this).attr('autocomplete','off');
		});
*/
	});

</script>

</body>
</html>