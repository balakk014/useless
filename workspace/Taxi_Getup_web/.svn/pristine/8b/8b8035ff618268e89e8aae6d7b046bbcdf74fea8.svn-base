<?php defined('SYSPATH') OR die("No direct access allowed."); 
?>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
         <form name="addpackage_form" id="addpackage_form" class="form" action="" method="post" enctype="multipart/form-data" onsubmit="form_validation();">
       <table border="0" cellpadding="5" cellspacing="0" width="100%">                               
	<tr>
	<td class="titlebold"><?php echo __('package_information'); ?></td>
	<td></td>	          
	</tr>
	
	<tr>
	<td valign="top" width="20%"><label><?php echo __('package_name'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
              <input type="text" title="<?php echo __('enter_package_name'); ?>" class="required" name="package_name" id="package_name" value="<?php if(isset($postvalue) && array_key_exists('package_name',$postvalue)){ echo $postvalue['package_name']; }?>"  maxlength="100" />
              <?php if(isset($errors) && array_key_exists('package_name',$errors)){ echo "<span class='error'>".ucfirst($errors['package_name'])."</span>";}?>
	</div>
	</td>   	
	</tr> 
	
	<tr>
	<td valign="top" width="20%"><label><?php echo __('package_description'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
              <textarea name="package_description" id="package_description" class="required" title="<?php echo __('enter_package_desc'); ?>" rows="7" cols="35"><?php if(isset($postvalue) && array_key_exists('package_description',$postvalue)){ echo $postvalue['package_description']; }?></textarea>
              <?php if(isset($errors) && array_key_exists('package_description',$errors)){ echo "<span class='error'>".ucfirst($errors['package_description'])."</span>";}?>
	</div>
	</td>   	
	</tr> 
	
	<tr>
	<td valign="top" width="20%"><label><?php echo __('no_of_taxi'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
	              <input type="text" title="<?php echo __('enter_no_of_taxi'); ?>" class="required number" name="no_of_taxi" id="no_of_taxi" value="<?php if(isset($postvalue) && array_key_exists('no_of_taxi',$postvalue)){ echo $postvalue['no_of_taxi']; }?>"  maxlength="6" oncopy="return false;" onpaste="return false;" oncut="return false;"/>
              <?php if(isset($errors) && array_key_exists('no_of_taxi',$errors)){ echo "<span class='error'>".ucfirst($errors['no_of_taxi'])."</span>";}?>
	</div>
	</td>   	
	</tr> 


	<tr>
	<td valign="top" width="20%"><label><?php echo __('no_of_driver'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
	              <input type="text" title="<?php echo __('enter_no_of_driver'); ?>" class="number required" name="no_of_driver" id="no_of_driver" value="<?php if(isset($postvalue) && array_key_exists('no_of_driver',$postvalue)){ echo $postvalue['no_of_driver']; }?>"  maxlength="6" oncopy="return false;" onpaste="return false;" oncut="return false;" />
              <?php if(isset($errors) && array_key_exists('no_of_driver',$errors)){ echo "<span class='error'>".ucfirst($errors['no_of_driver'])."</span>";}?>
	</div>
	</td>   	
	</tr> 

			
	<tr>
	<td valign="top" width="20%"><label><?php echo __('package_price'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
	              <input type="text" title="<?php echo __('enter_package_price'); ?>" class="numbersdots required" name="package_price" id="package_price" value="<?php if(isset($postvalue) && array_key_exists('package_price',$postvalue)){ echo $postvalue['package_price']; }?>"  maxlength="6" oncopy="return false;" onpaste="return false;" oncut="return false;" />
              <?php if(isset($errors) && array_key_exists('package_price',$errors)){ echo "<span class='error'>".ucfirst($errors['package_price'])."</span>";}?>
	</div>
	</td>   	
	</tr> 
	
	<tr>
	<td valign="top" width="20%"><label><?php echo __('no_of_days_expire'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
	              <input type="text" title="<?php echo __('enter_days_expire'); ?>" class="number required" name="days_expire" id="days_expire" value="<?php if(isset($postvalue) && array_key_exists('days_expire',$postvalue)){ echo $postvalue['days_expire']; }?>"  maxlength="4" oncopy="return false;" onpaste="return false;" oncut="return false;" />
              <?php if(isset($errors) && array_key_exists('days_expire',$errors)){ echo "<span class='error'>".ucfirst($errors['days_expire'])."</span>";}?>
	</div>
	</td>   	
	</tr> 

	<tr>
	<td valign="top" width="20%"><label><?php echo __('driver_tracking'); ?></label></td>        
	<td>
	<div class="new_input_field">
	              <input type="checkbox" name="driver_tracking" <?php if(isset($postvalue['driver_tracking'])) { if($postvalue['driver_tracking'] == 'S' ) { echo 'checked';  } } ?> value="S"   />

	</div>
	</td>   	
	</tr> 


	<tr>
	<td valign="top" width="20%"><label><?php echo __('admin_commission'); ?></label></td>        
	<td>
	<div class="new_input_field">
	              <label><?php echo CURRENCY.' '.ADMIN_COMMISSON; ?></label>
	</div>
	</td>   	
	</tr> 
		
	<tr>
	<td valign="top" width="20%"><label><?php echo __('transaction_based_commission'); ?></label></td>        
	<td>
	<div class="new_input_field">
	              <input type="radio" name="package_type" id="package_type" <?php if(isset($postvalue['package_type'])) { if($postvalue['package_type'] == 'T' ) { echo 'checked';  } } else { echo 'checked'; } ?> value="T"  />
	</div>
	</td>   	
	</tr> 

	<tr>
	<td valign="top" width="20%"><label><?php echo __('package_based_commission'); ?></label></td>        
	<td>
	<div class="new_input_field">
	              <input type="radio" name="package_type" <?php if(isset($postvalue['package_type'])) { if($postvalue['package_type'] == 'P' ) { echo 'checked';  } } ?> value="P"  />
	</div>
	</td>   	
	</tr> 


	<tr>
	<td valign="top" width="20%"><label><?php echo __('package_based_no_commission'); ?></label></td>        
	<td>
	<div class="new_input_field">
	              <input type="radio" name="package_type" <?php if(isset($postvalue['package_type'])) { if($postvalue['package_type'] == 'N' ) { echo 'checked';  } } ?> value="N"   />
	</div>
	</td>   	
	</tr> 
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td valign="top" width="20%" ><label class="titlebold"><?php echo __('note'); ?></label></td>        
	<td>&nbsp;
	</td>   	
	</tr> 
	
	<tr>
	<td valign="top" width="20%"><label><?php echo __('transaction_based_commission'); ?></label></td>   
	<td style="width:5px !important; float:left;"><span> : </span></td>	
	<td style="float: left;width: 95%;"><?php echo ''.__('transaction_based_details');?></td>   	
	</tr> 

	<tr>
	<td valign="top" width="20%"><label><?php echo __('package_based_commission'); ?></label></td> 
	<td style="width:5px !important; float:left;"><span> : </span></td>	
	<td style="float: left;width: 95%;"><?php echo ''.__('package_based_details');?></td>   	
	</tr> 

	<tr>
	<td valign="top" width="20%"><label><?php echo __('package_based_no_commission'); ?></label></td>     
	<td style="width:5px !important; float:left;"><span> : </span></td>
	<td style="float: left;width: 95%;"><span><?php echo ''.__('package_based_no_details');?></span></td>   	
	</tr> 
			
	<tr>
	<td>&nbsp;</td>
	<td colspan="" class="star">*<?php echo __('required_label'); ?></td>
	</tr>                         
	<tr>
		<td>&nbsp;</td>
		<td colspan="">
			<br />
			<div class="button blackB">     <input type="button" value="<?php echo __('button_back'); ?>" onclick="window.history.go(-1)" /></div>
			<div class="button dredB">   <input type="reset" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
			<div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_addpackage" title="<?php echo __('submit' );?>" /></div>
			<div class="clr">&nbsp;</div>
		</td>
	</tr> 
    </table>
        </form>
        </div>
        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>
    </div>
</div>  
<script type="text/javascript">
$(document).ready(function(){
 $("#package_name").focus(); 

 jQuery("#addpackage_form").validate();
 
 	$("#package_price" ).keyup(function() {
		this.value = this.value.replace(/[`~!@#$%^&*()\s_|+\-=?;:'",<>\{\}\[\]\\\/A-Z]/gi, '');
	});
 
});

function form_validation()
{
	var package_type = $("input[name='package_type']:checked").val();

	if(package_type != 'T')	
	{
		$('#no_of_taxi').addClass('required');
		$('#no_of_driver').addClass('required');
		$('#package_price').addClass('required');
		$('#days_expire').addClass('required');	
	}		
	else
	{
		$('#no_of_taxi').removeClass('required');
		$('#no_of_driver').removeClass('required');
		$('#package_price').removeClass('required');
		$('#days_expire').removeClass('required');	
	}

}

</script>
