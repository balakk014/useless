<?php defined('SYSPATH') OR die("No direct access allowed."); 
//echo html::script('public/ckeditor/ckeditor.js'); 

//For search values
//=================
$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
$company_val = isset($srch["filter_company"]) ? $srch["filter_company"] :''; 
$status_val = isset($srch["status"]) ? $srch["status"] :''; 
$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 

?>
<!--<script type="text/javascript" src="<?php echo URL_BASE;?>/public/js/validation/jquery-1.6.3.min.js"></script>-->
<script type="text/javascript" src="<?php echo URL_BASE;?>/public/js/validation/jquery.validate.js"></script>

<!-- time picker start-->
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<!--<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>-->
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>/public/js/validation/jquery.validate.js"></script>
<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle">
			<form method="POST" class="form" name="frmusers" id="frmusers" action="passenger_coupon">
				<table border="0" cellpadding="5" cellspacing="0" width="100%">			
			
					<input type="hidden" name="company" id="company" value="0" >
					<tr>
						<td valign="top" width="20%"><label><?php echo __('generate_id'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" class="required" name="generate_id" id="generate_id"  value="<?php echo $generate_id?>" readonly="readonly" />
							</div>
						</td>   	
					</tr>
					<tr>
						<td valign="top" width="20%"><label><?php echo __('coupon_name'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" class="required" title="<?php echo __('enter_coupon_name'); ?>" name="coupon_name" id="coupon_name"  value="" maxlength="200"  />
							</div>
						</td>   	
					</tr>
					<?php $field_type=""; ?>
					<?php /*<tr>
						<td valign="top" width="20%"><label><?php echo __('coupon_count'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<select name='coupon_count' style="width:200px;" id="coupon_count" class="required" >
									<option value="" style="text-align:center;">--Select --</option>
									<?php for($i=1;$i<=100;$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($field_type == $i) { echo 'selected=selected'; } ?> ><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
						</td>   	
					</tr> */ ?>
					
					<tr>
						<td valign="top" width="20%"><label><?php echo __('coupon_count'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" class="required onlynumbers" title="<?php echo __('enter_coupon_count'); ?>" name="coupon_count" id="coupon_count" min="1" max ="100" value="" maxlength="3"  />
							</div>
								
							
						</td>   	
					</tr>
					
					<tr>
						<td valign="top" width="20%"><label><?php echo __('coupon_amt'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
									<input type="text" class="required onlynumbers" title="<?php echo __('enter_voucher_amount'); ?>" name="coupon_amt" id="coupon_amt" min="1" value="" maxlength="10"  />
							</div>
						</td>   	
					</tr>
			   
					<tr>
						<td valign="top" width="20%"><label><?php echo __('start_date'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" class="required end_exp_valid" title="<?php echo __('enter_start_date'); ?>" name="start_date" id="start_date" readonly="readonly" value=""  />
							</div>
					   </td>   	
					</tr>     
			   
					<tr>
						<td valign="top" width="20%"><label><?php echo __('expire_date'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" class="required start_exp_valid" title="<?php echo __('enter_expire_date'); ?>" name="expire_date" readonly="readonly" id="expire_date" value="" />
							</div>
						</td>   	
					</tr>             
			  
					<tr>
						<td>&nbsp;</td>
						<td colspan="" class="star">*<?php echo __('required_label'); ?></td>
					</tr>                         
					<tr>
						<td>&nbsp;</td>
						<td colspan="">
							<br />                    
							<div class="new_button">     <input type="button" value="<?php echo __('button_back'); ?>" onclick="window.history.go(-1)" /></div>
							<div class="new_button">   <input type="reset" onclick='window.location.href="<?php echo URL_BASE."add/passenger_coupon";?>"' value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
							<div class="new_button">  <input type="submit" value="<?php echo __('submit' );?>" name="generate_coupon" id="generate_coupon" title="<?php echo __('submit' );?>" /></div>
							<div class="new_button">  <input type="submit" value="<?php echo __('print_submit' );?>" name="print_generate_coupon" id="print_generate_coupon" title="<?php echo __('print_submit' );?>" onClick="printCoupon();"/></div>
							<div class="clr">&nbsp;</div>
						</td>
					</tr>
				</table>                 
			</form>
		</div>
		<div class="clr">&nbsp;</div>
		<div class="clr">&nbsp;</div>
	</div>
</div>

<script type="text/javascript" language="javascript">

 $.validator.addMethod("end_exp_valid", function(value, element) 
 {	 
	$( "label" ).remove( ".errorvalid" );
	var expire_date = $('#expire_date').val();	
	var startdatevalue = $('#start_date').val();
	if(start_date != '' && value != '')
	{
		//Check the expire date from the start date
		if(Date.parse(expire_date) <= Date.parse(startdatevalue))
			return false;
		else
		   return true;
	}
	else
		return false;	

 },"Expire date should be greater than start date"); 
 
 $.validator.addMethod("start_exp_valid", function(value, element) 
 {	 
	$( "label" ).remove( ".errorvalid" );
	var expire_date = $('#expire_date').val();	
	var startdatevalue = $('#start_date').val();
	
	if(start_date != '' && value != '')
	{
		//Check the expire date from the start date
		if(Date.parse(expire_date) <= Date.parse(startdatevalue))
			return false;
		else
			return true;
	}
	else
		return false;
		 
 },"Expire date should be greater than start date");

</script>

<script type="text/javascript">
	$(document).ready(function()
	{
		jQuery("#frmusers").validate();
		
		$("#start_date").datetimepicker( 
		{
			showTimepicker:true,
			showSecond: true,
			timeFormat: 'hh:mm:ss',
			dateFormat: 'yy-mm-dd',
			stepHour: 1,
			stepMinute: 1,
			minDateTime : new Date(),
			stepSecond: 1,
			onClose: function( selectedDate ) {
				//alert(selectedDate);
				//$( "#expire_date" ).datepicker( "option", "minDateTime", selectedDate );
			}
		});

		$("#expire_date").datetimepicker( 
		{
			showTimepicker:true,
			showSecond: true,
			timeFormat: 'hh:mm:ss',
			dateFormat: 'yy-mm-dd',
			stepHour: 1,
			stepMinute: 1,
			minDateTime : new Date(), 
			stepSecond: 1,
			onClose: function( selectedDate ) {
				//$( "#start_date" ).datepicker( "option", "minDateTime", selectedDate );
			}			
		});	
	});

	function printCoupon()
	{
		event.preventDefault();
		
		var form = $("#frmusers");
		var validator = form.validate();
		validator.form();

		if(form.valid())
		{
			var win = window.open('about:blank', '_blank', "width=800,height=800");
			var data = form.serialize();

			$.ajax({
		        url : '<?php echo URL_BASE; ?>add/coupon',
		        type : "POST",
		        data : data+'&print_generate_coupon=Print & Submit',
		      })
		    .done(function(data) {
		    	var response = $.parseJSON(data);
		    	if(response.status == 1)
		    	{
		      		win.location.href = response.redirect_url;
		      		window.location.href = URL_BASE+'manage/coupon';
		    	}
		    	else
		    	{
		    		win.close();
		    	}	
		    		
		    })
		    .fail(function(data) {
		        console.log('fails');
		        win.close();
		    })
		}	
	};
</script>


