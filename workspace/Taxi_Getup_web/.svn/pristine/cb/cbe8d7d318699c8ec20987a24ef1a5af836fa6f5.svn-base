<?php defined('SYSPATH') OR die('No direct access allowed.');
?>
<!--login start-->
<div class="loginWrapper">
    <div class="loginWrapper_inner">
    <div class="loginLogo">	
	<?php if(COMPANY_CID > 1)
    { ?>
    	<a href="<?php echo URL_BASE; ?>"  title="<?php echo (COMPANY_CID > 1)?ucfirst(COMPANY_APP_NAME):''; ?>" target = "_blank"><img src="<?php echo URL_BASE; ?>public/<?php echo UPLOADS; ?>/site_logo/<?php echo SUBDOMAIN; ?>.png" border="0" title="" /></a>
    <?php
    }else
    { ?>
     <a href="<?php echo URL_BASE; ?>" title="<?php echo (COMPANY_CID > 1)?ucfirst(COMPANY_APP_NAME):''; ?>" target = "_blank"><img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo.png'; ?>" /></a>
	<?php }
    ?>
    </div>   
    <div class="widget" style="float: left; margin-top:27px;">
        <div class="title"><h6><?php echo __('forgot_password'); ?></h6>
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
				<input type="text" name="email" id="email" value="<?php if (isset($_POST['email'])) { echo $_POST['email'];} ?>"  maxlength="50" />
			<?php if(isset($errors) && array_key_exists('email',$errors)){ echo "<span class='error'>".__('please_provide_email')."</span>";}?>
			<div class="clear"></div>
                    </div>
                </div>


                <div class="loginControl">
			<div class="rememberMe"><a href="<?php echo URL_BASE; ?>company/login" title="<?php echo ucfirst(__('login')); ?>" class="frgtpsd"><?php echo ucfirst(__('login')); ?></a></div>
                    <input type="submit" class="dredB logMeIn" value="<?php echo __('button_send'); ?>"  name="submit_forgot_password_admin" title="<?php echo __('button_send'); ?>" />
                    <div class="clear"></div>
                </div>
            </fieldset>

        </form>
	</div>
</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$("#email").focus();	
});  
</script>
