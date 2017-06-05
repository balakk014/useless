<?php defined('SYSPATH') OR die("No direct access allowed."); ?>

<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery-1.6.3.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/validation/jquery.validate.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo GOOGLE_MAP_API_KEY; ?>&sensor=false&libraries=places"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/tdispatch.js"></script>

<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle">
         <form name="addjourney_form" class="form" id="addjourney_form" action="" method="post" enctype="multipart/form-data">
           <table border="0" cellpadding="5" cellspacing="0" width="100%">
           <tr>
           <td valign="top" width="20%"><label><?php echo __('journey_name'); ?></label><span class="star">*</span></td>        
			   <td>

				   <div class="new_input_field">
					  <input type="text" title="<?php echo __('enter_journey_name'); ?>" name="journey_name" id="journey_name" class="required" value="<?php if(isset($postvalue) && array_key_exists('journey_name',$postvalue)){ echo $postvalue['journey_name']; }?>"  maxlength="150" minlength="3" />
					  <?php if(isset($errors) && array_key_exists('journey_name',$errors)){ echo "<span class='error'>".ucfirst($errors['journey_name'])."</span>";}?>
				   </div>
				</td>
		  </tr>

           <tr>
           <td valign="top" width="20%"><label><?php echo __('from_frequent_journey'); ?></label><span class="star">*</span></td>        
			   <td>
			   <div class="new_input_field" style="width:400px !important;">
		   	  <input type="text" title="<?php echo __('enter_from_frequent_journey'); ?>" name="from_location" id="from_location" class="required" value="<?php if(isset($postvalue) && array_key_exists('from_location',$postvalue)){ echo $postvalue['from_location']; }?>"    />
		      <?php if(isset($errors) && array_key_exists('from_location',$errors)){ echo "<span class='error'>".ucfirst($errors['from_location'])."</span>";}?>
		   </div>
           </td>   	
           </tr>

           <tr>
           <td valign="top" width="20%"><label><?php echo __('to_frequent_journey'); ?></label><span class="star">*</span></td>        
			   <td>
			   <div class="new_input_field" style="width:400px !important;">
		   	  <input type="text" title="<?php echo __('enter_to_frequent_journey'); ?>" name="to_location" id="to_location" class="required notEqual" value="<?php if(isset($postvalue) && array_key_exists('to_location',$postvalue)){ echo $postvalue['to_location']; }?>"    />
		      <?php if(isset($errors) && array_key_exists('to_location',$errors)){ echo "<span class='error'>".ucfirst($errors['to_location'])."</span>";}?>
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
                            <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_addjourney" title="<?php echo __('submit' );?>" /></div>
                            <div class="clr">&nbsp;</div>
                        </td>
                    </tr> 

                </table>
                

        </form>
        </div>
        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt"></div></div>
    </div>
</div>    

