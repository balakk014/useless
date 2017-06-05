<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo GOOGLE_MAP_API_KEY; ?>&sensor=false&libraries=places"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/tdispatch.js"></script>

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">
         <form name="addlocation_form" class="form" id="addlocation_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%">
           <tr>
           <td valign="top" width="20%"><label><?php echo __('location_name'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_location_name'); ?>" name="location_name" id="location_name" class="required" value="<?php if(isset($postvalue) && array_key_exists('location_name',$postvalue)){ echo $postvalue['location_name']; }?>"  maxlength="150" minlength="3" autocomplete="off" />
					  <?php if(isset($errors) && array_key_exists('location_name',$errors)){ echo "<span class='error'>".ucfirst($errors['location_name'])."</span>";}?>
				   </div>
				</td>
		  </tr>
	<?php /*
           <tr>
           <td valign="top" width="20%"><label><?php echo __('type'); ?></label><span class="star">*</span></td>        
	   <td>
		   <div class="new_input_field">
		   	  <input type="text" title="<?php echo __('enter_type'); ?>" name="type" id="type" class="required" value="<?php if(isset($postvalue) && array_key_exists('type',$postvalue)){ echo $postvalue['type']; }?>"  maxlength="150" minlength="4" autocomplete="off" />
		      <?php if(isset($errors) && array_key_exists('type',$errors)){ echo "<span class='error'>".ucfirst($errors['type'])."</span>";}?>
		   </div>
			<label for="type" generated="true" class="errorvalid" style="display:none;">This field is required.</label>
      
			      <?php if(isset($errors) && array_key_exists('type',$errors)){ echo "<span class='error'>".ucfirst($errors['type'])."</span>";}?>
	   </td>
	  </tr>
           <tr>
           <td valign="top" width="20%"><label><?php echo __('keywords'); ?></label><span class="star">*</span></td>        
			   <td>
			   <div class="new_input_field">
		   	  <input type="text" title="<?php echo __('enter_keywords'); ?>" name="keywords" id="keywords" class="required" value="<?php if(isset($postvalue) && array_key_exists('keywords',$postvalue)){ echo $postvalue['keywords']; }?>"  maxlength="150" minlength="4" autocomplete="off" />
		      <?php if(isset($errors) && array_key_exists('keywords',$errors)){ echo "<span class='error'>".ucfirst($errors['keywords'])."</span>";}?>
		   </div>
           </td>   	
           </tr>
	*/ ?>	
           <tr>
           <td valign="top" width="20%"><label><?php echo __('location_label'); ?></label><span class="star">*</span></td>        
			   <td>
			   <div class="new_input_field" style="width:400px !important;">
		   	  <input type="text" title="<?php echo __('enter_location'); ?>" name="location" id="location" class="required" value="<?php if(isset($postvalue) && array_key_exists('location',$postvalue)){ echo $postvalue['location']; }?>"   />
		      <?php if(isset($errors) && array_key_exists('location',$errors)){ echo "<span class='error'>".ucfirst($errors['location'])."</span>";}?>
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
                            <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_addlocation" title="<?php echo __('submit' );?>" /></div>
                            <div class="clr">&nbsp;</div>
                        </td>
                    </tr> 

                </table>
                

        </form>
        </div>
        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>
    </div>
</div>    
