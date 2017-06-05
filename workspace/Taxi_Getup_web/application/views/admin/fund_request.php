<?php
defined('SYSPATH') OR die("No direct access allowed.");

?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
            <form method="POST" enctype="multipart/form-data" class="form" action="" >
                <table class="0" cellpadding="5" cellspacing="0" width="85%">
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('amount'); ?> </label><span class="star">*</span></td>   
                        <td><div class="new_input_field"><input type="text" name="amount" id="facebook_key" title="<?php echo __('enter_the_amount'); ?>" maxlength="5" value="<?php echo isset($postvalue['amount'])? trim($postvalue['amount']):''; ?>"></div>
                    <?php if(isset($errors) && array_key_exists('amount',$errors)){ echo "<span class='error'>".__(ucfirst($errors['amount']))."</span>";}?>
			<span class="textclass fl clr"><span class="star">*</span><?php echo __('minimum_withdraw_amount').' : '.CURRENCY.MIN_FUND; ?></span>		     	
			<span class="textclass fl clr"><span class="star">*</span><?php echo __('maximum_withdraw_amount').' : '.CURRENCY.MAX_FUND; ?></span>
			<input type="hidden" name="company_id" id="company_id" value="<?php echo $_SESSION['company_id'];?>">
			</td>
                    </tr>

                    <tr>
			<td>&nbsp;</td>
                        <td colspan="" class="star">*<?php echo __('required_label'); ?></td>
                    </tr>                                
                    <tr>
                        <td valign="top">&nbsp;</td>
                        <td style="padding-left:0px;">
                            <div class="button dredB"> <input type="reset" name="fundrequest_reset" title="<?php echo __('button_reset'); ?>" value="<?php echo __('button_reset'); ?>"></div>
                            <div class="button greenB">  <input type="submit" name="fundrequest_submit" title ="<?php echo __('button_update'); ?>" value="<?php echo __('button_update'); ?>"></div>

                        </td></tr>
                </table>

            </form>
            <br/><br/>

        </div>

        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt" ></div></div>
    </div>
</div>
<script type="text/javascript">
 $(document).ready(function(){
toggle(26);
});
</script>
