<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!-- Top fixed navigation -->
<div class="topNav">    
        <div class="userNav">
            <ul>
                <li><a href="<?php echo URL_BASE; ?>" title=""><i class="icon-reply"></i><span>Main website</span></a></li>
                <li><a href="<?php echo URL_BASE; ?>#contact-us"  title=""><i class="icon-user"></i><span>Contact admin</span></a></li>
                <li><a href="http://www.ndottech.com" target="_blank" title=""><i class="icon-comments"></i><span>Support</span></a></li>
                <li id="login_top_lang_select"></li>
            </ul>
        </div> 
</div>
  <?php  if(($action != 'login' && $action != 'forgot_password') ) { ?>
        <div class="head_in">
        <div class="head_rgt">
	    <?php if ($action != 'login'): ?>
		<p class="fl"> <?php echo __("welcome_label"); ?></p><p class="fl"> <?php echo $_SESSION['name'] . ' | '; ?>  </p>
		<p class="fl"><a href = "<?php echo URL_BASE; ?>admin/edifprofile/<?php echo $adminid; ?>" class='fl'><?php echo __("menu_myinfo") . ' | '; ?></a></p>
		<p class="fl"><a href = "<?php echo URL_BASE; ?>admin/changepassword" class='fl'><?php echo __("menu_change_password") . ' | '; ?></a></p>
		<p class="fl"><a href ="<?php echo URL_BASE; ?>admin/logout" class='fl' title="<?php echo __('logout_label') ?>"> <?php echo __('logout_label') ?></a></p>
	    <?php endif;
	} ?>		         

    </div>	
</div>

