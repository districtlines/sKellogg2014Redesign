 <!--  / RIGHT CONTAINER \ -->
                    <div id="rightCntr">
                    
                    <div id="slider_container">
                    
                    	<div id="slider">
                    	
                    	
                    	<? 
                    		
                    		$sql = "SELECT * FROM slideshow ORDER BY sort ASC LIMIT 21";
                    		
                    		$query = mysql_query($sql);
                    		
                    		if(mysql_num_rows($query)) {
                    		
                    			while($row = mysql_fetch_assoc($query)) {
                    				
                    				?>
                    					
                    					<div class="slide">
                    						
                    						<? if(!empty($row['link'])) : ?>
                    						<a href="<?=$row['link'];?>" title="<?=$row['name'];?>">
                    						<? endif; ?>
                    							<img src="<?=ROOT?>/uploads/slideshow/<?=$row['id']?>/thumb_<?=$row['image']?>" />
                    						<? if(!empty($row['link'])) : ?>
                    						</a>
                    						<? endif; ?>
                    					
                    					</div>
                    		
                    				<?
                    			}
                    		}
                    	
	                    ?>
	                    	
                    	</div> <!-- slider -->
                    	
                    	<div id="slider_pagination">
                    		
                    		<ul>
                    		
                    		
                    		</ul>
                    		
                    		<div class="clear"></div>
                    		
                    	</div> <!-- slider_pagination -->
                    
                    </div> <!-- slider_container -->
        			
        			<div class="twitter" style="margin-bottom:5px;">
                    	<a class="twitter-timeline"  href="https://twitter.com/stephen_kellogg"  data-widget-id="319296738567520256">Tweets by @stephen_kellogg</a>
                    	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    </div>
                    	
        				<?
        					$sql = "SELECT * FROM content_pages";
        					$query = mysql_query($sql);
        					if(mysql_num_rows($query)) {
        					
        						while($row = mysql_fetch_assoc($query)) {
        						
        							if($row['title'] == 'sidebar_album') {
        								
        								$s_album_id = $row['id'];
        								$s_album = $row['body'];
        								
        							} else if($row['title'] == 'sidebar_soundcloud') {
        							
        								$s_soundcloud 		= $row['body'];
        								$s_soundcloud_name  = $row['name'];
        								
        							}
        						
        						}
        					
        					}
        				?>
                       <div class="sidebar_album">
                        	<img src="<?=ROOT?>/uploads/content_pages/<?=$s_album_id?>/thumb_<?=$s_album?>" alt="" />
                     	</div>
                     	<div class="sidebar_music">
                     		
                     		<h3 style="font-size:14px;text-align:center;padding:0;margin: 10px 0 5px 0;"><?=$s_soundcloud_name?></h3>
                     		<?=$s_soundcloud?>
                     		
                     	</div>
                     	
                        <!--  / EVENT BOX \ -->
                        <div class="eventBox">
                            
                            <h3>UPCOMING EVENTS</h3>
                            <?php /*
                            <script type='text/javascript' src='http://www.bandsintown.com/javascripts/bit_widget.js'></script>
                            <script type='text/javascript'>var widget = new BIT.Widget({"artist":"Stephen Kellogg","prefix":"fbjs"});widget.insert_events();</script>
                            */ ?>
                            <ul>
                            
                            	<?
                            		$today = strtotime(date('m/d/Y'));
                            		$sql = "SELECT id,date,city,venue, venue_url FROM events WHERE date >= '$today' ORDER BY date ASC LIMIT 6";
                            		
                            		$query = mysql_query($sql);
                            		
                            		if(mysql_num_rows($query)) {
                            		
                            			while($row = mysql_fetch_assoc($query)) {
                            	?>
                            		<li>
                            			<span><?=date('m.d',$row['date'])?></span>
                            			<a href="<?=ROOT?>/events/#events-<?=$row['id']?>"><?=$row['city']?></a>
                            			
                            			<? if($row['venue_url']) { ?>
                            				<a href="<?=$row['venue_url']?>" target="_blank" class="venue_name">
                            			<? } ?>
                            					<?=strlen($row['venue']) >= 20 ? substr($row['venue'], 0, 20)."..." : $row['venue'];?>
                            			<? if($row['venue_url']) { ?>
                            			
                            				</a>
                            			
                            			<? } ?>
                            		</li>
                            	
                            	<?
                            			
                            			}
                            		}
                            		
                            	?>
                                
                            </ul>
                            
                        </div>
                        <!--  \ EVENT BOX / -->
                    	
                        <div class="clear"></div>
                        
                        <!--  / SIGNUP BOX \ -->
                        <div class="signupBox">
                        
							<h3>SIGNUP FOR OUR MAILING LIST</h3>
                            
                            <p class="errors"></p>
                            
                            <form class="newsletter" action="<?=ROOT?>/newsletter.php" method="post">
                            	<fieldset>
    								
                                    <input type="text" id="mailing" class="field" name="email" value="Email address" />
                                    
                                    <input type="submit" class="button" value="SIGNUP" style="cursor:pointer" />
                            	
                                </fieldset>
                                	
                        		<div class="merch_volunteer">
                        			
                        			<input type="checkbox" name="merch" value="1" />
                        			
                        			<label>&nbsp;I want to be a merch volunteer!</label>
                        			
                        		</div>
                            
                            </form>
                                                       
                        </div>
                        <!--  \ SIGNUP BOX / -->
                        
       					<div class="clear"></div>
                        
                        <!--  / MERCH BOX \ -->
                        <div class="merchBox">
	                        
	                        <? include('includes/ParseXML.php'); ?>
                            
                            <?
                            
                            
                            define('img_path','http://d3eum8lucccgeh.cloudfront.net/designs/%ID%/%IMAGE%');
							define('product_path','http://www.districtlines.com/%PRODUCT_ID%-%PRODUCT_CLEAN%/%VENDOR_CLEAN%');
							define('api_url','http://www.districtlines.com/api/');
                            
                            function image($id,$image = null,$prefix = '') {
								return str_replace('%IMAGE%',$prefix . $image,str_replace('%ID%',$id,img_path));
							}
							
							
							
							function product($id) {
								if (is_array($id)) {
									$path = product_path;
									
									foreach ($id as $var => $val) {
										$path = str_replace('%' . strtoupper($var) . '%',$val,$path);
									}
									
									return $path;
								} else {
									return str_replace('%ID%',$id,product_path);
								}
							}
						
							
                            
                            function quick_curl($url,$params = '',$debug = false) {
								$ch = curl_init();    // initialize curl handle
								
								curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
								//curl_setopt($ch, CURLOPT_FAILONERROR, 1);
								//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects (security issue i guess)
								curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
								curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 5s
								curl_setopt($ch, CURLOPT_POST, 1); // set POST method
								curl_setopt($ch, CURLOPT_POSTFIELDS, $params); // add POST fields
								$result = curl_exec($ch); // run the whole process
								
								if ($debug && curl_errno($ch)) {
									print curl_errno($ch) . ': ' . curl_error($ch);
								}
								
								curl_close($ch);
								return $result;
							}
                            
                            
                            function curl_load($url,$params = '',$debug = false) {
								$result = quick_curl($url,$params,$debug);
								$parser = new ParseXML;
								$rows = $parser->parse($result);
								if ($debug) {
									var_dump($rows);
								}
								return $rows['DATA'][0]['ROWS'][0]['ROW'];
							}
							
							$result = curl_load('http://www.districtlines.com/api/','action=products&q_vendor_id=1886&q_featured=1&o_date_added=DESC&limit=1&q_album_id=0&o_random=1');
							$count = 0;
							if (is_array($result)) :
								        foreach ($result as $row) :
								    	   $count++;
                   			?>
	                        
                            <h3>STEPHEN KELLOGG MERCH<a href="<?= product($row) ?>">VISIT THE STORE &raquo;</a></h3>
								
                   			<div id="merch_img">
	                        	<img style="border: 1px solid #D6D6D6; padding:0;margin:0 18px 0 0;" src="<?= image($row['PRODUCT_ID'],$row['IMAGE'],'browse_') ?>" alt="<?=$row['PRODUCT']?>" />
	                        </div>
	               		
                   			<p><span>$<?=number_format($row['PURCHASE_PRICE'],2)?></span><?=$row['PRODUCT'];?></p>
	             			
                            
                            <?php
							        endforeach;
							    endif;
							?>
                                                       
                        </div>
                        <!--  \ MERCH BOX / -->
                      
                    </div>
                    <!--  \ RIGHT CONTAINER / -->
                  
                    <div class="clear"></div>
                    
            	</div>
            </div>
		</div>
		
		
		
		<script type="text/javascript">
			
			$(document).ready(function(){
				
				
				//SLIDER
								
				$('#slider').cycle({ 
			    	fx: 'fade', 
			    	speed: 2000,
			    	pause: true,
			    	pauseOnPagerHover: true,
			    	timeout: 4000,
			    	pager:  '#slider_pagination ul', 
		    		pagerAnchorBuilder: function(idx, slide) { 
				        return '<li><a href="#">' + (idx+1) + '</a></li>'; 
				    } 
				});
				
				//MAILING LIST
				$(".newsletter #mailing").focus(function()
				{
					if($(this).val() == 'Email address')
					{
						$(this).val('');
					}
					
				});
				$(".newsletter #mailing").blur(function()
				{
				//	$(this).val('');
					if ($(this).val()== '')
					{
						$(this).val('Email address');
					}
				});
				$(".newsletter").submit(function(e){
					var datastring = $(this).serialize();
					var formAct = $(this).attr('action');
					$.ajax({
					  url: 'newsletter.php',
					  type: 'POST',
					  data: datastring,
					  success: function(data) {
					    if(data == 'true') {
					    	$("p.errors").html('Thanks!').css('color', '#000');
					    	
					    } else {
					    	$("p.errors").html(data).css('color', 'red');
					    }
					  }
					});
					
					return false;
				});
				
			});
		</script>
		
		
		
		<!--  \ CONTENT CONTAINER / -->