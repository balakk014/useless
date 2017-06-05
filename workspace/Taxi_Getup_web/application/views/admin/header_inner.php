<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>    
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/script.js"></script> 
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/plugins/bootstrap.min.js"></script>
<?php
if ($_SESSION['user_type'] != 'M' && $_SESSION['user_type'] == 'A') {
	$admin_available_balance = 0;
}

if ($_SESSION['user_type'] != 'M' && $_SESSION['user_type'] == 'C') {
	$get_accountbalance1 = 0;
}
?>
<?php
if ($action != 'login'):
    if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'S') {
	$url = "admin/editprofile/";
    } else if ($_SESSION['user_type'] == 'C') {
	$url = "company/editprofile/";
    } else if ($_SESSION['user_type'] == 'M') {
	$url = "manager/editprofile/";
    }

    if ($_SESSION['user_type'] != 'M' && $_SESSION['user_type'] != 'S') {
	if ($_SESSION['user_type'] == 'C') {
	    $company_currency = findcompany_currency($_SESSION['company_id']);
	} else {
	    $company_currency = CURRENCY;
	}
    }
    ?>

    <?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'S' || $_SESSION['user_type'] == 'DA') { ?>

	<a href="<?php echo URL_BASE; ?>" target = "_blank" title="" class="logo">
	    <span class="logo-mini">
		<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo-small.png'; ?>" alt="" />
	    </span>
	    <span class="logo-lg minus">
		<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo.png'; ?>" alt="" />
	    </span>
	</a>
	<nav class="navbar-static-top">
	    <a class="sidebar-toggle" href="javascript:;"></a>
	    <div class="header_rgt">
		<ul>

		    <li>
			<a class="all_balance"><?php
	if ($_SESSION['user_type'] != 'S' && $_SESSION['user_type'] != 'DA') {
	    echo __('available') . ' ';
	} else {
	    echo "";
	}
	if ($_SESSION['user_type'] != 'M' && $_SESSION['user_type'] == 'A') {
		echo CURRENCY.$admin_available_balance;
	}
	if ($_SESSION['user_type'] != 'M' && $_SESSION['user_type'] == 'C') {
		$company_currency = findcompany_currency($_SESSION['company_id']);
	}
	?></a>
		    </li>
		    <li>
				<a href="<?php echo URL_BASE."company/editprofile/".$_SESSION['userid']; ?>" title="Profile">
					<?php if (file_exists(DOCROOT.COMPANY_IMG_IMGPATH . $_SESSION['userid'].".png")) {
						$user_path_img = URL_BASE.COMPANY_IMG_IMGPATH . $_SESSION['userid'].".png";
					} else {
						$user_path_img = URL_BASE.COMPANY_IMG_IMGPATH."no_image.png";
					} ?>
					<img class="img-circle" alt="profile images" src="<?php echo $user_path_img; ?>" width="33" height="33">
				</a>
		    </li>
		    <li class="rgt_down_1">
			<div class="middle">
			    <a class="user-menu" data-toggle="dropdown">
				<?php
				if ((isset($user_data) && isset($user_data[0]['photo']) ) && $user_data[0]['photo'] != '' && file_exists(DOCROOT . USER_IMGPATH_HEADER_THUMB . $user_data[0]['photo'])) {
				    $user_path_img = URL_BASE . USER_IMGPATH_HEADER_THUMB . $user_data[0]['photo'];
				} else {
				    $user_path_img = IMGPATH . 'icons/userPic.png';
				}
				?>

				<span class="log_user"><?php echo __("administrator"); ?></span>
                                <span class="down_arr minus">&nbsp;</span>
			    </a>
			</div>
			<div class="header_profile_drop_down">			 
				<ul>
				    <li><a href="<?php echo URL_BASE; ?>admin/editprofile/<?php echo $adminid; ?>" title=""><i class="icon-user"></i><?php echo __('profile'); ?></a></li>
				    <?php if (COMPANY_CID > 1) {
					?>
	    			    <li><a href="<?php echo URL_BASE; ?>admin/changepassword/" title=""><i class="icon-cog"></i><?php echo __("menu_change_password"); ?></a></li>
				    <?php } ?>
				    <li><a href="<?php echo URL_BASE; ?>admin/logout/" title=""><i class="icon-remove"></i><?php echo __("logout_label"); ?></a></li>
				</ul>
			  
			</div>
		    </li>			    
		</ul>
	    </div>
	</nav>

    <?php } else if ($_SESSION['user_type'] == 'C') { ?>
	<a href="<?php echo URL_BASE; ?>" target = "_blank" title="" class="logo">
	<?php
		$company_logo = $_SERVER['DOCUMENT_ROOT'] . '/' . SITE_LOGO_IMGPATH . $_SESSION['company_id'] . '_logo.png';
		if (file_exists($company_logo)) { ?>
		<span class="logo-mini">
			<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . $_SESSION['company_id'].'.png'; ?>" alt="" />
		</span>
		<span class="logo-lg minus">
			<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . $_SESSION['company_id']. '_logo.png'; ?>" alt="" />
		</span>
	<?php } else { ?>
		<span class="logo-mini">
			<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo-small.png'; ?>" alt="" />
		</span>
		<span class="logo-lg minus">
			<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo.png'; ?>" alt="" />
		</span>
	<?php } ?>
	</a>
	<nav class="navbar-static-top">
	    <a class="sidebar-toggle" href="javascript:;"></a>
	    
	    <div class="header_rgt">
		<ul>

		    <li>
			<a class="all_balance"><?php
	echo __('available') . ' ';
	if ($_SESSION['user_type'] != 'M' && $_SESSION['user_type'] == 'A') {
		echo CURRENCY.$admin_available_balance;
	}
	if ($_SESSION['user_type'] != 'M' && $_SESSION['user_type'] == 'C') {
	    $company_currency = findcompany_currency($_SESSION['company_id']);
	}
	    ?></a></li>

		    <li>
			<a href="<?php echo URL_BASE."company/editprofile/".$_SESSION['userid']; ?>" title="Profile">
			   <?php if (file_exists(DOCROOT.COMPANY_IMG_IMGPATH . $_SESSION['userid'].".png")) {
					$user_path_img = URL_BASE.COMPANY_IMG_IMGPATH . $_SESSION['userid'].".png";
				} else {
					$user_path_img = URL_BASE.COMPANY_IMG_IMGPATH."no_image.png";
				} ?>
				<img class="img-circle" alt="profile images" src="<?php echo $user_path_img; ?>" width="33" height="33">
			</a>
		    </li>
		    <li class="rgt_down_1">
			<div class="middle">
			    <a class="user-menu" data-toggle="dropdown">
				<?php
				if ((isset($user_data) && isset($user_data[0]['photo']) ) && $user_data[0]['photo'] != '' && file_exists(DOCROOT . USER_IMGPATH_HEADER_THUMB . $user_data[0]['photo'])) {
				    $user_path_img = URL_BASE . USER_IMGPATH_HEADER_THUMB . $user_data[0]['photo'];
				} else {
				    $user_path_img = URL_BASE.COMPANY_IMG_IMGPATH."no_image.png";
				}
				?>

				<span class="log_user"><?php echo 'Welcome' . ' ' . $_SESSION['name']; ?></span>
				<span class="down_arr minus">
				    <span><b class="caret"></b></span>
				</span>
			    </a>
			</div>
			<div class="menu_drop_down_2">
			    <div class="menu_mid">
				<ul class="drop_down_1">
				    <li><a href="<?php echo URL_BASE; ?>company/editprofile/<?php echo $adminid; ?>" title=""><i class="icon-user"></i><?php echo __('profile'); ?></a></li>
				    <?php if (COMPANY_CID > 1) {
					?>
	    			    <li><a href="<?php echo URL_BASE; ?>company/changepassword/" title=""><i class="icon-cog"></i><?php echo __("menu_change_password"); ?></a></li>
				    <?php } ?>
				    <li><a href="<?php echo URL_BASE; ?>company/logout/" title=""><i class="icon-remove"></i><?php echo __("logout_label"); ?></a></li>
				</ul>
			    </div>
			</div>
		    </li>
		</ul>
	    </div>
	</nav>
	    <!-- /fixed top -->
	<?php } else if ($_SESSION['user_type'] == 'M') { ?>	
	    <!-- Fixed top -->
	    
	    
	<a href="<?php echo URL_BASE; ?>" target = "_blank" title="" class="logo">
	    <span class="logo-mini">
		<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo-small.png'; ?>" alt="" />
	    </span>
	    <span class="logo-lg minus">
		<img src="<?php echo URL_BASE . SITE_LOGO_IMGPATH . 'logo.png'; ?>" alt="" />
	    </span>
	</a>
	    
	    
		    
	    <nav class="navbar-static-top">
	    <a class="sidebar-toggle" href="javascript:;"></a>

	    <div class="header_rgt">
		    <ul>
			 <li>
			<a href="<?php echo URL_BASE."company/editprofile/".$_SESSION['userid']; ?>" title="Profile">
			  <?php
				if ((isset($user_data) && isset($user_data[0]['photo']) ) && $user_data[0]['photo'] != '' && file_exists(DOCROOT . USER_IMGPATH_HEADER_THUMB . $user_data[0]['photo'])) {
				    $user_path_img = URL_BASE . USER_IMGPATH_HEADER_THUMB . $user_data[0]['photo'];
				} else {
				    $user_path_img = URL_BASE.COMPANY_IMG_IMGPATH."no_image.png";
				}
				?>

				<img src="<?php echo $user_path_img; ?>" alt="" />
			</a>
		    </li>
			<li class="rgt_down_1">
			<div class="middle">
			    <a class="user-menu" data-toggle="dropdown">
				
				<span class="log_user"><?php echo 'Welcome' . ' ' . $_SESSION['name']; ?></span>
				<span class="down_arr minus">
				    <span><b class="caret"></b></span>
				</span>
			    </a>
			</div>
			<div class="menu_drop_down_2">
			    <div class="menu_mid">
				<ul class="drop_down_1">
				    <li><a href="<?php echo URL_BASE; ?>manager/editprofile/<?php echo $adminid; ?>" title=""><i class="icon-user"></i><?php echo __('profile'); ?></a></li>
				<?php if (COMPANY_CID > 1) {
				    ?>
	    			<li><a href="<?php echo URL_BASE; ?>manager/changepassword/" title=""><i class="icon-cog"></i><?php echo __("menu_change_password"); ?></a></li>
				<?php } ?>
				<li><a href="<?php echo URL_BASE; ?>manager/logout/" title=""><i class="icon-remove"></i><?php echo __("logout_label"); ?></a></li>
				</ul>
			    </div>
			</div>
		    </li>	    
		    
			
		    </ul>
		</div>
	    </nav>
	    <!-- /fixed top -->
	<?php } ?>
    <?php endif; ?>

