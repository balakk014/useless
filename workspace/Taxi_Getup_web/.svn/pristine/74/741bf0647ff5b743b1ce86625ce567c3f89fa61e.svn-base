<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<?php /* <script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script> */ ?>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>

<!-- time picker start-->
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<?php /* <script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script> */ ?>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle">
		<form method="POST" class="form" name="frmusers" id="frmusers" action="<?php echo URL_BASE."manageusers/add_driver_credits/".$driv_id ?>">
 
	<table border="0" cellpadding="5" cellspacing="0" width="100%">
           <tr>
           <td valign="top" width="20%"><label><?php echo __('Driver Name'); ?></label><span class="star">*</span></td>        
			   <td>
				   <div class="new_input_field">
					 <input type = "hidden" name="driver_id" id ="driver_id" value="<?php echo $driv_id; ?>">
					 <p id="driver_name"></p>
				   </div>
				</td>
		  </tr>
		  <tr>
           <td valign="top" width="20%"><label><?php echo __('Driver Phone'); ?></label><span class="star"></span></td>        
			   <td>
				   <div class="new_input_field">
					   <p id="driver_phone"></p>
				   </div>
				</td>
		  </tr>
           <tr>
           <td valign="top" width="20%"><label><?php echo __('available_balance'); ?></label><span class="star">*</span></td>        
			   <td>
			   <div class="new_input_field">
				<p id="driver_balance"><?php echo  CURRENCY."0" ?> </p> 	
		      
		   </div>
           </td>   	
           </tr>
           
           <tr>
           <td valign="top" width="20%"><label><?php echo __('pay_type'); ?></label><span class="star">*</span></td>        
			   <td>
				   <div class="new_input_field">
					 <select name="pay_type" id="pay_type" style="width:100%;" class="required">			            			
							<option value="1" <?php if(isset($postvalue['pay_type']) && $postvalue['pay_type'] ==1){ echo 'selected=selected'; } ?>><?php echo __('Credit');?></option>  
							<option value="2" <?php if(isset($postvalue['pay_type']) && $postvalue['pay_type'] ==2){ echo 'selected=selected'; } ?>><?php echo __('Debit');?></option>  
					</select>
				   </div>
				   <?php if(isset($errors) && array_key_exists('pay_type',$errors)){ echo "<span class='error'>".ucfirst($errors['pay_type'])."</span>"; }?>
				</td>  	
           </tr>     
           <tr>
			   <td valign="top" width="20%"><label><?php echo __('amount'); ?></label><span class="star">*</span></td>        
			   <td>
					<div class="new_input_field">
						<input type="text" name="amount" id="amount" maxlength="10" value="<?php if(isset($postvalue) && array_key_exists('amount',$postvalue)){ echo $postvalue['amount']; }?>" class="required" title="<?php echo __('amount'); ?>" rows="7">
					</div>
					<?php if(isset($errors) && array_key_exists('amount',$errors)){ echo "<span class='error'>".ucfirst($errors['amount'])."</span>"; }?>
				</td>   	
           </tr>
            
           <tr>
           <td valign="top" width="20%"><label><?php echo __('comments'); ?></label><span class="star">*</span></td>        
			   <td>
			   <div class="new_input_field">
		   	  <textarea name="comments" id="comments" class="required" title="<?php echo __('entercontent'); ?>" rows="7" cols="35">
		   	  <?php if(isset($postvalue) && array_key_exists('comments',$postvalue)){ echo $postvalue['comments']; }?>
		   	  </textarea>
		   </div>
		   <?php if(isset($errors) && array_key_exists('comments',$errors)){ echo "<span class='error'>".ucfirst($errors['comments'])."</span>"; }?>
           </td>   	
           </tr>
    
	<tr>
            <td class="empt_cel">&nbsp;</td>
	
	<td colspan="" class="star">*<?php echo __('required_label'); ?></td>
	</tr>                         
                    <tr>
			<td>&nbsp;</td>
                        <td colspan="">
                            <div class="new_button">     <input type="button" value="<?php echo __('button_back'); ?>" onclick="window.history.go(-1)" /></div>
							<div class="new_button">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_form" id="submit_form" title="<?php echo __('submit' );?>" /></div>
                            <div class="new_button">   <input type="reset" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
                        </td>
                    </tr> 

                </table>                        

 

                 
</form>
</div>






</div>
</div>
<script type="text/javascript" language="javascript">

$("#driver_id").change(function() {
	$.ajax({
			url:"<?php echo URL_BASE;?>manageusers/add_credits_driver",
			type:"get",
			dataType: "json",
			data:{ driver_id: $("#driver_id").val() },
			success:function(data){
				$('#driver_balance').html("<?php echo CURRENCY; ?>"+data.account_balance);
			},
			error:function(data)
			{
			}
		});	
});

$(document).ready(function() {
	var drive_id = $("#driver_id").val();
	if(drive_id !="" && drive_id !=null)
	{
		$.ajax({
				url:"<?php echo URL_BASE;?>manageusers/add_credits_driver",
				type:"get",
				dataType: "json",
				data:{ driver_id: $("#driver_id").val() },
				success:function(data){
					$('#driver_balance').html("<?php echo CURRENCY; ?>"+data.account_balance);
					$('#driver_name').html(data.driver_name);
					$('#driver_phone').html(data.driver_phone);
				},
				error:function(data)
				{
				}
			});
	}
	
});



</script>
