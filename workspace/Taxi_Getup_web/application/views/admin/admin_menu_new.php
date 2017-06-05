<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?php

$home = '';
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
$company_setting = '';
$car_setting = '';

if($action == 'company' || $action == 'companysearch' || $action =='companydetails' || $action == 'manager' || $action == 'managersearch' || $action =='managerdetails' || $controller =='admin_manager' || $controller =='admin_company')
{
        if(($action == 'add' || $action == 'search' || $action == 'manage' || $action == 'edit' || $action =='info')&&($controller =='admin_manager'))
		{
				$manage_manager = 'active';
		}
		else if(($action == 'add' || $action == 'search'  || $action == 'manage' || $action == 'edit' || $action =='info')&&($controller =='admin_company'))
		{
				$manage_company = 'active';
		}
	   $company_setting = 'active';
}
else if($action == 'account_report' || $action == 'account_report_list' || $action == 'active_driver_report' || $action == 'calendarwise_report' || $action == 'active_driver_search' )
{
	$account_report = 'active';
}
else if($controller =='companydetails')
{
   	if($_SESSION['user_type'] == 'A')
   	{
        	$manage_company = 'active';
        }
        else if($_SESSION['user_type'] == 'C')
        {
        	$manage_manager = 'active';
        	
        }
        else if($_SESSION['user_type'] == 'M')
        {
        	$manage_taxi = 'active';
        }
}
else if($action == 'taxi' || $action =='today_unassigned_taxi' || $action == 'taxisearch' || $action == 'availabilitytaxi' ||  $action == 'availabilitytaxisearch' || $action =="taxiinfo" || $action =="taxilogs" || $action == 'driver' || $action == 'unassign_driver' || $action == 'driversearch' || $action == 'availabilitydriver' || $action == 'availabilitydriversearch' || $action =='driverinfo' || $action =='driverlogs' ||$action == 'motor' || $action == 'motorsearch'|| $action == 'model' || $action == 'modelsearch' || $action == 'modelinfo' || $action == 'assigntaxi' || $action == 'assigntaxisearch' || $action == 'mile' || $controller=='admin_driver' || $controller == 'admin_taxi'|| $controller == 'admin_assigntaxi')
{
        $car_setting = 'active';
        if(($action == 'add' || $action == 'edit' ||$action == 'search' ||$action == 'info' ||$action == 'manage' || $action == 'availabilitytaxisearch' || $action == 'availabilitytaxi'  || $action =="taxiinfo" || $action =="taxilogs")&& ($controller == 'admin_taxi'))
        {
			$manage_taxi = 'active';
		}
		//else if($action == 'driver' || $action == 'unassign_driver' || $action == 'driversearch' || $action == 'availabilitydriver' || $action == 'availabilitydriversearch' || $action =='driverinfo' || $action =='driverlogs')
		else if(($action == 'add' || $action == 'edit' ||$action == 'search' ||$action == 'info' ||$action == 'manage' || $action == 'availabilitydriversearch' || $action == 'availabilitydriver' ||$action == 'driver_completed_logs')&&($controller == 'admin_driver'))
		{
			$manage_driver = 'active';
		}
		else if($action == 'motor' || $action == 'motorsearch')
		{
			$manage_motor = 'active';
		}
		else if($action == 'model' || $action == 'modelsearch' || $action == 'modelinfo')
		{
			$manage_model = 'active';
		}
		else if(($action == 'add' || $action == 'edit' || $action == 'search' || $action == 'info' ||$action == 'manage')&&($controller == 'admin_assigntaxi'))
		{
			$manage_assigntaxi = 'active';
		}
		else if($action == 'mile')
		{
		$manage_mile = 'active';
		}
}

else if($action == 'manage_site' || $action == 'mail_settings' || $action == 'social_network'|| $action == 'sms_settings' || $action == 'payment_gateways' || $action == 'module_settings' || $action == 'menu_settings'|| $action == 'company_setting' || $action == 'sms_template' || $action == 'contacts' || $action == 'contact_view' || $action == 'contacts_search' || $action == 'free_quotes' || $action == 'free_quotes_view' || $action == 'free_quotes_search'  || $action == 'country' || $action == 'countrysearch' || $action == 'city' || $action == 'citysearch' || $action == 'state' || $action == 'statesearch' || $action == 'admin' || $action == 'adminsearch' || $action =='admin_userinfo' || $action == 'field' || $action == 'fieldsearch' || $action == 'faq' || $action == 'faqsearch' || $controller == 'admin_moderator')
{
	if($action == 'country' || $action == 'countrysearch')
	{
         $manage_country = 'active';
	}
	else if($action == 'city' || $action == 'citysearch')
	{
			$manage_city = 'active';
	}
	else if($action == 'state' || $action == 'statesearch')
	{
			$manage_state = 'active';
	}	
	else if($action == 'field' || $action == 'fieldsearch')
	{
			$manage_field = 'active';
	}
	else if($action == 'faq' || $action == 'faqsearch')
	{
			$manage_faq = 'active';
	}
	else if(($action == 'add' || $action == 'edit' || $action == 'search' || $action == 'manage' || $action =='info')&&($controller == 'admin_moderator'))
	{
			$manage_admin = 'active';
	}
 	$manage_setting = 'active';
}


else if($action == 'package' || $action == 'packagesearch' || $action =='packagereport' || $action =='upgradepackage' || $action == 'packageupgrade')
{
        $manage_package = 'active';
}
else if($action == 'packagereports')
{
        $manage_package = 'active';
}
else if($action == 'index' || $action == 'history' || $action == 'passengers' || $action == 'live_users' || $action == 'editpassenger' || $action == 'userinfo' || $action == 'passengerinfo' ||  $action == 'editprofile ' || $action == 'search' || $action == 'passenger_search' || $action =='edituserprofile' || $action == 'company_passenger_search')
{
        $manage_users = 'active';
}
else if($action == 'ratingcompanies' || $action == 'ratingdrivers' || $action == 'managerating_companyview' || $action == 'managerating_driversview' || $action == 'ratingdriver_search')
{
        $manage_ratings = 'active';
}
else if($action == 'contacts' || $action == 'contact_view' || $action =='contacts_search')
{
         $manage_contactus = 'active';
}
else if($action == 'free_quotes' || $action == 'free_quotes_view' || $action == 'free_quotes_search')
{
         $manage_free_quotes = 'active';
}
else if($action == 'contents' || $action == 'content_view' || $action == 'content_edit_view' || $action =='menu')
{
        $manage_content = 'active';
}
else if($action == 'mile')
{
	$manage_mile = 'active';
}
else if($action == 'admintransaction' || $action == 'admintransaction_list')
{
	$manage_transaction = 'active';
}
else if($action == 'companytransaction' || $action == 'companytransaction_list')
{
	$manage_transaction = 'active';
}
else if($action == 'managertransaction' || $action == 'managertransaction_list')
{
	$manage_transaction = 'active';
}
else if($action == 'banner')
{
	$banner = 'active';
}
else if($action == 'fare' || $action == 'fareinfo')
{
	$fare = 'active';
}
else if($action == 'addbooking' || $action == 'managebooking' || $action == 'recurrentbooking' || $action == 'frequent_location' || $action == 'frequent_journey' || $action == 'accounts' || $action == 'tdispatch_settings' || $action == 'edit_recurrent_booking' || $action == 'edit_frequentlocation' || $action == 'add_frequentlocation' || $action == 'add_frequentjourney' || $action == 'edit_frequentjourney' || $action == 'add_accounts' || $action == 'edit_accounts' || $action == 'add_groups' || $action == 'edit_groups' || $action == 'add_users' || $action == 'edit_users')
{
	$tdispatch = 'active';
}
else if($action == 'fund_request_report' || $action == 'fund_request' || $action == "manage_fund_request" || $action == "manage_fund_request_list")
{
	$manage_fundrequest = 'active';
}

else
{
	$home = 'active';
}
$url = $_SERVER['REQUEST_URI'];
$split_url = explode('?',$_SERVER['REQUEST_URI']);
$search_url = isset($split_url[0])?$split_url[0]:'';

//echo $action;
//echo '<br>';
//echo $url;
//echo $manage_assigntaxi;
?>

<!-- Sidebar -->
		<div id="sidebar">
			<div class="sidebar-tabs">
		        <?php if(($_SESSION['user_type'] == 'C') || ($_SESSION['user_type'] == 'M')) { ?>
		        <ul class="tabs-nav two-items">
		            <li><a href="#general" id="dashboard" title=""><i class="icon-th-list"></i></a></li>
		           <li><a href="#stuff" id="dispatch" title=""><span style="color: #656565;padding: 5px 28px;float: left;">Taxi Dispatch</span><i></i><!--<i class="icon-taxi"></i>--></a></li> 
		        </ul>
				<?php } ?>
		        <div id="general">
				    <!-- Main navigation -->
			        <ul class="navigation widget">
						<?php if(isset($_SESSION['user_type']) &&  ($_SESSION['user_type'] =='A') || $_SESSION['user_type'] =='S') { ?>
			            <li class="<?php if($home) { echo $home; } ?>"><a href="<?php echo URL_BASE;?>admin/dashboard" title=""><i class="icon-dashboard"></i><?php echo __('button_home');?></a></li>
			            <?php } else if($_SESSION['user_type'] == 'C') { ?>
						<li class="<?php if($home) { echo $home; } ?>"><a href="<?php echo URL_BASE;?>company/dashboard" title=""><i class="icon-home"></i><?php echo __('button_home');?></a></li>
						<?php } else if($_SESSION['user_type'] == 'M') { ?>
						<li class="<?php if($home) { echo $home; } ?>"><a href="<?php echo URL_BASE;?>manager/dashboard" title=""><i class="icon-home"></i><?php echo __('button_home');?></a></li>
						<?php }  ?>
			           <?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='C'  ) { ?>
			            <li class="<?php if($manage_setting) { echo $manage_setting; } ?>"><a href="#" title="" class="expand <?php if($manage_setting == 'active') { echo 'subOpened';} ?>" <?php if($manage_setting == 'active') { echo "id='current'"; } ?>><i class="icon-wrench"></i><?php echo __('general_settings');?></a>
			                <ul>
								<?php  if($_SESSION['user_type'] =='A') {  ?>			
										<li><a href="<?php echo URL_BASE;?>admin/manage_site" <?php if($url == '/admin/manage_site') { echo 'class="current"'; } ?>  title=""><?php echo __('site_settings');?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/mail_settings" <?php if($url == '/admin/mail_settings') { echo 'class="current"'; } ?> title="<?php echo __('menu_mail_settings'); ?>"><?php echo __('menu_mail_settings'); ?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/sms_template" <?php if($url == '/admin/sms_template') { echo 'class="current"'; } ?> title="<?php echo __('sms_template'); ?>"><?php echo __('sms_template'); ?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/social_network" <?php if($url == '/admin/social_network') { echo 'class="current"'; } ?> title="<?php echo __('social_network_setting'); ?>"><?php echo __('social_network_setting'); ?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/payment_gateways" <?php if($url == '/admin/payment_gateways') { echo 'class="current"'; } ?> title="<?php echo __('payment_gateway_setting'); ?>"><?php echo __('payment_gateway_setting'); ?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/module_settings" <?php if($url == '/admin/module_settings') { echo 'class="current"'; } ?> title="<?php echo __('module_setting'); ?>"><?php echo __('module_setting'); ?></a></li>
									   <?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S'|| $_SESSION['user_type'] =='C'){ ?>
										<li><a href="<?php echo URL_BASE;?>manage/contacts" <?php if($url == '/manage/contacts') { echo 'class="current"'; }?>   title=""><?php echo __('manage_contactus');?></a></li>
										<?php } ?>
										<?php if($_SESSION['user_type'] =='A' ){ ?>
										<!--<li class="<?php if($manage_free_quotes) { ?>current<?php } ?>" id="<?php if($manage_free_quotes) { ?>active<?php } ?>"><a href="<?php echo URL_BASE;?>manage/free_quotes" <?php if($url == '/manage/free_quotes') { echo 'class="current"'; }?>   title=""><?php //echo __('manage_free_quotes');?></a></li>-->
										<?php } ?>

							<?php }?>
							
								<!--       General Settings -->
			                     <?php if(isset($_SESSION['user_type']) &&  ($_SESSION['user_type'] =='A') || $_SESSION['user_type'] =='S') { ?>
								<li class="<?php if($manage_country) { echo $manage_country; } ?>"><a href="#" title="" class="expand <?php if($manage_country == 'active') { echo 'subOpened';} ?>" <?php if($manage_country == 'active') { echo "id='current'"; } ?>>
								<!-- <i class="icon-globe"> --></i><?php echo __('country_management');?></a>
									<ul>
										<li><a href="<?php echo URL_BASE;?>admin/country/add" <?php if($url == '/add/country') { echo 'class="current"'; } ?>  title=""><?php echo __('add_country');?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/country/manage" <?php if($url == '/manage/country') { echo 'class="current"'; } ?> title="<?php echo __('manage_country'); ?>"><?php echo __('manage_country'); ?></a></li>								
									</ul>
							
								</li>
								<li class="<?php if($manage_state) { echo $manage_state; } ?>"><a href="#" title="" class="expand <?php if($manage_state == 'active') { echo 'subOpened';} ?>" <?php if($manage_state == 'active') { echo "id='current'"; } ?>>
								<!-- <i class="icon-bar-chart"></i>--><?php echo __('state_management');?></a>
									<ul>
										<li><a href="<?php echo URL_BASE;?>add/state" <?php if($url == '/add/state') { echo 'class="current"'; } ?>  title=""><?php echo __('add_state');?></a></li>
										<li><a href="<?php echo URL_BASE;?>manage/state" <?php if($url == '/manage/state') { echo 'class="current"'; } ?> title="<?php echo __('manage_state'); ?>"><?php echo __('manage_state'); ?></a></li>
									</ul>
								</li>
								<li class="<?php if($manage_city) { echo $manage_city; } ?>"><a href="#" title="" class="expand <?php if($manage_city == 'active') { echo 'subOpened';} ?>" <?php if($manage_city == 'active') { echo "id='current'"; } ?>>
								<!-- <i class="icon-building"></i> --><?php echo __('city_management');?></a>
									<ul>
										<li><a href="<?php echo URL_BASE;?>add/city/" <?php if($url == '/add/city') { echo 'class="current"'; } ?>  title=""><?php echo __('add_city');?></a></li>
										<li><a href="<?php echo URL_BASE;?>manage/city" <?php if($url == '/manage/city') { echo 'class="current"'; } ?> title="<?php echo __('manage_city'); ?>"><?php echo __('manage_city'); ?></a></li>
									</ul>
								</li>
			            <?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') { ?>
			            <!--<li class="<?php if($manage_field) { echo $manage_field; } ?>"><a href="#" title="" class="expand <?php if($manage_field == 'active') { echo 'subOpened';} ?>" <?php if($manage_field == 'active') { echo "id='current'"; } ?>>
			            <!-- <i class="icon-field"></i> --><?php //echo __('field_management');?><!--</a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>add/field" <?php if($url == '/add/field') { echo 'class="current"'; } ?>  title=""><?php echo __('add_field');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manage/field" <?php if($url == '/manage/field') { echo 'class="current"'; } ?> title="<?php echo __('manage_field'); ?>"><?php echo __('manage_field'); ?></a></li>
			                </ul>
			            </li>-->
						<?php }  ?>									
							<li class="<?php if($manage_faq) { echo $manage_faq; } ?>"><a href="#" title="" class="expand <?php if($manage_faq == 'active') { echo 'subOpened';} ?>" <?php if($manage_faq == 'active') { echo "id='current'"; } ?>>
							<!-- <i class="icon-field"></i>--><?php echo __('faq_management');?></a>
								<ul>
									<li><a href="<?php echo URL_BASE;?>add/faq" <?php if($url == '/add/faq') { echo 'class="current"'; } ?>  title="<?php echo __('add_faq');?>"><?php echo __('add_faq');?></a></li>
									<li><a href="<?php echo URL_BASE;?>manage/faq" <?php if($url == '/manage/faq') { echo 'class="current"'; } ?> title="<?php echo __('manage_faq'); ?>"><?php echo __('manage_faq'); ?></a></li>
								</ul>
							</li>		
							<?php if($_SESSION['user_type'] != 'S')  { ?>
								<li class="<?php if($manage_admin) { echo $manage_admin; } ?>"><a href="#" title="" class="expand <?php if($manage_admin == 'active') { echo 'subOpened';} ?>" <?php if($manage_admin == 'active') { echo "id='current'"; } ?>>
								<!-- <i class="icon-user"></i>--><?php echo __('superadmin_management');?></a>
									<ul>
										<li><a href="<?php echo URL_BASE;?>admin/moderator/add" <?php if($url == '/admin/moderator/add') { echo 'class="current"'; } ?>  title=""><?php echo __('add_superadmin');?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/moderator/manage" <?php if($url == '/admin/moderator/manage') { echo 'class="current"'; } ?> title="<?php echo __('manage_superadmin'); ?>"><?php echo __('manage_superadmin'); ?></a></li>
									</ul>
								</li>
								<?php } ?>														
								<?php } else { ?>
										<li><a href="<?php echo URL_BASE;?>company/company_setting" <?php if($url == '/company/company_setting') { echo 'class="current"'; } ?> title="<?php echo __('company_setting'); ?>"><?php echo __('company_setting'); ?></a></li>
										<li><a href="<?php echo URL_BASE;?>company/social_network" <?php if($url == '/company/social_network') { echo 'class="current"'; } ?> title="<?php echo __('social_network_setting'); ?>"><?php echo __('social_network_setting'); ?></a></li>			
										<!--<li><a href="<?php //echo URL_BASE;?>company/sms_settings" <?php //if($url == '/company/sms_settings') { echo 'class="current"'; } ?> title="<?php //echo __('sms_settings'); ?>"><?php //echo __('sms_settings'); ?></a></li>-->
									<?php } ?>	
			                </ul>
			            </li>			
			            <!-- General End --->	
			            <?php } ?>		
						 <!-- Company Banner Settings --->
						<?php if($_SESSION['user_type'] =='C') {  ?>
								<li class="<?php if($banner) { echo $banner; } ?>"><a href="#" title="" class="expand <?php if($banner == 'active') { echo 'subOpened';} ?>" <?php if($banner == 'active') { echo "id='current'"; } ?>><i class="icon-picture"></i><?php echo __('banner_mgmt');?></a>
									<ul>
										<!--<li><a href="<?php echo URL_BASE;?>add/banner" <?php if($url == '/add/banner') { echo 'class="current"'; } ?>  title=""><?php echo __('add_banner');?></a></li>-->
										<li><a href="<?php echo URL_BASE;?>manage/banner" <?php if($url == '/manage/banner') { echo 'class="current"'; } ?> title="<?php echo __('manage_banner'); ?>"><?php echo __('manage_banner'); ?></a></li>
									</ul>
								</li>									
						<!-- End -->				 
						 <!-- Company Fare -->
						  <li class="<?php if($fare) { echo $fare; } ?>"><a href="#" title="" class="expand <?php if($fare == 'active') { echo 'subOpened';} ?>" <?php if($fare == 'active') { echo "id='current'"; } ?>><i class="icon-reorder"></i><?php echo __('fare_management');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>admin/fare/add" <?php if($url == '/admin/fare/add') { echo 'class="current"'; } ?>  title=""><?php echo __('add_fare');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/fare/manage" <?php if($url == '/admin/fare/manage' || $action == 'fareinfo' ) { echo 'class="current"'; } ?> title="<?php echo __('manage_fare'); ?>"><?php echo __('manage_fare'); ?></a></li>
								
			                </ul>
			            </li>
			            <!-- End -->		
			            <?php } ?>		            
			            <!-- Company Settinf Start --->
			             <?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='C'  ) { ?>
			             <li class="<?php if($company_setting) { echo $company_setting; } ?>"><a href="#" title="" class="expand <?php if($company_setting == 'active') { echo 'subOpened';} ?>" <?php if($company_setting == 'active') { echo "id='current'"; } ?>><i class="icon-wrench"></i><?php echo __('company_settings');?></a>
			                <ul>
								<?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') { ?>
								<li class="<?php if($manage_company) { echo $manage_company; } ?>"><a href="#" title="" class="expand <?php if($manage_company == 'active') { echo 'subOpened';} ?>" <?php if($manage_company == 'active') { echo "id='current'"; } ?>><i class="icon-trello"></i><?php echo __('taxicompany');?></a>
									<ul>
										<li><a href="<?php echo URL_BASE;?>admin/company/add" <?php if($url == '/admin/company/add') { echo 'class="current"'; } ?>  title=""><?php echo __('add_company');?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/company/manage" <?php if($url == '/admin/company/manage') { echo 'class="current"'; } ?> title="<?php echo __('manage_company'); ?>"><?php echo __('manage_company'); ?></a></li>
									</ul>
								</li>
								<?php } ?>
			            
								<?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='C' || $_SESSION['user_type'] =='S') { ?>
								<li class="<?php if($manage_manager) { echo $manage_manager; } ?>"><a href="#" title="" class="expand <?php if($manage_manager == 'active') { echo 'subOpened';} ?>" <?php if($manage_manager == 'active') { echo "id='current'"; } ?>><i class="icon-suitcase"></i><?php echo __('manager_management');?></a>
									<ul>
										<li><a href="<?php echo URL_BASE;?>admin/manager/add" <?php if($url == '/admin/manager/add') { echo 'class="current"'; } ?>  title=""><?php echo __('add_manager');?></a></li>
										<li><a href="<?php echo URL_BASE;?>admin/manager/manage" <?php if($url == '/admin/manager/manage') { echo 'class="current"'; } ?> title="<?php echo __('manage_manager'); ?>"><?php echo __('manage_manager'); ?></a></li>
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
						<!-- Company Setting end ----->
						<!-- Car Settings Start --->
					<li class="<?php if($car_setting) { echo $car_setting; } ?>"><a href="#" title="" class="expand <?php if($car_setting == 'active') { echo 'subOpened';} ?>" <?php if($car_setting == 'active') { echo "id='current'"; } ?>><i class="icon-wrench"></i><?php echo __('car_settings');?></a>
			                <ul>
					 <?php if(isset($_SESSION['user_type']) &&  ($_SESSION['user_type'] =='A') || $_SESSION['user_type'] =='S') { ?>			           
					 <!--<li class="<?php if($manage_motor) { echo $manage_motor; } ?>"><a href="#" title="" class="expand <?php if($manage_motor == 'active') { echo 'subOpened';} ?>" <?php if($manage_motor == 'active') { echo "id='current'"; } ?>><i class="icon-circle"></i><?php //echo __('motor_management');?></a>
			                <ul>
								<?php /*<li><a href="<?php echo URL_BASE;?>add/motor" <?php if($url == '/add/motor') { echo 'class="current"'; } ?>  title=""><?php echo __('add_motor_company');?></a></li> */ ?>
			                    <li><a href="<?php echo URL_BASE;?>manage/motor" <?php if($url == '/manage/motor') { echo 'class="current"'; } ?> title="<?php echo __('manage_motor_company'); ?>"><?php echo __('manage_motor_company'); ?></a></li>
			                </ul>
			            </li>-->
			            <!--<li class="<?php if($manage_model) { echo $manage_model; } ?>"><a href="#" title="" class="expand <?php if($manage_model == 'active') { echo 'subOpened';} ?>" <?php if($manage_model == 'active') { echo "id='current'"; } ?>><i class="icon-reorder"></i><?php echo __('model_management');?></a>
			                <ul>
								<!-- <li><a href="<?php //echo URL_BASE;?>add/model" <?php //if($url == '/add/model') { echo 'class="current"'; } ?>  title=""><?php //echo __('add_model');?></a></li> -->
			                    <!--<li><a href="<?php echo URL_BASE;?>admin/model/manage" <?php if($url == '/manage/model') { echo 'class="current"'; } ?> title="<?php echo __('manage_model'); ?>"><?php echo __('manage_model'); ?></a></li>
			                </ul>-->
			            </li>
			             <?php /*<li class="<?php if($manage_mile) { echo $manage_mile; } ?>"><a href="#" title="" class="expand <?php if($manage_mile == 'active') { echo 'subOpened';} ?>" <?php if($manage_mile == 'active') { echo "id='current'"; } ?>><i class="icon-reorder"></i><?php echo __('manage_mile');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>add/mile" <?php if($url == '/add/mile') { echo 'class="current"'; } ?>  title=""><?php echo __('add_mile');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manage/mile" <?php if($url == '/manage/mile') { echo 'class="current"'; } ?> title="<?php echo __('manage_mile'); ?>"><?php echo __('manage_mile'); ?></a></li>
			                </ul>
			            </li>		*/ ?>	            
			            <?php } ?>
			            <?php if(isset($_SESSION['user_type']) &&  ($_SESSION['user_type'] =='A') || $_SESSION['user_type'] =='S'|| $_SESSION['user_type'] =='C'|| $_SESSION['user_type'] =='M') { ?>
			            <li class="<?php if($manage_taxi) { echo $manage_taxi; } ?>"><a href="#" title="" class="expand <?php if($manage_taxi == 'active') { echo 'subOpened';} ?>" <?php if($manage_taxi == 'active') { echo "id='current'"; } ?>><i class="icon-taxi"></i><?php echo __('taximanagement');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>admin/taxi/add" <?php if($url == '/admin/taxi/add') { echo 'class="current"'; } ?>  title=""><?php echo __('add_taxi');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/taxi/manage" <?php if($url == '/admin/taxi/manage') { echo 'class="current"'; } ?> title="<?php echo __('manage_taxi'); ?>"><?php echo __('manage_taxi'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/taxi/availabilitytaxi" <?php if($action == 'availabilitytaxi') { echo 'class="current"'; } ?> title="<?php echo __('manage_availability_taxi'); ?>"><?php echo __('manage_availability_taxi'); ?></a></li>
			                </ul>
			            </li>
			            <li class="<?php if($manage_driver) { echo $manage_driver; } ?>"><a href="#" title="" class="expand <?php if($manage_driver == 'active') { echo 'subOpened';} ?>" <?php if($manage_driver == 'active') { echo "id='current'"; } ?>><i class="icon-user"></i><?php echo __('drivermanagement');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>admin/driver/add" <?php if($url == '/admin/driver/add') { echo 'class="current"'; } ?>  title=""><?php echo __('add_driver');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/driver/manage" <?php if($url == '/admin/driver/manage') { echo 'class="current"'; } ?> title="<?php echo __('manage_driver'); ?>"><?php echo __('manage_driver'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/driver/availabilitydriver" <?php if($action == 'availabilitydriver') { echo 'class="current"'; } ?> title="<?php echo __('manage_availability_driver'); ?>"><?php echo __('manage_availability_driver'); ?></a></li>
			                </ul>
			            </li>
			           
			             <li class="<?php if($manage_assigntaxi) { echo $manage_assigntaxi; } ?>"><a href="#" title="" class="expand <?php if($manage_assigntaxi == 'active') { echo 'subOpened';} ?>" <?php if($manage_assigntaxi == 'active') { echo "id='current'"; } ?>><i class="icon-tasks"></i><?php echo __('assign_taxi');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>admin/assigntaxi/add" <?php if($url == '/admin/assigntaxi/add') { echo 'class="current"'; } ?>  title=""><?php echo __('assign_taxi');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/assigntaxi/manage" <?php if($url == '/admin/assigntaxi/manage') { echo 'class="current"'; } ?> title="<?php echo __('manage_assigned_taxi'); ?>"><?php echo __('manage_assigned_taxi'); ?></a></li>
			                </ul>
			            </li>		
			             <?php }// }?>	            
							</ul>
						 </li>						 
						 <!-- CAr Setings End --->
		            <?php if($_SESSION['user_type'] =='A' ||  $_SESSION['user_type'] =='S') { ?>
			            <li class="<?php if($manage_package) { echo $manage_package; } ?>"><a href="#" title="" class="expand <?php if($manage_package == 'active') { echo 'subOpened';} ?>" <?php if($manage_package == 'active') { echo "id='current'"; } ?>><i class="icon-briefcase"></i><?php echo __('packages');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>add/package" <?php if($url == '/add/package') { echo 'class="current"'; } ?>  title=""><?php echo __('add_package');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manage/package" <?php if($url == '/manage/package') { echo 'class="current"'; } ?> title="<?php echo __('manage_package'); ?>"><?php echo __('manage_package'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manage/packagereport" <?php if($action == 'packagereport') { echo 'class="current"'; } ?> title="<?php echo __('upgrade_reports'); ?>"><?php echo __('upgrade_reports'); ?></a></li>
			                </ul>
			            </li>
			             <?php }  ?> 
			             <!-- End -->						
						 <!-- Manage CMS --->
						  <?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S'|| $_SESSION['user_type'] =='C') { ?>
			            <li class="<?php if($manage_content) { echo $manage_content; } ?>"><a href="#" title="" class="expand <?php if($manage_content == 'active') { echo 'subOpened';} ?>" <?php if($manage_content == 'active') { echo "id='current'"; } ?>><i class="icon-book"></i><?php echo __('manage_cms');?></a>
			                <ul>
								<?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') { ?> 
								<li><a href="<?php echo URL_BASE;?>add/menu" <?php if($url == '/add/menu') { echo 'class="current"'; } ?>  title=""><?php echo __('add_menu');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manage/menu" <?php if($url == '/manage/menu') { echo 'class="current"'; } ?> title="<?php echo __('manage_menu'); ?>"><?php echo __('manage_menu'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>add/contents" <?php if($url == '/add/contents') { echo 'class="current"'; } ?> title="<?php echo __('add_content'); ?>"><?php echo __('add_content'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manage/contents" <?php if($url == '/manage/contents') { echo 'class="current"'; } ?> title="<?php echo __('manage_content'); ?>"><?php echo __('manage_content'); ?></a></li>
			                    <?php } else if($_SESSION['user_type'] =='C') { ?>
			                    <li><a href="<?php echo URL_BASE;?>manage/company_contents" <?php if($action == 'company_contents') { echo 'class="current"'; } ?> title="<?php echo __('manage_content'); ?>"><?php echo __('manage_content'); ?></a></li>
			                     <?php } ?>
			                </ul>
			            </li>
			            <?php } ?>
			            <!-- Manage CMS --->
			            <!-- User management -->
			            <?php if($_SESSION['user_type'] =='A' || $_SESSION['user_type'] =='S') { ?>
			            <li class="<?php if($manage_users) { echo $manage_users; } ?>"><a href="#" title="" class="expand <?php if($manage_users == 'active') { echo 'subOpened';} ?>" <?php if($manage_users == 'active') { echo "id='current'"; } ?>><i class="icon-users"></i><?php echo __('user_management');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>manageusers/index" <?php if($url == '/manageusers/index') { echo 'class="current"'; } ?>  title=""><?php echo __('Users List');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manageusers/passengers" <?php if($url == '/manageusers/passengers') { echo 'class="current"'; } ?> title="<?php echo __('menu_manage_passengers'); ?>"><?php echo __('menu_manage_passengers'); ?></a></li>
			                </ul>
			            </li>
			            <?php } ?> 
			            <!---- End --->
			            <!-- Rating management -->
			              <li class="<?php if($manage_ratings) { echo $manage_ratings; } ?>"><a href="#" title="" class="expand <?php if($manage_ratings == 'active') { echo 'subOpened';} ?>" <?php if($manage_ratings == 'active') { echo "id='current'"; } ?>><i class="icon-heart"></i><?php echo __('manage_rating');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>manage/ratingdrivers" <?php if($action == 'ratingdrivers') { echo 'class="current"'; } ?>  title=""><?php echo __('manage_rating_taxi');?></a></li>
			                </ul>
			            </li>
						 <!-- End -->
						 
						 <!-- Tramactions --->
			            <?php if($_SESSION['user_type'] =='A'){ ?>
			            <li class="<?php if($manage_transaction) { echo $manage_transaction; } ?>"><a href="#" title="" class="expand <?php if($manage_transaction == 'active') { echo 'subOpened';} ?>" <?php if($manage_transaction == 'active') { echo "id='current'"; } ?>><i class="icon-money"></i><?php echo __('reports');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>admin/transaction/admintransaction/all/" <?php if($url == '/admin/transaction/admintransaction/all/' || $search_url == '/admin/transaction/admintransaction_list/all/' ) { echo 'class="current"'; } ?>  title=""><?php echo __('all_transaction_log');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/transaction/admintransaction/success/" <?php if($url == '/admin/transaction/admintransaction/success/' || $search_url == '/admin//transaction/admintransaction_list/success/' ) { echo 'class="current"'; } ?> title="<?php echo __('success_transaction_log'); ?>"><?php echo __('success_transaction_log'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/transaction/admintransaction/cancelled/" <?php if($url == '/admin/transaction/admintransaction/cancelled/' || $search_url == '/admin/transaction/admintransaction_list/cancelled/') { echo 'class="current"'; } ?> title="<?php echo __('cancelled_transaction_log'); ?>"><?php echo __('cancelled_transaction_log'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>admin/transaction/admintransaction/rejected/" <?php if($url == '/admin/transaction/admintransaction/rejected/' || $search_url == '/admin/transaction/admintransaction_list/rejected/' ) { echo 'class="current"'; } ?> title="<?php echo __('rejected_trip_log'); ?>"><?php echo __('rejected_trip_log'); ?></a></li>
			                </ul>
			            </li>
			            <?php } ?>						 
			            <?php if($_SESSION['user_type'] =='C'){ ?>
			            <li class="<?php if($manage_users) { echo $manage_users; } ?>"><a href="#" title="" class="expand <?php if($manage_users == 'active') { echo 'subOpened';} ?>" <?php if($manage_users == 'active') { echo "id='current'"; } ?>><i class="icon-reorder"></i><?php echo __('user_management');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>company/passengers" <?php if($action == 'passengers') { echo 'class="current"'; } ?>  title=""><?php echo __('menu_manage_passengers');?></a></li>
			                </ul>
			            </li>
			            <li class="<?php if($manage_transaction) { echo $manage_transaction; } ?>"><a href="#" title="" class="expand <?php if($manage_transaction == 'active') { echo 'subOpened';} ?>" <?php if($manage_transaction == 'active') { echo "id='current'"; } ?>><i class="icon-money"></i><?php echo __('reports');?></a>
			               <ul>
								<li><a href="<?php echo URL_BASE;?>company/transaction/companytransaction/all/" <?php if($url == '/company/transaction/companytransaction/all/'  || $search_url == '/company/transaction/companytransaction_list/all/') { echo 'class="current"'; } ?>  title=""><?php echo __('all_transaction_log');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>company/transaction/companytransaction/success/" <?php if($url == '/company/transaction/companytransaction/success/'  || $search_url == '/company/transaction/companytransaction_list/success/') { echo 'class="current"'; } ?> title="<?php echo __('success_transaction_log'); ?>"><?php echo __('success_transaction_log'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>company/transaction/companytransaction/cancelled/" <?php if($url == '/company/transaction/companytransaction/cancelled/'  || $search_url == '/company/transaction/companytransaction_list/cancelled/') { echo 'class="current"'; } ?> title="<?php echo __('cancelled_transaction_log'); ?>"><?php echo __('cancelled_transaction_log'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>company/transaction/companytransaction/rejected/" <?php if($url == '/company/transaction/companytransaction/rejected/'  || $search_url == '/company/transaction/companytransaction_list/rejected/') { echo 'class="current"'; } ?> title="<?php echo __('rejected_trip_log'); ?>"><?php echo __('rejected_trip_log'); ?></a></li>
			                </ul>
			            </li>
			            <?php } ?>						 
						<?php if($_SESSION['user_type'] =='M'){ ?>
			            <li class="<?php if($manage_users) { echo $manage_users; } ?>"><a href="#" title="" class="expand <?php if($manage_users == 'active') { echo 'subOpened';} ?>" <?php if($manage_users == 'active') { echo "id='current'"; } ?>><i class="icon-reorder"></i><?php echo __('user_management');?></a>
			               <ul>
								<li><a href="<?php echo URL_BASE;?>company/passengers" <?php if($url == '/company/passengers') { echo 'class="current"'; } ?>  title=""><?php echo __('menu_manage_passengers');?></a></li>
			                </ul>
			            </li>
			            <li class="<?php if($manage_transaction) { echo $manage_transaction; } ?>">
			            <a href="#" title="" class="expand <?php if($manage_transaction == 'active') { echo 'subOpened';} ?>" <?php if($manage_transaction == 'active') { echo "id='current'"; } ?>><i class="icon-money"></i><?php echo __('reports');?></a>
			               <ul>
								<li><a href="<?php echo URL_BASE;?>manager/transaction/managertransaction/all/" <?php if($url == '/manager/transaction/managertransaction/all/' || $search_url == '/manager/transaction/managertransaction_list/all/') { echo 'class="current"'; } ?>  title=""><?php echo __('all_transaction_log');?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manager/transaction/managertransaction/success/" <?php if($url == '/manager/transaction/managertransaction/success/' || $search_url == '/manager/transaction/managertransaction_list/success/') { echo 'class="current"'; } ?> title="<?php echo __('success_transaction_log'); ?>"><?php echo __('success_transaction_log'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manager/transaction/managertransaction/cancelled/" <?php if($url == '/manager/transaction/managertransaction/cancelled/' || $search_url == '/manager/transaction/managertransaction_list/cancelled/') { echo 'class="current"'; } ?> title="<?php echo __('cancelled_transaction_log'); ?>"><?php echo __('cancelled_transaction_log'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>manager/transaction/managertransaction/rejected/" <?php if($url == '/manager/transaction/managertransaction/rejected/' || $search_url == '/manager/transaction/managertransaction_list/rejected/') { echo 'class="current"'; } ?> title="<?php echo __('rejected_trip_log'); ?>"><?php echo __('rejected_trip_log'); ?></a></li>
			                </ul>
			            </li>
			            <?php } ?>
						<!--- End ----------->
						<!-- Account Report -->
									           <?php if($_SESSION['user_type'] =='A'){ ?>
			            <li class="<?php if($account_report) { echo $account_report; } ?>"><a href="#" title="" class="expand <?php if($account_report == 'active') { echo 'subOpened';} ?>" <?php if($account_report == 'active') { echo "id='current'"; } ?>><i class="icon-bar-chart"></i><?php echo __('account_report');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>admin/account_report/" <?php if($action == 'account_report') { echo 'class="current"'; } ?>  title=""><?php echo __('account_report');?></a></li>
								<li><a href="<?php echo URL_BASE;?>admin/active_driver_report/" <?php if($action == 'active_driver_report') { echo 'class="current"'; } ?>  title=""><?php echo __('active_driver_report');?></a></li>
								<li><a href="<?php echo URL_BASE;?>admin/calendarwise_report/" <?php if($action == 'calendarwise_report') { echo 'class="current"'; } ?>  title=""><?php echo __('calendarwise_report');?></a></li>
			                </ul>
			            </li>
			            <?php } ?>
			            <!-- End -->						
			           </ul>
							   
					
			
					<?php /*if($_SESSION['user_type'] =='A'){ ?>      	
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
			            
						<?php /*if($_SESSION['user_type'] =='C'){ ?>        
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
 
		        <div id="stuff">

			        <ul class="navigation widget">
						<?php if(($_SESSION['user_type'] =='C')||($_SESSION['user_type'] =='M')) { ?>
			            <li class="<?php if($tdispatch) { echo $tdispatch; } ?>"><a href="#" title="" class="expand subOpened" id='current'"<?php //if($tdispatch == 'active') { echo 'subOpened';} ?>" <?php //if($tdispatch == 'active') { echo "id='current'"; } ?>><i class="icon-reorder"></i><?php echo __('tdispatch');?></a>
			                <ul>
								<li><a href="<?php echo URL_BASE;?>tdispatch/addbooking/#stuff" <?php if($action == 'addbooking') { echo 'class="current"'; } ?>  title=""><?php echo __('add_booking');?></a></li>
								
			                    <li><a href="<?php echo URL_BASE;?>tdispatch/managebooking/#stuff" <?php if($action == 'managebooking') { echo 'class="current"'; } ?> title="<?php echo __('manage_booking'); ?>"><?php echo __('manage_booking'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>tdispatch/recurrentbooking/#stuff" <?php if($action == 'recurrentbooking') { echo 'class="current"'; } ?> title="<?php echo __('recurrent_booking'); ?>"><?php echo __('recurrent_booking'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>tdispatch/frequent_location/#stuff" <?php if($action == 'frequent_location') { echo 'class="current"'; } ?> title="<?php echo __('frequent_location'); ?>"><?php echo __('frequent_location'); ?></a></li>
			                    <li><a href="<?php echo URL_BASE;?>tdispatch/frequent_journey/#stuff" <?php if($action == 'frequent_journey') { echo 'class="current"'; } ?> title="<?php echo __('frequent_journey'); ?>"><?php echo __('frequent_journey'); ?></a></li>
			                    <?php /*<li><a href="<?php echo URL_BASE;?>tdispatch/accounts/#stuff" <?php if($action == 'accounts') { echo 'class="current"'; } ?> title="<?php echo __('accounts'); ?>"><?php echo __('accounts'); ?></a></li> */ ?>
			                   <?php if($_SESSION['user_type'] =='C') { ?>
			                    <li><a href="<?php echo URL_BASE;?>tdispatch/tdispatch_settings/#stuff" <?php if($action == 'tdispatch_settings') { echo 'class="current"'; } ?> title="<?php echo __('tdispatch_setting'); ?>"><?php echo __('tdispatch_setting'); ?></a></li>
			                   <?php } ?>
			                </ul>
			            </li>
			            <?php  } ?>
			        </ul>

		        </div>  

		    </div>
		</div>
		<!-- /sidebar -->
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/plugins/jquery.easytabs.min.js"></script>
<script type="text/javascript" src="<?php echo URL_BASE;?>public/js/plugins/jquery.collapsible.min.js"></script>

<script type="text/javascript">
//===== Collapsible plugin for main nav =====//
	
	$('.expand').collapsible({
		defaultOpen: 'current,third',
		cookieName: 'navAct',
		cssOpen: 'subOpened',
		cssClose: 'subClosed',
		speed: 200
	});
</script>
<?php if(($_SESSION['user_type'] == 'C')||($_SESSION['user_type'] == 'M')) { ?>
<script type="text/javascript">
//===== Easy tabs =====//
	
	$('.sidebar-tabs').easytabs({
		animationSpeed: 150,
		collapsible: false,
		tabActiveClass: "active"
	});
	
	$('#dashboard').click(function(){
		var url = '<?php echo URL_BASE; ?>company/dashboard#general';
		$(location).attr('href',url);
	});
	
	$('#dispatch').click(function(){
		var url = '<?php echo URL_BASE; ?>tdispatch/addbooking/#stuff';
		$(location).attr('href',url);
	});
</script>
<?php } ?>
