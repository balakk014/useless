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
		<form method="POST" class="form" name="frmusers" id="frmusers" action="<?php echo URL_BASE."manageusers/add_passenger_credits/".$pass_id ?>">
 
	<table border="0" cellpadding="5" cellspacing="0" width="100%">
           <tr>
           <td valign="top" width="20%"><label><?php echo __('Passenger Name'); ?></label><span class="star"></span></td>        
			   <td>
				   <div class="new_input_field">
					   <input type = "hidden" name="passenger_id" id ="passenger_id" value="<?php echo $pass_id; ?>">
					   <p id="passenger_name"></p>
				   </div>
				</td>
		  </tr>
		  
		  <tr>
           <td valign="top" width="20%"><label><?php echo __('Passenger Phone'); ?></label><span class="star"></span></td>        
			   <td>
				   <div class="new_input_field">
					   <p id="passenger_phone"></p>
				   </div>
				</td>
		  </tr>
           <tr>
           <td valign="top" width="20%"><label><?php echo __('available_balance'); ?></label><span class="star"></span></td>        
			   <td>
			   <div class="new_input_field">
				<p id="passenger_balance"><?php echo  CURRENCY."0" ?> </p> 	
		      
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
						<input type="text" name="amount" id="amount" class="required" value="<?php if(isset($postvalue) && array_key_exists('amount',$postvalue)){ echo $postvalue['amount']; }?>" title="<?php echo __('amount'); ?>" rows="7">
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

$("#passenger_id").change(function() {
	$.ajax({
			url:"<?php echo URL_BASE;?>manageusers/add_credits",
			type:"get",
			dataType: "json",
			data:{ passenger_id: $("#passenger_id").val() },
			success:function(data){
				$('#passenger_balance').html("<?php echo CURRENCY; ?>"+data.wallet_amount);
			},
			error:function(data)
			{
			}
		});	
});

$(document).ready(function() {
	var passenger_id = $("#passenger_id").val();
	if(passenger_id !="" && passenger_id !=null)
	{
		$.ajax({
				url:"<?php echo URL_BASE;?>manageusers/add_credits",
				type:"get",
				dataType: "json",
				data:{ passenger_id: $("#passenger_id").val() },
				success:function(data){
					$('#passenger_balance').html("<?php echo CURRENCY; ?>"+data.wallet_amount);
					$('#passenger_name').html(data.passenger_name);
					$('#passenger_phone').html(data.passenger_phone);
				},
				error:function(data)
				{
				}
			});
	}
	
});


</script>
