<?php defined('SYSPATH') OR die("No direct access allowed."); ?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
         <form name="editmotor_form" class="form" id="editmotor_form" action="" method="post" enctype="multipart/form-data">
         <table border="0" cellpadding="5" cellspacing="0" width="100%">          
	<tr>
	<td valign="top" width="20%"><label><?php echo __('motorcompanyname'); ?></label><span class="star">*</span></td>        
	<td>
		  <?php 
				if(isset($company_details[0]['motor_name'])){
					$motor_name = $company_details[0]['motor_name'];
				}else{
					if(isset($postvalue['motor_name'])){
						$motor_name = $postvalue['motor_name'];
					}else{
						$motor_name = "";
					}
				}
				?>       
	<div class="new_input_field">
              <input type="text"  maxlength="30" minlength="2"  title="<?php echo __('enterthemotorcompanyname'); ?>" id="companyname" name="companyname" value="<?php echo $motor_name; ?>" />
              <?php if(isset($errors) && array_key_exists('companyname',$errors)){ echo "<span class='error'>".ucfirst(__('enterthemotorcompanyname'))."</span>";}?>
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
                            <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_editmotor" title="<?php echo __('submit' );?>" /></div>
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

var field_val = $("#companyname").val();
$("#companyname").focus().val("").val(field_val);
});
</script>
