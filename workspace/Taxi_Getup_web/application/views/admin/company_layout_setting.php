<?php
defined('SYSPATH') OR die("No direct access allowed.");
?>

<script type="text/javascript" src="http://192.168.1.49:1013/public/js/jscolor/jscolor.js"></script>
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
            <form method="POST"  class="form" action="" >
                <table class="0" cellpadding="5" cellspacing="0" width="85%">
                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('header_bgcolor'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field"><input type="text" name="header_bg" class="color" title="<?php echo __('header_bgcolor'); ?>" maxlength="50" value="<?php echo isset($layoutsetting) && (!array_key_exists('header_bg', $errors)) ? $layoutsetting[0]['header_bgcolor'] : $validator['menu_bg']; ?>" readonly></div>
                    <?php if(isset($errors) && array_key_exists('header_bg',$errors)){ echo "<span class='error'>".ucfirst($errors['header_bg'])."</span>";}?></td>
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('header_menucolor'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field"><input type="text" name="menu_bg" class="color" title="<?php echo __('header_menucolor'); ?>" maxlength="100" value="<?php echo isset($layoutsetting) && (!array_key_exists('menu_bg', $errors)) ? $layoutsetting[0]['menu_color'] : $validator['menu_bg']; ?>" readonly> </div>
                            <?php if(isset($errors) && array_key_exists('menu_bg',$errors)){ echo "<span class='error'>".ucfirst($errors['menu_bg'])."</span>";}?></td>
                    </tr>

                    <tr>
                        <td valign="top" width="20%"><label><?php echo __('header_movercolor'); ?></label><span class="star">*</span></td>   
                        <td><div class="new_input_field"><input type="text" name="mover_bg" class="color" title="<?php echo __('header_movercolor'); ?>" maxlength="50" value="<?php echo isset($layoutsetting) && (!array_key_exists('mover_bg', $errors)) ? $layoutsetting[0]['mouseover_color'] : $validator['mover_bg']; ?>" readonly></div>
                            <?php if(isset($errors) && array_key_exists('mover_bg',$errors)){ echo "<span class='error'>".ucfirst($errors['mover_bg'])."</span>";}?></td>
                    </tr>

                               
                    <tr>
                        <td valign="top">&nbsp;</td>
                        <td style="padding-left:0px;">
                            <div class="button dredB"> <input type="reset" name="layoutsettings_reset" title="<?php echo __('button_reset'); ?>" value="<?php echo __('button_reset'); ?>"></div>
                            <div class="button greenB">  <input type="submit" name="layoutsettings_submit" title ="<?php echo __('button_update'); ?>" value="<?php echo __('button_update'); ?>"></div>

                        </td></tr>
                </table>

            </form>
            <br/><br/>

        </div>

        <div class="content_bottom"><div class="bot_left"></div><div class="bot_center"></div><div class="bot_rgt" ></div></div>
    </div>

</div>

