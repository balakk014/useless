<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php
$withdrawrequest=$driverwithdraw="";
$home = '';
$driver_home = '';
$manage_company = '';
$manage_setting = '';
$manage_manager = '';
$manage_taxi = '';
$manage_driver = '';
$manage_field = '';
$manage_country = '';
$manage_city = '';
$manage_motor = '';
$manage_model = '';
$manage_ratings = '';
$manage_state = '';
$manage_payment = '';
$manage_contactus = '';
$manage_free_quotes = '';
$manage_assigntaxi = '';
$manage_package = '';
$manage_users = '';
$manage_content = '';
$manage_transaction = '';
$tdispatch = '';
$banner = '';
$fare = '';
$manage_mile = '';
$manage_fundrequest = '';
$live_users = '';
$today_unassigned_taxi = '';
$unassign_driver = '';
$manage_admin = '';
$account_report = '';
$manage_tdispatch = '';
$manage_faq = '';
$moderator = '';
$company_setting = '';
$car_setting = '';
$create_login = '';
$callcenter_settings = '';
$trip_balance_report = "";
$driverwithdraw = "";
$manage_coupon = '';
$manage_passenger_coupon = '';
if ($action == 'company' || $action == 'companysearch' || $action == 'companydetails' || $action == 'manager' || $action == 'managersearch' || $action == 'managerdetails') {
if ($action == 'manager' || $action == 'managersearch' || $action == 'managerdetails') {
$manage_manager = 'active';
} else if ($action == 'company' || $action == 'companysearch' || $action == 'companydetails') {
$manage_company = 'active';
}
$company_setting = 'active';
} else if ($action == 'account_report' || $action == 'account_reports' || $action == 'account_report_list' || $action == 'active_driver_report' || $action == 'calendarwise_report' || $action == 'active_driver_search' || $action == "saas_report") {
$account_report = 'active';
} else if ($action == "moderator") {
$moderator = 'active';
} else if ($action == 'companydetails') {
if ($_SESSION['user_type'] == 'A') {
$manage_company = 'active';
} else if ($_SESSION['user_type'] == 'C') {
$manage_manager = 'active';
} else if ($_SESSION['user_type'] == 'M') {
$manage_taxi = 'active';
}
} else if ($action == 'taxi' || $action == 'today_unassigned_taxi' || $action == 'taxisearch' || $action == 'availabilitytaxi' || $action == 'availabilitytaxisearch' || $action == "taxiinfo" || $action == "taxilogs" || $action == 'driver' || $action == 'unassign_driver' || $action == 'driversearch' || $action == 'availabilitydriver' || $action == 'availabilitydriversearch' || $action == 'driverinfo' || $action == 'driverlogs' || $action == 'motor' || $action == 'motorsearch' || $action == 'model' || $action == 'modelsearch' || $action == 'modelinfo' || $action == 'assigntaxi' || $action == 'assigntaxisearch' || $action == 'mile' || $action == 'walletrequests' || $action == 'add_driver_credits'|| $action == 'drivercreditlogs') {
$car_setting = 'active';
if ($action == 'taxi' || $action == 'today_unassigned_taxi' || $action == 'taxisearch' || $action == 'availabilitytaxi' || $action == 'availabilitytaxisearch' || $action == "taxiinfo" || $action == "taxilogs") {
$manage_taxi = 'active';
} else if ($action == 'driver' || $action == 'unassign_driver' || $action == 'driversearch' || $action == 'availabilitydriver' || $action == 'availabilitydriversearch' || $action == 'driverinfo' || $action == 'driverlogs' || $action == 'walletrequests' || $action == 'add_driver_credits'|| $action == 'drivercreditlogs') {
$manage_driver = 'active';
} else if ($action == 'motor' || $action == 'motorsearch') {
$manage_motor = 'active';
} else if ($action == 'model' || $action == 'modelsearch' || $action == 'modelinfo') {
$manage_model = 'active';
} else if ($action == 'assigntaxi' || $action == 'assigntaxisearch') {
$manage_assigntaxi = 'active';
} else if ($action == 'mile') {
$manage_mile = 'active';
}
} else if ($action == 'manage_site' || $action == 'mail_settings' || $action == 'social_network' || $action == 'layout_setting' || $action == 'sms_settings' || $action == 'payment_gateways' || $action == 'paymentgateway' || $action == 'payment_gateway_module' || $action == 'withdraw_payment_mode' || $action == 'payment_module_setting' || $action == 'module_settings' || $action == 'menu_settings' || $action == 'company_setting' || $action == 'sms_template' || $action == 'contacts' || $action == 'contact_view' || $action == 'contacts_search' || $action == 'free_quotes' || $action == 'free_quotes_view' || $action == 'free_quotes_search' || $action == 'country' || $action == 'countrysearch' || $action == 'city' || $action == 'citysearch' || $action == 'state' || $action == 'statesearch' || $action == 'admin' || $action == 'adminsearch' || $action == 'admin_userinfo' || $action == 'field' || $action == 'fieldsearch' || $action == 'faq' || $action == 'faqsearch') {
if ($action == 'country' || $action == 'countrysearch') {
$manage_country = 'active';
} else if ($action == 'city' || $action == 'citysearch') {
$manage_city = 'active';
} else if ($action == 'state' || $action == 'statesearch') {
$manage_state = 'active';
} else if ($action == 'payment_gateways' || $action == 'paymentgateway') {
$manage_payment = 'active';
} else if ($action == 'field' || $action == 'fieldsearch') {
$manage_field = 'active';
} else if ($action == 'faq' || $action == 'faqsearch') {
$manage_faq = 'active';
} else if ($action == 'admin' || $action == 'adminsearch' || $action == 'admin_userinfo') {
$manage_admin = 'active';
}
$manage_setting = 'active';
} else if ($action == 'package' || $action == 'packagesearch' || $action == 'packagereport' || $action == 'upgradepackage' || $action == 'packageupgrade') {
$manage_package = 'active';
} else if ($action == 'packagereports') {
$manage_package = 'active';
} else if ($action == 'index' || $action == 'history' || $action == 'passengers' || $action == 'live_users' || $action == 'editpassenger' || $action == 'userinfo' || $action == 'passengerinfo' || $action == 'editprofile ' || $action == 'search' || $action == 'passenger_search' || $action == 'edituserprofile' || $action == 'company_passenger_search' || $action == 'passengerspromo' || $action == 'promocode' || $action == 'passengerwalletlogs' || $action == 'passengercreditlogs' || $action == 'add_passenger_credits' ) {
$manage_users = 'active';
} else if ($action == 'ratingcompanies' || $action == 'ratingdrivers' || $action == 'managerating_companyview' || $action == 'managerating_driversview' || $action == 'ratingdriver_search') {
$manage_ratings = 'active';
} else if ($action == 'contacts' || $action == 'contact_view' || $action == 'contacts_search') {
$manage_contactus = 'active';
} else if ($action == 'free_quotes' || $action == 'free_quotes_view' || $action == 'free_quotes_search') {
$manage_free_quotes = 'active';
} else if ($action == 'contents' || $action == 'content_view' || $action == 'content_edit_view' || $action == 'menu') {
$manage_content = 'active';
} else if($action == 'coupon' || $action == 'generated_coupon_list'){
$manage_coupon = 'active';
} else if($action == 'passenger_coupon' || $action == 'passenger_generated_coupon_list'){
$manage_passenger_coupon = 'active';
} 
else if ($action == 'mile') {
$manage_mile = 'active';
} else if ($action == 'admintransaction' || $action == 'admintransaction_list') {
$manage_transaction = 'active';
} else if ($action == 'companytransaction' || $action == 'companytransaction_list') {
$manage_transaction = 'active';
} else if ($action == 'settlement_list') {
$manage_transaction = 'active';
} else if ($action == 'managertransaction' || $action == 'managertransaction_list') {
$manage_transaction = 'active';
} else if ($action == 'banner') {
$banner = 'active';
} else if ($action == 'fare' || $action == 'fareinfo') {
$fare = 'active';
} else if ($action == 'addbooking' || $action == 'managebooking' || $action == 'recurrentbooking' || $action == 'frequent_location' || $action == 'frequent_journey' || $action == 'accounts' || $action == 'tdispatch_settings' || $action == 'edit_recurrent_booking' || $action == 'edit_frequentlocation' || $action == 'add_frequentlocation' || $action == 'add_frequentjourney' || $action == 'edit_frequentjourney' || $action == 'add_accounts' || $action == 'edit_accounts' || $action == 'add_groups' || $action == 'edit_groups' || $action == 'add_users' || $action == 'edit_users') {
$tdispatch = 'active';
} else if ($action == 'fund_request_report' || $action == 'fund_request' || $action == "manage_fund_request" || $action == "manage_fund_request_list") {
$manage_fundrequest = 'active';
} elseif ($action == 'add_executives' || $action == 'add_flows' || $action == "add_devices") {
$callcenter_settings = 'active';
} elseif ($action == 'trip_balance_report') {
$trip_balance_report = 'active';
}elseif($action == 'driverwithdraw') {
	$driverwithdraw='active';
}elseif($action == 'driver_dashboard') {
	$driver_home='active';
}else {
$home = 'active';
}
$url = $_SERVER['REQUEST_URI'];
$split_url = explode('?', $_SERVER['REQUEST_URI']);
$search_url = isset($split_url[0]) ? $split_url[0] : '';

//echo $action;
//echo '<br>';
//echo $url;
?>

<!--<style type="text/css">	
.dispatch_sidebar{width:250px !important;}	
.dispatch_sidebar .navigation > li a{padding:10px 17px 10px 30px;}
.dispatch_sidebar .widget{box-shadow:none; border:none;}
</style>-->

<!-- Sidebar -->

<?php if (($_SESSION['user_type'] != 'S')) { ?>



<div class="tabs_menu_outer" style="display: none;">
<ul class="tabs-nav two-items">
<li><a href="javascript:;" class="sidebar-toggle"><i class="icon-th-list"></i></a></li>


<?php if (isset($_SESSION['vbx_show']) && $_SESSION['vbx_show'] == 1) { ?>
				      <li id=""><a href="<?php echo URL_BASE; ?>callcenter/dispatch" id="dispatch" title=""><span style="color: #656565;padding: 5px 28px;float: left;"><?php echo __('tdispatch'); ?></span><i></i><!--<i class="icon-taxi"></i>--></a></li>
<?php } else { ?>
<li id=""><a href="<?php echo URL_BASE; ?>taxidispatch/dashboard" title=""><span style="color: #656565;padding: 5px 28px;float: left;"><?php echo __('tdispatch'); ?></span><i></i><!--<i class="icon-taxi"></i>--></a></li>
<?php } ?>





</ul>
</div>

<?php } ?>

<div id="sidebar" <?php if ($controller != "tdispatch") { ?>class="test"<?php } else { ?>class="dispatch_sidebar"<?php } ?> >
		        		        			
<?php if ($controller != "tdispatch") { ?>
    <nav class="menu">
<ul class="sidebar-menu">

<!-- Main navigation -->

<?php if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'DA'))/* || $_SESSION['user_type'] =='S' */ { ?>
	<li class="<?php if ($home) { echo $home; } ?>">
	    <a href="<?php echo URL_BASE; ?>admin/dashboard" title="<?php echo __('button_home'); ?>">
		<i class="dashboard">&nbsp;</i>
		<span><?php echo __('button_home'); ?></span>
	    </a>
	</li>
	
	<li class="<?php if ($driver_home) { echo $driver_home; } ?>">
	    <a href="<?php echo URL_BASE; ?>admin/driver_dashboard" title="<?php echo __('Driver Dashboard'); ?>">
		<i class="user_management_icon">&nbsp;</i>
		<span><?php echo __('Driver Dashboard'); ?></span>
	    </a>
	</li>
	<?php /*<li class="<?php if ($withdrawrequest) { echo $withdrawrequest;	} ?>">
		<a href="<?php echo URL_BASE; ?>manage/withdrawrequest" title="">
		    <i class="withdraw_settings_icon"></i>
		    <span><?php echo __('withdraw_request_label'); ?></span>
		</a>
	</li> */ ?>
	
	<li class="<?php if ($trip_balance_report) { echo $trip_balance_report;	} ?>">
		<a href="<?php echo URL_BASE; ?>manage/trip_balance_report" title="">
		    <i class="withdraw_settings_icon"></i>
		    <span><?php echo __('Trip Balance Report'); ?></span>
		</a>
	</li>
<?php } else if ($_SESSION['user_type'] == 'C') { ?>
	    <li class="<?php if ($home) {  echo $home; } ?>">
		<a href="<?php echo URL_BASE; ?>company/dashboard" title="">
			<i class="dashboard">&nbsp;</i>
			<span><?php echo __('button_home'); ?></span>
		</a>
	    </li>
	    
	    <li class="<?php if ($trip_balance_report) { echo $trip_balance_report;	} ?>">
		<a href="<?php echo URL_BASE; ?>manage/trip_balance_report" title="">
		    <i class="withdraw_settings_icon"></i>
		    <span><?php echo __('Trip Balance Report'); ?></span>
		</a>
		</li>
	    
	    <?php /*<li class="treeview <?php if($withdrawrequest || $driverwithdraw) { echo 'active'; } ?>">
			<a href="#" title="" class="expand <?php if($withdrawrequest == 'active' || $driverwithdraw == 'active') { echo 'subOpened';} ?>" <?php if($withdrawrequest == 'active' || $driverwithdraw == 'active') { echo "id='current'"; } ?>>
			    <i class="withdraw_settings_icon"></i>
			    <span><?php echo __('withdraw_request');?></span>
			</a>
			<ul class="treeview-menu menu_drop_down">
				<li>
					<a href="<?php echo URL_BASE;?>manage/withdrawrequest" class="lastmenu <?php if($url == '/manage/withdrawrequest') { echo 'current'; } ?>"  title=""> <?php echo __('company_withdraw');?></a>
				</li>
				<li>
					<a href="<?php echo URL_BASE;?>manage/driverwithdraw" class="lastmenu <?php if($url == '/manage/driverwithdraw' || $action == 'fareinfo' ) { echo 'current'; } ?>" title="<?php echo __('driver_withdraw'); ?>"> <?php echo __('driver_withdraw'); ?></a>
				</li>
			</ul> 
		</li> */ ?>
<?php } else if ($_SESSION['user_type'] == 'M') { ?>	    
	    <li class="<?php if ($home) { echo $home; } ?>">
		<a href="<?php echo URL_BASE; ?>manager/dashboard" title="">
		  <i class="dashboard">&nbsp;</i>
		    <span><?php echo __('button_home'); ?></span>
		</a>
	    </li>	    
<?php } ?>
	    
	     <li id="dispatch_li">
      <a href="<?php echo URL_BASE."taxidispatch/dashboard"; ?>" title="">
	<i class="taxidispatch_icon"></i>
	<span><?php echo __('tdispatch'); ?></span>
      </a>
  </li>
	   
    	
	    
	    
	    <!-- Company Settinf Start --->
<?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'C' || $_SESSION['user_type'] == 'DA') { ?>
		<li class="treeview <?php if ($company_setting) { echo $company_setting;	} ?>">
		    <a href="#" title="" class="expand <?php if ($company_setting == 'active') { echo 'subOpened'; } ?>" <?php if ($company_setting == 'active') { echo "id='current'";	} ?>>
			 <i class="company_settings_icon"></i>
			<span>   <?php echo __('company_settings'); ?></span>
		    </a>
		<ul class="treeview-menu menu_drop_down">
		<?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'DA') { ?>
		<li class="treeview <?php if ($manage_company) {	echo $manage_company;	}?>">
		    <a href="#" title="" class="expand <?php if ($manage_company == 'active') {	echo 'subOpened'; } ?>" <?php if ($manage_company == 'active') { echo "id='current'"; }	?>>
			 <?php echo __('taxicompanies'); ?> 
		    </a>
			<ul class="treeview-menu menu_drop_down">
			<li>
			    <a href="<?php echo URL_BASE; ?>add/company" class="lastmenu <?php if ($url == '/add/company') { echo 'current'; } ?>"  title="">
				<?php echo __('add_company'); ?>
			    </a>
			</li>
			<li>
			    <a href="<?php echo URL_BASE; ?>manage/company" class="lastmenu <?php if ($url == '/manage/company') { echo 'current'; } ?>" title="<?php echo __('manage_company'); ?>">
				<?php echo __('manage_company'); ?>
			    </a>
			</li>
			</ul>
		</li>
		<?php } ?>

		<?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'DA' || $_SESSION['user_type'] == 'C') { ?>
		<li class="treeview <?php if ($manage_manager) { echo $manage_manager;	} ?>">
			<a href="#" title="" class="expand <?php if ($manage_manager == 'active') { echo 'subOpened'; }	?>" <?php if ($manage_manager == 'active') { echo "id='current'"; } ?>>
			    <?php echo __('comapny_dispatchers'); ?> 
			</a>
			<ul class="treeview-menu menu_drop_down">
			    <li>
				<a href="<?php echo URL_BASE; ?>add/manager" class="lastmenu <?php if ($url == '/add/manager') { echo 'current';  } ?>"  title="">
				    <?php echo __('add_manager'); ?>
				</a>
			    </li>
			    <li>
				<a href="<?php echo URL_BASE; ?>manage/manager" class="lastmenu <?php if ($url == '/manage/manager') {  echo 'current';	} ?>" title="<?php echo __('manage_manager'); ?>">
				    <?php echo __('manage_manager'); ?></a>
			    </li>
			</ul>
		</li>
		<?php } ?>
		<?php /* if($_SESSION['user_type'] =='C') { ?>
		<li class="<?php if($manage_package) { echo $manage_package; } ?>"><a href="#" title="" class="expand <?php if($manage_package == 'active') { echo 'subOpened';} ?>" <?php if($manage_package == 'active') { echo "id='current'"; } ?>><i class="icon-briefcase"></i><?php echo __('packages');?></a>
		<ul>
		<li><a href="<?php echo URL_BASE;?>add/upgradepackage" <?php if($action == 'upgradepackage') { echo 'class="current"'; } ?>  title=""><?php echo __('package_upgrade');?></a></li>
		<li><a href="<?php echo URL_BASE;?>manage/packagereports" <?php if($action == 'packagereports') { echo 'class="current"'; } ?> title="<?php echo __('upgrade_reports'); ?>"><?php echo __('upgrade_reports'); ?></a></li>
		</ul>
		</li>
		<?php } */ ?>								
		</ul>
		</li>
<?php } ?>
		


<!-- Company Fare -->
<?php if ($_SESSION['user_type'] == 'C') { ?>
	<li class="treeview <?php if ($fare) { echo $fare; } ?>">
	    <a href="#" title="" class="expand <?php if ($fare == 'active') { echo 'subOpened'; } ?>" <?php if ($fare == 'active') { echo "id='current'"; } ?>>
		  <i class="fare_management_icon"></i> <span><?php echo __('fare_management'); ?></span>
	    </a>
		 <ul class="treeview-menu menu_drop_down">
		    <li>
			<a href="<?php echo URL_BASE; ?>add/fare" class="lastmenu <?php if ($url == '/add/fare') { echo 'current';  } ?>"  title="">
			    <?php echo __('add_fare'); ?> 
			</a>
		    </li>
		    <li>
			<a href="<?php echo URL_BASE; ?>manage/fare" class="lastmenu <?php if ($url == '/manage/fare' || $action == 'fareinfo') { echo 'current';  } ?>" title="<?php echo __('manage_fare'); ?>">
			    <?php echo __('manage_fare'); ?>
			</a>
		    </li>
		</ul>
	</li>
<!-- End -->		
<?php } ?>		            

<!-- Car Settings Start --->
<?php if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'DA') || ($_SESSION['user_type'] == 'C' /*|| $_SESSION['user_type'] == 'M'*/ )) { ?>			           
			<li class="treeview <?php if ($car_setting) { echo $car_setting;	} ?>">
			    <a href="#" title="" class="expand <?php if ($car_setting == 'active') { echo 'subOpened';	}?>" <?php if ($car_setting == 'active') { echo "id='current'";	} ?>>
			  <i class="taxi_setings_icon"></i>	<span> <?php echo __('car_settings'); ?></span>
			    </a>
			<ul class="treeview-menu menu_drop_down">
			<?php if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'DA')) { ?>			           
			<?php /*   <li class="<?php if ($manage_motor) { echo $manage_motor; } ?>"><a href="#" title="" class="expand <?php	if ($manage_motor == 'active') { echo 'subOpened'; } ?>" <?php	if ($manage_motor == 'active') { echo "id='current'"; } ?>>
			     <i class="icon-circle"></i><?php //echo __('motor_management');?></a>
			<ul class="inner_list">
			 <li><a href="<?php echo URL_BASE;?>add/motor" <?php if($url == '/add/motor') { echo 'class="current"'; } ?>  title=""><?php echo __('add_motor_company');?></a></li> 
			<li><a href="<?php //echo URL_BASE;  ?>manage/motor" class="lastmenu <?php //if($url == '/manage/motor') { echo 'current'; }   ?>" title="<?php //echo __('manage_motor_company');   ?>"><?php //echo __('manage_motor_company');   ?></a></li>
			</ul>
			</li> */?>
			<li class="treeview <?php if ($manage_model) { echo $manage_model; } ?>">
			    <a href="#" title="" class="expand <?php if ($manage_model == 'active') { echo 'subOpened';	} ?>" <?php if ($manage_model == 'active') { echo "id='current'"; } ?>>
				<?php echo __('vehicle_profile'); ?>
			    </a>
				    <ul class="treeview-menu menu_drop_down">
					<!-- <li><a href="<?php //echo URL_BASE;?>add/model" <?php //if($url == '/add/model') { echo 'class="current"'; } ?>  title=""><?php //echo __('add_model');?></a></li> -->
					<li>
					    <a href="<?php echo URL_BASE; ?>manage/model" class="lastmenu <?php if ($url == '/manage/model') {  echo 'current'; } ?>" title="<?php echo __('manage_model'); ?>">
						<?php echo __('manage_model'); ?>
					    </a>
					</li>
				    </ul>
			</li>
			<?php /*
			<li class="<?php if($manage_mile) { echo $manage_mile; } ?>"><a href="#" title="" class="expand <?php if($manage_mile == 'active') { echo 'subOpened';} ?>" <?php if($manage_mile == 'active') { echo "id='current'"; } ?>><i class="icon-reorder"></i><?php echo __('manage_mile');?></a>
			<ul>
			<li><a href="<?php echo URL_BASE;?>add/mile" <?php if($url == '/add/mile') { echo 'class="current"'; } ?>  title=""><?php echo __('add_mile');?></a></li>
			<li><a href="<?php echo URL_BASE;?>manage/mile" <?php if($url == '/manage/mile') { echo 'class="current"'; } ?> title="<?php echo __('manage_mile'); ?>"><?php echo __('manage_mile'); ?></a></li>
			</ul>
			</li> */ ?>	            
			<?php } ?>
			<?php if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 'A') || $_SESSION['user_type'] == 'C' /*|| $_SESSION['user_type'] == 'M'*/ || $_SESSION['user_type'] == 'DA') { ?>
			<li class="treeview <?php if ($manage_taxi) { echo $manage_taxi; } ?>">
			    <a href="#" title="" class="expand <?php if ($manage_taxi == 'active') { echo 'subOpened'; } ?>" <?php if ($manage_taxi == 'active') {	echo "id='current'"; }	?>>
			        <?php echo __('taximanagement'); ?> 
			    </a>
				<ul class="treeview-menu menu_drop_down">
					<li>
					    <a href="<?php echo URL_BASE; ?>add/taxi" class="lastmenu <?php if ($url == '/add/taxi') { echo 'current'; } ?>"  title="<?php echo __('add_taxi'); ?>">
						<?php echo __('add_taxi'); ?>
					    </a>
					</li>
					<li>
					    <a href="<?php echo URL_BASE; ?>manage/taxi" class="lastmenu <?php if ($url == '/manage/taxi') { echo 'current';} ?>" title="<?php echo __('manage_taxi'); ?>">
						<?php echo __('manage_taxi'); ?>
					    </a>
					</li>
					<?php /* <li><a href="<?php echo URL_BASE;?>manage/availabilitytaxi" class="lastmenu <?php if($action == 'availabilitytaxi') { echo 'current'; } ?>" title="<?php echo __('manage_availability_taxi'); ?>"><?php echo __('manage_availability_taxi'); ?></a></li> */ ?>
				</ul>
			</li>
			<li class="treeview <?php if ($manage_driver) { echo $manage_driver; } ?>">
			    <a href="#" title="" class="expand <?php if ($manage_driver == 'active') { echo 'subOpened'; } ?>" <?php if ($manage_driver == 'active') { echo "id='current'"; } ?>>
				<?php echo __('drivermanagement'); ?>
			    </a>
				<ul class="treeview-menu menu_drop_down">
				<li>
				    <a href="<?php echo URL_BASE; ?>add/driver" class="lastmenu <?php if ($url == '/add/driver') { echo 'current'; } ?>"  title="">
					<?php echo __('add_driver'); ?>
				    </a>
				</li>
				<li>
				    <a href="<?php echo URL_BASE; ?>manage/driver" class="lastmenu <?php if ($url == '/manage/driver') { echo 'current'; } ?>" title="<?php echo __('manage_driver'); ?>">
				    <?php echo __('manage_driver'); ?>
				    </a>
				</li>
				<?php /* <li><a href="<?php echo URL_BASE;?>manage/availabilitydriver" class="lastmenu <?php if($action == 'availabilitydriver') { echo 'current'; } ?>" title="<?php echo __('manage_availability_driver'); ?>"><?php echo __('manage_availability_driver'); ?></a></li> */ ?>
				<?php //if(isset($_SESSION['user_type']) &&  ($_SESSION['user_type'] =='A') || ($_SESSION['user_type'] =='C')) {  ?>
				<li>
				    <a href="<?php echo URL_BASE; ?>manage/walletrequests" class="lastmenu <?php if ($action == 'walletrequests') { echo 'current';  } ?>" title="<?php echo __('manage_driver_request'); ?>">
					<?php echo __('manage_driver_request'); ?>
				    </a>
				</li>
				
				
				<?php /*<li>
					<a href="<?php echo URL_BASE; ?>manageusers/add_driver_credits" class="lastmenu <?php if ($url == '/manageusers/add_driver_credits') { echo 'current';	}?> " title="<?php echo __('add_credit_debit'); ?>">
					    <?php echo __('add_credit_debit'); ?>
					</a>
				    </li> */ ?>
				    
				    <li>
					<a href="<?php echo URL_BASE; ?>manageusers/drivercreditlogs" class="lastmenu <?php if ($url == '/manageusers/drivercreditlogs') { echo 'current';	}?> " title="<?php echo __('manage_credit_debit'); ?>">
					    <?php echo __('manage_credit_debit'); ?>
					</a>
				    </li>
				<?php /* <li><a href="<?php echo URL_BASE;?>add/sendmessage" class="lastmenu <?php if($action == 'sendmessage') { echo 'current'; } ?>" title="<?php echo __('send_message'); ?>"><?php echo __('send_message'); ?></a></li><li><a href="<?php echo URL_BASE;?>manage/drivermessageslist" class="lastmenu <?php if($action == 'drivermessageslist') { echo 'current'; } ?>" title="<?php echo __('manage_messages'); ?>"><?php echo __('manage_messages'); ?></a></li>
				<?php } ?> */ //}  ?>
				</ul>
			</li>
			
			
			<li class="treeview <?php if ($manage_assigntaxi) { echo $manage_assigntaxi; } ?>">
			    <a href="#" title="" class="expand <?php if ($manage_assigntaxi == 'active') { echo 'subOpened'; } ?>" <?php if ($manage_assigntaxi == 'active') { echo "id='current'"; } ?>>
				<?php echo __('assign_taxi'); ?>
			    </a>
			    <ul class="treeview-menu menu_drop_down">
				    <li>
					<a href="<?php echo URL_BASE; ?>add/assigntaxi" class="lastmenu <?php  if ($url == '/add/assigntaxi') { echo 'current'; } ?>"  title="<?php echo __('assign_taxi'); ?>">
					    <?php echo __('assign_taxi'); ?>
					</a>
				    </li>
				    <li>
					<a href="<?php echo URL_BASE; ?>manage/assigntaxi" class="lastmenu <?php if ($url == '/manage/assigntaxi') { echo 'current'; } ?>" title="<?php echo __('manage_assigned_taxi'); ?>">
					    <?php echo __('manage_assigned_taxi'); ?>
					</a>
				    </li>
			    </ul>
			</li>		
			<?php }// } ?>	            
			</ul>
	<?php } ?>					 
<!-- CAr Setings End --->
<?php /* if($_SESSION['user_type'] =='A') { ?>
<li class="<?php if($manage_package) { echo $manage_package; } ?>"><a href="#" title="" class="expand <?php if($manage_package == 'active') { echo 'subOpened';} ?>" <?php if($manage_package == 'active') { echo "id='current'"; } ?>><i class="icon-briefcase"></i><?php echo __('packages');?></a>
<ul class="inner_list">
<li><a href="<?php echo URL_BASE;?>add/package" class="lastmenu <?php if($url == '/add/package') { echo 'current'; } ?>"  title="<?php echo __('add_package');?>"><?php echo __('add_package');?></a></li>
<li><a href="<?php echo URL_BASE;?>manage/package" class="lastmenu <?php if($url == '/manage/package') { echo 'current'; } ?>" title="<?php echo __('manage_package'); ?>"><?php echo __('manage_package'); ?></a></li>
<li><a href="<?php echo URL_BASE;?>manage/packagereport" class="lastmenu <?php if($action == 'packagereport') { echo 'current'; } ?>" title="<?php echo __('upgrade_reports'); ?>"><?php echo __('upgrade_reports'); ?></a></li>
</ul>
</li>
<?php } */ ?> 
<!-- End -->						
<!-- Manage CMS --->
<!-- Manage Driver Coupons --->
<?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') { ?>
			 <li class="treeview <?php if($manage_coupon) { echo $manage_coupon; } ?>">
			 <a href="#" title="" class="expand <?php if($manage_coupon == 'active') { echo 'subOpened';} ?>" <?php if($manage_coupon == 'active') { echo "id='current'"; } ?>>
			 <i class="manage_cms_icon"></i><span><?php echo __('Driver Coupons');?></span>
			 </a>
				<ul class="treeview-menu menu_drop_down">
					<?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') 
					{ 
						?> 
							<li><a href="<?php echo URL_BASE;?>add/coupon" class="lastmenu <?php if($url == '/add/coupon') { echo 'current'; } ?>"  title=""><?php echo __('generate_coupon');?></a></li>
							<li><a href="<?php echo URL_BASE;?>manage/coupon" class="lastmenu <?php if($url == '/manage/coupon') { echo 'current'; } ?>" title="<?php echo __('manage_coupon'); ?>"><?php echo __('manage_coupon'); ?></a></li>
							<li><a href="<?php echo URL_BASE;?>manage/generated_coupon_list" class="lastmenu <?php if($url == '/manage/generated_coupon_list') { echo 'current'; } ?>" title="<?php echo __('generated_coupon_list'); ?>"><?php echo __('generated_coupon_list'); ?></a></li>
						<?php 
					}  
					?>
				</ul>
			</li>
			<?php } ?>
			
			<?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') { ?>
			 <li class="treeview <?php if($manage_passenger_coupon) { echo $manage_passenger_coupon; } ?>">
			 <a href="#" title="" class="expand <?php if($manage_passenger_coupon == 'active') { echo 'subOpened';} ?>" <?php if($manage_passenger_coupon == 'active') { echo "id='current'"; } ?>>
			 <i class="manage_cms_icon"></i><span><?php echo __('Passenger Coupons');?></span>
			 </a>
				<ul class="treeview-menu menu_drop_down">
					<?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') 
					{ 
						?> 
							<li><a href="<?php echo URL_BASE;?>add/passenger_coupon" class="lastmenu <?php if($url == '/add/passenger_coupon') { echo 'current'; } ?>"  title=""><?php echo __('generate_coupon');?></a></li>
							<li><a href="<?php echo URL_BASE;?>manage/passenger_coupon" class="lastmenu <?php if($url == '/manage/passenger_coupon') { echo 'current'; } ?>" title="<?php echo __('manage_coupon'); ?>"><?php echo __('manage_coupon'); ?></a></li>
							<li><a href="<?php echo URL_BASE;?>manage/passenger_generated_coupon_list" class="lastmenu <?php if($url == '/manage/passenger_generated_coupon_list') { echo 'current'; } ?>" title="<?php echo __('generated_coupon_list'); ?>"><?php echo __('generated_coupon_list'); ?></a></li>
						<?php 
					}  
					?>
				</ul>
			</li>
			<?php } ?>


<?php if ($_SESSION['user_type'] == 'A') { // || $_SESSION['user_type'] =='C'  ?>
		    <li class="treeview <?php if ($manage_content) { echo $manage_content; } ?>">
			<a href="#" title="" class="expand <?php if ($manage_content == 'active') {   echo 'subOpened'; } ?>" <?php if ($manage_content == 'active') { echo "id='current'"; } ?>>
			     <i class="manage_cms_icon"></i>  <span> <?php echo __('manage_cms'); ?></span>
			</a>
			    <ul class="treeview-menu menu_drop_down">
				<?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'S') { ?> 
				<li>
				    <a href="<?php echo URL_BASE; ?>add/menu" class="lastmenu <?php if ($url == '/add/menu') { echo 'current'; } ?>"  title="<?php echo __('add_menu'); ?>">
				    <?php echo __('add_menu'); ?>
				    </a>
				</li>
				<li>   
				    <a href="<?php echo URL_BASE; ?>manage/menu" class="lastmenu <?php if ($url == '/manage/menu') { echo 'current'; } ?>" title="<?php echo __('manage_menu'); ?>"> 
					    <?php echo __('manage_menu'); ?>
				    </a>
				</li>
				<li>
				    <a href="<?php echo URL_BASE; ?>add/contents" class="lastmenu <?php if ($url == '/add/contents') { echo 'current'; } ?>" title="<?php echo __('add_content'); ?>">
					<?php echo __('add_content'); ?>
				    </a>
				</li>
				<li>
				    <a href="<?php echo URL_BASE; ?>manage/contents" class="lastmenu <?php if ($url == '/manage/contents') { echo 'current'; } ?>" title="<?php echo __('manage_content'); ?>">
					<?php echo __('manage_content'); ?>
				    </a>
				</li>
				<?php } else if ($_SESSION['user_type'] == 'C') { ?>
				<li>
				    <a href="<?php echo URL_BASE; ?>manage/company_contents" class="lastmenu <?php if ($action == 'company_contents') {  echo 'current';  } ?>" title="<?php echo __('manage_content'); ?>">
				    <?php echo __('manage_content'); ?>
				    </a>
				</li>
				<?php } ?>
			    </ul>
		    </li>
<?php } ?>
<!-- Manage CMS --->
<!-- User management -->
<?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'DA')/* || $_SESSION['user_type'] =='S' */ { ?>
		    <li class="treeview <?php if ($manage_users) { echo $manage_users; } ?>">
			<a href="#" title="" class="expand <?php if ($manage_users == 'active') { echo 'subOpened'; } ?>" <?php if ($manage_users == 'active') { echo "id='current'"; } ?>>
			  <i class="user_management_icon"></i>    <span><?php echo __('user_management'); ?></span>
			</a>
				<ul class="treeview-menu menu_drop_down">
				    <?php if ($_SESSION['user_type'] != 'DA') { ?>
					<li>
					    <a href="<?php echo URL_BASE; ?>manageusers/index" class="lastmenu <?php if ($url == '/manageusers/index') { echo 'current'; } ?>"  title="<?php echo __('Users List'); ?>">
						<?php echo __('Users List'); ?>
					    </a>
					</li>
				    <?php } ?>
				    <li>
					<a href="<?php echo URL_BASE; ?>manageusers/passengers" class="lastmenu <?php if ($url == '/manageusers/passengers') { echo 'current'; } ?>" title="<?php echo __('menu_manage_passengers'); ?>">
					    <?php echo __('menu_manage_passengers'); ?>
					</a>
				    </li>
				    <?php if (($_SESSION['company_id'] != '37') && ($_SESSION['company_id'] != '194') && ($_SESSION['company_id'] != '162') && ($_SESSION['company_id'] != '122') && $_SESSION['user_type'] != 'DA') { ?>
				    <li>
					<a href="<?php echo URL_BASE; ?>manageusers/passengerspromo" class="lastmenu <?php if ($url == '/manageusers/passengerspromo') { echo 'current'; } ?>" title="<?php echo __('passengerspromo'); ?>">
					    <?php echo __('passengerspromo'); ?>
					</a>
				    </li>
				    <li>
					<a href="<?php echo URL_BASE; ?>manage/promocode" class="lastmenu <?php if ($url == '/manage/promocode') { echo 'current';	}?> " title="<?php echo __('promocode_list'); ?>">
					    <?php echo __('promocode_list'); ?>
					</a>
				    </li>
				    <li>
					<a href="<?php echo URL_BASE; ?>manageusers/passengerwalletlogs" class="lastmenu <?php if ($url == '/manageusers/passengerwalletlogs') { echo 'current';	}?> " title="<?php echo __('promocode_list'); ?>">
					    <?php echo __('manage_passenger_wallet'); ?>
					</a>
				    </li>
				    <?php /*<li>
					<a href="<?php echo URL_BASE; ?>manageusers/add_passenger_credits" class="lastmenu <?php if ($url == '/manageusers/add_passenger_credits') { echo 'current';	}?> " title="<?php echo __('add_credit_debit'); ?>">
					    <?php echo __('add_credit_debit'); ?>
					</a>
				    </li> */ ?>
				    
				    <li>
					<a href="<?php echo URL_BASE; ?>manageusers/passengercreditlogs" class="lastmenu <?php if ($url == '/manageusers/passengercreditlogs') { echo 'current';	}?> " title="<?php echo __('manage_credit_debit'); ?>">
					    <?php echo __('manage_credit_debit'); ?>
					</a>
				    </li>
				    
				    
				    
				    <?php } ?>
				</ul>
		    </li>
<?php } ?> 
<?php if ($_SESSION['user_type'] == 'C')/* || $_SESSION['user_type'] =='S' */ { ?>
		<li class="treeview <?php if ($manage_users) { echo $manage_users; } ?>">
		    <a href="#" title="" class="expand <?php if ($manage_users == 'active') { echo 'subOpened';	} ?>" <?php if ($manage_users == 'active') { echo "id='current'"; } ?>>
			  <i class="user_management_icon"></i> <span>	<?php echo __('user_management'); ?></span>
		    </a>
			<ul class="treeview-menu menu_drop_down">
			<li><a href="<?php echo URL_BASE; ?>company/passengers" class="lastmenu <?php
			if ($action == 'passengers') {
			echo 'current';
			}
			?>"  title=""><?php echo __('menu_manage_passengers'); ?></a></li>
			<?php if ($_SESSION['company_id'] != 37 && $_SESSION['company_id'] != 194 && $_SESSION['company_id'] != 162) { ?>
			<li><a href="<?php echo URL_BASE; ?>manageusers/passengerspromo" class="lastmenu" <?php
			if ($url == '/manageusers/passengerspromo') {
			echo 'class="current"';
			}
			?> title="<?php echo __('passengerspromo'); ?>"> <?php echo __('passengerspromo'); ?> </a></li>
			<li><a href="<?php echo URL_BASE; ?>manage/promocode" class="lastmenu" <?php
			if ($url == '/manage/promocode') {
			echo 'class="current"';
			}
			?> title="<?php echo __('promocode_list'); ?>"> <?php echo __('promocode_list'); ?> </a></li>
			<?php } ?>
			</ul>
		</li>
<?php } ?> 
<!---- End --->
<!-- Rating management -->
<?php if ($_SESSION['user_type'] != 'S' && $_SESSION['user_type'] != 'M' && $_SESSION['user_type'] != 'DA') { ?>

		<li class="treeview <?php if ($manage_ratings) { echo $manage_ratings; }?>">
		    <a href="#" title="" class="expand <?php if ($manage_ratings == 'active') { echo 'subOpened'; } ?>" <?php if ($manage_ratings == 'active') { echo "id='current'"; } ?>>
			  <i class="manage_rating_icon"></i> <span>	<?php echo __('manage_rating'); ?></span>
		    </a>
		<ul class="treeview-menu menu_drop_down">
		    <li>
			<a href="<?php echo URL_BASE; ?>manage/ratingdrivers" class="lastmenu <?php if ($action == 'ratingdrivers') { echo 'current'; }	?>"  title="">
			     <?php echo __('manage_rating_taxi'); ?>
			</a>
		    </li>
		</ul>
		</li>	
  <?php } ?>
<!-- End -->

<!-- Tramactions --->
<?php if ($_SESSION['user_type'] == 'A') { ?>
		<li class="treeview <?php if ($manage_transaction) { echo $manage_transaction; }	?>">
		    <a href="#" title="" class="expand <?php if ($manage_transaction == 'active') { echo 'subOpened'; }	?>" <?php if ($manage_transaction == 'active') { echo "id='current'"; }	?>>
		  <i class="reports_icon"></i>	<span><?php echo __('reports'); ?></span>
		    </a>
		<ul class="treeview-menu menu_drop_down">
		<li><a href="<?php echo URL_BASE; ?>transaction/admintransaction/all/" class="lastmenu <?php
		if ($url == '/transaction/admintransaction/all/' || $search_url == '/transaction/admintransaction_list/all/') {
		echo 'current';
		}
		?>"  title=""><?php echo __('all_transaction_log'); ?></a></li>
		<li><a href="<?php echo URL_BASE; ?>transaction/admintransaction/success/" class="lastmenu <?php
		if ($url == '/transaction/admintransaction/success/' || $search_url == '/transaction/admintransaction_list/success/') {
		echo 'current';
		}
		?>" title="<?php echo __('success_transaction_log'); ?>"><?php echo __('success_transaction_log'); ?></a></li>
		<li><a href="<?php echo URL_BASE; ?>transaction/admintransaction/cancelled/" class="lastmenu <?php
		if ($url == '/transaction/admintransaction/cancelled/' || $search_url == '/transaction/admintransaction_list/cancelled/') {
		echo 'current';
		}
		?>" title="<?php echo __('cancelled_transaction_log'); ?>"><?php echo __('cancelled_transaction_log'); ?></a></li>
		<?php /* <li><a href="<?php echo URL_BASE;?>transaction/admintransaction/upcoming/" <?php if($url == '/transaction/admintransaction/upcoming/' || $search_url == '/transaction/admintransaction_list/upcoming/' ) { echo 'class="current"'; } ?>  title=""><?php echo __('upcoming_transaction_log');?></a></li> */ ?>
		<li><a href="<?php echo URL_BASE; ?>transaction/admintransaction/rejected/" class="lastmenu <?php
		if ($url == '/transaction/admintransaction/rejected/' || $search_url == '/transaction/admintransaction_list/rejected/') {
		echo 'current';
		}
		?>" title="<?php echo __('rejected_trip_log'); ?>"><?php echo __('rejected_trip_log'); ?></a></li>
		<li><a href="<?php echo URL_BASE; ?>transaction/admintransaction/pendingpayment/" class="lastmenu <?php
		if ($url == '/transaction/admintransaction/pendingpayment/' || $search_url == '/transaction/admintransaction_list/pendingpayment/') {
		echo 'current';
		}
		?>" title="<?php echo __('pending_payment_details'); ?>"><?php echo __('pending_payment_details'); ?></a></li>
		<li><a href="<?php echo URL_BASE; ?>transaction/settlement_list" class="lastmenu <?php
		if ($url == '/transaction/settlement_list') {
		echo 'current';
		}
		?>" title="<?php echo __('braintree_settlemant'); ?>"><?php echo __('braintree_settlemant'); ?></a></li>

		</ul>
		</li>
<?php } ?>						 
<?php if ($_SESSION['user_type'] == 'C') { ?>

		<li class="treeview <?php if ($manage_transaction) { echo $manage_transaction;	} ?>">
		    <a href="#" title="" class="expand <?php if ($manage_transaction == 'active') { echo 'subOpened'; }	?>" <?php if ($manage_transaction == 'active') { echo "id='current'";	} ?>>
			  <i class="reports_icon"></i> <span><?php echo __('reports'); ?></span>
		    </a>
			<ul class="treeview-menu menu_drop_down">
			<li><a href="<?php echo URL_BASE; ?>transaction/companytransaction/all/" class="lastmenu <?php
			if ($url == '/transaction/companytransaction/all/' || $search_url == '/transaction/companytransaction_list/all/') {
			echo 'current';
			}
			?>"  title=""> <?php echo __('all_transaction_log'); ?> </a></li>
			<li><a href="<?php echo URL_BASE; ?>transaction/companytransaction/success/" class="lastmenu <?php
			if ($url == '/transaction/companytransaction/success/' || $search_url == '/transaction/companytransaction_list/success/') {
			echo 'current';
			}
			?>" title="<?php echo __('success_transaction_log'); ?>"> <?php echo __('success_transaction_log'); ?> </a></li>
			<li><a href="<?php echo URL_BASE; ?>transaction/companytransaction/cancelled/" class="lastmenu <?php
			if ($url == '/transaction/companytransaction/cancelled/' || $search_url == '/transaction/companytransaction_list/cancelled/') {
			echo 'current';
			}
			?>" title="<?php echo __('cancelled_transaction_log'); ?>"> <?php echo __('cancelled_transaction_log'); ?> </a></li>
			<li><a href="<?php echo URL_BASE; ?>transaction/companytransaction/rejected/" class="lastmenu <?php
			if ($url == '/transaction/companytransaction/rejected/' || $search_url == '/transaction/companytransaction_list/rejected/') {
			echo 'current';
			}
			?>" title="<?php echo __('rejected_trip_log'); ?>"> <?php echo __('rejected_trip_log'); ?> </a></li>
			<li><a href="<?php echo URL_BASE; ?>transaction/settlement_list" class="lastmenu <?php
			if ($url == '/transaction/settlement_list') {
			echo 'current';
			}
			?>" title="<?php echo __('braintree_settlemant'); ?>"><?php echo __('braintree_settlemant'); ?></a></li>
			</ul>
		</li>
<?php } ?>	


<?php /*if ($_SESSION['user_type'] == 'M') { ?>
	    <li class="treeview <?php if ($manage_users) { echo $manage_users; } ?>">
		<a href="#" title="" class="expand <?php if ($manage_users == 'active') { echo 'subOpened'; } ?>" <?php if ($manage_users == 'active') { echo "id='current'"; } ?>>
			  <i class="user_management_icon"></i>
			  <span><?php echo __('user_management'); ?></span>
		</a>
		    <ul class="treeview-menu menu_drop_down">
			    <li>
				<a href="<?php echo URL_BASE; ?>company/passengers" class="lastmenu <?php  if ($url == '/company/passengers') {  echo 'current';   } ?>"  title=""> 
				    <?php echo __('menu_manage_passengers'); ?>
				</a>
			    </li>
		    </ul>
	    </li>
		<li class="treeview <?php if ($manage_transaction) { echo $manage_transaction; } ?>">
		    <a href="#" title="" class="expand <?php if ($manage_transaction == 'active') { echo 'subOpened'; }	?>" <?php if ($manage_transaction == 'active') { echo "id='current'"; } ?>>
			    <i class="reports_icon"></i><span><?php echo __('reports'); ?></span>
		    </a>
			    <ul class="treeview-menu menu_drop_down">
				<li>
				    <a href="<?php echo URL_BASE; ?>transaction/managertransaction/all/" class="lastmenu <?php
				if ($url == '/transaction/managertransaction/all/' || $search_url == '/transaction/managertransaction_list/all/') {
				echo 'current';
				}
				?>"  title=""><?php echo __('all_transaction_log'); ?> </a></li>
				<li><a href="<?php echo URL_BASE; ?>transaction/managertransaction/success/" class="lastmenu <?php
				if ($url == '/transaction/managertransaction/success/' || $search_url == '/transaction/managertransaction_list/success/') {
				echo 'current';
				}
				?>" title="<?php echo __('success_transaction_log'); ?>"> <?php echo __('success_transaction_log'); ?> </a></li>
				<li><a href="<?php echo URL_BASE; ?>transaction/managertransaction/cancelled/" class="lastmenu <?php
				if ($url == '/transaction/managertransaction/cancelled/' || $search_url == '/transaction/managertransaction_list/cancelled/') {
				echo 'current';
				}
				?>" title="<?php echo __('cancelled_transaction_log'); ?>"> <?php echo __('cancelled_transaction_log'); ?> </a></li>
				<li><a href="<?php echo URL_BASE; ?>transaction/managertransaction/rejected/" class="lastmenu <?php
				if ($url == '/transaction/managertransaction/rejected/' || $search_url == '/transaction/managertransaction_list/rejected/') {
				echo 'current';
				}
				?>" title="<?php echo __('rejected_trip_log'); ?>"> <?php echo __('rejected_trip_log'); ?> </a></li>
				<li><a href="<?php echo URL_BASE; ?>transaction/settlement_list" class="lastmenu <?php
				if ($url == '/transaction/settlement_list') {
				echo 'current';
				}
				?>" title="<?php echo __('braintree_settlemant'); ?>"><?php echo __('braintree_settlemant'); ?></a></li>
			    </ul>
		</li>
<?php } */ ?>
<!--- End ----------->
<!-- Account Report -->
<?php if ($_SESSION['user_type'] == 'A')/* || $_SESSION['user_type'] == 'S' */ { ?>
				<li class="treeview <?php if ($account_report) { echo $account_report; }	?>">
				    <a href="#" title="" class="expand <?php if ($account_report == 'active') {	echo 'subOpened'; }?>" <?php if ($account_report == 'active') {	echo "id='current'"; } ?>>
				   <i class="account_report_icon"></i> 	<span><?php echo __('account_report'); ?></span>
				    </a>
					    <ul class="treeview-menu menu_drop_down">
						    <li><a href="<?php echo URL_BASE; ?>admin/account_report/" class="lastmenu <?php
						    if ($action == 'account_report') {
						    echo 'current';
						    }
						    ?>"  title="<?php echo __('account_report'); ?>"> <?php echo __('account_report'); ?> </a></li>
						    <?php /*<li><a href="<?php echo URL_BASE; ?>admin/saas_report/" class="lastmenu <?php
						    if ($action == 'saas_report') {
						    echo 'current';
						    }
						    ?>"  title="<?php echo __('saas_report'); ?>"> <?php echo __('saas_report'); ?> </a></li> */ ?>
						    <!-- <li><a href="<?php echo URL_BASE; ?>admin/active_driver_report/" <?php //if($action == 'active_driver_report') { echo 'class="current"'; }  ?>  title=""><?php //echo __('active_driver_report'); ?></a></li>
						    <li><a href="<?php echo URL_BASE; ?>admin/calendarwise_report/" <?php //if($action == 'calendarwise_report') { echo 'class="current"'; }  ?>  title=""><?php //echo __('calendarwise_report'); ?></a></li> -->

					   </ul>
				<?php /* if(($_SESSION['user_type'] =='M')) { ?>
				<li class="recent_activity" >
				<a href="javascript:;" title="Recent Activity"><i class="icon-reorder"></i>Recent Activity</a>
				<ul id="log_content">
				</ul>
				</li>
				<?php } */ ?>
				</li>
<?php } ?>
<!-- End -->

<!-- Account Report -->
<?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'DA' || $_SESSION['user_type'] == 'S') { ?>
		<?php /* <li class="treeview <?php if ($moderator) { echo $moderator;} ?>">
		    <a href="#" title="" class="expand <?php if ($moderator == 'active') { echo 'subOpened'; } ?>" <?php if ($moderator == 'active') { echo "id='current'"; } ?>>
			   <i class="free_trail_icon"></i>  <span><?php echo __('moderator_login'); ?></span>
		    </a>
			<ul class="treeview-menu menu_drop_down">
			    <li><a href="<?php echo URL_BASE; ?>add/moderator/" class="lastmenu <?php if ($action == 'moderator') { echo 'current'; } ?>"  title="<?php echo __('moderator_login'); ?>"> <?php echo __('moderator_login'); ?> </a></li>
			</ul>
		</li> */ ?>
		
		<li class="treeview <?php if ($create_login) {	echo $create_login; } ?>">
		    <a href="#" title="" class="expand <?php if ($create_login == 'active') {	echo 'subOpened';} ?>" <?php if ($create_login == 'active') { echo "id='current'"; } ?>>
			  <i class="create_login_icon"></i> <span><?php echo __('Create a Login'); ?></span>
		    </a>
			<ul class="treeview-menu menu_drop_down">
			    <li>
				<a href="<?php echo URL_BASE; ?>add/create_login/" class="lastmenu <?php
			    if ($url == '/add/create_login/' || $search_url == '/add/create_login/') {
			    echo 'current';
			    }
			    ?>"  title=""><?php echo __('create_login'); ?>
				</a>
			    </li>
			</ul>
		</li> 
<?php } ?>
<!-- Account Report -->
<?php if ($_SESSION['user_type'] == 'C') { ?>
	<li class="treeview <?php  if ($account_report) {  echo $account_report;  }
	?>"><a href="#" title="" class="expand <?php if ($account_report == 'active') { echo 'subOpened';}?>" <?php if ($account_report == 'active') { echo "id='current'"; } ?>>
		  <i class="account_report_icon"></i> <span><?php echo __('account_report'); ?></span>
	    </a>
	<ul class="treeview-menu menu_drop_down">
	<li><a href="<?php echo URL_BASE; ?>company/account_reports/" class="lastmenu <?php
	if ($action == 'account_reports') {
	echo 'current';
	}
	?>"  title=""> <?php echo __('account_report'); ?> </a></li>		
	</ul>
	</li>
<?php } ?>


<?php if (($_SESSION['user_type'] != 'S') && (TDISPATCH_VIEW == 1) && isset($_SESSION['vbx_show']) && $_SESSION['vbx_show'] == 1) { ?>
    <li class="treeview <?php  if ($callcenter_settings) {   echo $callcenter_settings;    }    ?>">
	<a href="#" title="" class="expand <?php if ($callcenter_settings == 'active') { echo 'subOpened'; } ?>" <?php if ($callcenter_settings == 'active') { echo "id='current'"; } ?>>
	   <i class="call_center_icon"></i>   <span><?php echo __('Call center Settings'); ?></span>
	</a>
	    <ul class="treeview-menu menu_drop_down">
	    <li>
		<a href="<?php echo URL_BASE; ?>setting/manage_executives/" class="lastmenu <?php if ($url == '/manage_executives/' || $search_url == '/setting/manage_executives/') {  echo 'current';}  ?>"  title="">
		    <?php echo __('Manage Executives'); ?>
		</a>
	    </li>
	    <li>
		<a href="<?php echo URL_BASE; ?>setting/add_flows/" class="lastmenu <?php if ($url == '/add_flows/' || $search_url == '/setting/add_flows/') { echo 'current'; } ?>"  title="">
		<?php echo __('add_call_flow'); ?>
		</a>
	    </li>
	    <li>
		<a href="<?php echo URL_BASE; ?>setting/add_devices/" class="lastmenu <?php if ($url == '/add_devices/' || $search_url == '/setting/add_devices/') { echo 'current';  } ?>"  title="">
		    <?php echo __('Add Devices'); ?>
		</a>
	    </li>
	    <li><a href="<?php echo URL_BASE; ?>setting/add_numbers/" class="lastmenu <?php
	    if ($url == '/add_numbers/' || $search_url == '/setting/add_numbers/') {
	    echo 'current';
	    }
	    ?>"  title=""><?php echo __('Add Numbers'); ?></a></li>

	    </ul>
    </li>
<?php } ?>

<!-- End -->	
<?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='C'  ) { ?>
<li class=" treeview <?php if($manage_setting) { echo $manage_setting; } ?>">
<a href="#" title="" class="expand <?php if($manage_setting == 'active') { echo 'subOpened';} ?>" <?php if($manage_setting == 'active') { echo "id='current'"; } ?>>
 <i class="general_settings"></i> 
 <span><?php echo __('general_settings');?></span>
</a>
    <ul class="treeview-menu menu_drop_down">
		<?php if ($_SESSION['user_type'] == 'A') { ?>			
	    	<li><a href="<?php echo URL_BASE; ?>admin/manage_site" class="lastmenu <?php if ($url == '/admin/manage_site') {
		echo 'current';
	    } ?>"  title="<?php echo __('site_settings'); ?>"><?php echo __('site_settings'); ?></a></li>
		
	    	<li><a href="<?php echo URL_BASE; ?>admin/mail_settings" class="lastmenu <?php if ($url == '/admin/mail_settings') {
		echo 'current';
	    } ?>" title="<?php echo __('menu_mail_settings'); ?>"><?php echo __('menu_mail_settings'); ?></a></li>
		
	    	<li><a href="<?php echo URL_BASE; ?>admin/sms_template" class="lastmenu <?php if ($url == '/admin/sms_template') {
		echo 'current';
	    } ?>" title="<?php echo __('sms_template'); ?>"><?php echo __('sms_template'); ?></a></li>
		
	    	<li><a href="<?php echo URL_BASE; ?>admin/social_network" class="lastmenu <?php if ($url == '/admin/social_network') {
		echo 'current';
	    } ?>" title="<?php echo __('social_network_setting'); ?>"><?php echo __('social_network_setting'); ?></a></li>
		
	    	<li><a href="<?php echo URL_BASE; ?>admin/payment_gateway_module" class="lastmenu <?php if ($url == '/admin/payment_gateway_module') {
		echo 'current';
	    } ?>" title="<?php echo __('payment_gateway_setting'); ?>" ><?php echo __('payment_gateway_setting'); ?></a></li>
	    			    
	    	<li><a href="<?php echo URL_BASE; ?>admin/withdraw_payment_mode" class="lastmenu <?php if ($url == '/admin/withdraw_payment_mode') {
		echo 'current';
	    } ?>" title="<?php echo __('withdraw_payment_mode'); ?>"><?php echo __('withdraw_payment_mode'); ?></a></li>
	    			    
	    	<li><a href="<?php echo URL_BASE; ?>admin/payment_gateways" class="lastmenu <?php if ($url == '/admin/payment_gateways') {
		echo 'current';
	    } ?>" title="<?php echo __('payment_gateway_module'); ?>"><?php echo __('payment_gateway_module'); ?></a></li>
	    	
	    <?php if ($_SESSION['user_type'] == 'A' || $_SESSION['user_type'] == 'S' || $_SESSION['user_type'] == 'C') { ?>
			<li><a href="<?php echo URL_BASE; ?>manage/contacts" class="lastmenu <?php if ($url == '/manage/contacts') {
		    echo 'current';
		} ?>"   title=""><?php echo __('manage_contactus'); ?></a></li>
	    <?php } ?>
	    <?php /* if($_SESSION['user_type'] =='A' ){ ?>
	      <li class="<?php if($manage_free_quotes) { ?>current<?php } ?>" id="<?php if($manage_free_quotes) { ?>active<?php } ?>"><a href="<?php echo URL_BASE;?>manage/free_quotes" <?php if($url == '/manage/free_quotes') { echo 'class="current"'; }?>   title=""><?php echo __('manage_free_quotes');?></a></li>
	      <?php } */ ?>
	        
	<?php } ?>
	    
		<!--       General Settings -->
	<?php if (isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 'A') || $_SESSION['user_type'] == 'S') { ?>
	    	<li class="treeview <?php if ($manage_country) {
		echo $manage_country;
	    } ?>">
	    	    <a href="#" title="" class="expand <?php if ($manage_country == 'active') {
		echo 'subOpened';
	    } ?>" <?php if ($manage_country == 'active') {
		echo "id='current'";
	    } ?>>
	    	    <!-- <i class="icon-globe"> --></i><?php echo __('manage_country_list'); ?></a>
	    	    <ul class="treeview-menu menu_drop_down">
	    		<li><a href="<?php echo URL_BASE; ?>add/country" class="lastmenu <?php if ($url == '/add/country') {
		echo 'current';
	    } ?>"  title=""><?php echo __('add_country'); ?></a></li>
	    		<li><a href="<?php echo URL_BASE; ?>manage/country" class="lastmenu <?php if ($url == '/manage/country') {
		echo 'current';
	    } ?>" title="<?php echo __('manage_country'); ?>"><?php echo __('manage_country'); ?></a></li>
	    	    </ul>
	        
	    	</li>
	    	<li class="treeview <?php if ($manage_state) {
		echo $manage_state;
	    } ?>"><a href="#" title="" class="expand <?php if ($manage_state == 'active') {
		echo 'subOpened';
	    } ?>" <?php if ($manage_state == 'active') {
		echo "id='current'";
	    } ?>>
	    	<!-- <i class="icon-bar-chart"></i>--> <?php echo __('manage_state_list'); ?> </a>
	    	    <ul class="treeview-menu menu_drop_down">
	    		<li><a href="<?php echo URL_BASE; ?>add/state" class="lastmenu <?php if ($url == '/add/state') {
		echo 'current';
	    } ?>"  title=""><?php echo __('add_state'); ?></a></li>
	    		<li><a href="<?php echo URL_BASE; ?>manage/state" class="lastmenu <?php if ($url == '/manage/state') {
		echo 'current';
	    } ?>" title="<?php echo __('manage_state'); ?>"> <?php echo __('manage_state'); ?> </a></li>
	    	    </ul>
	    	</li>
	    	<li class="treeview <?php if ($manage_city) {
		echo $manage_city;
	    } ?>">
	    	    <a href="#" title="" class="expand <?php if ($manage_city == 'active') {
		echo 'subOpened';
	    } ?>" <?php if ($manage_city == 'active') {
		echo "id='current'";
	    } ?>>
	    	    <!-- <i class="icon-building"></i> --> <?php echo __('manage_city_list'); ?> </a>
	    	    <ul class="treeview-menu menu_drop_down">
	    		<li><a href="<?php echo URL_BASE; ?>add/city" class="lastmenu <?php if ($url == '/add/city') {
		echo 'current';
	    } ?>"  title=""><?php echo __('add_city'); ?></a></li>
	    		<li><a href="<?php echo URL_BASE; ?>manage/city" class="lastmenu <?php if ($url == '/manage/city') {
		echo 'current';
	    } ?>" title="<?php echo __('manage_city'); ?>"> <?php echo __('manage_city'); ?> </a></li>
	    	    </ul>
	    	</li>
	    <?php /* if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') { ?>
	      <li class="<?php if($manage_field) { echo $manage_field; } ?>"><a href="#" title="" class="expand <?php if($manage_field == 'active') { echo 'subOpened';} ?>" <?php if($manage_field == 'active') { echo "id='current'"; } ?>>
	      <!-- <i class="icon-field"></i> --><?php echo __('field_management');?></a>
	      <ul>
	      <li><a href="<?php echo URL_BASE;?>add/field" <?php if($url == '/add/field') { echo 'class="current"'; } ?>  title=""><?php echo __('add_field');?></a></li>
	      <li><a href="<?php echo URL_BASE;?>manage/field" <?php if($url == '/manage/field') { echo 'class="current"'; } ?> title="<?php echo __('manage_field'); ?>"><?php echo __('manage_field'); ?></a></li>
	      </ul>
	      </li>
	      <?php } * ?>									
	    	<li class="treeview <?php if ($manage_faq) {
		echo $manage_faq;
	    } ?>"><a href="#" title="" class="expand <?php if ($manage_faq == 'active') {
		echo 'subOpened';
	    } ?>" <?php if ($manage_faq == 'active') {
		echo "id='current'";
	    } ?>>
	    	<!-- <i class="icon-field"></i>--> <?php echo __('faq_management'); ?> </a>
	    	    <ul class="treeview-menu menu_drop_down">
	    		<li><a href="<?php echo URL_BASE; ?>add/faq" class="lastmenu <?php if ($url == '/add/faq') {
		echo 'current';
	    } ?>"  title="<?php echo __('add_faq'); ?>"><?php echo __('add_faq'); ?></a></li>
	    		<li><a href="<?php echo URL_BASE; ?>manage/faq" class="lastmenu <?php if ($url == '/manage/faq') {
		echo 'current';
	    } ?>" title="<?php echo __('manage_faq'); ?>"><?php echo __('manage_faq'); ?></a></li>
	    	    </ul>
	    	</li>		
	    <?php if ($_SESSION['user_type'] != 'S') { ?>
			<li class="treeview <?php if ($manage_admin) {
		    echo $manage_admin;
		} ?>"><a href="#" title="" class="expand <?php if ($manage_admin == 'active') {
		    echo 'subOpened';
		} ?>" <?php if ($manage_admin == 'active') {
		    echo "id='current'";
		} ?>>
			<!-- <i class="icon-user"></i>--> <?php echo __('superadmin_management'); ?> </a>
					<ul class="treeview-menu menu_drop_down">
				<li><a href="<?php echo URL_BASE; ?>add/admin" class="lastmenu <?php if ($url == '/add/admin') {
		    echo 'current';
		} ?>"  title=""><?php echo __('add_superadmin'); ?></a></li>
				<li><a href="<?php echo URL_BASE; ?>manage/admin" class="lastmenu <?php if ($url == '/manage/admin') {
		    echo 'current';
		} ?>" title="<?php echo __('manage_superadmin'); ?>"><?php echo __('manage_superadmin'); ?></a></li>
			    </ul>
			</li>
	    <?php } */ ?>														
	<?php } else { ?>
	    	<li><a href="<?php echo URL_BASE; ?>company/company_setting" class="lastmenu <?php if ($url == '/company/company_setting') {
		echo 'current';
	    } ?>" title="<?php echo __('company_setting'); ?>"><?php echo __('company_setting'); ?></a></li>
	    <?php /* <li><a href="<?php echo URL_BASE;?>company/layout_setting" class="lastmenu <?php if($url == '/company/layout_setting') { echo 'current'; } ?>" title="<?php echo __('layout_setting'); ?>"><span><?php echo __('layout_setting'); ?></span></a></li>
	      <li><a href="<?php echo URL_BASE;?>company/social_network" class="lastmenu <?php if($url == '/company/social_network') { echo 'current'; } ?>" title="<?php echo __('social_network_setting'); ?>"><span><?php echo __('social_network_setting'); ?></span></a></li> */ ?>		
	    <?php /* if(isset($_SESSION['user_type']) &&  ($_SESSION['user_type'] =='C') ) { ?>
	      <li><a href="<?php echo URL_BASE;?>company/payment_gateway_module" class="lastmenu <?php if($url == '/company/payment_gateway_module' || $manage_payment=='active') { echo 'current'; } ?>" title="<?php echo __('payment_gateway_setting'); ?>"><span><?php echo __('payment_gateway_setting'); ?></span></a></li>
	      <li><a href="<?php echo URL_BASE;?>company/payment_module_setting" class="lastmenu <?php if($url == '/company/payment_module_setting') { echo 'current'; } ?>" title="<?php echo __('payment_gateway_module'); ?>"><span><?php echo __('payment_gateway_module'); ?></span></a></li>
	      <?php } */ ?>
	    	<!--<li><a href="<?php echo URL_BASE; ?>company/sms_settings" <?php //if($url == '/company/sms_settings') { echo 'class="current"'; }  ?> title="<?php //echo __('sms_settings');  ?>"><?php //echo __('sms_settings');  ?></a></li>-->
	<?php } ?>
		<!-- Company Banner Settings --->
	<?php /* if($_SESSION['user_type'] =='C') {  ?>
	  <li class="<?php if($banner) { echo $banner; } ?>"><a href="#" title="" class="expand <?php if($banner == 'active') { echo 'subOpened';} ?>" <?php if($banner == 'active') { echo "id='current'"; } ?>><i class="icon-picture"></i><span><?php echo __('banner_mgmt');?></span></a>
	  <ul class="inner_list">
	  <!--<li><a href="<?php echo URL_BASE;?>add/banner" <?php if($url == '/add/banner') { echo 'class="current"'; } ?>  title=""><?php echo __('add_banner');?></a></li>-->
	  <li><a href="<?php echo URL_BASE;?>manage/banner" class="lastmenu <?php if($url == '/manage/banner') { echo 'current'; } ?>" title="<?php echo __('manage_banner'); ?>"><?php echo __('manage_banner'); ?></a></li>
	  </ul>
	  </li>
	  <?php } */ ?>		
		<!-- End -->				 
	    </ul>
</li>			
<!-- General End --->	
<?php } ?>
</ul>




<?php /* if($_SESSION['user_type'] =='A'){ ?>      	
<li <?php if($manage_fundrequest) { echo $manage_fundrequest; } ?>>
<div class="menu_lft"></div>
<a href="#" title="" class="expand <?php if($manage_fundrequest == 'active') { echo 'subOpened';} ?>" <?php if($manage_fundrequest == 'active') { echo "id='current'"; } ?>><i class="icon-money"></i><?php echo __('fund_report');?></a>

<ul class="toggleul_27">
<li>
<div class="menu_lft1"></div>
<a href="<?php echo URL_BASE;?>admin/manage_fund_request/all" class="menu_rgt1" title="<?php echo __('transaction_fr_all');?>">
<span class="pl15"><?php echo __('transaction_fr_all');?></span>
</a>
</li>

<li>
<div class="menu_lft1"></div>
<a href="<?php echo URL_BASE;?>admin/manage_fund_request/approved" class="menu_rgt1" title="<?php echo __('transaction_fr_approved');?>">

<span class="pl15"><?php echo __('transaction_fr_approved');?></span>
</a>
</li>

<li>
<div class="menu_lft1"></div>
<a href="<?php echo URL_BASE;?>admin/manage_fund_request/rejected" class="menu_rgt1" title="<?php echo __('transaction_fr_rejected');?>">

<span class="pl15"><?php echo __('transaction_fr_rejected');?></span>
</a>
</li>

<li>
<div class="menu_lft1"></div>
<a href="<?php echo URL_BASE;?>admin/manage_fund_request/success" class="menu_rgt1" title="<?php echo __('transaction_fr_success');?>">

<span class="pl15"><?php echo __('transaction_fr_success');?></span>
</a>
</li>

<li>
<div class="menu_lft1"></div>
<a href="<?php echo URL_BASE;?>admin/manage_fund_request/failed" class="menu_rgt1" title="<?php echo __('transaction_fr_failed');?>">

<span class="pl15"><?php echo __('transaction_fr_failed');?></span>
</a>
</li>

<li>
<div class="menu_lft1"></div>
<a href="<?php echo URL_BASE;?>admin/manage_fund_request/pending" class="menu_rgt1" title="<?php echo __('newfundreq');?>">

<span class="pl15"><?php echo __('newfundreq');?></span>
</a>
</li>

</ul>
</li>


<?php } */ ?> 

<?php /* if($_SESSION['user_type'] =='C'){ ?>        
<li class="<?php if($manage_fundrequest) { echo $manage_fundrequest; } ?>">
<div class="menu_lft"></div>
<a href="#" title="" class="expand <?php if($manage_fundrequest == 'active') { echo 'subOpened';} ?>" <?php if($manage_fundrequest == 'active') { echo "id='current'"; } ?>><i class="icon-money"></i><?php echo __('transaction_fr');?></a>

<ul>
<li>
<div class="menu_lft1"></div>
<a href="<?php echo URL_BASE;?>company/fund_request" class="menu_rgt1" title="<?php echo __('transaction_withdraw');?>">
<span class="pl15"><?php echo __('transaction_withdraw');?></span>
</a>
</li>

<li>
<div class="menu_lft1"></div>
<a href="<?php echo URL_BASE;?>company/fund_request_report" class="menu_rgt1" title="<?php echo __('reqlist');?>">

<span class="pl15"><?php echo __('reqlist');?></span>
</a>
</li>

</ul>
</li>


<?php } */ ?> 				            

<!-- /main navigation -->

</div>
<?php } else { ?>
<div id="stuff">
    <nav class="menu">
<ul class="sidebar-menu">
<?php if (($_SESSION['user_type'] == 'A') || ($_SESSION['user_type'] == 'C') || ($_SESSION['user_type'] == 'M')) { ?>
<?php /*
<li class="<?php if($tdispatch) { echo $tdispatch; } ?>"><a href="#" title="" class="expand subOpened" id='current'"<?php //if($tdispatch == 'active') { echo 'subOpened';} ?>" <?php //if($tdispatch == 'active') { echo "id='current'"; } ?>><i class="icon-reorder"></i><?php echo __('tdispatch');?></a>
*/ ?>
<li>
<!--ul-->
<!-- <li class="menu_1"><a href="<?php //echo URL_BASE; ?>tdispatch/addbooking/#stuff" <?php //if($action == 'addbooking') { echo 'class="current"'; }  ?>  title=""><?php //echo __('add_booking'); ?></a></li>								-->
<?php if ($controller == "tdispatch") { ?>
<li class="menu_1" id="add_booking_popup_li">
    <a href="<?php echo URL_BASE; ?>taxidispatch/dashboard" title="<?php echo __('add_booking'); ?>">
        <i class="add_booking_icon"></i>
        <span><?php echo __('add_booking'); ?></span> 
    </a>
</li>
<?php } ?>
<li class="menu_2">
    <a href="<?php echo URL_BASE; ?>taxidispatch/manage_booking" <?php if ($action == 'managebooking') { echo 'class="current"'; } ?> title="<?php echo __('manage_booking'); ?>">
        <i class="manage_booking_icon"></i>
        <span><?php echo __('manage_booking'); ?></span>
    </a>
</li>
<?php /* <li class="menu_3"><a href="<?php echo URL_BASE;?>tdispatch/recurrentbooking/#stuff" <?php if($action == 'recurrentbooking') { echo 'class="current"'; } ?> title="<?php echo __('recurrent_booking'); ?>"><?php echo __('recurrent_booking'); ?></a></li>
<li class="menu_4"><a href="<?php echo URL_BASE;?>tdispatch/frequent_location/#stuff" <?php if($action == 'frequent_location') { echo 'class="current"'; } ?> title="<?php echo __('frequent_location'); ?>"><?php echo __('frequent_location'); ?></a></li>
<li class="menu_5"><a href="<?php echo URL_BASE;?>tdispatch/frequent_journey/#stuff" <?php if($action == 'frequent_journey') { echo 'class="current"'; } ?> title="<?php echo __('frequent_journey'); ?>"><?php echo __('frequent_journey'); ?></a></li>
<!-- <li><a href="<?php echo URL_BASE;?>tdispatch/accounts/#stuff" <?php if($action == 'accounts') { echo 'class="current"'; } ?> title="<?php echo __('accounts'); ?>"><?php echo __('accounts'); ?></a></li> --> */ ?>
<?php if (($_SESSION['user_type'] == 'A') || ($_SESSION['user_type'] == 'C')) { ?>
<li class="menu_6"><a href="<?php echo URL_BASE; ?>tdispatch/tdispatch_settings" <?php if ($action == 'tdispatch_settings') { echo 'class="current"';
} ?> title="<?php echo __('tdispatch_setting'); ?>"><i class="general_settings"></i><span><?php echo __('tdispatch_setting'); ?></span></a></li>
<?php } ?>
<!--/ul-->

<?php /* if(($_SESSION['user_type'] =='M')) { ?>
<li class="recent_activity" >
<a href="javascript:;" title="Recent Activity">Recent Activity</a>
<ul id="log_content">
</ul>
</li>
<?php } */ ?>
</li>


<?php } ?>

</ul>
    </nav>
</div>



<?php /* <div style="navigation widget">
<div class="stuff_inner">
<?php if(($_SESSION['user_type'] =='M') || ($_SESSION['user_type'] =='C') && ($controller == 'tdispatch')) {
$r_class="class_fixed";
?>
<ul>
<li class="recent_activity <?php echo $r_class; ?>" >
<a href="javascript:;" title="<?php echo __('recent_activity'); ?>"><?php echo __('recent_activity'); ?></a>
</li>
<li id="description">
<ul id="log_content"><!--LOG CONTENT APPEND HERE--></ul>
</li>
</ul>
<?php } ?>
</div>
</div> */ ?>
<?php } ?>

<!-- /sidebar -->
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/plugins/jquery.easytabs.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE; ?>public/js/plugins/jquery.collapsible.min.js"></script>
<script src="<?php echo URL_BASE; ?>public/js/plugins/jquery.mousewheel.js"></script>
<script src="<?php echo URL_BASE; ?>public/js/plugins/perfect-scrollbar.js"></script>

<script type="text/javascript">
$(document).ready(function ($) {
$('#description').perfectScrollbar({
wheelSpeed: 20,
wheelPropagation: false
}); 


});
</script>

<script type="text/javascript">
//===== Collapsible plugin for main nav =====//

//$('.expand').collapsible({
//defaultOpen: 'current,third',
//cookieName: 'navAct',
//cssOpen: 'subOpened',
//cssClose: 'subClosed',
//speed: 200
//}); 

</script>
