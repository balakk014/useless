<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php
$action = Request::initial()->action();
$toggle_menu = "";
$redirection = URL_BASE;
if($action == "passenger_dashboard" || $action == "completed_trips" || $action == "transaction_details" || $action == "cancelled_trips" || $action == "passenger_editprofile" || $action == "change_password" || $action == "payment_option" || $action == "add_money" || $action == "add_card_details" || $action == "update_card_details") {
	$toggle_menu = "menu_icon";
	$redirection = "javascript:;";
}
?>
<header>
	<div class="header_outer">
		<div class="header_inner">
			<a href="<?php echo $redirection; ?>" class="<?php echo $toggle_menu; ?>" id="menu_icon"><i class="fa fa-navicon"></i></a>
			<div class="logo">
				<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo.png'; ?>" alt="" />
			</div>
			<?php //$details = Commonfunction::get_user_wallet_amount(); ?>
			<?php $alert = json_decode(ALERT); ?>
			<div class="header_rgt">
                                <a href="#" title="Menu" class="toggleMenu" style="display:none;">&nbsp;</a> 
				<ul class="hnav">
					<li><p><?php echo  __('language'); ?>: 
						<span>
							<select id="language" name="language">
								<?php foreach($langArr as $lkey=>$lval): ?>
										<option value="<?php echo $lkey; ?>" <?php if($lkey == $language) { ?>selected<?php } ?> ><?php echo $lval; ?></option>
								<?php endforeach; ?>
							</select>
						</span></p></li>
					<li class="ref_code"><i class="ico fa_tags"></i><p><?php echo  __('referral_code'); ?>: <span><?php echo PASSENGER_REFERRALCODE; ?></span></p></li>
					<li class="alert"><i class="fa fa-bell-o"></i>
						<p><?php echo  __('alert'); ?>
							<?php if($alert->count > 0) { ?>
							<a id="header_alert" href="<?php if($action != "passenger_dashboard") { echo URL_BASE."dashboard.html#upcoming-trip"; } else { echo "#"; } ?>" class="view_details" data-condition="2" data-trip="<?php echo $alert->trip_id; ?>" data-id="1-upcomming"><?php echo $alert->count; ?></a>
							<?php } ?>
						</p>
					</li>
					<li class="acc_bln"><i class=""></i><p><?php echo  __('acc_bal'); ?> : <span><?php echo CURRENCY.PASSENGER_WALLET; ?></span></p></li>
					<?php $userid = $global_session->get('id');  if(file_exists(DOCROOT.PASS_IMG_IMGPATH.$userid.".png")) {
						$profile_image = URL_BASE.PASS_IMG_IMGPATH.$userid.".png";
					} else {
						$profile_image = URL_BASE."public/logged_in/images/profile_noimage.png";
					} ?>
					<li class="usr_pro">
						<a href="<?php echo URL_BASE."edit-profile.html"; ?>">
							<img width="38" height="38" alt="user image" src="<?php echo $profile_image; ?>"/>
							<span><?php echo $global_session->get("passenger_name"); ?></span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>
<?php
	$sucessful_message = Message::display();
	if($sucessful_message) { ?>
		<div id="messagedisplay">
			 <div class="alert alert-success">
				<?php /* <div class="success_float_tt">
					<button type="button" class="close close_message" data-dismiss="alert">×</button>
					<label style="float: left; margin: 4px 0 0 6px;"><?php echo $sucessful_message; ?></label>
				</div> */ ?>
				<?php echo $sucessful_message; ?>
			</div>
		</div>
<?php } ?>
<script>
$(document).ready( function () {
	$(".close_message").click( function() {
		$("#messagedisplay").hide();
	});
	
	$(window).load(function() {
		if(window.location.hash) {
			if(window.location.hash == "#upcoming-trip") {
				$('.view_details[data-id=1-upcomming]').addClass('minize_icon');
				$('#upcomming-trip-1').toggle();
			}
		}
	});

	$(".view_details").live('click',function () {
		$(this).toggleClass('minize_icon');
		var condition = $(this).data("condition");
		var id = $(this).data("id");
		id = id.split("-");
		$("#"+id[1]+"-trip-"+id[0]).toggle();
		if(condition == 2) {
			$('.view_details[data-id=1-upcomming]').toggleClass('minize_icon');
			$.ajax({
				url:'<?php echo URL_BASE; ?>users/change_header_alert',
				type:'post',
				dataType:'json',
				data: {trip_id : $(this).data("trip")},
				success: function() {
					
				}
			});
			$("#header_alert").hide();
		}
	});
});
</script>
<script>
var ww = document.body.clientWidth;
$(document).ready(function() {
	$(".hnav li a").each(function() {
		if ($(this).next().length > 0) {
			$(this).addClass("parent");
		}
	});
	
	$(".toggleMenu").click(function(e) {
		e.preventDefault();
		$(this).toggleClass("active");
		$(".hnav").toggle();
	});
	adjustMenu();
	
	$("#language").change(function(){
		var lang = $(this).val();
		$.ajax({
			url:'<?php echo URL_BASE;?>users/setLanguage',
			type:'POST',
			data:"lang="+lang,
			success:function(res)
			{
				location.reload();
			}
		});
	});
})

$(window).bind('resize orientationchange', function() {
	ww = document.body.clientWidth;
	adjustMenu();
});

var adjustMenu = function() {
	if (ww < 1100) {	 
		jQuery(".toggleMenu").css("display", "inline-block");
		if (!jQuery(".toggleMenu").hasClass("active")) {
			jQuery(".hnav").css("display", "none");
		} else {
			jQuery(".nav").show();
		}
		jQuery(".hnav li").unbind('mouseenter mouseleave');
		jQuery(".hnav li a.parent").unbind('click').bind('click', function(e) {
			// must be attached to anchor element to prevent bubbling
			//e.preventDefault();
			jQuery(this).parent("li").toggleClass("hover");
		});
	} 
	else if (ww >= 1101) {
		jQuery(".toggleMenu").css("display", "none");
		jQuery(".hnav").css("display", "inline-block");
		jQuery(".hnav li").removeClass("hover");
		jQuery(".hnav li a").unbind('click');
		jQuery(".hnav li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
		 	// must be attached to li so that mouseleave is not triggered when hover over submenu
		 	jQuery(this).toggleClass('hover');
		});
	}
}

</script>
