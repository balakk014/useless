<?php $out= file_get_contents(str_replace('callcenter/','',tenant_url())."get-constants.html"); 
$out= json_decode($out);
define('URL_BASE', $out->URL_BASE);
define('COMPANY_CID', $out->COMPANY_CID);
define('SITE_LOGO_IMGPATH', $out->SITE_LOGO_IMGPATH);
define('SUBDOMAIN', $out->SUBDOMAIN);
$cookie_set='A';
$cookie_name='user_type_openvbx';
if(isset($_COOKIE[$cookie_name])) { 
$cookie_set= $_COOKIE[$cookie_name];
}
define('SESSION', $cookie_set);
?>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/bootstrap.css"/>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/style.css"/>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/bootstrap-3.2.0/vendor/bootstrap/css/simple-sidebar.css"/>
<link rel="stylesheet" href="<?php echo URL_BASE;?>callcenter/assets/c/overwrite.css"/>

			

<div class="loader">
	<div class="loader_inner">
		<div class="clearfix" style="margin-left: 11px; margin-bottom: 15px;">
			<?php if(COMPANY_CID > 1)
		{ 
			$company_logo = URL_BASE.'/'.SITE_LOGO_IMGPATH.SUBDOMAIN.'.png';
			if(file_exists($company_logo))
			{?>
			<img src="<?php echo URL_BASE.SITE_LOGO_IMGPATH.SUBDOMAIN.'.png'; ?>" alt="Logo">
			<?php 
			} else { ?>
			<img src="<?php echo URL_BASE; ?>public/uploads/site_logo/logo.png" alt="Logo">
			<?php } 
		} else { ?>
		<img src="<?php echo URL_BASE; ?>public/uploads/site_logo/logo.png" alt="Logo" style="width:200px;">
		<?php }
		?>
			</div>		
		<div class="clearfix"><img src='<?php echo URL_BASE; ?>public/css/img/ajax-loaders/294.gif' /></div>
	</div>
</div>
<div class="taxi_dispatcher_inner">
    <div class="row"> 
        <div class="col-lg-5">
<div id="wrapper" class="toggled taxi_match">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a class="sidebar_menu" href="javascript:;">
                        Menu
                        <span class="close_side_bar">&nbsp;</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_BASE; ?>callcenter/dispatch">Dashboard</a>
                </li>
                
                <li>
                    <a href="<?php echo URL_BASE; ?>taxidispatch/manage_booking"><?php echo 'Manage Booking'; ?></a>
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
                <?php if(SESSION =='C' || SESSION =='A') {  ?>
                <li>
                    <a target="_top" href="<?php echo URL_BASE;?>tdispatch/tdispatch_settings/"><?php echo 'Taxi Dispatch Setting'; ?></a>
                </li>
                <?php } ?>
            </ul>
            
           
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">                       
                        <a href="#menu-toggle" id="menu-toggle"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
     </div>
        <div class="col-md-6">
            <a href="<?php echo URL_BASE; ?>taxidispatch/dashboard" title="logo">
            <?php if(COMPANY_CID > 1)
			{ 
				$company_logo = $_SERVER['DOCUMENT_ROOT'].'/'.SITE_LOGO_IMGPATH.SUBDOMAIN.'.png';
				if(file_exists($company_logo))
				{?>
				<img src="<?php echo URL_BASE.SITE_LOGO_IMGPATH.SUBDOMAIN.'.png'; ?>" alt="Logo">
				<?php 
				} else { ?>
				<img src="<?php echo URL_BASE; ?>public/uploads/site_logo/logo.png" alt="Logo">
				<?php } 
			} else { ?>
			<?php /* <img src="<?php echo URL_BASE; ?>public/uploads/site_logo/logo.png" alt="Logo"> */ ?>
			<?php }
			?>
            </a>
            <div class="battle_view_button">
             <button class="call-button twilio-call" data-href="<?php echo site_url('messages/call') ?>"><span>Call</span></button>
			<button class="sms-button twilio-sms" data-href="<?php echo site_url('messages/sms') ?>"><span>SMS</span></button>
            </div>
        </div>
         <div id="vbx-client-status" class="<?php echo ($user_online == 1 ? 'online' : ''); ?>">
		<div class="client-button-wrap">
	     	<button class="client-button twilio-client">
	        	<span class="isoffline">Offline</span><span class="isonline">Online</span>
	     	</button>
		</div>
	 </div>    
        <div class="col-lg-5 rgt_menu"> 
			       
            <ul>
				<?php if(SESSION=="A" || SESSION=="DA") { ?>
					<li><a target="_top" href="<?php echo URL_BASE; ?>admin/dashboard"  title="Go to">Goto Admin</a></li>
					<li><a target="_top" href="<?php echo URL_BASE; ?>admin/logout" title="Logout">Logout</a></li>
				<?php } else if(SESSION=="C") { ?>
					<li><a target="_top" href="<?php echo URL_BASE; ?>company/dashboard" title="Go to">Goto Company</a></li>
					<li><a target="_top" href="<?php echo URL_BASE; ?>company/logout" title="Logout">Logout</a></li>
				<?php }	else if(SESSION=="M") { ?>
					<li><a target="_top" href="<?php echo URL_BASE; ?>manager/dashboard" title="Go to">Goto Dispatcher</a></li>
					<li><a target="_top" href="<?php echo URL_BASE; ?>manager/logout" title="Logout">Logout</a></li>
				<?php }  ?>
            </ul>
        </div>
    </div>
    
    
    <div id="vbx-context-menu" class="context-menu">

	<div class="sms-dialog">
		<a class="close action" href=""><span class="replace">close</span></a>
		<h3>Send a Text Message</h3>
		<form action="<?php echo site_url('messages/sms') ?>" method="post" class="sms-dialog-form vbx-form">
			<fieldset class="vbx-input-complex vbx-input-container">
				<label class="field-label left">To
					<input class="small" name="to" type="text" placeholder="(555) 867 5309" value="" />
				</label>
				<?php if(isset($callerid_numbers) && count($callerid_numbers) > 1): ?>
					<label class="field-label left">From
						<select name="from" class="small">
							<?php foreach($callerid_numbers as $number):
								if (!$number->capabilities->sms)
								{
									continue;
								} 
							?>
							<option value="<?php echo $number->phone ?>">
								<?php echo $number->name ?>
							</option>
							<?php endforeach; ?>
						</select>
					</label>
				<?php elseif(isset($callerid_numbers) && count($callerid_numbers) == 1): 
					$c = $callerid_numbers[0]; ?>
					<input type="hidden" name="from" value="<?php echo $c->phone ?>" />
				<?php endif; ?>
				
				<br class="clear" />

				<label class="field-label left">Message
					<textarea class="sms-message" name="content" placeholder="Enter your message, must be 1600 characters or less. (higher rates may apply for messages of more than 160 characters)"></textarea><span class="count left">1600</span>
				</label>
			</fieldset>

			<button class="send-sms-button sms-button"><span>Send SMS</span></button>
			<img class="sms-sending hide" src="<?php echo asset_url('assets/i/ajax-loader.gif'); ?>" alt="loading" />
		</form>
	</div> <!-- .sms-dialog -->

	<div class="notify <?php echo (isset($error) && !empty($error))? '' : 'hide' ?>">
	 	<p class="message">
			<?php if(isset($error) && $error): ?>
				<?php echo $error ?>
			<?php endif; ?>
			<a href="" class="close action"><span class="replace">Close</span></a>
		</p>
	</div><!-- .notify -->

</div>
    
    
</div>




<?php if($user_online === 'client-first-run') { $this->load->view('banners/client-first-run'); } ?>
