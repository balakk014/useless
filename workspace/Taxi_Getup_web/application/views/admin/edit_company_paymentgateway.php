<?php
defined('SYSPATH') OR die("No direct access allowed.");
?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
            <form method="POST" enctype="multipart/form-data" class="form" action="" >
              <table class="0" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('payment_gatway_name'); ?></label></td>   
                        <td><div class="new_input_field"><label><?php echo isset($payment_settings[0]['payment_gatway'])? $payment_settings[0]['payment_gatway'] : ""; ?></label></div>
                   </td>
                    </tr>
					<?php 
					if(isset($payment_settings[0]['description'])){
						$description = $payment_settings[0]['description'];
					}else{
						if(isset($postvalue['description'])){
							$description = $postvalue['description'];
						}else{
							$description = "";
						}
					}
					?>         
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('payment_description'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field"><input type="text" name="description" id="description1" title="<?php echo __('enter_payment_description'); ?>" maxlength="250" value="<?php echo $description; ?>" > </div>
                            <?php if(isset($errors) && array_key_exists('description',$errors)){ echo "<span class='error'>".ucfirst($errors['description'])."</span>";}?></td>
                        </td>

                    </tr>                               
                     <tr>
                        <td valign="top" width="20%"><label><?php echo __('currency_code'); ?></label><span class="star">*</span></td>   
                        <td>
				<div class="formRight">
				<div class="selector" id="uniform-user_type">
				<span><?php echo __('select_label'); ?></span>
				<select name="currency_code" id="currency_code" title="<?php echo __('enter_currency_code'); ?>" >
				<!--<option value="">-- Select Currency Code--</option>-->
				<option value="USD">USD</option>
				<!-- <option value="EUR">EUR</option>  -->
				<?php /*foreach($currency_code as $key=>$currencycode){ ?>
				<option value='<?php echo $currencycode;?>' <?php if($payment_settings[0]['currency_code'] == $currencycode) { echo 'selected=selected'; } ?> ><?php echo $currencycode;?></option>
				<?php } */                    
                            
                            
					if(isset($payment_settings[0]['currency_code']) && (!array_key_exists('currency_code', $errors))){
						$currency_code = $payment_settings[0]['currency_code'];
					}else{
						if(isset($validator['description'])){
							$currency_code = $validator['currency_code'];
						}else{
							$currency_code = "";
						}
					}
				     echo $currency_code;
                            
                           /* echo isset($payment_settings) && (!array_key_exists('currency_code', $errors)) ? $payment_settings[0]['currency_code'] : $validator['currency_code'];  */ ?></select>
                            </div>
                            </div>
                            <span class="error"><?php echo isset($errors['currency_code']) ? $errors['currency_code'] : ''; ?></span>
                            </td>
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('currency_symbol'); ?></label><span class="star">*</span></td>   
                        <td>
			<div class="formRight">
			<div class="selector" id="uniform-user_type">
			<span><?php echo __('select_label'); ?></span>

				<select name="currency_symbol" id="currency_symbol" title="<?php echo __('enter_currency_symbol'); ?>" >
				<!--<option value="">-- Select Currency Symbol--</option>-->
				<option value="$">$</option> 
				 <!--<option value="€">€</option> -->
				<?php /* foreach($currency_symbol as $key=>$currencysymbol){ ?>
				<option value='<?php echo $currencysymbol;?>' <?php if($payment_settings[0]['currency_symbol'] == $currencysymbol) { echo 'selected=selected'; } ?> ><?php echo $currencysymbol;?></option>
				<?php } */ ?>
				 <?php 
					if(isset($payment_settings[0]['currency_symbol'])){
						$currency_symbol = $payment_settings[0]['currency_symbol'];
					}else{
						if(isset($postvalue['currency_symbol'])){
							$currency_symbol = $postvalue['currency_symbol'];
						}else{
							$currency_symbol = "";
						}
					}
					?>     
				<?php echo $currency_symbol; ?>
				</select>
			</div>
			</div>      
                        <span class="error"><?php echo isset($errors['currency_symbol']) ? $errors['currency_symbol'] : ''; ?></span>
                        </td>
                    </tr> 
                
                    <tr>
			<tr>
                        <td valign="top" width="20%"><label><?php echo __('payment_method'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field">
                        
                        <input type="radio" name="payment_method" onclick="change_payment_option('T');" id="payment_method" title="<?php echo __('enter_payment_method'); ?>"  value="T" <?php if(isset($postvalue['payment_method'])){ if($postvalue['payment_method']=='T'){ echo 'checked=checked';} }else{ if($payment_settings[0]['payment_method']=='T'){ echo 'checked=checked';} }?> ><?php echo 'Test Mode'; ?>
                        
                        <input type="radio" name="payment_method" onclick="change_payment_option('L');" id="payment_method" title="<?php echo __('enter_payment_method'); ?>"  value="L" <?php if(isset($postvalue['payment_method'])){ if($postvalue['payment_method']=='L'){ echo 'checked=checked';} }else{ if($payment_settings[0]['payment_method']=='L'){ echo 'checked=checked';} }?>><?php  echo 'Live Mode'; ?>
                        </div>
                    <?php if(isset($errors) && array_key_exists('payment_method',$errors)){ echo "<span class='error'>".ucfirst($errors['payment_method'])."</span>";}?></td>
                    </tr>
                     <?php 
						if(isset($payment_settings[0]['paypal_api_username'])){
							$paypal_api_username = $payment_settings[0]['paypal_api_username'];
						}else{
							if(isset($postvalue['paypal_api_username'])){
								$paypal_api_username = $postvalue['paypal_api_username'];
							}else{
								$paypal_api_username = "";
							}
						}
						?>     
					<tr class="test_mode">
						<td valign="top" width="20%"><label><?php echo __('paypal_api_username'); ?></label><span class="star">*</span></td>   
						<td><div class="new_input_field"><input type="text" name="paypal_api_username" id="paypal_api_username" title="<?php echo __('enter_paypal_api_username'); ?>" maxlength="250" value="<?php echo $paypal_api_username; ?>" > </div>
							<?php if(isset($errors) && array_key_exists('paypal_api_username',$errors)){ echo "<span class='error'>".ucfirst($errors['paypal_api_username'])."</span>"; } /* <?php echo isset($payment_settings) && (!array_key_exists('paypal_api_username', $errors)) ? $payment_settings[0]['paypal_api_username'] : $validator['paypal_api_username']; ?> */ ?></td>
						</td>
					</tr> 
					<?php 
						if(isset($payment_settings[0]['paypal_api_signature'])){
							$paypal_api_signature = $payment_settings[0]['paypal_api_signature'];
						}else{
							if(isset($postvalue['paypal_api_password'])){
								$paypal_api_signature = $postvalue['paypal_api_password'];
							}else{
								$paypal_api_signature = "";
							}
						}
						?>    
					<tr class="test_mode">
						<td valign="top" width="20%"><label><?php echo __('paypal_api_password'); ?></label><span class="star">*</span></td>   
						<td><div class="new_input_field"><input type="text" name="paypal_api_password" id="paypal_api_password" title="<?php echo __('enter_paypal_api_password'); ?>" maxlength="250" value="<?php echo $paypal_api_signature; ?>"></div>
							<?php if(isset($errors) && array_key_exists('paypal_api_password',$errors)){ echo "<span class='error'>".ucfirst($errors['paypal_api_password'])."</span>";} /* <?php echo isset($payment_settings) && (!array_key_exists('paypal_api_password', $errors)) ? $payment_settings[0]['paypal_api_password'] : $validator['paypal_api_password']; ?> */ ?></td>
					</tr>
					<?php 
						if(isset($payment_settings[0]['paypal_api_signature'])){
							$paypal_api = $payment_settings[0]['paypal_api_signature'];
						}else{
							if(isset($postvalue['paypal_api_signature'])){
								$paypal_api = $postvalue['paypal_api_signature'];
							}else{
								$paypal_api = "";
							}
						}
						?>    
					<tr class="test_mode">
						<td valign="top" width="20%"><label><?php echo __('paypal_api_signature'); ?></label><span class="star">*</span></td>   
						<td><div class="new_input_field"><input type="text" name="paypal_api_signature" id="paypal_api_signature"  title="<?php echo __('enter_paypal_api_signature'); ?>" maxlength="250" value="<?php echo $paypal_api; ?>"></div>
							<?php if(isset($errors) && array_key_exists('paypal_api_signature',$errors)){ echo "<span class='error'>".ucfirst($errors['paypal_api_signature'])."</span>";}?></td>
					</tr>
						<?php 
						if(isset($payment_settings[0]['live_paypal_api_username'])){
							$live_paypal_api_username = $payment_settings[0]['live_paypal_api_username'];
						}else{
							if(isset($postvalue['live_paypal_api_username'])){
								$live_paypal_api_username = $postvalue['live_paypal_api_username'];
							}else{
								$live_paypal_api_username = "";
							}
						}
						?>    
					<tr class="live_mode">
						<td valign="top" width="20%"><label><?php echo __('paypal_api_username'); ?></label><span class="star">*</span></td>   
						<td><div class="new_input_field"><input type="text" name="live_paypal_api_username" id="live_paypal_api_username" title="<?php echo __('enter_paypal_api_username'); ?>" maxlength="250" value="<?php echo $live_paypal_api_username; ?>" > </div>
							<?php if(isset($errors) && array_key_exists('live_paypal_api_username',$errors)){ echo "<span class='error'>".ucfirst($errors['live_paypal_api_username'])."</span>"; } ?></td>
						</td>
					</tr> 
					<?php 
						if(isset($payment_settings[0]['live_paypal_api_password'])){
							$live_paypal_api_password = $payment_settings[0]['live_paypal_api_password'];
						}else{
							if(isset($postvalue['live_paypal_api_password'])){
								$live_paypal_api_password = $postvalue['live_paypal_api_password'];
							}else{
								$live_paypal_api_password = "";
							}
						}
						?> 
					<tr class="live_mode">
						<td valign="top" width="20%"><label><?php echo __('paypal_api_password'); ?></label><span class="star">*</span></td>   
						<td><div class="new_input_field"><input type="text" name="live_paypal_api_password" id="live_paypal_api_password" title="<?php echo __('enter_paypal_api_password'); ?>" maxlength="250" value="<?php echo $live_paypal_api_password;?>"></div>
							<?php if(isset($errors) && array_key_exists('live_paypal_api_password',$errors)){ echo "<span class='error'>".ucfirst($errors['live_paypal_api_password'])."</span>";} ?></td>
					</tr>
					<?php 
						if(isset($payment_settings[0]['live_paypal_api_signature'])){
							$live_paypal_api_signature = $payment_settings[0]['live_paypal_api_signature'];
						}else{
							if(isset($postvalue['live_paypal_api_signature'])){
								$live_paypal_api_signature = $postvalue['live_paypal_api_signature'];
							}else{
								$live_paypal_api_signature = "";
							}
						}
						?> 
					<tr class="live_mode">
						<td valign="top" width="20%"><label><?php echo __('paypal_api_signature'); ?></label><span class="star">*</span></td>   
						<td><div class="new_input_field"><input type="text" name="live_paypal_api_signature" id="live_paypal_api_signature" title="<?php echo __('live_paypal_api_signature'); ?>" maxlength="250" value="<?php echo $live_paypal_api_signature;?>"></div>
							<?php if(isset($errors) && array_key_exists('live_paypal_api_signature',$errors)){ echo "<span class='error'>".ucfirst($errors['live_paypal_api_signature'])."</span>";}?></td>
					</tr>

					<tr>
                        <td valign="top">&nbsp;</td>
                        <td style="padding-left:0px;">
							<input type="hidden" name="check_default" value="<?php echo isset($payment_settings[0]['default_payment_gateway']) ? $payment_settings[0]['default_payment_gateway'] : 0; ?>">
                            <div class="new_button"> <input type="reset" name="submit_editpayment" title="<?php echo __('button_reset'); ?>" value="<?php echo __('button_reset'); ?>"></div>
                            <div class="new_button">  <input type="submit" name="submit_editpayment" title ="<?php echo __('button_update'); ?>" value="<?php echo __('button_update'); ?>"></div>

                        </td>
                    </tr>

 		</table> 
		

            </form>
            <br/><br/>

        </div>

        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt" ></div></div>
    </div>

</div>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	
	var field_val = $("#payment_gatway_name").val();
	$("#payment_gatway_name").focus().val("").val(field_val);

	<?php if($payment_settings[0]['payment_method'] == 'T') { ?>
		$(".live_mode").hide();$(".test_mode").show();
	<?php } else { ?>
		$(".test_mode").hide();$(".live_mode").show();
	<?php } ?>
	
	<?php if(isset($errors) && (array_key_exists('live_paypal_api_username',$errors) || array_key_exists('live_paypal_api_password',$errors) || array_key_exists('live_paypal_api_signature',$errors))){ ?>
		$(".test_mode").hide();$(".live_mode").show();
	<?php } ?>
});
function change_payment_option(val)
{
	if(val == "T") {
		$(".live_mode").hide();$(".test_mode").show();
	} else {
		$(".test_mode").hide();$(".live_mode").show();
	}
}
</script>