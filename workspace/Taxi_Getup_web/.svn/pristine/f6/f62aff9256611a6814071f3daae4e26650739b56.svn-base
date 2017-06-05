
<?php /*
<!--All in one CSS File [Below mentioned files are combined]-->
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/main_base.css"/>
*/ ?>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/bootstrap.css"/>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/style.css"/>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/simple-sidebar.css"/>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/dist/css/formValidation.css"/>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/bootstrap-datetimepicker.css"/>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/media_style.css"/>
<?php $session = Session::instance(); $companyId = ($session->get('company_id') > 0) ? $session->get('company_id') : 0; ?>
<div class="loader">
	<div class="loader_inner">
		<div class="clearfix" style="margin-bottom: 10px;text-align: center;">
			<?php 
				$company_logo = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_LOGO_IMGPATH.$companyId.'_logo.png';
				if(file_exists($company_logo)) { ?>
					<img src="<?php echo URL_BASE.SITE_LOGO_IMGPATH.$companyId.'_logo.png'; ?>" alt="Logo" style="width:154px;">
			<?php } else { ?>
				<img src="<?php echo URL_BASE.SITE_LOGO_IMGPATH; ?>logo.png" alt="Logo" style="width:154px;">
			<?php } ?>
		</div>
		<div class="clearfix"><img src='<?php echo URL_BASE; ?>public/css/img/ajax-loaders/294.gif'/></div>
	</div>
</div>
<div class="taxi_dispatcher_inner">
    <div class="row top_row"> 
        <div class="col-lg-5">
<div id="wrapper" class="toggled">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a class="sidebar_menu" href="javascript:;">
                        <span class="close_side_bar">&nbsp;</span>
                    </a>
                </li>
			<?php if(isset($_SESSION['vbx_show']) && $_SESSION['vbx_show']==1){?>
			
				 <li>
                    <a href="<?php echo URL_BASE; ?>callcenter/dispatch">Dashboard</a>
                </li>

			<?php }else{?>
				 <li>
                    <a href="<?php echo URL_BASE; ?>taxidispatch/dashboard">Dashboard</a>
                </li>

			<?php } ?>
               
                <li>
                    <a href="<?php echo URL_BASE; ?>taxidispatch/manage_booking"><?php echo __('manage_booking'); ?></a>
                </li>
               <?php /* <li>
                    <a href="<?php echo URL_BASE;?>tdispatch/recurrentbooking/"><?php echo __('recurrent_booking'); ?></a>
                </li>
                <li>
                    <a href="<?php echo URL_BASE;?>tdispatch/frequent_location/"><?php echo __('frequent_location'); ?></a>
                </li>
                <li>
                    <a href="<?php echo URL_BASE;?>tdispatch/frequent_journey/"><?php echo __('frequent_journey'); ?></a>
                </li> */ ?>
                <?php if($_SESSION['user_type'] =='C' || $_SESSION['user_type'] =='A') { ?>
                <li>
                    <a target="_top" href="<?php echo URL_BASE;?>tdispatch/tdispatch_settings/"><?php echo __('tdispatch_setting'); ?></a>
                </li>
                <?php } ?>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-80 head_leftmenu">
                         <?php if(isset($_SESSION['vbx_show']) && $_SESSION['vbx_show']==1){?>
                        <a class="head_dash" title="Dashboard" href="<?php echo URL_BASE; ?>callcenter/dispatch">
                        <span class="show_menu">Dashboard</span>
                        </a>
			<?php }else{?>
                        <a class="head_dash" title="Dashboard" href="<?php echo URL_BASE; ?>taxidispatch/dashboard">
                            <span class="show_menu">Dashboard</span>
                        </a>
			<?php } ?>
                        <a class="head_msgbook" href="<?php echo URL_BASE; ?>taxidispatch/manage_booking">
                            <span class="show_menu">Booking</span>
                            </a>
                        <?php if($_SESSION['user_type'] =='C' || $_SESSION['user_type'] =='A') { ?>
                        <a class="head_setting"  href="<?php echo URL_BASE;?>tdispatch/tdispatch_settings/">
                            <span class="show_menu">Settings</span>
                            </a>
                <?php } ?>
                <input type ="text" name="search_map_location" id="search_map_location">
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
     </div>
        <div class="col-md-6">
            <a class="logo" href="<?php echo URL_BASE; ?>taxidispatch/dashboard" title="logo">
            <?php 
				$company_logo = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_LOGO_IMGPATH.$companyId.'_logo.png';
				if(file_exists($company_logo)) { ?>
					<img src="<?php echo URL_BASE.SITE_LOGO_IMGPATH.$companyId.'_logo.png'; ?>" alt="Logo">
				<?php } else { ?>
					<img src="<?php echo URL_BASE.SITE_LOGO_IMGPATH; ?>logo.png" alt="Logo">
				<?php } ?>
            </a>
        </div>
        <div class="col-lg-5 rgt_menu">            
            <ul>
				<?php if($_SESSION['user_type']=="A" || $_SESSION['user_type']=="DA") { ?>
                                        <li><a class="goto_admin" href="<?php echo URL_BASE; ?>admin/dashboard"  title="Go to"></a></li>
                                        <li><a class="logout" href="<?php echo URL_BASE; ?>admin/logout" title="Logout"></a></li>
				<?php } else if($_SESSION['user_type']=="C") { ?>
					<li><a class="goto_company" href="<?php echo URL_BASE; ?>company/dashboard" title="Go to"></a></li>
					<li><a class="logout" href="<?php echo URL_BASE; ?>company/logout" title="Logout"></a></li>
				<?php }	else if($_SESSION['user_type']=="M") { ?>
					<li><a class="goto_dispatch" href="<?php echo URL_BASE; ?>manager/dashboard" title="Go to"></a></li>
					<li><a class="logout" href="<?php echo URL_BASE; ?>manager/logout" title="Logout"></a></li>
				<?php }  ?>
            </ul>
        </div>
    </div>
</div>
