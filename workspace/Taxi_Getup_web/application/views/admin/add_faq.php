<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
        <div class="content_middle"> 
            <form name="addmotor_form" id="addmotor_form" class="form" action="" method="post" enctype="multipart/form-data">
                <table border="0" cellpadding="5" cellspacing="0" width="100%">                                                  
                    <td valign="top" width="20%"><label><?php echo __('faq_title'); ?></label><span class="star">*</span></td>        
                    <td>	
                        <div class="formRight">
                            <div class="new_input_field">
                                <input type="text" title="<?php echo __('enter_faq_title'); ?>" name="faq_title" id="faq_title" value="<?php if (isset($postvalue) && array_key_exists('faq_title', $postvalue)) {
    echo $postvalue['faq_title'];
} ?>"  />
<?php if (isset($errors) && array_key_exists('faq_title', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['faq_title']) . "</span>";
} ?>
                            </div>
                        </div>      
                    </td>   	
                    </tr> 

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('faq_details'); ?></label><span class="star">*</span></td>        
                        <td>
                            <div class="new_input_field">
                                <textarea name="faq_details" id="faq_details"  title="<?php echo __('enter_faq_details'); ?>" class="required" rows="7" cols="35"><?php if (isset($postvalue) && array_key_exists('faq_details', $postvalue)) {
    echo $postvalue['faq_details'];
} ?></textarea>

<?php if (isset($errors) && array_key_exists('faq_details', $errors)) {
    echo "<span class='error'>" . ucfirst($errors['faq_details']) . "</span>";
} ?>
                            </div>
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
                            <div class="new_button">  <input type="submit" value="<?php echo __('submit'); ?>" name="submit_addmodel" title="<?php echo __('submit'); ?>" /></div>
                            <div class="new_button">   <input type="reset" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
                            
                        </td>
                    </tr> 
                </table>
            </form>
        </div>
    </div>
</div>  
