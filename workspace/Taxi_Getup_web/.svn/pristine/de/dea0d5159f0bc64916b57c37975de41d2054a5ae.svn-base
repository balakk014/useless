<?php
defined('SYSPATH') OR die("No direct access allowed.");
$mile_name = $model_details[0]['mile_name'];
?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
            <form method="POST" enctype="multipart/form-data" class="form" action="" >
			<table class="0" cellpadding="5" cellspacing="0">
				<tr>
					<td>
						<label><?php echo __('mile_name_label'); ?>:<span class="star">*</span></label> 					
						<div class="new_input_field">
						<input type="text" class="required onlynumbers" title="<?php echo __('enter_the_mile'); ?>" name="mile" id="mile" value="<?php echo $mile_name; ?>" >					
						<?php if(isset($errors) && array_key_exists('mile',$errors)){ echo "<span class='error'>".ucfirst($errors['mile'])."</span>";}?>
						</div>						
					</td>					
				</tr>
			<tr>
                        <td>
                            <div class="button dredB"> <input type="reset" name="reset_mile" title="<?php echo __('button_reset'); ?>" value="<?php echo __('button_reset'); ?>"></div>
                            <div class="button greenB">  <input type="submit" name="submit_editmile" title ="<?php echo __('submit'); ?>" value="<?php echo __('submit'); ?>"></div>

                        </td></tr>

			</table>
            </form>

        </div>

        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt" ></div></div>
    </div>

</div>

<script type="text/javascript">
 $(document).ready(function(){

var field_val = $("#mile").val();
$("#mile").focus().val("").val(field_val);   

});
</script>
