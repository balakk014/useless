<?php defined('SYSPATH') OR die("No direct access allowed.");?>

<style>
		.alert-message {
		    font-family: sans-serif;
		    margin: 20px;
		    font-weight: bold;
		    
		    -moz-border-radius: 3px;
		    -webkit-border-radius: 3px;
		    border-radius: 3px;
		    
		    padding: 1px;
		    position: relative;
		    font-size: 12px;
		    width:335px;
		}

		.alert-message .close {
		    color: #745050;
		    text-decoration: none;
		    float: right;
		    margin: 7px 7px 0 0;
		    font-weight: bold;
		    font-size: 16px;
		}

		.alert-message p {
		    display: block;
		    margin: 0;
		    padding: 8px 20px 7px 10px;
		    
		    -moz-border-radius: 3px;
		    -webkit-border-radius: 3px;
		    border-radius: 3px;
		}

		.errorp {
		    background-color: #d29191;
		    
		    -webkit-box-shadow: 0px 0px 6px rgba(244,187,187,0.7);
		    -moz-box-shadow: 0px 0px 6px rgba(244,187,187,0.7);
		    box-shadow: 0px 0px 6px rgba(244,187,187,0.7);
		}

		.errorp p {
		    background-color: #f4baba;
		    
		    background-image: -webkit-gradient(linear, left top, left bottom, from(#f4baba), to(#db7f7f));
		    background-image: -webkit-linear-gradient(top, #f4baba, #db7f7f);
		    background-image: -moz-linear-gradient(top, #f4baba, #db7f7f);
		    background-image: -ms-linear-gradient(top, #f4baba, #db7f7f);
		    background-image: -o-linear-gradient(top, #f4baba, #db7f7f);
		    background-image: linear-gradient(top, #f4baba, #db7f7f);
		    filter:progid:DXImageTransform.Microsoft.gradient(startColorStr='#f4baba', EndColorStr='#db7f7f');
		    
		    -webkit-box-shadow: inset 0px 1px 0px #f7d0d0;
		    -moz-box-shadow: inset 0px 1px 0px #f7d0d0;
		    box-shadow: inset 0px 1px 0px #f7d0d0;
		    color: #745050;
		    text-shadow: 1px 1px 0px #eaadad;
		    font-size:13px;
		}
		</style>

	

				<!-- Validation start -->			
		<!--<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/site.js"></script>-->
		
		<!-- time picker start-->
		<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
		
		<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>
		<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
		<!--
		<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/calendar/jquery-ui.multidatespicker.js"></script>
		-->
		
		<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/jquery.autocomplete.js"></script>
		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/tdispatch_editbooking.js"></script>
		
		<!-- time picker start-->
		<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE;?>public/css/jquery.autocomplete.css" />
		<link href="<?php echo URL_BASE;?>public/css/starrating.css" rel="stylesheet" type="text/css" media="screen" />
		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
		<!--script src="<?php echo URL_BASE;?>public/js/remote_cont.js" type="text/javascript"></script-->
		<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/jquery.lightbox_me.js"></script>
		<link rel="stylesheet" href="<?php echo URL_BASE;?>public/css/dispatch_popup.css" />



	<div class="errorp alert-message"><a  class="close"></a><p><?php echo  __('note').' : '.__('create_user_not_exist');?></p></div>

	<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">   
	<div class="first_left">	
		<ul>	
		<li class=""><a href="javascript:;" id="edit_booking">Edit</a></li>
		<li class=""><a href="javascript:;" id="log_booking">Log</a></li>
		<li class=""><a href="javascript:;" id="feedback_booking">Feedback</a></li>
		<li class=""><a href="javascript:;" id="share_booking">share</a></li>
		</ul>
	</div> 
         <form name="editbooking_form" class="form" id="editbooking_form" action="" method="post" enctype="multipart/form-data" onSubmit="check_passengerexit()">
<div id="edit_details">
	<div class="first_fieldset">	
	<fieldset class="booking-form-fieldset">
	<legend><?php echo strtoupper(__('passengers_information')); ?></legend>
	   <div class="new_booking_field ">
              <input type="text" title="<?php echo __('enterfirstname_msg'); ?>" class="required" name="firstname" id="firstname" value="<?php echo $edit_bookingdetails[0]['passenger_name']; ?> "  minlength="4" placeholder="<?php echo __('firstname'); ?>"  autocomplete="off" />
		<input name="passenger_id" id="passenger_id" type="hidden" value=" <?php echo $edit_bookingdetails[0]['passengers_id']; ?>" >

              <?php if(isset($errors) && array_key_exists('firstname',$errors)){ echo "<span class='error'>".ucfirst($errors['firstname'])."</span>"; } ?>
		  <span id="unameavilable" class="error"> </span></div>
		   <div class="new_booking_field mt10">
              <input type="text" title="<?php echo __('enteremailaddress'); ?>" class="required email" name="email" id="email" value="<?php echo $edit_bookingdetails[0]['passenger_email']; ?>"  maxlength="50" placeholder="<?php echo __('email'); ?>" autocomplete="off"  />

              <?php if(isset($errors) && array_key_exists('email',$errors)){ echo "<span class='error'>".ucfirst($errors['email'])."</span>"; } ?>
		<span id="uemailavilable" class="error"> </span></div>

		<div class="new_booking_field mt10"><input type="text" title=<?php echo __('entermobileno'); ?>" name="phone" id="phone" class="required" value="<?php echo $edit_bookingdetails[0]['passenger_phone']; ?>" minlength="7" maxlength="20" placeholder="<?php echo __('mobile'); ?>" autocomplete="off" />
              <?php if(isset($errors) && array_key_exists('phone',$errors)){ echo "<span class='error'>".ucfirst($errors['phone'])."</span>";} ?>

		<span id="uphoneavilable" class="error"> </span></div>


		<div id="show_group" style="display:none"><div class="formRight mt10">
		<!-- <div class="selector" id="usergroup_list"></div>--></div>
		<label for="group_id" generated="true" class="errorvalid" style="display:none"><?php echo __('select_group'); ?></label>
		</div><div class="new_booking_field mt10 mb50"></div></fieldset>

		<fieldset class="booking-form-fieldset">
		<legend><?php echo strtoupper(__('booking')); ?></legend><div class="new_booking_field">

		<input type="text" title="<?php echo __('enter_currentlocation'); ?>" class="required" name="current_location" id="edit_current_location" value="<?php echo $edit_bookingdetails[0]['current_location']; ?> "  autocomplete="off"  placeholder="<?php echo __('enter_currentlocation'); ?>" onchange="GeocodeFromAddress()"/>
		<?php if(isset($errors) && array_key_exists('current_location',$errors)){ echo "<span class='error'>".ucfirst($errors['current_location'])."</span>";} ?>

		<input type="hidden" name="pickup_lat" id="pickup_lat" value="<?php echo $edit_bookingdetails[0]['pickup_latitude']; ?>"><input type="hidden" name="pickup_lng" id="pickup_lng" value="<?php echo $edit_bookingdetails[0]['pickup_longitude']; ?>"></div>

		<div class="new_booking_field mt10"><input type="text" title="<?php echo __('enter_droplocation'); ?>"  name="drop_location" id="edit_drop_location" value="<?php echo $edit_bookingdetails[0]['drop_location'] ?>"  autocomplete="off" placeholder="<?php echo __('enter_droplocation'); ?>" onchange="GeocodeFromAddress()"/>
            <?php  if(isset($errors) && array_key_exists('drop_location',$errors)){ echo "<span class='error'>".ucfirst($errors['drop_location'])."</span>";} ?>
		<input type="hidden" name="drop_lat" id="drop_lat" value="<?php echo $edit_bookingdetails[0]['drop_latitude']; ?>"><input type="hidden" name="drop_lng" id="drop_lng" value="<?php echo $edit_bookingdetails[0]['drop_longitude']; ?>">
		   </div>
		<div class="new_booking_field mt10">
		      <input type="text" title="<?php echo __('note_driver'); ?>" name="notes" id="notes" value="<?php echo $edit_bookingdetails[0]['notes_driver']; ?>"  autocomplete="off"  placeholder="<?php echo __('note_driver'); ?>" />		     	
		   </div>
  	      <div class="new_booking_field mt10"><span class="first"><input type="text" title="<?php echo __('pickup_date'); ?>" class="" name="pickup_date" id="pickup_date" value="<?php echo $pickup_date; ?>"  autocomplete="off" placeholder="<?php echo __('today'); ?>" readonly />
	<?php	if(isset($errors) && array_key_exists('pickup_date',$errors)){ echo "<span class='error'>".ucfirst($errors['pickup_date'])."</span>";} ?>
			</span><span class="second">
		      <input type="text" title="<?php echo __('pickup_time'); ?>" class="" name="pickup_time" id="pickup_time" value="<?php echo $pickup_time; ?>"  autocomplete="off" placeholder="<?php echo __('now'); ?>" readonly />
		<?php if(isset($errors) && array_key_exists('pickup_time',$errors)){ echo "<span class='error'>".ucfirst($errors['pickup_time'])."</span>";} ?>
			</span></div><div class="new_booking_field mt10"><span class="first mt10 ">	
		      <input type="text" title="<?php echo __('no_luggage'); ?>" class="required onlynumbers" name="luggage" id="luggage" value="<?php echo $edit_bookingdetails[0]['luggage']; ?>"  autocomplete="off" placeholder="<?php echo __('luggage'); ?>" />
		 <?php if(isset($errors) && array_key_exists('luggage',$errors)){ echo "<span class='error'>".ucfirst($errors['luggage'])."</span>";} ?>
			</span><span class="second mt10"><input type="text" title="<?php echo __('no_passengers'); ?>" class="required number" name="no_passengers" id="no_passengers" value="<?php echo $edit_bookingdetails[0]['no_passengers']; ?>"  autocomplete="off" placeholder="<?php echo __('passengers'); ?>" />
		 <?php if(isset($errors) && array_key_exists('no_passengers',$errors)){ echo "<span class='error'>".ucfirst($errors['no_passengers'])."</span>"; } ?>
			</span></div><div class="new_booking_field mb20 clr"></div></fieldset></div>


	<div class="second_fieldset third_fieldset fl ml20"><div id="show_suggestion"><div id="show_suggested"><?php echo __('show_suggested'); ?></div><div id="locations_journeys" class="locations"><h2><?php echo __('favorite_journey'); ?></h2><ul id="load_journey">
		<?php if(count($get_suggestedjourney) > 0)
		{
			foreach($get_suggestedjourney as $details)
			{


				echo "<li title=\"".__('from').' '.$details['from_location'].' '.__('to').' '.$details['to_location']."\"><a href=\"javascript:;\" id=\"change_fromtolocation\" onclick=\"change_fromtolocation('".urlencode($details['from_location'])."','".urlencode($details['to_location'])."');\"><span class=\"journey-from\">".__('from').":</span><span class='journey-name'>".$details['from_location']."</span><span class='journey-to'>".__('to').":</span><span class='journey-name'>".$details['to_location']."</span></a></li>";

			}
		}
		else
		{
				echo '<li>'.__('no_data').'</li>';
		}
		?>

		</ul></div><div id="locations_locations" class="locations mt20 mb20"><h2><?php echo __('favorite_locations'); ?></h2><ul id="load_location">
		<?php if(count($get_suggestedlocation) > 0)
		{
			foreach($get_suggestedlocation as $details)
			{
				echo '<li class="" id="selectclass_'.$details['fid'].'"><a href="javascript:void(0)" onclick="popup_location('.$details['fid'].');" id="fid_'.$details['fid'].'">'.$details['location'].'</a></li>';
			}
		}
		else
		{
			echo '<li>'.__('no_data').'</li>';
		}
		?>
		</ul></div></div>



			<fieldset class="booking-form-fieldset"><legend><?php echo strtoupper(__('vehicle')); ?></legend><div class="formRight"><div class="selector" id="uniform-user_type">

		     <?php  $field_type = $edit_bookingdetails[0]['taxi_modelid']; ?>
	
		      <select name="taxi_model" id="taxi_model" class="required" title="'<?php __('select_the_taximodel'); ?>" OnChange="change_minfare(this.value,'edit');"><option value=""><?php echo __('select_label'); ?></option>
			<?php foreach($model_details as $list) {  ?>
		      <option value="<?php echo $list['model_id']; ?>" <?php if($field_type == $list['model_id']) { echo 'selected=selected'; } ?> >  <?php echo ucfirst($list['model_name']); ?></option>		    
		      <?php   } ?>
		     </select></div></div><label for="taxi_model" generated="true" class="errorvalid" style="display:none;width:250px;"><?php echo __('select_the_taximodel'); ?></label><div class="new_booking_field mt20 mb50"></div></fieldset>

			<?php 
			/*<fieldset class="booking-form-fieldset"><legend><?php echo strtoupper(__('extra')); ?></legend>
			
            if($field_count > 0)
            {
		    for($i=0; $i<$field_count; $i++)
		    { 
		    	$field_name = $additional_fields[$i]['field_name']; 
			echo '<tr><td valign="top" width="20%"><div class="new_input_field"><label>'.$additional_fields[$i]['field_labelname'].'</label></div></td><td><div class="new_input_field">';
		
			if($additional_fields[$i]['field_type'] == 'Textbox')
			{  
				$field_val = get_val($field_name,$specific_additional_fields);

	                	echo '<input type="text" title="'.__('enter_the').$additional_fields[$i]['field_labelname'].'" class="" id="'.$field_name.'" name="addition_fields['.$field_name.']" value="'.$field_val.'" maxlength="20" />';
 			}
	                else if($additional_fields[$i]['field_type'] == 'Checkbox')
	                { 
		                $field_val = get_val($field_name,$specific_additional_fields);
	                        $field_chkvalue = ''; 
	            	 	if(isset($postvalue) && array_key_exists($field_name,$postvalue)){ $field_chkvalue =  $postvalue[$field_name]; }
	            	 	
	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);
	                 	
	                 	foreach($field_value as $key => $value)
	                 	{ 
	                echo '<input type="checkbox" title="'.__('enter_the').'"  name="addition_fields['.$field_name.']" value="'.$value.'"';
			if(trim($field_val) == trim($value)) { echo 'checked'; } echo ' />'.$value;                	
	                 	
	                 	}
			}
		        else if($additional_fields[$i]['field_type'] == 'Radio')
	                {  
				$field_val = get_val($field_name,$specific_additional_fields);
	            	 
	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);
	                 	
	                 	foreach($field_value as $key => $value)
	                 	{ 
	               echo '<input type="radio" title="'.__('select_the').$field_name.'" class="" name="addition_fields['.$field_name.']" id="'.$field_name.'" value="'.$value.'"';
				if(trim($field_val) == trim($value)) { echo ' checked'; } echo ' />'.$value;
	                 	
	                 	}
	               }
	                else if($additional_fields[$i]['field_type'] == 'Select')
	                { 
			 $field_val = get_val($field_name,$specific_additional_fields);
	            	 $field_selvalue =''; 

	            	 if(isset($postvalue) && array_key_exists($field_name,$postvalue)){ $field_selvalue =  $postvalue[$field_name]; }

	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);

				echo '<div class="formRight"><div class="selector" id="uniform-user_type"><span>'.__('select_label').'</span><select name="addition_fields['.$field_name.']" id="'.$field_name.'" class="" title="'.__('select_the').$field_name.'"><option value="">--Select--</option>';

	                 	foreach($field_value as $key => $value)
	                 	{ 
				echo '<option value="'.$value.'"';

				if(trim($field_val) == trim($value)) { echo 'selected=selected'; } echo '>'.ucfirst($value).'</option>';
	                 	}
	                 	echo '</select></div></div>';
			}

	                echo '<label for="'.$field_name.'" generated="true" style="display:none" class="errorvalid">'.__('select_the').$field_name.'</label>';              	 
		       if(isset($errors) && array_key_exists($field_name,$errors)){ echo "<span class='error'>".ucfirst($errors[$field_name])."</span>"; }

	                echo '</div></td></tr>';
		      }
            } 

		 <div class="new_booking_field mt20 mb50"></div></fieldset> */?></div>

	<div class="second_fieldset  fl ml50" id="payment_sec"><fieldset class="booking-form-fieldset"><legend><?php echo strtoupper(__('payment')) ?> </legend><div class="new_booking_field ">
	<?php 
	/*$query2 = "SELECT * FROM payment_modules where pay_mod_active='1'  and pay_mod_name!='uncard' order by pay_mod_name asc";

				$result = Db::query(Database::SELECT, $query2)
						->execute()
						->as_array();

				foreach($result as $resultset) { 
					echo '<span class="second1"><input type="radio" class="required " name="payment_type" value="'.$resultset['pay_mod_id'].'"';
					if($edit_bookingdetails[0]['faretype'] == $resultset['pay_mod_id']) { echo ' checked'; } else { echo ''; }echo ' />'.$resultset['pay_mod_name'].'</span>';
				}*/
				?>
				<input type="hidden" name="payment_type" value=""/>
	<span id="payment_error" class="error" style="display:none;"></span></div>

	<div class="new_booking_field mt10"><ul id="acc"><!--<li><label><?php //echo __('account'); ?> :</label><span></span></li>--><li><label><?php echo __('journey'); ?> :</label><span id="edit_find_duration"><?php echo $edit_bookingdetails[0]['approx_duration']; ?></span></li><li><label><?php  echo __('distance'); ?> :</label><span id="edit_find_km"><?php echo $edit_bookingdetails[0]['approx_distance'].'km'; ?></span></li>

	<li><label><?php echo __('tax'); ?> :</label><span>%</span><span id="vat_tax"><?php echo $company_tax ; ?></span></li>
	<li><label><?php echo __('fare'); ?> :</label><span id="edit_min_fare" class=""><?php echo $edit_bookingdetails[0]['approx_fare']; ?></span><span><?php echo findcompany_currency($_SESSION['company_id']); ?></span></li></ul></div>

	<div class="new_booking_field "><label><?php echo __('fixedprice'); ?> :</label><span style="width:50%;float:right !important;"><input type="text" title="<?php echo __('enter_override_price'); ?>" class="onlynumbers" name="fixedprice" id="fixedprice" value="<?php echo $edit_bookingdetails[0]['fixedprice']; ?>"  maxlength="6" placeholder="<?php echo __('override_price'); ?>" autocomplete="off" /></span>
	<?php if(isset($errors) && array_key_exists('fixedprice',$errors)){ echo "<span class='error'>".ucfirst($errors['fixedprice'])."</span>"; } ?>
	</div><div class="new_booking_field mt10 clr"><ul id="acc"><li><label></label><span id="edit_total_price"><?php echo $edit_bookingdetails[0]['approx_fare'];?> </span><span><?php echo __('total').':'.findcompany_currency($_SESSION['company_id']); ?></span></li></ul></div>	


	<div class="new_booking_field mt10"><ul id="acc" style="display:none;"><li><label><?php echo __('description').':'; ?></label><span id="desc"><?php echo __('rate_kilometer').' '.$edit_bookingdetails[0]['approx_distance']?></span></li>
	<li><label><?php echo __('value').':'; ?></label><span><?php echo findcompany_currency($_SESSION['company_id']); ?></span><span id="min_value"><?php //echo $edit_bookingdetails[0]['min_fare']; ?>
	</span></li><li><label><?php echo __('subtotal'); ?>:</label><span><?php echo findcompany_currency($_SESSION['company_id']); ?></span><span id="edit_sub_total"><?php echo $edit_bookingdetails[0]['approx_fare']; ?></span></li></ul><input type="hidden" name="model_minfare" id="model_minfare" value="<?php //echo $edit_bookingdetails[0]['min_fare']; ?>" >
	<input type="hidden" name="model_id" id="model_id" value="<?php echo $edit_bookingdetails[0]['taxi_modelid']; ?>" >
	<input type="hidden" name="distance_km" id="edit_distance_km" value="<?php echo $edit_bookingdetails[0]['approx_distance']; ?>" >
	<input type="hidden" name="total_fare" id="edit_total_fare" value="<?php echo $edit_bookingdetails[0]['approx_fare']; ?>" >
	<input type="hidden" name="total_duration" id="edit_total_duration" value="<?php echo $edit_bookingdetails[0]['approx_duration']; ?>" >
	<input type="hidden" name="city_id" id="city_id" value="<?php echo $edit_bookingdetails[0]['search_city']; ?>" >
	<input type="hidden" name="cityname" id="cityname" value="" >
	 <input type="hidden" name="company_tax" id="company_tax" value="<?php echo $company_tax; ?>" >
	 <input type="hidden" name="default_company_unit" id="default_company_unit" value="<?php echo UNIT_NAME; ?>" >
	 <input type="hidden" name="pass_logid" id="pass_logid" value="<?php echo $edit_bookingdetails[0]['pass_logid']; ?>" >
<?php 
	$div = 0;
	if($edit_bookingdetails[0]['travel_status'] !=1)
	{ ?>

	<div class="mt10 button greenB  mb20" ><input type="submit" name="update" value="<?php echo __('update'); ?>" ></div>
<?php  
	if($edit_bookingdetails[0]['travel_status'] !=9 && $edit_bookingdetails[0]['travel_status'] !=8)
	{ ?>
		<div class="mt10 button greenB fr" ><input type="submit" name="dispatch_button" id="dispatch_button" value="<?php echo __('dispatch'); ?>" ></div>
<?php  
	}

	if($edit_bookingdetails[0]['travel_status'] ==9 || $edit_bookingdetails[0]['travel_status'] ==2 || $edit_bookingdetails[0]['travel_status'] ==5 )
	{
	$div = 1; 
	?>
	</div><div class="mt10 button blackB  mb20" ><input type="submit" name="complete_button" id="complete_button" value="<?php echo __('complete'); ?>" ></div>
	<?php }

	if($edit_bookingdetails[0]['travel_status'] !=2 )
	{
		if($div == 1)
		{
			?>
			<div class="mt10 button greenB fr " style="margin-right: 25px;" ><input type="submit" name="cancel_button" id="cancel_button" value="<?php echo  __('cancel'); ?>" ></div>
		<?php
		}	
		else
		{ ?>
			</div><div class="mt10 button greenB fr mb20" style="margin-right: 0;" ><input type="submit" name="cancel_button" id="cancel_button" value="<?php echo __('cancel'); ?>" ></div>
		<?php }
	}

	} ?>
	<div class="new_booking_field mt20 "></div></fieldset>

	<fieldset class="booking-form-fieldset"><legend><?php echo strtoupper(__('map')); ?></legend>
<?php
	if(SHOW_MAP ==1 ) { 
		echo '<div id="map" style="display:none;"></div>';//<div>Show Suggesstion Route</div>
	}
	else
	{ 
		echo '<div id="map" ></div>';
	} 	?>


	<div id="edit_directions"></div><div style="display:none;"><table><tr><td>Start altitude:</td><td id="start"></td></tr><tr><td>End altitude:</td><td id="end"></td></tr><tr><td>Maximum altitude:</td><td id="max"></td></tr><tr><td>Minimum altitude:</td><td id="min"></td></tr><tr><td>Distance:</td><td id="distance"></td></tr><tr><td>Total ascent:</td><td id="ascent"></td></tr><tr><td>Total descent:</td>	<td id="descent"></td></tr></table></div>

	<div class="new_booking_field mb20" ></div></fieldset></div></div></form>
	</div>

	<div id="log_details" style="display:none;">
<?php  
		foreach($log_details as $log_data)
		{
			echo '<div style="margin-bottom:4px;">'.$log_data['log_createdate'].'</div>';
			echo '<div class="log_message">'.$log_data['log_booking'].'</div>';
		}
		?>

	</div>	

	<div id="feedback_details" style="display:none;">
	<form name="sharing_form" class="form" id="sharing_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%" style="border:none;float:left;">
           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"><label><?php echo  __('customer'); ?></label></td>        
		   <td style="border:none;"><?php echo __('customer_not_feedback'); ?></td>
	   </tr>
           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"><label><?php echo __('controllers'); ?></label></td>        
		<td style="border:none;"><label><?php echo __('give_your_rating'); ?></label></td>
	   </tr>

           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"></td>        
		<td style="border:none;">
			<ul class="star-rating">
			<li class="current-rating" id="current-rating" style="width: <?php echo (($edit_bookingdetails[0]['controller_rating'] * 26) - 8); ?>'px; background: url('<?php echo URL_BASE ?>'public/images/alt_star.gif) left center!important; left: 0px!important; margin: 0px!important"></li>
			<span id="ratelinks"><li><a href="javascript:rateProduct(1,'<?php echo $edit_bookingdetails[0]['pass_logid']; ?>')" title="<?php echo ('star1'); ?>" class="one-star">1</a></li>
			<li><a href="javascript:rateProduct(2,<?php echo $edit_bookingdetails[0]['pass_logid']; ?>)" title="<?php echo __('star2'); ?>" class="two-stars">2</a></li>
			<li><a href="javascript:rateProduct(3,<?php echo $edit_bookingdetails[0]['pass_logid']; ?>)" title="<?php echo __('star3'); ?>" class="three-stars">3</a></li>
			<li><a href="javascript:rateProduct(4,<?php echo $edit_bookingdetails[0]['pass_logid']; ?>)" title="<?php echo __('star4'); ?>" class="four-stars">4</a></li>
			<li><a href="javascript:rateProduct(5,<?php echo $edit_bookingdetails[0]['pass_logid']; ?>)" title="<?php echo __('star5'); ?>" class="five-stars">5</a></li></span>
			</ul>
			<p style="margin-top: 15px;"><span id="ratingtext"></span></p>
		</td>
	   </tr>

           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"></td>        
		<td style="border:none;"><label><?php echo __('write_your_comment'); ?></label></td>
	   </tr>

           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"></td>        
		<td style="border:none;">
			<div class="new_input_field">
			<textarea type="text" title="<?php echo __('please_enter_comment'); ?>" name="feedback_comment" id="feedback_comment" cols="5" rows="5" ></textarea>
			</div>
			<span class="error" id="feedback_commenterror"></span>
	</td>
	   </tr>

	   <tr style="border:none;"> <td style="border:none;"></td>
	   <td style="border:none;"><div class="button greenB">  <input type="button" id="rating_button" value="<?php echo __('submit' );?>" name="submit_ratingbutton" title="<?php echo __('submit' ); ?>" /></div>
                            </td>	
	   </tr>
	   <tr style="border:none;"> <td style="border:none;"></td>
	   <td style="border:none;"><div id="feedback_success"></div>
                            </td>	
	   </tr>		
	</table></form>
	</div>

	<div id="share_details" style="display:none;">
	<form name="sharing_form" class="form" id="sharing_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%" style="border:none;float:left;">
           <tr style="border:none;">
           <td style="border:none;"><h4><?php echo __('manual_sharing'); ?></h4></td>        
	   <td style="border:none;"></td>
	   </tr>
           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"><label><?php echo __('email_label'); ?></label><span class="star">*</span></td>        
		   <td style="border:none;">
			<div class="new_input_field">
			<input type="text" title="<?php echo __('please_provide_email'); ?>" name="share_email" id="share_email" value="" placeholder="<?php echo __('emaillabel'); ?>"/>
			</div>
			<span class="error" id="share_emailerror"></span>
		   </td>
	   </tr>
           <tr style="border:none;">
           <td style="border:none;" valign="top" width="20%"><label><?php echo __('note'); ?></label><span class="star">*</span></td>        
		<td style="border:none;">
			<div class="new_input_field">
			<textarea type="text" title="<?php echo __('please_enter_note'); ?>" name="share_note" id="share_note" cols="5" rows="5" ></textarea>
			</div>
			<span class="error" id="share_noteerror"></span>
		</td>
	   </tr>
	   <tr style="border:none;"> <td style="border:none;"></td>
	   <td style="border:none;"><div class="button greenB">  <input type="button" id="sharing_button" value="<?php echo  __('submit' ); ?>" name="submit_sharebooking" title="<?php echo __('submit' ); ?>" /></div>
                            </td>	
	   </tr>
	   <tr style="border:none;"> <td style="border:none;"></td>
	   <td style="border:none;"><div id="shared_success"></div>
           </td>	
	   </tr>		
	</table></form>
	</div>	


	</div><div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>

	</div></div></div>

	 <?php if(SHOW_MAP == 1){ ?>

		<div id="dispatch_popup" style="display:none;width:25% !important;"><div class="header_part"><span><?php echo __('choose_driver_from_list'); ?></span></div><div class="login_total"><div class="login_lft"><div class="login_form"><ul><li><div class="controls"><div class="new_input_field"><span class="add-on"><i class=""></i></span><input type="text" name="search_driver" id="search_driver" value="" onKeyUp="driver_details()"></div><input type="hidden" name="passenger_log_id" id="passenger_log_id" value="<?php echo $edit_bookingdetails[0]['pass_logid']; ?>"></div></li><div id="show_process"><div id="driver_details"></div></ul></div></div><div class="login_rgt"><div id="dispatch_css" ></div></div></div><a id="close_x" class="close sprited" onclick="window.location.reload();"></a></div>

	<?php }
	else
	{
		?>

		<div id="dispatch_popup" style="display:none;"><div class="header_part"><span><?php echo __('choose_driver_from_list'); ?></span></div><div class="login_total"><div class="login_lft"><div class="login_form"><ul><li><div class="controls"><div class="new_input_field"><span class="add-on"><i class=""></i></span><input type="text" name="search_driver" id="search_driver" value="" onKeyUp="driver_details()"></div><input type="hidden" name="passenger_log_id" id="passenger_log_id" value="<?php echo $edit_bookingdetails[0]['pass_logid']; ?>"></div></li><div id="show_process"><div id="driver_details"></div></ul></div></div><div class="login_rgt"><div id="dispatch_css" ><div id="dispatch_map"></div></div></div></div><a id="close_x" class="close sprited" onclick="window.location.reload();"></a></div>
<?php 
	}

	$total_fare = ($edit_bookingdetails[0]['fixedprice']!='')?$edit_bookingdetails[0]['fixedprice']:$edit_bookingdetails[0]['approx_fare'];
	
	?>
	<div id="complete_popup" style="display:none;"><div class="header_part"><span><?php echo __('complete_trip_by_opearotor'); ?></span></div><div class="login_total"><div class="complete_lft"><div class="complete_form"><ul><li><div class="controls"><div class="mt10 mb10"><span class="add-on"><i class=""><?php echo __("distance_km"); ?></i></span><input type="text" name="complete_distance" id="complete_distance" value="<?php echo $edit_bookingdetails[0]['approx_distance']; ?>" ></div><div class="mt10 mb10"><span class="add-on"><i class=""><?php echo __('waiting_time'); ?></i></span><input type="text" name="complete_waitingtime" id="complete_waitingtime" value="0"></div><div class="mt10 mb10"><span class="add-on"><i class=""><?php echo __('waiting_time_cost'); ?></i></span><input type="text" name="complete_waitingcost" id="complete_waitingcost" value="0"></div><div class="mt10 mb10"><span class="add-on"><i class=""><?php echo __('night_charge'); ?></i></span><input type="text" name="complete_nightcharge" id="complete_nightcharge" value="0"></div><div class="mt10 mb10"><span class="add-on"><i class=""><?php echo __('total_amount'); ?></i></span><input type="text" name="complete_total" id="complete_total" value="<?php echo $total_fare; ?>"></div><div class="mt10 mb10"><span class="add-on"><i class=""><?php echo __('any_remarks'); ?></i></span><textarea cols="4" rows="7" name="complete_remark" id="complete_remark" ></textarea></div><div class="mt10 mb10 fr" style="width:260px !important;"><div class="button greenB"><input type="button" id="complete_payment" value="<?php echo __('payment' ); ?>" name="complete_payment" title="<?php echo __('payment' ); ?>" /></div><div class="button dredB ml20"><input type="button" id="complete_update" value="<?php echo __('change_status' ); ?>" name="complete_update" title="<?php echo __('change_status' ); ?>" /></div></div><div id="complete_loading"></div>
</li></ul></div></div></div><a id="close_x" class="close sprited" onclick="window.location.reload();"></a></div>


<style>
#dispatch_map{
  display: block;
  width: 400px !important;
  height: 380px !important;
  margin-top:20px ;	
  margin-left:-20px;
  position:absolute !important;
  -moz-box-shadow: 0px 5px 20px #ccc !important;
  -webkit-box-shadow: 0px 5px 20px #ccc !important;
  box-shadow: 0px 5px 20px #ccc !important;
}
#dispatch_map.large{
  height:380px !important;
}

#map{
  display: block;
  width: 100%;
  height: 350px;
  margin-top:20px;	
  margin: 0 auto;
  -moz-box-shadow: 0px 5px 20px #ccc;
  -webkit-box-shadow: 0px 5px 20px #ccc;
  box-shadow: 0px 5px 20px #ccc;
}
#map.large{
  height:350px;
}

.implementation{
	border:dashed 2px #333333;
	background-color:#CCCCCC;
	color:#000000;
	width:50%;
}

.spacer{
clear:both;
height:0px;
}
.left{
	float:left;
	width:250px;
}

</style>

<script>
//get_editgroupdetails('<?php echo $edit_bookingdetails[0]["passengers_id"];?>','<?php echo $edit_bookingdetails[0]["accgroup_id"];?>');

	$(document).ready(function () {

		var center = map.getCenter();
		google.maps.event.trigger(map, "resize");
		map.setCenter(center);
		//google.maps.event.trigger(map, "resize");
	});

	function rateProduct(rating,pass_logid)  {
		
		var dataS = 'rating='+rating+'&pass_logid='+pass_logid;
		var url_path = SrcPath+"tdispatch/update_controllerrating";
		var rating = (rating * 26) - 8;
		$.ajax
		({
			type: "GET",
			url: url_path,
			data: dataS,  
			cache: false, 
			dataType: 'html',
			success: function(response) 
			{
				document.getElementById('current-rating').style.width = rating+'px';
				//document.getElementById('ratelinks').style.display = 'none';
				document.getElementById('ratingtext').innerHTML = 'Rating Updated !!';

				if($('#ratingtext')){
				  $('#ratingtext').fadeIn('fast');
				  $('#ratingtext').animate({opacity: 1.0}, 2000)
				  $('#ratingtext').fadeOut('slow');
				}
			} 
		});

				
			/*var remotos = new xhr,
			nt = remotos.send(this.hostname+'/tdispatch/update_controllerrating/?rating='+rating+'&pass_logid='+pass_logid),
			rating = (rating * 26) - 8;
			document.getElementById('current-rating').style.width = rating+'px';
			//document.getElementById('ratelinks').style.display = 'none';
			document.getElementById('ratingtext').innerHTML = 'Rating Updated !!';

			if($('#ratingtext')){
			  $('#ratingtext').fadeIn('fast');
			  $('#ratingtext').animate({opacity: 1.0}, 2000)
			  $('#ratingtext').fadeOut('slow');
			}*/
	}

</script>
