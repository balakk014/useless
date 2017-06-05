<?php defined('SYSPATH') OR die("No direct access allowed."); 
echo html::script('public/ckeditor/ckeditor.js'); 

//For search values
//=================
$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
$company_val = isset($srch["filter_company"]) ? $srch["filter_company"] :''; 
$status_val = isset($srch["status"]) ? $srch["status"] :''; 
$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 

?>
<script type="text/javascript" src="<?php echo URL_BASE;?>/public/js/validation/jquery-1.6.3.min.js"></script>
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
			<form method="POST" class="form" name="frmusers" id="frmusers">
				<table border="0" cellpadding="5" cellspacing="0" width="100%">			
			
					<input type="hidden" name="company" id="company" value="0" >
					
					<tr>
						<td valign="top" width="20%"><label><?php echo __('coupon_code'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" title="" name="coupon_code" id="coupon_code" readonly value="<?php echo isset($couponcode_details[0]['coupon_code']) &&!array_key_exists('coupon_code',$postvalue)? trim($couponcode_details[0]['coupon_code']):$postvalue['coupon_code']; ?>"  />
								<?php if(isset($errors) && array_key_exists('coupon_code',$errors)){ echo "<span class='error'>".ucfirst($errors['coupon_code'])."</span>";}?>
							</div>
						</td>   	
					</tr>
					<tr>
						<td valign="top" width="20%"><label><?php echo __('serial_number'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" title="" name="serial_number" id="serial_number" readonly value="<?php echo isset($couponcode_details[0]['serial_number']) &&!array_key_exists('serial_number',$postvalue)? trim($couponcode_details[0]['serial_number']):$postvalue['serial_number']; ?>"  />
								<?php if(isset($errors) && array_key_exists('serial_number',$errors)){ echo "<span class='error'>".ucfirst($errors['serial_number'])."</span>";}?>
							</div>
						</td>   	
					</tr>
					<tr>
						<td valign="top" width="20%"><label><?php echo __('coupon_name'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" title="" class="required " name="coupon_name" id="coupon_name"  value="<?php echo isset($couponcode_details[0]['coupon_name']) &&!array_key_exists('coupon_name',$postvalue)? trim($couponcode_details[0]['coupon_name']):$postvalue['coupon_name']; ?>" maxlength="200"  />
								<?php if(isset($errors) && array_key_exists('coupon_name',$errors)){ echo "<span class='error'>".ucfirst($errors['coupon_name'])."</span>";}?>
							</div>
						</td>   	
					</tr>
					<tr>
						<td valign="top" width="20%"><label><?php echo __('coupon_amt'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" title="" class="required onlynumbers" name="amount" id="amount"  value="<?php echo isset($couponcode_details[0]['amount']) &&!array_key_exists('amount',$postvalue)? trim($couponcode_details[0]['amount']):$postvalue['amount']; ?>"  min="1" value="" maxlength="5" />
								<?php if(isset($errors) && array_key_exists('amount',$errors)){ echo "<span class='error'>".ucfirst($errors['amount'])."</span>";}?>
							</div>
						</td>   	
					</tr>
			   
					<tr>
						<td valign="top" width="20%"><label><?php echo __('start_date'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" class="required end_exp_valid" title="" name="start_date" id="start_date" readonly="readonly" value="<?php echo isset($couponcode_details[0]['start_date']) &&!array_key_exists('start_date',$postvalue)? trim($couponcode_details[0]['start_date']):$postvalue['start_date']; ?>"  />
							</div>
					   </td>   	
					</tr>     
			   
					<tr>
						<td valign="top" width="20%"><label><?php echo __('expire_date'); ?>:</label><span class="star">*</span></td>        
						<td>
							<div class="new_input_field">
								<input type="text" class="required start_exp_valid" title="" name="expire_date" readonly="readonly" id="expire_date" value="<?php echo isset($couponcode_details[0]['expiry_date']) &&!array_key_exists('expire_date',$postvalue)? trim($couponcode_details[0]['expiry_date']):$postvalue['expire_date']; ?>"  />
              <?php if(isset($errors) && array_key_exists('expire_date',$errors)){ echo "<span class='error'>".ucfirst($errors['expire_date'])."</span>";}?>
							</div>
						</td>   	
					</tr>
					<tr>
						<td valign="top" width="20%"><label><?php echo __('Status'); ?>:</label></td>        
						<td>
							<div class="new_input_field">
								<?php 
								  $status = null;
								  if($couponcode_details[0]['coupon_status'] == 'A')
								  	$status = 'Active';
								  elseif($couponcode_details[0]['coupon_status'] == 'B')
								  	$status = 'Block';
								  elseif($couponcode_details[0]['coupon_status'] == 'T')
								  	$status = 'Trash';
								?>
								<label><b><?php echo $status; ?></b></label>
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
							<div class="new_button">   <input type="reset" onclick='window.location.href="<?php echo URL_BASE."edit/coupon/".$coupon_id;?>"' value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
							<div class="new_button">  <input type="submit" value="<?php echo __('submit' );?>" name="generate_coupon" id="generate_coupon" title="<?php echo __('submit' );?>" /></div>
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
		//toggle(1);
		
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

function checkcoupon_code(coupon_code)
{
	if(coupon_code == '') 
		coupon_code = $("#promo_code").val();
	var company_id;
	<?php if($company_id == 0) { ?>
		company_id = $("#company").val();
	<?php } else { ?>
		company_id = '<?php echo $company_id; ?>';
	<?php } ?>
	
	if(/^[a-zA-Z0-9]*$/.test(coupon_code) == false) {
		$('#unameavilable').html('');
		$('#unameavilable').wrapInner('<span style="color:red;">No special chars allowed.</span>');
	}
	else
	{
		$('#unameavilable').html('');
		if(trim(coupon_code).length!=0) 
			loadurl('<?php echo URL_BASE;?>'+"/manageusers/checkcoupon_code?promo="+coupon_code+"&company_id="+company_id,"unameavilable");
	}
}
</script>


<script type="text/javascript">
	function selectToggle(toggle, form) {
		var myForm = document.forms[form];
		for( var i=0; i < myForm.length; i++ ) { 
		    if(toggle) {
		        myForm.elements[i].checked = "checked";
		    } 
		    else
		    { myForm.elements[i].checked = ""; }
		}
	}
</script>
