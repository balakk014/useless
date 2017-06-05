<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php 
	$cat_url = $_SERVER['REQUEST_URI'];
	$session = Session::instance();
?>
<aside id="sidebar">
	<ul>
		<li <?php if($cat_url == "/dashboard.html") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."dashboard.html"; ?>" title="<?php echo __("button_home"); ?>"><i class="ico fa_dashboard"></i><?php echo __("button_home"); ?></a></li>
		<li <?php if($cat_url == "/booking.html") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."booking.html"; ?>" title="<?php echo __("booking"); ?>"><i class="ico fa_calendar"></i><?php echo __("booking"); ?></a></li>
		<li <?php if($cat_url == "/completed-trips.html" || $session->get('trip_tab_act') == "completed-trip") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."completed-trips.html"; ?>" title="<?php echo __("completed_trip"); ?>"><i class="ico fa_taxi"></i><?php echo __("completed_trip"); ?></a></li>
		<li <?php if($cat_url == "/cancelled-trips.html" || $session->get('trip_tab_act') == "cancelled-trip") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."cancelled-trips.html"; ?>" title="<?php echo __("cancelledtrip_logs"); ?>"><i class="ico fa_thumbs"></i><?php echo __("cancelledtrip_logs"); ?></a></li>
		<li <?php if($cat_url == "/edit-profile.html") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."edit-profile.html"; ?>" title="<?php echo __("editprofile_label"); ?>"><i class="ico fa_edit"></i><?php echo __("editprofile_label"); ?></a></li>
		<li <?php if($cat_url == "/change-password.html") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."change-password.html"; ?>" title="<?php echo __("menu_change_password"); ?>"><i class="ico fa_key"></i><?php echo __("menu_change_password"); ?></a></li>
		<li <?php if($cat_url == "/payment-option.html" || $session->get('payment_option_tab_act') == "payment-option") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."payment-option.html"; ?>" title="<?php echo __("payment_option"); ?>"><i class="ico fa_credit_card"></i><?php echo __("payment_option"); ?></a></li>
		<li <?php if($cat_url == "/addmoney.html") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."addmoney.html"; ?>" title="<?php echo __("wallet"); ?>"><i class="ico fa_briefcase"></i><?php echo __("wallet"); ?></a></li>
		<li <?php if($cat_url == "/voucher.html") { ?> class="active" <?php } ?>><a href="<?php echo URL_BASE."voucher.html"; ?>" title="<?php echo __("add_coupon_recharge"); ?>"><i class="ico fa_tags"></i><?php echo __("add_coupon_recharge"); ?></a></li>
		<li><a href="<?php echo URL_BASE."users/logout"; ?>" title="<?php echo __("logout_label"); ?>"><i class="ico fa_logout"></i><?php echo __("logout_label"); ?></a></li>
	</ul>
</aside>

