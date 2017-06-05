<?php defined('SYSPATH') OR die("No direct access allowed."); 
$select_pack_id ='';  ?>
<?php /*<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>

<!-- time picker start-->
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>*/ ?>
<!-- time picker start-->

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">    
         <form name="addcompany_form" class="form" id="addcompany_form" action="" method="post" enctype="multipart/form-data">
         <table border="0" cellpadding="5" cellspacing="0" width="100%">
	   <tr>
	   <td><h2 class="tab_sub_tit"><?php echo __('personalinform'); ?></h2></td>
	   <td></td>	
	   </tr>          
           <tr>
           <td valign="top" width="20%"><label><?php echo __('firstname'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
              <input type="text" title="<?php echo __('enterfirstname_msg'); ?>" class="required" name="name" id="name" value="<?php if(isset($postvalue) && array_key_exists('name',$postvalue)){ echo $postvalue['name']; }?>"  minlength="4" maxlength="30" />
              <?php if(isset($errors) && array_key_exists('name',$errors)){ echo "<span class='error'>".ucfirst($errors['name'])."</span>";}?>
		   </div>
           </td>   	
           </tr>        

           <tr>
           <td valign="top" width="20%"><label><?php echo __('Company email'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
              <input type="text" title="<?php echo __('enteremailaddress'); ?>" class="required" name="email" id="email" value="<?php if(isset($postvalue) && array_key_exists('email',$postvalue)){ echo $postvalue['email']; }?>"  maxlength="50" />
              <?php if(isset($errors) && array_key_exists('email',$errors)){ echo "<span class='error'>".ucfirst($errors['email'])."</span>";}?>
		   </div>
           </td>   	
           </tr>  
           
           <tr>
           <td valign="top" width="20%"><label><?php echo __('sales_person_email'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
              <input type="text" title="<?php echo __('enter_sales_person_email_address'); ?>" class="required" name="sales_person_email" id="sales_person_email" value="<?php if(isset($postvalue) && array_key_exists('sales_person_email',$postvalue)){ echo $postvalue['sales_person_email']; }?>"  maxlength="50" />
                  <span style="width: 209px;">Detail of Trail pack sent to this email</span>
              <?php if(isset($errors) && array_key_exists('sales_person_email',$errors)){ echo "<span class='error'>".ucfirst($errors['sales_person_email'])."</span>";}?>
		   </div>
           </td>  
            	
           </tr>
                        
           <tr>
           <td valign="top" width="20%"><label><?php echo __('mobile'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
              <input type="text" title="<?php echo __('entermobileno'); ?>" name="phone" id="phone" class="" value="<?php if(isset($postvalue) && array_key_exists('phone',$postvalue)){ echo $postvalue['phone']; }?>" minlength="7" maxlength="20" />
              <?php if(isset($errors) && array_key_exists('phone',$errors)){ echo "<span class='error'>".ucfirst($errors['phone'])."</span>";}?>
		   </div>
           </td>   	
           </tr>  
            
           <tr>
	   <td><?php echo ucfirst(__('companyinformation')); ?></td>
	   <td></td>	          
           </tr>
           
           <tr>
           <td valign="top" width="20%"><label><?php echo __('companyname'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
              <input type="text" title="<?php echo __('enterthecompanyname_msg'); ?>" class="required" name="company_name" id="company_name" value="<?php if(isset($postvalue) && array_key_exists('company_name',$postvalue)){ echo $postvalue['company_name']; }?>"  minlength="4" maxlength="30"  />
              <?php if(isset($errors) && array_key_exists('company_name',$errors)){ echo "<span class='error'>".ucfirst($errors['company_name'])."</span>";}?>
		   </div>
           </td>   	
           </tr>   

           <td valign="top" width="20%"><label><?php echo __('company_domain'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
              <input type="text" title="<?php echo __('enter_company_domain'); ?>" class="required" onblur="checkdomainname(this.value)" name="domain_name" id="domain_name" value="<?php if(isset($postvalue) && array_key_exists('domain_name',$postvalue)){ echo $postvalue['domain_name']; }?>"  minlength="5" maxlength="15" onkeyup="nospaces(this)" style="display:inline"/> 
              <span><?php echo SUB_DOMAIN_NAME; ?></span><br /><span id="unameavilable" class="validerror"> </span>
              <?php if(isset($errors) && array_key_exists('domain_name',$errors)){ echo "<span class='error'>".ucfirst($errors['domain_name'])."</span>";}?>
		   </div>
           </td>   	
           </tr>  
	<tr>
           <td valign="top" width="20%"><label><?php echo __('Number of Taxi'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
			<?php $field_type =''; if(isset($postvalue) && array_key_exists('no_of_taxi',$postvalue)){ $field_type =  $postvalue['no_of_taxi']; } ?>
			<select name='no_of_taxi' style="width:200px;">
			<option value="">--Select Taxi --</option>
			<?php for($i=1;$i<=10;$i++){ ?>
				<option value="<?php echo $i; ?>" <?php if($field_type == $i) { echo 'selected=selected'; } ?> ><?php echo $i; ?></option>
			<?php } ?>
			</select>
			<?php if(isset($errors) && array_key_exists('no_of_taxi',$errors)){ echo "<span class='error'>".ucfirst($errors['no_of_taxi'])."</span>";}?>
            <?php /*  <input type="text" title="<?php echo __('No.of.taxi'); ?>" name="no_of_taxi" id="no_of_taxi" class="" value="<?php if(isset($postvalue) && array_key_exists('no_of_taxi',$postvalue)){ echo $postvalue['no_of_taxi']; }?>"  />
              <?php if(isset($errors) && array_key_exists('no_of_taxi',$errors)){ echo "<span class='error'>".ucfirst($errors['no_of_taxi'])."</span>";}?> */ ?>
		   </div>
           </td>   	
           </tr>
                      
           <tr>
           <td valign="top" width="20%"><label><?php echo __('message'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
			<textarea name="message" id="company_address"  class="required" title="<?php echo __('message'); ?>" rows="7" cols="35"><?php if(isset($postvalue) && array_key_exists('message',$postvalue)){ echo $postvalue['message']; }?></textarea>

              <?php if(isset($errors) && array_key_exists('message',$errors)){ echo "<span class='error'>".ucfirst($errors['message'])."</span>";}?>
		   </div>
           </td>   	
           </tr>   
	<input type="hidden" name="currency_symbol" id="currency_symbol" value="$"/>
	<input type="hidden" name="currency_code" id="currency_code" value="USD"/>
	<tr>
	<?php $field_type =''; if(isset($postvalue) && array_key_exists('time_zone',$postvalue)){ $field_type =  $postvalue['time_zone']; } ?>
	<td valign="top" width="20%"><label><?php echo __('time_zone'); ?></label><span class="star">*</span></td>
	<td>
	<div class="formRight">
	<div class="selector selector_timezone" >
	<span><?php echo __('select_label'); ?></span>
	<div id="timezone_list">
		<select name="time_zone" id="time_zone" class="required" title="<?php echo __('select_time_zone'); ?>">
		<option value=""><?php echo __('select_label'); ?></option>
		<?php

		 $timezone = unserialize(SELECT_TIMEZONE);
	     ksort($timezone);

		foreach($timezone as $key => $value) {  ?>
		
		<option value="<?php echo $value; ?>" <?php if($field_type == $value) { echo 'selected=selected'; } ?> ><?php echo ucfirst($value); ?></option>
		<?php	} ?>
		</select>

	</div>	
		</div></div>
		<span class="ref-text-info">Reference Url: <a href="http://php.net/manual/en/timezones.php" target="_blank">http://php.net/manual/en/timezones.php</a></span>
		<label for="time_zone" generated="true" style="display:none" class="errorvalid"><?php echo __('select_time_zone'); ?></label>	
              <?php if(isset($errors) && array_key_exists('time_zone',$errors)){ echo "<span class='error'>".ucfirst($errors['time_zone'])."</span>"; }?>
          </td>    
	</tr>
	
	
	</table>

	<table border="0" cellpadding="5" cellspacing="0" width="100%">         
             
 	<tr>
	<td width="20%" class="empt_cel">&nbsp;</td>
	<td colspan="" class="star">*<?php echo __('required_label'); ?></td>
	</tr>   
                    <tr>
						<td width="20%">&nbsp;</td>
                        <td colspan="">
                            <br/>
                    		<input type="hidden" name="type"  value="2" />
                            <div class="new_button">     <input type="button" value="<?php echo __('button_back'); ?>" onclick="window.history.go(-1)" /></div>
                            <div class="new_button">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_addcompany" title="<?php echo __('submit' );?>" /></div>
                            <div class="new_button">   <input type="reset" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
                            
                        </td>
                    </tr> 

                </table>
        </form>
        </div>
    </div>
</div>  

<?php

$date = new DateTime('now', new DateTimeZone(TIMEZONE));
$current_time = $date->format('H:i:s');

?>

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/select2.js"></script>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/css/select2.css"/>
<style>
	.ref-text-info{
		 padding: 10px 0 0 0;
		display: block;
		clear: both;
	}
		
</style>
<script type="text/javascript">
$(document).ready(function(){
	$("#firstname").focus();
	//jQuery("#addcompany_form").validate();
	$("#time_zone").select2();
});
function nospaces(t){
  if(t.value.match(/\s/g)){
    alert("Do Not Allow Space");
    t.value=t.value.replace(/\s/g,'');
  }

}


	
</script>
