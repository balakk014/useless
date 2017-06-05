<?php defined('SYSPATH') OR die("No direct access allowed."); ?>


<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">
         <form name="addmotor_form" class="form" id="addmotor_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%">
           <tr>
           <td valign="top" width="20%"><label><?php echo __('motorcompanyname'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
		   	              <input type="text" title="<?php echo __('enterthemotorcompanyname'); ?>" name="companyname" id="companyname" value="<?php if(isset($postvalue) && array_key_exists('companyname',$postvalue)){ echo $postvalue['companyname']; }?>"  minlength="2" maxlength="30" />
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
                            <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_addmotor" title="<?php echo __('submit' );?>" /></div>
                            <div class="clr">&nbsp;</div>
                        </td>
                    </tr> 

                </table>
                

        </form>
        </div>
        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>
    </div>
</div>    
</div>
<script type="text/javascript">
$(document).ready(function(){
 $("#companyname").focus(); 
});
</script>
