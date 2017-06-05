<?php defined('SYSPATH') OR die('No direct access allowed.');
?>
<!--login start-->

<div class="loginWrapper">
    <div class="loginWrapper_inner">
    <div class="loginLogo">	
   
     <a href="<?php echo URL_BASE; ?>" target = "_blank">
		<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo.png'; ?>" />
	</a>
    </div>   
    <?php if($controller == 'company') { ?>
     <div style="width:200px;margin-left:65px;" align="center">
        <div style="float:left"><a href="<?php echo URL_BASE; ?>company/login" target = "_self">Company Log In</a></div>
        <div style="float:right"><a href="<?php echo URL_BASE; ?>manager/login" target = "_self">Dispatcher Log In</a> </div>
     </div>
	<?php } ?>

	    <div class="widget" style="float: left; margin-top:27px;">
        <div class="title"><h6>
        <?php if($controller == 'company') { echo __('companyad_login'); } elseif($controller == 'manager') { echo __('managerad_login');   } if($controller == 'admin') { echo __('page_login_title'); }
        	?>
        </h6>
        </div>
        <?php
        //For Notice Messages
        //===================
        //echo $message->message;exit;
        $sucessful_message = Message::display();

        if ($sucessful_message) {
            ?>

            <div id="messagedisplay" class="padding_150">
                <div class="notice_message">
            <?php echo $sucessful_message; ?>
                </div>
            </div>
<?php } ?> 
<?php if (isset($error_login)) { ?><span class="login_error"><?php echo $error_login; ?></span><?php } ?>
        <form class="form" method="post" name="frmlogin" id="frmlogin">
            <fieldset>
                <div class="formRow">
                    <label for="login"><?php echo __('email_label'); ?>:</label>
                    <div class="loginInput">
						
						<input type="text" id="email" name="email" value="<?php if (isset($_POST['email'])) {
                           echo $_POST['email'];
                        }?>"  maxlength="50" />
                        
                        
						
<?php if(isset($errors) && array_key_exists('email',$errors)){ echo "<span class='error'>".__('please_provide_email')."</span>";}?>
</div>
                    <div class="clear"></div>
                </div>

                <div class="formRow">
                    <label for="pass"><?php echo __('password_label'); ?></label>
                    <div class="loginInput">
						
						<input type="password" name="password" maxlength="100" />
						
<?php if(isset($errors) && array_key_exists('password',$errors)){ echo "<span class='error'>".ucfirst($errors['password'])."</span>";}?>
</div>
                    <div class="clear"></div>
                </div>

                <div class="loginControl">
                    <div class="rememberMe"><a href="<?php echo URL_BASE; ?>company/forgot_password" class="frgtpsd"><?php echo __('forgot_password'); ?></a></div> 
                    <input type="submit" class="dredB logMeIn" value="Log me in"  name="admin_login" title="<?php echo __('admin_login'); ?>" />
                    <div class="clear"></div>
                </div>
            </fieldset>

        </form>

    </div>
   </div>
 </div>   
    <!--login_end-->

    

