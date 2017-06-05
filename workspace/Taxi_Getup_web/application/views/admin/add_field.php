<?php defined('SYSPATH') OR die("No direct access allowed."); 
?>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
         <form name="addfield_form" id="addfield_form" class="form" action="" method="post" enctype="multipart/form-data">
       <table border="0" cellpadding="5" cellspacing="0" width="100%">                               
	<tr>
	<td><?php echo ucfirst(__('managefieldinform')); ?></td>
	<td></td>	          
	</tr>
	
	<tr>
	<td valign="top" width="20%"><label><?php echo __('label_name'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
              <input type="text" title="<?php echo __('enter_label_name'); ?>" name="field_labelname" id="field_labelname" value="<?php if(isset($postvalue) && array_key_exists('field_labelname',$postvalue)){ echo $postvalue['field_labelname']; }?>"  maxlength="20" />
              <?php if(isset($errors) && array_key_exists('field_labelname',$errors)){ echo "<span class='error'>".ucfirst($errors['field_labelname'])."</span>";}?>
	</div>
	</td>   	
	</tr> 
	
	<tr>
	<td valign="top" width="20%"><label><?php echo __('field_name'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
               <input type="text" title="<?php echo __('enter_field_name'); ?>" id="field_name" name="field_name" value="<?php if(isset($postvalue) && array_key_exists('field_name',$postvalue)){ echo $postvalue['field_name']; }?>" maxlength="20" />
              <?php if(isset($errors) && array_key_exists('field_name',$errors)){ echo "<span class='error'>".ucfirst($errors['field_name'])."</span>";}?>
	</div>
	</td>   	
	</tr> 
	
	<tr>
	<?php $field_type =''; if(isset($postvalue) && array_key_exists('field_type',$postvalue)){ $field_type =  $postvalue['field_type']; } ?>
	<td valign="top" width="20%"><label><?php echo __('field_type'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="formRight">
	<div class="selector" id="uniform-user_type">
	<span><?php echo __('select_label'); ?></span>
              <select name="field_type" id="field_type">
              <option value='Textbox' <?php if($field_type == 'Textbox') { echo 'selected=selected'; } ?>>Textbox</option>
              <option value='Select' <?php if($field_type == 'Select') { echo 'selected=selected'; } ?> >Select</option>
              <?php /* <option value='Checkbox' <?php if($field_type == 'Checkbox') { echo 'selected=selected'; } ?>>Checkbox</option> */ ?>
              <option value='Radio' <?php if($field_type == 'Radio') { echo 'selected=selected'; } ?>>Radio</option>
              </select>
        </div>
	</div>      
              <?php if(isset($errors) && array_key_exists('field_type',$errors)){ echo "<span class='error'>".ucfirst($errors['field_type'])."</span>";}?>
	</td>   	
	</tr> 
		
	<tr id="show_textarea">
	<td valign="top" width="20%"><label><?php echo __('field_value'); ?></label><span class="star">*</span></td>        
	<td>
	<div class="new_input_field">
               <textarea name="field_value" id="field_value"  title="<?php echo __('enter_field_value'); ?>" rows="7" cols="35"><?php if(isset($postvalue) && array_key_exists('field_value',$postvalue)){ echo $postvalue['field_value']; }?></textarea>
              <?php if(isset($errors) && array_key_exists('field_value',$errors)){ echo "<span class='error'>".ucfirst($errors['field_value'])."</span>";}?>
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
                            <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_addfield" title="<?php echo __('submit' );?>" /></div>
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
 $("#field_labelname").focus(); 
	toggle(8);
	$('#show_textarea').hide();
	show_value();
});

function show_value()
{
	var sel_val = $("#field_type").val();
	if(sel_val != 'Textbox')
	{
		$('#show_textarea').show();
	}
	else
	{
		$('#show_textarea').hide();
	}
	
}

$("#field_type").change(function() {
	var sel_val = $("#field_type").val();
	if(sel_val != 'Textbox')
	{
		$('#show_textarea').show();
	}
	else
	{
		$('#show_textarea').hide();
	}
});
</script>
