<?php defined('SYSPATH') OR die("No direct access allowed."); 
	$date = new DateTime('now', new DateTimeZone(TIMEZONE));
	$current_datetime = $date->format('Y-m-d H:i:s');
	$current_time = $date->format('H:i');
	$current_date = $date->format('Y-m-d');
	$to_date = date("Y-m-d", time() + 86400); ?>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<!-- time picker start-->
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/tdispatch.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/jquery.lightbox_me.js"></script>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/css/dispatch_popup.css" />
<script src="<?php echo URL_BASE;?>public/js/kkcountdown.js" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY; ?>&libraries=places,geometry&amp;sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/gmaps.js"></script>
<?php $startdate = date('Y-m-d 00:00:00'); $enddate = date('Y-m-d H:i:s'); $not_setting = "ON"; ?>
<?php
	//For search values
	$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
	$company_val = isset($srch["filter_company"]) ? $srch["filter_company"] :''; 							
	$status_val = isset($srch["status"]) ? $srch["status"] :''; 							
	$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 
	
	//For CSS class deefine in the table if the data's available
	$booking_list=count($all_booking_list);
	$table_css=$export_excel_button="";
	if($booking_list>0)
	{ 
		$table_css='class="table_border"'; 
		$export_excel_button='
	        				<input type="button"  title="'.__('button_export').'" class="button" value="'.__('button_export').'" 
	        				onclick="location.href=\''.URL_BASE.'manage/export?keyword='.$keyword.'&status='.$status_val.'&type='.$user_type_val.'\'" /> '; }?>
<!--Dashboard Current Status of The Driver -->
<div class="container_content fl clr">
	<div class="cont_container">
		<div class="content_middle">
			<div class="widget margin-bottom" >
				<div class="title">
					<!--img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /-->
					<h6><?php echo __('Current Status of Driver'); ?></h6>
					<div class="title" align="right">
						<div class="one">
							<select name="select_driver_status" id="select_driver_status" style="width:150px;height:25px;">
								<option value=""><?php echo __('all_label'); ?></option>
								<option value="A">Active</option>
								<option value="F">Free</option>
								<option value="OUT">Free and Shitout</option>
							</select>
							<div class="button blackB"> 
								<input type="button" name="search_driver_status" onclick=dashboard_map() id="search_driver_status" value="GO" title="Go" >
							</div>
						</div>
					</div>
				</div>
				<div id="on_going_trip_map" >
					<div class="ongoing">
						<div id="on_going_trip"></div>
						<div id="on_going_place"></div>
					</div>
					<?php if(SHOW_MAP !=1) { ?>
					<div id="map-canvas" style="width:100%;height:500px;"></div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!--Dashboard Current Status of The Driver -->
<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle">
			<form method="get" class="form" name="frmcompany" style="overflow: hidden; overflow-x: auto;" id="frmcompany" action="companysearch">
				<table class="list_table1" border="0" width="100%" cellpadding="6" cellspacing="0">
					<tr>
						<td width="20%">
							<div class="new_input_field">
								<input type="text" name="search_txt" style="width:150px;margin-top:22px;" maxlength="150" id="search_txt" value="" placeholder="Filter"  onKeyUp="changetableresult()"/>
							</div>
						</td>
						<td width="20%">
							<div class="new_input_field">
								<label><?php echo __('from_date'); ?></label>
								<input type="text" name="filter_date" style="width:150px;" id="filter_date" readonly value="<?php echo $current_date; ?>" onChange="changetableresult()"/>
							</div>
						</td>
						<td width="20%">
							<div class="new_input_field">
								<label><?php echo __('to_date'); ?></label>
								<input type="text" name="to_date" style="width:150px;" id="to_date" readonly value="" onChange="changetableresult()"/>
							</div>
						</td>
						<td valign="top" width="20%">
							<div class="filter" id="uniform-user_type">
								<select class="select2" name="sort_type" id="sort_type" style="margin-top:22px;">
									<option value=""><?php  echo '--'.__('sort_type').'--'; ?></option>
									<option value="1"><?php echo __('pickup_time'); ?></option>
									<option value="2"><?php echo __('customer'); ?></option>
									<option value="3"><?php echo __('journey_time'); ?></option>
									<option value="4"><?php echo __('journey_distance'); ?></option>
									<option value="5"><?php echo __('booking_type'); ?></option>
									<option value="6"><?php  echo __('booking_status'); ?></option>
									<option value="7"><?php  echo __('creation_date'); ?></option>
								</select>
							</div>
						</td>
						<td valign="top" width="10%">
							<div class="filter1" id="uniform-user_type">
								<select class="select2"  name="sort_by" id="sort_by" style="margin-top:22px;">
									<option value="desc"><?php echo __('desc'); ?></option>
									<option value="asc"><?php echo __('asc'); ?></option>
								</select>
							</div>
						</td>
						<td valign="top" width="20%">
							<div class="filter" id="uniform-user_type">
								<select class="select2" name="booking_filter" id="booking_filter" style="margin-top:22px;">
									<option value=""><?php  echo '--'.__('booking_filter').'--'; ?></option>
									<option value="0"><?php  echo __('assign'); ?></option>
									<option value="1"><?php echo __('completed'); ?></option>
									<option value="2"><?php echo __('inprogress'); ?></option>
									<option value="5"><?php echo __('waiting_payment'); ?></option>
									<option value="6"><?php echo __('missed'); ?></option>
									<option value="7"><?php echo __('dispatched'); ?></option>
									<option value="8"><?php echo __('cancelled'); ?></option>
									<option value="9"><?php echo __('confirmed'); ?></option>
									<option value="10"><?php echo __('reassign'); ?></option>
								</select>
							</div>
						</td>
					<tr/>
					<tr>
						<td colspan='2'>
							<p style="font:normal 15px 'nunitobold';"><?php echo __('search_manage_booking'); ?></p>
						</td>
					</tr>
				</table>
			</form>
			<!-- change data-->
			<div id="change_result">
				<div class="widget">
					<div class="title">
						<img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" />
						<h6><?php echo $page_title.'<span style="color:red;"> ('.__('byclicking_booking').') </span>'; ?></h6>
						<div style="width:auto; float:right; margin: 4px 3px;">
							<div class="button greyishB"> <?php //echo $export_excel_button; ?></div>
						</div>
					</div>
					<?php if($booking_list > 0){ ?>
					<div class= "overflow-block">
						<?php } ?>
						<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive" id="changetr">
							<?php if($booking_list > 0){ ?>
							<thead>
								<tr>
									<td align="left" width="10%"><?php echo __('companyname'); ?></td>
									<td align="left" width="10%"><?php echo __('time'); ?></td>
									<td align="left" width="30%" ><?php echo __('pickuploc_droploc'); ?></td>
									<td align="left" width="10%" ><?php echo __('fare'); ?></td>
									<td align="left" width="10%" ><?php echo __('payment'); ?></td>
									<td align="left" width="10%" ><?php echo __('passenger&phone'); ?></td>
									<td align="left" width="15%" ><?php echo __('driver'); ?></td>
									<td align="left" width="10%" ><?php echo __('status'); ?></td>
								</tr>
							</thead>
							<tbody>
								<?php $sno=$Offset; /* For Serial No */ $i =0;
									foreach($all_booking_list as $listings) { 
									//S.No Increment
									$sno++; $i++;	//For Odd / Even Rows
									     $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  	 
									    ?>     
								<tr class="<?php echo $trcolor; ?>" id="addtr_<?php echo $listings['pass_logid']; ?>">
									</td>
									<td><?php echo $listings['company_name']; ?></td>
									<td><?php echo $listings['pickup_time']; ?></td>
									<td>
										<?php 
											if($listings['travel_status'] == 0) {   $color = "color:red;"; } 
											elseif($listings['travel_status'] == 1) { $color = "color:green;"; }  
											elseif($listings['travel_status'] == 2) { $color = "color:orange;"; }  
											elseif($listings['travel_status'] == 3) { $color = "color:#909090;"; } 
											elseif($listings['travel_status'] == 4) { $color = "color:#303030;"; }
											elseif($listings['travel_status'] == 5) { $color = "color:#A52A2A"; } 
											elseif($listings['travel_status'] == 6) { $color = "color:blue;"; }
											elseif($listings['travel_status'] == 7) { $color = "color:#B8860B;"; }
											elseif($listings['travel_status'] == 8) { $color = "color:#9932CC;";  } 
											elseif($listings['travel_status'] == 9) { $color = "color:#2F4F4F;"; }
											elseif($listings['travel_status'] == 10) { $color = "color:#FFD700;"; } 
											?>	
										<?php echo '<span style="'.$color.'">'.$listings['current_location'].'</span></br><span>'.$listings['drop_location'].'</span></br><span style="color:red;">'.__('booking_id').' : '.$listings['pass_logid'].'</span>'; ?>
									</td>
									<td><?php echo '<span>'.$listings['approx_distance'].' '.UNIT_NAME.'</span></br><span>'.$listings['approx_duration'].'</span>'; ?></td>
									<td>	
										<?php if($listings['fixedprice'] != 0) { 
											echo '<span>'.findcompany_currency($_SESSION['company_id']).' '.$listings['fixedprice'].'</span>';
											} else { 
											echo '<span>'.findcompany_currency($_SESSION['company_id']).' '.$listings['approx_fare'].'</span>'; } ?>
									</td>
									<td><?php echo '<span>'.$listings['passenger_name'].'</span></br><span>'.$listings['passenger_phone'].'</span>'; ?></td>
									<td><?php if($listings['driver_name'] !='') { 
										echo '<span>'.$listings['driver_name'].' ('.$listings['driver_phone'].')</span></br>';
										}
										if($listings['travel_status'] == 6) { echo '<span style="color:red;">'.__('missed').'</span>'; }
										elseif($listings['travel_status'] == 7  && $listings['dispatch_time'] >= date('Y-m-d H:i:s')) { echo '<span style="color:#B8860B;">'.__('dispatched').'</span>'; }
										elseif($listings['travel_status'] == 7 ) { echo '<span style="color:#B8860B;">'.__('missed').'</span>'; }
										elseif($listings['travel_status'] == 8) { echo '<span style="color:#9932CC;">'.__('cancelled').'</span>';  } 
										elseif($listings['travel_status'] == 9) { echo '<span style="color:#2F4F4F;">'.__('confirmed').'</span>'; }
										elseif($listings['travel_status'] == 10) { echo '<span style="color:#FFD700;">'.__('driver_cancelled').'</span>'; } 
										?></td>
									<td>
										<?php 
											//$current_datetime =	date('Y-m-d H:i:s');	
											if($listings['travel_status'] == 7 && $listings['dispatch_time'] >= date('Y-m-d H:i:s')) { ?>
										<script>
											var secs = <?php echo ADMIN_NOTIFICATION_TIME;?>;
											$(document).ready(function(){
											$(".kkcount-down-<?php echo $i;?>")
											.attr('data-seconds', secs)
											.kkcountdown({
											minutesText	: ':',
											secondsText	: '',
											timeout: 60000,
											displayZeroDays : false,
											textAfterCount: '',//'Waiting for driver response',
											callback	: changedriver_status<?php echo $i;?>,
											addClass : 'errors',
											});
											});
											setTimeout(function() { getdriver_status<?php echo $i;?>() }, 5000);
											function getdriver_status<?php echo $i;?>() {
											var dataS = "value="+<?php echo $listings['pass_logid'];?>;
											var SrcPath = '<?php echo URL_BASE; ?>';
											var response;
											//$('.kkcount-down-<?php echo $i;?>').html('<img alt="ajax-loading" src="'+SrcPath+'/public/css/img/ajax-loaders/ajax-loader-1.gif" />');
											$.ajax({ 			
											type: "POST",
											url: SrcPath+"tdispatch/getdriver_status", 
											data: dataS, 
											cache: false, 
											dataType: 'html',
											success: function(response) 
											{ 		
											 changetableresult();
											$('.kkcount-down-<?php echo $i;?>').html('');	
											$('.passlog_status-<?php echo $i;?>').html(response);	
											} 
											});	
											}
											function changedriver_status<?php echo $i;?>()
											{ 
											//alert(<?php echo $listings['pass_logid'];?>);
											var dataS = "value="+<?php echo $listings['pass_logid'];?>;
											var SrcPath = '<?php echo URL_BASE; ?>';
											var response;
											//$('.kkcount-down-<?php echo $i;?>').html('<img alt="ajax-loading" src="'+SrcPath+'/public/css/img/ajax-loaders/ajax-loader-1.gif" />');
											
											$.ajax({ 			
											type: "POST",
											url: SrcPath+"tdispatch/changedriver_status", 
											data: dataS, 
											cache: false, 
											dataType: 'html',
											success: function(response) 
											{ 	
											 changetableresult();
											$('.kkcount-down-<?php echo $i;?>').html('');		
											$('.passlog_status-<?php echo $i;?>').html(response);	
											} 
											
											});	
											}
										</script>
										<span data-seconds="60" class="kkcount-down-<?php echo $i;?>"></span>
										<span class="passlog_status-<?php echo $i;?>">
										<?php } ?>
										<?php 
											if($listings['travel_status'] == 0) { echo '<div style="color:red;">'.__('assign').'</div>'; } 
											elseif($listings['travel_status'] == 1) { echo '<div style="color:green;">'.__('completed').'</div>'; }  
											elseif($listings['travel_status'] == 2) { echo '<div style="color:orange;">'.__('inprogress').'</div>'; }  
											elseif($listings['travel_status'] == 3) { echo '<div style="color:#909090;">'.__('start_to_pickup').'</div>'; } 
											elseif($listings['travel_status'] == 4) { echo '<div style="color:#303030;">'.__('cancel_by_passenger').'</div>'; }
											elseif($listings['travel_status'] == 5) { echo '<div style="color:#A52A2A">'.__('waiting_payment').'</div>'; } 
											elseif($listings['travel_status'] == 6) { echo '<div style="color:blue;">'.__('reassign').'</div>'; }
											elseif($listings['travel_status'] == 7 && $listings['dispatch_time'] >= date('Y-m-d H:i:s') ) { echo '<div style="color:#B8860B;">'.__('dispatched').'</div>'; }
											elseif($listings['travel_status'] == 7) { echo '<div style="color:#FFD700;">'.__('reassign').'</div>'; }
											elseif($listings['travel_status'] == 8) { echo '<div style="color:#9932CC;">'.__('cancelled').'</div>';  } 
											elseif($listings['travel_status'] == 9) { echo '<div style="color:#2F4F4F;">'.__('confirmed').'</div>'; }
											elseif($listings['travel_status'] == 10) { echo '<div style="color:#FFD700;">'.__('reassign').'</div>'; } 
											
											?>	
										</span>
									</td>
								</tr>
								<?php } } else{ ?> 
								<tr>
									<td class="nodata"><?php echo __('no_data'); ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php if ($booking_list > 0) { ?> 
					</div>
					<?php } ?>
				</div>
				<!-- change data-->
			</div>
		</div>
		<div class="clr">&nbsp;</div>
		<div class="pagination" id="hide_pagi">
			<?php if($booking_list > 0): ?>
			<p><?php echo $pag_data->render(); ?></p>
			<?php endif; ?> 
		</div>
		<div class="clr">&nbsp;</div>
	</div>
</div>
<script>
	var current_date = "<?php echo $current_datetime; ?>";
</script>
<?php //echo $show_popup['show_pass_logid'];exit;
	if(isset($show_popup['show_pass_logid'])) { ?>
<!-- Search Form -->
<?php  if(SHOW_MAP !='1' ) { ?>
<div id="sign_up" style="display:none;">
<?php } else { ?>
<div id="sign_up" style="display:none;width:25% !important;">
<?php } ?>
<div class="header_part"><span><?php echo __('choose_driver_from_list'); ?></span></div>
<div class="login_total">
	<div class="login_lft">
		<div class="login_form">
			<ul>
				<li>
					<div class="controls">
						<div class="new_input_field">
							<span class="add-on"></span>
							<input type="text" name="search_driver" id="search_driver" value="" onKeyUp="driver_details()">
						</div>
						<input type="hidden" name="passenger_log_id" id="passenger_log_id" value="<?php echo $show_popup['show_pass_logid']; ?>">
					</div>
				</li>
				<div id="show_process">
					<div id="driver_details"></div>
			</ul>
			</div>                        
		</div>
		<div class="login_rgt">
			<?php /*  if(SHOW_MAP !='1' ) { ?>
			<div id="dispatch_css" >
				<div id="dispatch_map"></div>
			</div>
			<?php } */?>
		</div>
	</div>
	<a id="close_x" class="close sprited" href="<?php echo URL_BASE;?>tdispatch/managebooking"></a>
</div>
<!-- Search Form End -->
<script type="text/javascript">
	var map;
	$(document).ready(function(){
	$('#close_x').click(function() {
	window.location.href = "<?php echo URL_BASE;?>tdispatch/managebooking"; });
	
	$('.js_lb_overlay').click(function() { 
	window.location.href = "<?php echo URL_BASE;?>tdispatch/managebooking"; });
	driver_details();
	
	  map = new GMaps({
	    el: '#dispatch_map',
	    lat: <?php echo $show_popup['pickup_latitude']; ?>,
	    lng: <?php echo $show_popup['pickup_longitude']; ?>,
	  });
	/*** This is used for load the map with out hidden *****/
	var center = map.getCenter();
	google.maps.event.trigger(map, "resize");
	map.setCenter(center);
	/*** This is used for load the map with out hidden *****/
	  map.addMarker({
	    lat: <?php echo $show_popup['pickup_latitude']; ?>,
	    lng: <?php echo $show_popup['pickup_longitude']; ?>,
	    title: "<?php echo __('Current_Location'); ?>",
	   /* details: { database_id: 42, author: 'HPNeo' },click: function(e){ if(console.log) console.log(e); alert('You clicked in this marker');
	    }, mouseover: function(e){ if(console.log) console.log(e);  }*/
	  });
	console.log(<?php echo $show_popup['drop_latitude']; ?>);
	console.log(<?php echo $show_popup['drop_longitude']; ?>);
	<?php if($show_popup['drop_latitude'] !="" && $show_popup['drop_longitude'] != "") { ?>
	 map.addMarker({
	    lat: <?php echo $show_popup['drop_latitude']; ?>,
	    lng: <?php echo $show_popup['drop_longitude']; ?>,
	    title: "<?php echo __('Drop_Location'); ?>",
	    /*infoWindow: {
	      content: '<p>HTML Content</p>'
	    }*/
	  });
	<?php } ?>
	});
</script>
<style>
	#dispatch_map{
	display: block; width: 100% !important;height: 380px !important;margin-top:20px ;margin-left:-20px;position:absolute !important;
	-moz-box-shadow: 0px 5px 20px #ccc !important; -webkit-box-shadow: 0px 5px 20px #ccc !important; box-shadow: 0px 5px 20px #ccc !important; }
	#dispatch_map.large{ height:380px !important; }
</style>
<?php } ?>
<script>
	$(document).ready(function(){
	initialize();
	function initialize() 
		{  
			var map;
			var bounds = new google.maps.LatLngBounds();
			var mapOptions = {
				mapTypeId: 'roadmap'
			};
			var markers=[];
				 <?php
		$a=0;
		$b=5 ;
		if(count($all_company_map_list) > 0)
		{		
			 foreach($all_company_map_list as $v)
			{ 
				for($b=0;$b<6;$b++)
				{
					if($b==0)
					{  ?>
							markers [<?php echo $a; ?>] = new Array(5);
							markers[<?php echo $a; ?>][<?php echo $b; ?>]=<?php echo $v['latitude']; ?>;
						<?php }
		if($b==1)
		{ ?>
							markers[<?php echo $a; ?>][<?php echo $b; ?>]=<?php echo $v['longitude']; ?>;
						<?php }
		if($b==2)
		{ ?>
							markers[<?php echo $a; ?>][<?php echo $b; ?>]='<?php echo '<div class="marker-info-win"><div class="marker-inner-win"><span class="info-content"><b>'.__('driver_name').'</b> : '.$v['name']; ?>';
						<?php }
		if($b==3)
		{ $driver_status =($v['driver_status']=='F')?__('Free'):(($v['driver_status']=='A')?"<span>".__('Hired')."</span>":__('Free'));?>
							markers[<?php echo $a; ?>][<?php echo $b; ?>]='<?php echo '<div id="bodyContent"><p><b>'.__('driver_status').'</b>: <b style="color:green;">'.$driver_status.'</b>'; ?>';
						<?php }
		if($b==4)
		{ $shift_status=($v['shift_status']=='IN')?__('in'):__('out');?>
							markers[<?php echo $a; ?>][<?php echo $b; ?>]='<?php echo '<b style="color:#0F9ED6;">'.$shift_status.'</b></p></div></span></div></div>'; ?>';
						<?php }
		if($b==5){
		if($v['driver_status']=='F' && $v['shift_status']=='OUT'){ ?>
							markers[<?php echo $a; ?>][<?php echo $b; ?>]='<?php echo PUBLIC_IMGPATH.'/driver_four.png'; ?>';
						<?php }elseif($v['driver_status']=='A'){ ?>
							markers[<?php echo $a; ?>][<?php echo $b; ?>]='<?php echo PUBLIC_IMGPATH.'/driver_one.png'; ?>';
						<?php }else{ ?>
							markers[<?php echo $a; ?>][<?php echo $b; ?>]='<?php echo PUBLIC_IMGPATH.'/driver_two.png'; ?>';
						<?php }
		}
		}
		$a++;
		}
		}
		?>   
			    
			// Display a map on the page
			map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
			map.setTilt(45);
			if(markers != "")
			{
				  for( i = 0; i < markers.length; i++ ) { 
				// Display multiple markers on a map
				var infoWindow = new google.maps.InfoWindow(), marker, i;
				var iconBase = '<?php echo PUBLIC_IMGPATH.'/' ; ?>';
					   
				// Loop through our array of markers & place each one on the map  
					var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
					bounds.extend(position);
					marker = new google.maps.Marker({
						position: position,
						map: map,
						animation: google.maps.Animation.DROP,
						//icon: iconBase + 'car.png',
						icon: markers[i][5],
						//closeclick:true,
						//title: markers[i][2]
					});
					// Allow each marker  to have an info window
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infoWindow.setContent(markers[i][2]+markers[i][3]+'		'+markers[i][4]);
							infoWindow.open(map, marker);
						}
					})(marker, i));
					;
					// Automatically center the map fitting all markers on the screen
					map.fitBounds(bounds);	
				}
			}
			else
			{  $('#on_going_trip_map').html('No drivers found');	 }
		
			// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
			var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
				//this.setZoom(12);
				google.maps.event.removeListener(boundsListener);
			});
		} });
</script>
<script>
	/*** Map of taxis **********/
			function dashboard_map()
			{
					//initialize();
					var status = $("#select_driver_status").val();
					if(status !=""){
						var driver_status=$("#select_driver_status").val();
					}else{
						var driver_status="";
					}
				
			if(driver_status!='')
			{
				$('#map-canvas').html('<img alt="ajax-loading" src="'+SrcPath+'/public/css/img/ajax-loaders/ajax-loader-1.gif" />');
				var Path = "<?php echo URL_BASE; ?>";
				
				if(driver_status!=""){
					var dataS = "driver_status="+driver_status;
					var url_path = Path+"dashboard/driver_status_details_search";
				}
				
				var markers=new Array();
				  $.ajax({
					type: "GET",
					url:url_path,
					data: dataS, 
					async: true,
					//contentType: "application/json; charset=utf-8",
					//dataType: "json",
					success:function(data){
						
						if(data != "")
						{
								markers=$.parseJSON(data);
								var map;
								var bounds = new google.maps.LatLngBounds();
								var mapOptions = {
									mapTypeId: 'roadmap'
								};
								// Display a map on the page
								map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
								map.setTilt(45);
	
								  for( i = 0; i < markers.length; i++ ) { 
								// Display multiple markers on a map
								var infoWindow = new google.maps.InfoWindow(), marker, i;
								var iconBase = '<?php echo PUBLIC_IMGPATH."/" ; ?>';
								
								// Loop through our array of markers & place each one on the map  
							   
									var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
									bounds.extend(position);
									marker = new google.maps.Marker({
										position: position,
										map: map,
										animation: google.maps.Animation.DROP,
										//icon: iconBase + 'car.png',
										icon: markers[i][5],
										//title: markers[i][2]
									});
									// Allow each marker  to have an info window
									google.maps.event.addListener(marker, 'click', (function(marker, i) {
										return function() {
											infoWindow.setContent(markers[i][2]+markers[i][3]+'		'+markers[i][4]);
											infoWindow.open(map, marker);
										}
									})(marker, i));
									;
									// Automatically center the map fitting all markers on the screen
									map.fitBounds(bounds);
								}
								// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
								var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
									//this.setZoom(4);
									google.maps.event.removeListener(boundsListener);
								});
							}
							else
							{
								$('#map-canvas').html('<div class="map-canvas">No data found</div>');	
							/*	$('.nodata').css('height','50px');
								$('.nodata').css('width','100%');
								$('.nodata').css('background-color','white');*/
							}
					},
					error:function() { //alert('failed'); 
					}
				});
			}else{
				
				$('#map-canvas').html('<img alt="ajax-loading" src="'+SrcPath+'/public/css/img/ajax-loaders/ajax-loader-1.gif" />');	
				var Path = "<?php echo URL_BASE; ?>";
				var url_path = Path+"manage/view_all_drivers";
				var markers=new Array();
				
				  $.ajax({
					url:url_path,
					async: true,
					//contentType: "application/json; charset=utf-8",
					//dataType: "json",
					success:function(response){
						console.log(response);
						if(response != "")
						{
								markers=$.parseJSON(response);
								var map;
								var bounds = new google.maps.LatLngBounds();
								var mapOptions = {
									mapTypeId: 'roadmap'
								};
								// Display a map on the page
								map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
								map.setTilt(45);
	
								  for( i = 0; i < markers.length; i++ ) { 
								// Display multiple markers on a map
								var infoWindow = new google.maps.InfoWindow(), marker, i;
								var iconBase = '<?php echo PUBLIC_IMGPATH.'/' ; ?>';
								
								// Loop through our array of markers & place each one on the map  
									var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
									bounds.extend(position);
									marker = new google.maps.Marker({
										position: position,
										map: map,
										animation: google.maps.Animation.DROP,
										//icon: iconBase + 'car.png',
										icon: markers[i][5],
										//title: markers[i][2]
									});
									// Allow each marker  to have an info window
									google.maps.event.addListener(marker, 'click', (function(marker, i) {
										return function() {
											infoWindow.setContent(markers[i][2]+markers[i][3]+'		'+markers[i][4]);
											infoWindow.open(map, marker);
										}
									})(marker, i));
									;
									// Automatically center the map fitting all markers on the screen
									map.fitBounds(bounds);
								}
								// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
								var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
									//this.setZoom(4);
									google.maps.event.removeListener(boundsListener);
								});
							}
							else
							{
								$('#map-canvas').html('No drivers found');	
							}
					},
					error:function() { //alert('failed'); 
					}
				});
			}
		}
</script>
