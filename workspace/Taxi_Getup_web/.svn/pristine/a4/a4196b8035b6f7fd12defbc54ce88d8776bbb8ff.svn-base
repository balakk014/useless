<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">
         <form name="addgroups_form" class="form" id="addgroups_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%">
           <tr>
           <td valign="top" width="20%"><label><?php echo __('group_name'); ?></label><span class="star">*</span></td>        
			   <td>
					<input type="hidden" name = "acc_id" value = "<?php echo $account_id; ?>"/>
				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_group_name'); ?>" name="group_name" id="group_name" class="required" value="<?php if(isset($postvalue) && array_key_exists('group_name',$postvalue)){ echo $postvalue['group_name']; }?>"  maxlength="150" minlength="4" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('group_name',$errors)){ echo "<span class='error'>".__('enter_group_name')."</span>";}?>					  
				   </div>
				</td>
		  </tr>
		  <?php if($account_limit > 0)
		  { ?>
		  <tr>
           <td valign="top" width="20%"><label><?php 
           $company_currency = findcompany_currency($_SESSION['company_id']);
           echo __('limit').'('.$company_currency.')'; ?></label></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_group_limit'); ?>" name="limit" id="limit" class="required number" value="<?php if(isset($postvalue) && array_key_exists('limit',$postvalue)){ echo $postvalue['limit']; }?>"  maxlength="7" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('limit',$errors)){ echo "<span class='error'>".__('enter_group_limit')."</span>";}?>
				   </div>
				</td>
		  </tr>
		  <?php } else {  ?>
			<input type="hidden" name="limit" id="limit" value="0"/>
			<?php } ?>
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
                            <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_addgroups" title="<?php echo __('submit' );?>" /></div>
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

	toggle(31);
	$("#addgroups_form").validate();
});

</script>
