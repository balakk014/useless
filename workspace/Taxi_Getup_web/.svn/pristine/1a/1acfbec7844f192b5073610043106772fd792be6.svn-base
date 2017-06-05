<?php defined('SYSPATH') OR die("No direct access allowed."); 
$select_pack_id ='';  
$field_count = count($additional_fields); ?>

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
    width:570px;
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

<?php 

$daysofweek = isset($get_recurrent_booking[0]['days'])?unserialize($get_recurrent_booking[0]['days']):'';
$specific_dates = isset($get_recurrent_booking[0]['specific_dates'])?unserialize($get_recurrent_booking[0]['specific_dates']):'';
$specific_additional_fields = isset($get_recurrent_booking[0]['recurrent_additionalfields'])?unserialize($get_recurrent_booking[0]['recurrent_additionalfields']):'';


		function get_val($key_to_check, $array){
		    if(isset($array[$key_to_check])) {
			return $array[$key_to_check];
		    }
		}


 ?>



<script src="http://maps.google.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY; ?>&libraries=places,geometry&amp;sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/gmaps.js"></script>
<!-- <script type="text/javascript" src="<?php echo URL_BASE;?>public/js/site.js"></script> -->

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/calendar/jquery-ui.multidatespicker.js"></script>

<!-- time picker start-->
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<!--<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>-->
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
<!-- time picker start-->

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE;?>public/css/jquery.autocomplete.css" />
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/jquery.autocomplete.js"></script>

<?php

$date = new DateTime('now', new DateTimeZone($company_timezone));
$current_datetime = $date->format('Y-m-d H:i:s');
$current_time = $date->format('H:i');
$current_date = $date->format('Y-m-d');

?>

<div class="errorp alert-message">
    <a  class="close">!!</a>
	<p><?php echo __('update_affect_upcoming24hrs'); ?> </p>
</div>

<div class="errorp alert-message">
    <a  class="close"></a>
	<p><?php echo __('note').' : '.__('create_user_not_exist');?></p>
</div>

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">    

         <form name="addcompany_form" class="form" id="addcompany_form" action="" method="post" enctype="multipart/form-data"  onSubmit="check_passengerexit()">

	<div class="first_fieldset">	
	<fieldset class="booking-form-fieldset">
	<legend><?php echo strtoupper(__('passengers_information')); ?></legend>
	   <div class="new_booking_field ">
              <input type="text" title="<?php echo __('enterfirstname_msg'); ?>" class="required" name="firstname" id="firstname" value="<?php echo isset($get_recurrent_booking[0]['passenger_name']) &&!array_key_exists('firstname',$postvalue)? trim($get_recurrent_booking[0]['passenger_name'].' - ('.$get_recurrent_booking[0]['passenger_phone'].')'):$postvalue['firstname']; ?>"  minlength="4"  placeholder="<?php echo __('firstname'); ?>"  autocomplete="off" />
		<input name="passenger_id" id="passenger_id" type="hidden" value="<?php echo $get_recurrent_booking[0]['recurrent_passengerid']; ?>" >
              <?php if(isset($errors) && array_key_exists('firstname',$errors)){ echo "<span class='error'>".ucfirst($errors['firstname'])."</span>";}?>
		  <span id="unameavilable" class="error"> </span>
		   </div>

		   <div class="new_booking_field mt10">
              <input type="text" title="<?php echo __('enteremailaddress'); ?>" class="required email" name="email" id="email" value="<?php echo isset($get_recurrent_booking[0]['passenger_email']) &&!array_key_exists('email',$postvalue)? trim($get_recurrent_booking[0]['passenger_email']):$postvalue['email']; ?>"  maxlength="50" placeholder="<?php echo __('email'); ?>" autocomplete="off" --onblur="checkuseremail(this.value)" />
              <?php if(isset($errors) && array_key_exists('email',$errors)){ echo "<span class='error'>".ucfirst($errors['email'])."</span>";}?>
		<span id="uemailavilable" class="error"> </span>
		   </div>

		   <div class="new_booking_field mt10">
              <input type="text" title="<?php echo __('entermobileno'); ?>" name="phone" id="phone" class="required" value="<?php echo isset($get_recurrent_booking[0]['passenger_phone']) &&!array_key_exists('phone',$postvalue)? trim($get_recurrent_booking[0]['passenger_phone']):$postvalue['phone']; ?>" minlength="7" maxlength="20" placeholder="<?php echo __('mobile'); ?>" autocomplete="off" --onblur="checkuserphone(this.value)" />
              <?php if(isset($errors) && array_key_exists('phone',$errors)){ echo "<span class='error'>".ucfirst($errors['phone'])."</span>";}?>
		<span id="uphoneavilable" class="error"> </span>
		   </div>


		<div id="show_group" style="display:none;">
		<div class="formRight mt10">
		<div class="selector" id="usergroup_list" style="display:none;"></div>
		   </div>	
		
		</div>

		<label for="group_id" generated="true" class="errorvalid" style="display:none"><?php echo __('select_group'); ?></label>

		  <div class="new_booking_field mt20 mb50"></div>

		</fieldset>

		<fieldset class="booking-form-fieldset">
		<legend><?php echo strtoupper(__('booking')); ?></legend>
		
		   <div class="new_booking_field">
		      <input type="text" title="<?php echo __('enter_currentlocation'); ?>" class="required " name="current_location" id="current_location" value="<?php echo isset($get_recurrent_booking[0]['recurrent_pickuplocation']) &&!array_key_exists('current_location',$postvalue)? trim($get_recurrent_booking[0]['recurrent_pickuplocation']):$postvalue['current_location']; ?>"  autocomplete="off"  placeholder="<?php echo __('enter_currentlocation'); ?>" --onchange="GeocodeFromAddress()"/>
		      <?php if(isset($errors) && array_key_exists('current_location',$errors)){ echo "<span class='error'>".ucfirst($errors['current_location'])."</span>";}?>

			<input type="hidden" name="pickup_lat" id="pickup_lat" value="<?php echo $get_recurrent_booking[0]['recurrent_pickuplatitude']; ?>"><input type="hidden" name="pickup_lng" id="pickup_lng" value="<?php echo $get_recurrent_booking[0]['recurrent_pickuplongitude']; ?>">
		   </div>

		   <div class="new_booking_field mt10">
              <input type="text" title="<?php echo __('enter_droplocation'); ?>" name="drop_location" id="drop_location" value="<?php echo isset($get_recurrent_booking[0]['recurrent_droplocation']) &&!array_key_exists('drop_location',$postvalue)? trim($get_recurrent_booking[0]['recurrent_droplocation']):$postvalue['drop_location']; ?>"  autocomplete="off" placeholder="<?php echo __('enter_droplocation'); ?>" --onchange="GeocodeFromAddress()"/>
              <?php if(isset($errors) && array_key_exists('drop_location',$errors)){ echo "<span class='error'>".ucfirst($errors['drop_location'])."</span>";}?>
		<input type="hidden" name="drop_lat" id="drop_lat" value="<?php echo $get_recurrent_booking[0]['recurrent_droplatitude']; ?>"><input type="hidden" name="drop_lng" id="drop_lng" value="<?php echo $get_recurrent_booking[0]['recurrent_droplongitude']; ?>">
		   </div>

  	      <div class="new_booking_field mt10">

		      <span class="first">	
		<?php
		$pickup_datetime = explode (' ',$get_recurrent_booking[0]['recurrent_pickuptime']);		
		$pickup_date = date('Y-m-d');
		$pickup_time = $pickup_datetime[0]; ?>

		      <input type="text" title="<?php echo __('pickup_date'); ?>" class="" name="pickup_date" id="pickup_date" value="<?php  echo __('pickup_date'); ?>"  autocomplete="off" placeholder="<?php echo __('today'); ?>" readonly />
		      <?php if(isset($errors) && array_key_exists('pickup_date',$errors)){ echo "<span class='error'>".ucfirst($errors['pickup_date'])."</span>";}?>
			</span>

			<span class="second">
		      <input type="text" title="<?php echo __('pickup_time'); ?>" class="" name="pickup_time" id="pickup_time" value="<?php  echo $pickup_time; ?>"  autocomplete="off" placeholder="<?php echo __('now'); ?>" readonly />
		      <?php if(isset($errors) && array_key_exists('pickup_time',$errors)){ echo "<span class='error'>".ucfirst($errors['pickup_time'])."</span>";}?>
			</span>

		   </div>


  	      <div class="new_booking_field mt10">

		      <span class="first mt10 ">	
		      <input type="text" title="<?php echo __('no_luggage'); ?>" class="required number" name="luggage" id="luggage" value="<?php echo isset($get_recurrent_booking[0]['recurrent_luggage']) &&!array_key_exists('luggage',$postvalue)? trim($get_recurrent_booking[0]['recurrent_luggage']):$postvalue['luggage']; ?>"  autocomplete="off" placeholder="<?php echo __('luggage'); ?>" />
		      <?php if(isset($errors) && array_key_exists('luggage',$errors)){ echo "<span class='error'>".ucfirst($errors['luggage'])."</span>";}?>
			</span>

		      <span class="second mt10">
		      <input type="text" title="<?php echo __('no_passengers'); ?>" class="required number" name="no_passengers" id="no_passengers" value="<?php echo isset($get_recurrent_booking[0]['recurrent_noofpassengers']) &&!array_key_exists('no_passengers',$postvalue)? trim($get_recurrent_booking[0]['recurrent_noofpassengers']):$postvalue['no_passengers']; ?>"  autocomplete="off" placeholder="<?php echo __('passengers'); ?>" />
		      <?php if(isset($errors) && array_key_exists('no_passengers',$errors)){ echo "<span class='error'>".ucfirst($errors['no_passengers'])."</span>";}?>
			</span>
		   </div>

		<div class="new_booking_field mb20 clr"></div>			
		
	</fieldset>	
	</div>

	<div class="second_fieldset third_fieldset fl ml20">	

	<div id="show_suggestion" >
		<div id="show_suggested"><?php echo __('show_suggested'); ?></div>
	    	<div id="locations_journeys" class="locations">
		<h2><?php echo __('favorite_journey'); ?></h2>
		<ul id="load_journey">
		<?php
		if(count($get_suggestedjourney) > 0)
		{
			foreach($get_suggestedjourney as $details)
			{


				echo "<li title=\"".__('from').' '.$details['from_location'].' '.__('to').' '.$details['to_location']."\"><a href=\"javascript:;\" onclick=\"change_fromtolocation('".urlencode($details['from_location'])."','".urlencode($details['to_location'])."');\"><span class=\"journey-from\">".__('from').":</span><span class='journey-name'>".$details['from_location']."</span><span class='journey-to'>".__('to').":</span><span class='journey-name'>".$details['to_location']."</span></a></li>";

			}
		}
		else
		{
				echo '<li>'.__('no_data').'</li>';
		}
		?>
		</ul>
	    </div>

	    <div id="locations_locations" class="locations mt20 mb20">
		<h2><?php echo __('favorite_locations'); ?></h2>
		<ul id="load_location">
		<?php
		if(count($get_suggestedlocation) > 0)
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
		</ul>
	    </div>
	</div>	

	<fieldset class="booking-form-fieldset">
	<legend><?php echo strtoupper(__('vehicle')); ?></legend>
		<div class="formRight">
		<div class="selector" id="uniform-user_type">
<?php $field_type =''; 
$field_type = $get_recurrent_booking[0]['recurrent_modelid']; ?>

		      <select name="taxi_model" id="taxi_model" class="required" title="<?php echo __('select_the_taximodel'); ?>" OnChange="change_minfare(this.value);">
			<option value=""><?php echo __('select_label'); ?></option>			
		      <?php 
			foreach($model_details as $list) { ?>
		      <option value="<?php echo $list['model_id']; ?>" <?php if($field_type == $list['model_id']) { echo 'selected=selected'; } ?>><?php echo ucfirst($list['model_name']); ?></option>
		      <?php } ?>
		      </select>
		   </div>
		   </div>	
		<label for="taxi_model" generated="true" class="errorvalid" style="display:none;width:250px;"><?php echo __('select_the_taximodel'); ?></label>
		  <div class="new_booking_field mt20 mb50"></div>
		</fieldset>

		<fieldset class="booking-form-fieldset">
		<legend><?php echo strtoupper(__('recurrent')); ?></legend>
		


  	      <div class="new_booking_field mt10" id="recurrent_info" >



		<div class="new_booking_field mt20">
		<input type="text" title="<?php echo __('enter_recurrent_booking_label'); ?>" class="" name="labelname" id="labelname" value="<?php echo isset($get_recurrent_booking[0]['labelname']) &&!array_key_exists('labelname',$postvalue)? trim($get_recurrent_booking[0]['labelname']):$postvalue['labelname']; ?>"  minlength="4" maxlength="30" placeholder="<?php echo __('recurrent_booking_label'); ?>" autocomplete="off" />
		<?php if(isset($errors) && array_key_exists('labelname',$errors)){ echo "<span class='error'>".ucfirst($errors['labelname'])."</span>";}?>
		</div>

  	      <div class="new_booking_field mt10">

		      <span class="first">	
		      <input type="text" title="<?php echo __('select_start_date'); ?>" class="" readonly name="frmdate" id="frmdate" value="<?php echo isset($get_recurrent_booking[0]['frmdate']) &&!array_key_exists('frmdate',$postvalue)? trim($get_recurrent_booking[0]['frmdate']):$postvalue['frmdate']; ?>"  autocomplete="off" placeholder="<?php echo __('start_date'); ?>" />
		      <?php if(isset($errors) && array_key_exists('frmdate',$errors)){ echo "<span class='error'>".ucfirst($errors['frmdate'])."</span>";}?>
			</span>

			<span class="second">
		      <input type="text" title="<?php echo __('select_end_date'); ?>" class="" readonly name="todate" id="todate" value="<?php echo isset($get_recurrent_booking[0]['todate']) &&!array_key_exists('todate',$postvalue)? trim($get_recurrent_booking[0]['todate']):$postvalue['todate']; ?>"  autocomplete="off" placeholder="<?php echo __('end_date'); ?>" />
		      <?php if(isset($errors) && array_key_exists('todate',$errors)){ echo "<span class='error'>".ucfirst($errors['todate'])."</span>";}?>
			</span>

			<input type="hidden" name="valid_enddate" title="<?php echo __('select_enddate_greater_frmdate'); ?>"  class="cmpenddt" id="cmpenddt" >

		   </div>

		<div class="new_booking_field mt20 mb50"></div>
		<div id="calendar">
			    <div id="daysOfWeek">
				<table cellspacing="0" cellpadding="0">
				    <tbody><tr class="firstCalendarRow" border="">
			<?php if (is_array($daysofweek) && in_array('MON',$daysofweek)) { $checked = 'checked'; $active = 'green_active'; } else {  $checked =''; $active =''; }  ?>
					<td><div id="calactive_MON" class=""><a href="javascript:void(0)" class="cale_label <?php echo $active; ?>"><?php echo 'MON'; ?></a><input type="checkbox" name="daysofweek[]" <?php echo $checked; ?> id="daysofweek_MON" value="MON" style="display:none" ></div></td>
			<?php if (is_array($daysofweek) && in_array('TUE',$daysofweek)) { $checked = 'checked'; $active = 'green_active'; } else { $checked = '';  $active =''; }  ?>
					<td><div id="calactive_TUE" class=""><a href="javascript:void(0)" class="cale_label <?php echo $active; ?>"><?php echo 'TUE'; ?></a><input type="checkbox" name="daysofweek[]" <?php echo $checked; ?> id="daysofweek_TUE" value="TUE" style="display:none"></div></td>
			<?php if (is_array($daysofweek) && in_array('WED',$daysofweek)) { $checked = 'checked'; $active = 'green_active'; } else {  $checked = ''; $active =''; }  ?>
					<td><div id="calactive_WED" class=""><a href="javascript:void(0)" class="cale_label <?php echo $active; ?>"><?php echo 'WED'; ?></a><input type="checkbox" name="daysofweek[]" <?php echo $checked; ?> id="daysofweek_WED" value="WED" style="display:none"></div></td>
			<?php if (is_array($daysofweek) && in_array('THU',$daysofweek)) { $checked = 'checked'; $active = 'green_active'; } else {  $checked = ''; $active =''; }  ?>
					<td><div id="calactive_THU" class=""><a href="javascript:void(0)" class="cale_label <?php echo $active; ?>"><?php echo 'THU'; ?></a><input type="checkbox" name="daysofweek[]" <?php echo $checked; ?> id="daysofweek_THU" value="THU" style="display:none"></div></td>
			<?php if (is_array($daysofweek) && in_array('FRI',$daysofweek)) { $checked = 'checked'; $active = 'green_active'; } else {  $checked = ''; $active =''; }  ?>
					<td><div id="calactive_FRI" class=""><a href="javascript:void(0)" class="cale_label <?php echo $active; ?>"><?php echo 'FRI'; ?></a><input type="checkbox" name="daysofweek[]" <?php echo $checked; ?> id="daysofweek_FRI" value="FRI" style="display:none"></div></td>
			<?php if (is_array($daysofweek) && in_array('SAT',$daysofweek)) { $checked = 'checked'; $active = 'green_active'; } else {  $checked = ''; $active =''; }  ?>
					<td><div id="calactive_SAT" class=""><a href="javascript:void(0)" class="cale_label <?php echo $active; ?>"><?php echo 'SAT'; ?></a><input type="checkbox" name="daysofweek[]" <?php echo $checked; ?> id="daysofweek_SAT" value="SAT" style="display:none"></div></td>
			<?php if (is_array($daysofweek) && in_array('SUN',$daysofweek)) { $checked = 'checked'; $active = 'green_active'; } else {  $checked = ''; $active =''; }  ?>
					<td><div id="calactive_SUN" class=""><a href="javascript:void(0)" class="cale_label <?php echo $active; ?>"><?php echo 'SUN'; ?></a><input type="checkbox" name="daysofweek[]" <?php echo $checked; ?> id="daysofweek_SUN" value="SUN" style="display:none"></div></td>

				    </tr>
				    <tr class="space_tr">
				        <td colspan="7"><?php echo __('(or)'); ?></span></td>
				    </tr>
				   <?php /*for($j=1;$j<=31;$j++)
				   { 
					   if($j ==1 ||$j ==8 || $j ==15 || $j ==22 || $j ==29) { ?>
					    <tr class="monthDays">
				<?php	} ?>
	<?php if (is_array($specific_dates) && in_array($j,$specific_dates)) { $checked = 'checked'; $active_dates ='green_active'; } else { $checked = ''; $active_dates ='';} ?>
						<td><div id="calactive_<?php echo $j; ?>" class=""><a href="javascript:void(0)" class="cal_label <?php echo $active_dates; ?>" ><?php echo $j; ?></a><input type="checkbox" name="monthDays[]" <?php echo $checked; ?> id="monthDays_<?php echo $j; ?>" value ="<?php echo $j; ?>" style="display:none"></div></td>

					   <?php if($j ==7 || $j ==14 || $j ==21 || $j ==28) { ?>
						</tr>
				  	  <?php } ?>

				   <?php } */?>			

				</tbody></table>
			    </div>
			</div>

			<?php $default_date =  !empty($specific_dates)?implode(',',$specific_dates):''; ?>
			<!-- Datepicker calender -->
			<div id="withAltField" class="box" style="margin-top:-40px;">
			<div id="with-altField"></div>
			<input type="hidden" id="altField" name="all_dates" title="<?php echo __('select_the_date'); ?>" value="<?php echo $default_date; ?>" >
			<input type="hidden" name="define_date" value="<?php echo $default_date; ?>"> 	
			<input type="hidden" name="datepicker_status" id="datepicker_status" value="0"> 	
			<label class="errors" id="errors_days"></label>
			</div>
			<!-- Datepicker calender -->


	</fieldset>	

	<?php
          if($field_count > 0)
          {
			  ?>
	<fieldset class="booking-form-fieldset">
	<legend><?php echo strtoupper(__('extra')); ?></legend>
	<?php
		    for($i=0; $i<$field_count; $i++)
		    { 
		    	$field_name = $additional_fields[$i]['field_name']; ?>
			<tr><td valign="top" width="20%"><div class="new_input_field"><label><?php echo $additional_fields[$i]['field_labelname']; ?></label></div></td><td><div class="new_input_field">
			<?php
			if($additional_fields[$i]['field_type'] == 'Textbox')
			{  
				$field_val = get_val($field_name,$specific_additional_fields); ?>

	                	<input type="text" title="<?php echo __('enter_the').$additional_fields[$i]['field_labelname']; ?>" class="" id="<?php echo $field_name; ?>" name="addition_fields[<?php echo $field_name; ?>]" value="<?php echo $field_val; ?>" maxlength="20" />
 			<?php }
	                else if($additional_fields[$i]['field_type'] == 'Checkbox')
	                { 
				
		                $field_val = get_val($field_name,$specific_additional_fields);
				$field_chkvalue = ''; 
	            	 	if(isset($postvalue) && array_key_exists($field_name,$postvalue)){ $field_chkvalue =  $postvalue[$field_name]; }
	            	 	
	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);
	                 	
	                 	foreach($field_value as $key => $value)
	                 	{ ?>
	                		<input type="checkbox" title="<?php echo __('enter_the'); ?>"  name="addition_fields[<?php echo$field_name; ?>]" value="<?php echo $value; ?>"	<?php if(trim($field_val) == trim($value)) { echo 'checked'; } ?> /><?php echo $value; ?>           	
	                 	<?php
	                 	}
			}
		        else if($additional_fields[$i]['field_type'] == 'Radio')
	                {  
				$field_val = get_val($field_name,$specific_additional_fields);
	            	 
	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);
	                 	
	                 	foreach($field_value as $key => $value)
	                 	{ ?>
	                		<input type="radio" title="<?php echo __('select_the').$field_name; ?>" class="" name="addition_fields[<?php echo $field_name; ?>]" id="<?php echo $field_name; ?>" value="<?php echo $value; ?>" <?php if(trim($field_val) == trim($value)) { echo 'checked'; } ?> /><?php echo $value; ?>
	                 <?php	
	                 	}
	               }
	                else if($additional_fields[$i]['field_type'] == 'Select')
	                { 
			 $field_val = get_val($field_name,$specific_additional_fields);
	            	 $field_selvalue =''; 

	            	 if(isset($postvalue) && array_key_exists($field_name,$postvalue)){ $field_selvalue =  $postvalue[$field_name]; }

	                 	$field_value = explode(',',$additional_fields[$i]['field_value']);
				?>
				<div class="formRight"><div class="selector" id="uniform-user_type"><span><?php echo __('select_label'); ?></span><select name="addition_fields[<?php echo $field_name; ?>]" id="<?php echo $field_name; ?>" class="" title="<?php echo __('select_the').$field_name; ?>"><option value="">--Select--</option>
				<?php
	                 	foreach($field_value as $key => $value)
	                 	{ ?>
					<option value="<?php echo $value; ?>"	<?php if(trim($field_val) == trim($value)) { echo 'selected=selected'; } ?>  ><?php echo ucfirst($value); ?></option>
	                 <?php	}
				?>
				</select></div></div>
			<?php } ?>

	                <label for="<?php echo $field_name; ?>" generated="true" style="display:none" class="errorvalid"><?php echo __('select_the').$field_name; ?></label>              	 
		       <?php if(isset($errors) && array_key_exists($field_name,$errors)){ ?> <span class='error'><?php ucfirst($errors[$field_name]); ?> </span>
			<?php } ?>

	                </div></td></tr>
			<?php
		      }
           
	?>


		  <div class="new_booking_field mt20 mb50"></div>
		</fieldset> <?php } ?>
	</div>


	<div class="second_fieldset  fl ml50" id="payment_sec" >	
	<fieldset class="booking-form-fieldset">
	<legend><?php echo strtoupper(__('payment')); ?></legend>
	   <div class="new_booking_field ">


<?php

				foreach($get_gatewaydetails as $resultset) { 
				?>
		      <span class="second1">	
					<input type="radio" class="required " name="payment_type" value="<?php echo $resultset['pay_mod_id']; ?>" <?php if($get_recurrent_booking[0]['recurrent_faretype'] == $resultset['pay_mod_id']) { echo 'checked'; } ?> /><?php echo $resultset['pay_mod_name']; ?>
			</span>
			<span id="payment_error" class="error" style="display:none;"></span>
			<?php } ?>

		   </div>

  	         <div class="new_booking_field mt10">

			<ul id="acc">
				<li><label><?php echo __('account'); ?> :</label><span></span></li>
				<li><label><?php echo __('journey'); ?> :</label><span id="find_duration"><?php echo $get_recurrent_booking[0]['recurrent_approxduration']; ?></span></li>
				<li><label><?php echo __('distance'); ?> :</label><span id="find_km"><?php echo $get_recurrent_booking[0]['recurrent_approxdistance']; ?></span></li>
				<li><label><?php echo __('tax'); ?> :</label><span>%</span><span id="vat_tax"><?php echo $company_tax; ?></span></li>
				<li><label><?php echo __('fare'); ?> :</label><span id="min_fare" class=""><?php echo $get_recurrent_booking[0]['recurrent_approxfare']; ?></span><span><?php echo findcompany_currency($_SESSION['company_id']); ?></span></li>
		    	</ul>
		 </div>	

		<div class="new_booking_field "><label><?php echo __('fixedprice'); ?> :</label><span style="width:50%;float:right !important;">
			<input type="text" title="<?php echo __('enter_override_price'); ?>" class="onlynumbers" name="fixedprice" id="fixedprice" value="<?php echo isset($get_recurrent_booking[0]['recurrent_fixedprice']) &&!array_key_exists('fixedprice',$postvalue)? trim($get_recurrent_booking[0]['recurrent_fixedprice']):$postvalue['fixedprice']; ?>"  maxlength="6" placeholder="<?php echo __('override_price'); ?>" autocomplete="off" /></span>
			<?php if(isset($errors) && array_key_exists('fixedprice',$errors)){ echo "<span class='error'>".ucfirst($errors['fixedprice'])."</span>";}?>
		</div>


  	         <div class="new_booking_field mt10 clr">

			<ul id="acc">
				<li><label><?php /*echo findcompany_currency($_SESSION['company_id']); */ ?></label><span id="total_price"><?php echo isset($get_recurrent_booking[0]['recurrent_approxfare']) &&!array_key_exists('approx_fare',$postvalue)? trim($get_recurrent_booking[0]['recurrent_approxfare']):$postvalue['approx_fare']; ?></span><span><?php echo __('total'); ?> : <?php echo findcompany_currency($_SESSION['company_id']); ?></span></li>

		    	</ul>
		 </div>	

  	         <div class="new_booking_field mt10">

			<ul id="acc" style="display:none">
				<li><label><?php echo __('description'); ?> :</label><span id="desc">Rate Kilometer <?php echo $get_recurrent_booking[0]['recurrent_approxdistance']; ?> </span></li>
				<li><label><?php echo __('value'); ?> :</label><span><?php echo findcompany_currency($_SESSION['company_id']); ?></span><span id="min_value"><?php $get_recurrent_booking[0]['min_fare']; ?></span></li>
				<li><label><?php echo __('subtotal'); ?> :</label><span><?php echo findcompany_currency($_SESSION['company_id']); ?></span><span id="sub_total"><?php echo $get_recurrent_booking[0]['recurrent_approxfare']; ?></span></li>				
		    	</ul>
		<input type="hidden" name="model_minfare" id="model_minfare" value="<?php echo $get_recurrent_booking[0]['min_fare']; ?>" >
		<input type="hidden" name="model_id" id="model_id" value="<?php echo $get_recurrent_booking[0]['recurrent_modelid']; ?>" >
		<input type="hidden" name="distance_km" id="distance_km" value="<?php echo $get_recurrent_booking[0]['recurrent_approxdistance']; ?>" >
		<input type="hidden" name="total_fare" id="total_fare" value="<?php echo $get_recurrent_booking[0]['recurrent_approxfare']; ?>" >
		<input type="hidden" name="total_duration" id="total_duration" value="<?php echo $get_recurrent_booking[0]['recurrent_approxduration']; ?>" >
		<input type="hidden" name="city_id" id="city_id" value="<?php echo $get_recurrent_booking[0]['recurrent_city']; ?>" >
		<input type="hidden" name="cityname" id="cityname" value="" >


		<input type="hidden" name="company_tax" id="company_tax" value="<?php echo $company_tax; ?>" >
		<div class="mt10 button greenB  mb20" ><input type="submit" name="update" value="<?php echo __('update'); ?>" ></div>
		<!--<div class="mt10 button greenB fr" ><input type="submit" name="delete" value="<?php echo __('delete'); ?>" ></div>-->
		 </div>	


	   

	  <div class="new_booking_field mt20 "></div>
	</fieldset>

	<fieldset class="booking-form-fieldset">
	<legend><?php echo strtoupper(__('map')); ?></legend>
		<?php if(SHOW_MAP ==1 ) { ?>
		<div id="map" style="display:none;"></div>
		<div>Show Suggesstion Route</div>
		<?php }
		else
		{ ?>
		<div id="map" ></div>
		<?php } ?>	
		<div id="directions"></div>
		<div style="display:none;">
		<table>
			<tr>
			<td>Start altitude:</td>
			<td id="start"></td>
			</tr>
			<tr>
			<td>End altitude:</td>
			<td id="end"></td>
			</tr>
			<tr>
			<td>Maximum altitude:</td>
			<td id="max"></td>
			</tr>
			<tr>
			<td>Minimum altitude:</td>
			<td id="min"></td>
			</tr>
			<tr>
			<td>Distance:</td>
			<td id="distance"></td>
			</tr>
			<tr>
			<td>Total ascent:</td>
			<td id="ascent"></td>
			</tr>
			<tr>
			<td>Total descent:</td>
			<td id="descent"></td>
			</tr>
		</table>
	</div>	

		<div class="new_booking_field mb20" ></div>
	</fieldset>	

	</div>
		

  </div>
        </form>
        
        
        </div>
        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>
    </div>

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/tdispatch_editrecurrent.js"></script>

<script>
var current_date ="<?php echo $current_date; ?>";
/** calendar **/
$('#with-altField').multiDatesPicker({
dateFormat: "yy-mm-dd", 
altField: '#altField',
defaultDate:null,
minDate:new Date("<?php echo $current_date; ?>"),
maxDate:new Date("<?php echo $get_recurrent_booking[0]['todate']; ?>"),
onSelect: function (date) { set_defaultvalue(); }
});
/** calendar **/

<?php if (!empty($specific_dates)) {
		
	foreach($specific_dates as $key => $val){ ?>
$('#with-altField').multiDatesPicker('addDates', [new Date('<?php echo $val;?>')]);
<?php } 
	}
?>
get_editgroupdetails('<?php echo $get_recurrent_booking[0]["recurrent_passengerid"];?>','<?php echo $get_recurrent_booking[0]["recurrent_groupid"];?>');
</script>
<style>
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

</style>
