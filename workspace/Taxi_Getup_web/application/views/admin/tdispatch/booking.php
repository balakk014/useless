<!-- Latest compiled and minified CSS -->

<script src="http://maps.google.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY; ?>&libraries=places,geometry&amp;sensor=false" type="text/javascript"></script>
<!-- jQuery library -->
    <div class="container">
        <div class="row">
            <!-- form: -->
            <section>
               <h4><?php echo strtoupper(__('passengers_information')); ?></h4>
                <div class="col-lg-5 ">
                    <form id="defaultForm" method="post" class="form-horizontal" action="target.php">
                        <div class="form-group">
                            
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="firstName" placeholder="<?php echo __('name_label'); ?>"  autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group">
                           
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="email" placeholder="<?php echo __('email_id'); ?>" autocomplete="off" />
                            </div>
                        </div>
			<div class="form-group">
               
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="phone" placeholder="<?php echo __('mobile'); ?>" />
                            </div>
                        </div>

			<?php /** booking details **/ ?>
                        <h4><?php echo strtoupper(__('booking_details')); ?></h4>
			<div class="form-group">
                            <div class="col-lg-5">
                                <input type="text" class="form-control" id="current_location" name="current_location" autocomplete="off"  placeholder="<?php echo __('enter_currentlocation'); ?>" />
                            </div>
                        </div>
			<div class="form-group">
                            <div class="col-lg-5">
                                <input type="text" class="form-control" id="drop_location" name="drop_location" autocomplete="off" placeholder="<?php echo __('enter_droplocation'); ?>" />
                            </div>
                        </div>
			<div class="form-group">
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="notes" autocomplete="off"  placeholder="<?php echo __('note_driver'); ?>"  />
                            </div>
                        </div>
			<div class="form-group">
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="pickup_date" id="pickup_date" autocomplete="off"  placeholder="<?php echo __('today'); ?>"   />
                            </div>
                        </div>
			<div class="form-group">
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="pickup_time" id="pickup_time" autocomplete="off" placeholder="<?php echo __('now'); ?>" />
                            </div>
                        </div>
			<div class="form-group">
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="luggage" id="pickup_date" autocomplete="off"  placeholder="<?php echo __('luggage'); ?>"   />
                            </div>
                        </div>
			<div class="form-group">
                            <div class="col-lg-5">
                                <input type="text" class="form-control" name="no_passengers" id="pickup_date" autocomplete="off" placeholder="<?php echo __('passengers'); ?>"  />
                            </div>
                        </div>
			<?php /** booking details **/ ?>
			<?php /**  VEHICLE details **/ ?>
			<h4><?php echo strtoupper(__('vehicle')); ?></h4>
			<div class="form-group">
                            <div class="col-lg-5">
                                <?php $field_type =''; if(isset($postvalue) && array_key_exists('taxi_model',$postvalue)){ $field_type =  $postvalue['taxi_model']; } ?>
						  <select name="taxi_model" id="taxi_model" class="form-control" title="<?php echo __('select_the_taximodel'); ?>" OnChange="change_minfare(this.value);">
						<option value=""><?php echo __('select_label'); ?></option>			
						  <?php 
						foreach($model_details as $list) { ?>
						  <option value="<?php echo $list['model_id']; ?>" <?php if($field_type == $list['model_id']) { echo 'selected=selected'; } ?>><?php echo ucfirst($list['model_name']); ?></option>
						  <?php } ?>
						  </select>
                            </div>
                        </div>
			<?php /**  VEHICLE details **/ ?>
			
			<?php /**  Booking type **/ ?>
			<h4><?php echo strtoupper(__('booking_type')); ?></h4>
			<div class="form-group">
                            <div class="col-lg-5">
                                        <input type="radio" name="recurrent" value="1" <?php if(isset($postvalue) && array_key_exists('recurrent',$postvalue)) {  if($postvalue['recurrent'] == 1) { echo 'checked'; } } else { echo 'checked'; } ?> /> <?php echo __('single'); ?>
                                   
                                
                                        <input type="radio" name="recurrent" value="2" /> <?php echo __('recurrent'); ?>
                                    
                            </div>
                        </div>
			<?php /**  Booking type **/ ?>
			<?php /**  Booking type **/ ?>
			<h4><?php echo strtoupper(__('payment')); ?></h4>
		<ul class="col-lg-5">
		<li><label><?php echo __('journey'); ?> :</label><span id="find_duration"><?php echo __('zero_mins'); ?></span></li>
							<li><label><?php echo __('distance'); ?> :</label><span id="find_km"></span></li>
							<li><label><?php echo __('tax'); ?> :</label><span>%</span><span id="vat_tax"><?php echo $company_tax; ?></span></li>
							<li><label><?php echo __('fare'); ?> :</label><span id="min_fare" class=""><?php echo '0'; ?></span><span><?php echo $company_currency = findcompany_currency($_SESSION['company_id']); ?></span></li>
</ul>
			<?php /**  Booking type **/ ?>
			
			<div id="map_addbooking"></div>
			<div class="form-group"><div id="directions"></div></div>
			
			<?php /** hidden fields **/ ?>
				
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
			<ul id="acc" style="display:none;">
							<li><label><?php echo __('description'); ?> :</label><span id="desc">Rate Kilometer</span></li>
							<li><label><?php echo __('value'); ?> :</label><span><?php echo $company_currency = findcompany_currency($_SESSION['company_id']); ?></span><span id="min_value"></span></li>
							<li><label><?php echo __('subtotal'); ?> :</label><span><?php echo $company_currency = findcompany_currency($_SESSION['company_id']); ?></span><span id="sub_total"></span></li>				
							</ul>
			<input type="hidden" name="pickup_lat" id="pickup_lat" value="">
			<input type="hidden" name="pickup_lng" id="pickup_lng" value="">
			<input type="hidden" name="drop_lat" id="drop_lat" value="">
			<input type="hidden" name="drop_lng" id="drop_lng" value="">
			<input type="hidden" name="info" id="info" value="">
			<input type="hidden" name="model_minfare" id="model_minfare" value="0" >
			<input type="hidden" name="distance_km" id="distance_km" value="0" >
			<input type="hidden" name="total_fare" id="total_fare" value="0" >
			<input type="hidden" name="total_duration" id="total_duration" value="0" >
			<input type="hidden" name="city_id" id="city_id" value="" >
			<input type="hidden" name="cityname" id="cityname" value="" >
			<input type="hidden" name="payment_sec" id="payment_sec" value="" >
			<input type="hidden" name="company_tax" id="company_tax" value="<?php echo $company_tax; ?>" >
			<input type="hidden" name="default_company_unit" id="default_company_unit" value="<?php echo UNIT_NAME; ?>" >
                      <?php /*  <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <div id="errors"></div>
                            </div>
                        </div> */ ?>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Sign up</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <!-- :form -->
        </div>
    </div>
  
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/gmaps.js"></script>
   <link rel="stylesheet"href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/bootstrap.css"/>
   <link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/dist/css/formValidation.css"/>
 <script type="text/javascript" src="<?php echo URL_BASE;?>public/js/jquery1.11.0.js"></script>
   <script type="text/javascript" src="<?php echo URL_BASE;?>public/bootstrap-3.2.0/dist/js/formValidation.js"></script>
   <script type="text/javascript" src="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="<?php echo URL_BASE;?>public/bootstrap-3.2.0/dist/js/framework/bootstrap.js"></script>
   <script type="text/javascript" src="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/js/bootstrap-datepicker.js"></script>
   <link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/bootstrap-datepicker.css"/>
    <script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE;?>public/css/jquery.autocomplete.css" />







<script type="text/javascript">
$(document).ready(function() {
    $('#defaultForm')
        .formValidation({
            message: 'This value is not valid',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
           fields: {
            firstName: {
                validators: {
                    notEmpty: {
                        message: 'The first name is required and cannot be empty'
                    },
			stringLength: {
                        min: 3,
                        max: 30,
                        message: 'The username must be more than 6 and less than 30 characters long'
                    },
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email is required and cannot be empty'
                    },
		    regexp: {
                        regexp: "[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\\.[A-Za-z]{2,4}",
                        message: 'The input is not a valid email address'
                    }	
                }
           	 },
		 phone: {
		        validators: {
			    regexp: {
		                regexp: "[0-9.+-]",
		                message: 'Enter valid phone number'
		            }	
		        }
           	 },
		current_location: {
		        validators: {
			    notEmpty: {
                                message: 'Enter the Pickup Location'
                    	}
		        }
           	 },
		taxi_model: {
		        validators: {
			    notEmpty: {
                                message: 'Select the Taxi Model'
                    	}
		        }
           	 }
        }
        })
        .on('err.field.fv', function(e, data) {
            var messages = data.fv.getMessages(data.field);
            $('#errors').find('li[data-fv-for="' + data.field + '"]').remove();
            for (var i in messages) {
                $('<li/>').attr('data-fv-for', data.field).html(messages[i]).appendTo('#errors');
            }
            $('#errors').parents('.form-group').removeClass('hide');
        })
        .on('success.field.fv', function(e, data) {
            $('#errors').find('li[data-fv-for="' + data.field + '"]').remove();
        })
        .on('success.form.fv', function(e) {
            $('#errors')
                .html('')
                .parents('.form-group').addClass('hide');
        });

	$("#pickup_date").datepicker( {
		dateFormat: 'yy-mm-dd',
		 "setDate": new Date(),
       		 "autoclose": true,
		"minDate": 0
	} );

	
});
</script>


<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/tdispatch_addbooking_new.js"></script>


