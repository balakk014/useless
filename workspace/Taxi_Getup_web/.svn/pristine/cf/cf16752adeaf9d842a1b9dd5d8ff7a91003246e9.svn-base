<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL_BASE;?>public/css/jquery.autocomplete.css" />
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/jquery.autocomplete.js"></script>

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">
         <form name="addausers_form" class="form" id="addusers_form" action="" method="post" enctype="multipart/form-data" onSubmit="check_passengerexit()">
           <table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr>
           <td valign="top" width="20%"><label><?php echo __('passenger_name'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_passenger_name'); ?>" name="firstname" id="account_firstname" class="required" value="<?php if(isset($postvalue) && array_key_exists('firstname',$postvalue)){ echo $postvalue['firstname']; }?>"  minlength="4" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('name',$errors)){ echo "<span class='error'>".__('enter_passenger_name')."</span>";}?>
					<input name="passenger_id" id="passenger_id" type="hidden" >
				   </div>
				</td>
		  </tr>
		  <tr>
           <td valign="top" width="20%"><label><?php echo __('passenger_email'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enteremailaddress'); ?>" name="email" id="account_email" class="required email" value="<?php if(isset($postvalue) && array_key_exists('email',$postvalue)){ echo $postvalue['email']; }?>"  maxlength="50" minlength="4" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('email',$errors)){ echo "<span class='error'>".__('enteremailaddress')."</span>";}?>
					<span id="uemailavilable" class="error"> </span>
				   </div>
				</td>
		  </tr>
		  <tr>
           <td valign="top" width="20%"><label><?php echo __('passenger_phone'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('entermobileno'); ?>" name="phone" id="account_phone" class="required number" value="<?php if(isset($postvalue) && array_key_exists('phone',$postvalue)){ echo $postvalue['phone']; }?>"  maxlength="15" minlength="4" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('phone',$errors)){ echo "<span class='error'>".__('entermobileno')."</span>";}?>
				<span id="uphoneavilable" class="error"> </span>
				   </div>
				</td>
		  </tr>

		   <tr>
           		<td valign="top" width="20%"><label><?php echo __('group_name'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div  class="selector">
					  <select name="group_name" id="group_name">
					  <?php foreach($getgroup_details as $groups) { ?>
					  <option value="<?php echo $groups['gid']; ?>"><?php echo ucfirst($groups['department']); ?></option>
					  <?php } ?>
					  </select>
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
                    
                            <div class="button blackB">     <input type="button" value="<?php echo __('button_back'); ?>" onclick="window.history.go(-1)" /></div>
                            <div class="button dredB">   <input type="reset" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
                            <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_addusers" title="<?php echo __('submit' );?>" /></div>
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
	$("#addusers_form").validate();
	
	$("#account_firstname").autocomplete("<?php echo URL_BASE; ?>tdispatch/firstname_load", {
	});


	$("#account_email").autocomplete("<?php echo URL_BASE; ?>tdispatch/email_load", {
	});

	$("#account_phone").autocomplete("<?php echo URL_BASE; ?>tdispatch/phone_load", {
	});

	$('#account_firstname').change(function(){
		$('#passenger_id').val('');
		//$('#account_email').val('');
		$('#account_email').removeAttr('readonly');
		//$('#account_phone').val('');
		$('#account_phone').removeAttr('readonly');
	});

	$('#account_email').change(function(){
		$('#passenger_id').val('');
		//$('#account_firstname').val('');
		$('#account_firstname').removeAttr('readonly');
		//$('#account_phone').val('');
		$('#account_phone').removeAttr('readonly');
	});

	$('#account_phone').change(function(){
		$('#passenger_id').val('');
		//$('#account_email').val('');
		$('#account_email').removeAttr('readonly');
		//$('#account_firstname').val('');
		$('#account_firstname').removeAttr('readonly');
	});


});


function check_passengerexit()
{

	var passenger_id = $('#passenger_id').val();
	var email = $('#account_email').val();
	var phone = $('#account_phone').val();

	var SrcPath = '<?php echo URL_BASE; ?>';
	var url= SrcPath+"tdispatch/checkemailuserdetails/";
	var dataS = "email="+email+"&passenger_id="+passenger_id;		

	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		async:false,
		cache: false, 
		dataType: 'html',
		success: function(response) 
		{ 	
			if(trim(response) != 'N')
			{		
				$('#uemailavilable').html('');
				$('#uemailavilable').html(trim(response));
			}
			else
			{
				$('#uemailavilable').html('');
			}	
		} 
		 
	});



	var url= SrcPath+"tdispatch/checkphoneuserdetails/";
	var dataS = "phone="+phone+"&passenger_id="+passenger_id;	
	
	$.ajax
	({ 			
		type: "POST",
		url: url, 
		data: dataS, 
		cache: false, 
		async:false,
		dataType: 'html',
		success: function(response) 
		{ 	
			if(trim(response) != 'N')
			{		
				$('#uphoneavilable').html('');
				$('#uphoneavilable').html(trim(response));
			}
			else
			{
				$('#uphoneavilable').html('');
			}	
		} 
		 
	});

	var uemailavilable = $('#uemailavilable').html();
	var uphoneavilable = $('#uphoneavilable').html();

	if(uphoneavilable == "" && uemailavilable == "")
	{
		return true;
	}	
	else
	{
		event.preventDefault();	
		return false;
	}


}

</script>
