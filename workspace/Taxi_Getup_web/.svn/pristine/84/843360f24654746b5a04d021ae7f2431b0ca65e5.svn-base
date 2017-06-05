<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php $action = $this->router->fetch_class();
$method = $this->router->fetch_method();
 ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Dispatcher</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
	<?php echo $_styles; ?>
	<?php foreach($css as $link): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo ASSET_ROOT ?>/<?php echo $link ?>.css" />

	<?php endforeach;  ?>

	<!--[if IE 7]>
		<link type="text/css" rel="stylesheet" href="<?php echo ASSET_ROOT ?>/c/ie.css" />
	<![endif]-->
	
	<style>
	 #hd{display:none !important;}
	</style>
   	 
		 
</head>
<body >
<?php

$base_url = explode("/",base_url());
$base_url = isset($base_url[0])?$base_url[0]:'';


if($action == 'devices' &&  $method == 'dispatch' )
{
 //print_r($this->session->userdata); ?>
<!-- wrapper_header -->
<?php  if(empty($this->session->userdata['loggedin'])){  if($this->session->userdata['loggedin'] == ''){ echo $wrapper_header; } }else{ echo $wrapper_header; } ?>

<!-- header -->
<?php if(empty($this->session->userdata['loggedin'])){ if($this->session->userdata['loggedin'] == ''){ echo $header; } }else{ echo $header; } ?>

	<!-- utility_menu -->
	<?php echo $utility_menu; ?>

<!-- context_menu -->
	<?php echo $context_menu; ?>

		<!-- content_header -->
	<?php
	if(empty($this->session->userdata['loggedin'])){
	 if($this->session->userdata['loggedin'] == ''){
	  echo $content_header;
	   }
	   }
	   else{
	    echo $content_header;
	   } ?>

	<!-- content_main -->
	<?php if(empty($this->session->userdata['loggedin'])){  if($this->session->userdata['loggedin'] == ''){ echo $content_main; }  } else{ echo $content_header; } ?>

	<!-- content_sidebar -->
	<?php if(empty($this->session->userdata['loggedin'])){  if($this->session->userdata['loggedin'] == ''){ echo $content_sidebar;  }} else{ echo $content_header; } ?>

	<!-- content_footer -->
	<?php if(empty($this->session->userdata['loggedin'])){  if($this->session->userdata['loggedin'] == ''){ echo $content_footer; } } else{ echo $content_header; } ?>
	<?php  if(empty($this->session->userdata['loggedin'])){
		if($this->session->userdata['loggedin'] != '')
		{   ?>

		<iframe  scrolling="no" frameborder="0" allowfullscreen allowtransparency="true" id="scrollbar_height_both" width="100%" style="width:100%;" src="<?php echo $base_url ?>/taxidispatch/dashboard" sandbox="allow-modals allow-same-origin allow-popups allow-scripts allow-forms allow-top-navigation"></iframe>

	<?php  } }
	else{
	?>
	<iframe  scrolling="no" frameborder="0" allowfullscreen allowtransparency="true" width="100%" id="scrollbar_height" style="width:100%;" src="<?php echo $base_url; ?>/taxidispatch/dashboard" sandbox="allow-modals allow-same-origin allow-popups allow-scripts allow-forms allow-top-navigation"></iframe>
	<?php
	} ?>
<!-- footer -->
<?php if(empty($this->session->userdata['loggedin'])){ if($this->session->userdata['loggedin'] == ''){  echo $footer; } }else{ echo $content_header; } ?>

<?php }
else
{ ?>
<?php echo $wrapper_header; ?>

<!-- header -->
<?php echo $header; ?>

	<!-- utility_menu -->
	<?php if($action == 'devices' &&  $method == 'dispatch' ) {  echo $utility_menu; } ?>

	<!-- context_menu -->
	<?php if(($action == 'devices' &&  $method == 'dispatch') || ($action == 'flows' &&  $method == 'edit')) {   echo $context_menu; }?>

 

	<!-- content_header -->
	<?php if(($action == 'devices' &&  $method == 'dispatch')) { echo $content_header; } ?>

	<!-- content_main -->
	<?php echo $content_main; ?>

	<!-- content_sidebar -->
	<?php if($action == 'devices' &&  $method == 'dispatch' ) { echo $content_sidebar; }?>

	<!-- content_footer -->
	<?php if($action == 'devices' &&  $method == 'dispatch' ) {  echo $content_footer; } ?>



<!-- footer -->
<?php if($action == 'devices' &&  $method == 'dispatch' ) {  echo $footer; }?>
<?php } ?>

<!-- wrapper_footer -->
<?php if($action == 'devices' &&  $method == 'dispatch' ) {   echo $wrapper_footer; }?>
<?php if($action == 'devices' &&  $method == 'dispatch' ) { echo $error_dialog;} ?>
<?php if($action == 'devices' &&  $method == 'dispatch' ) { echo $analytics;} ?>
<?php $this->load->view('js-init'); ?>

					
<?php echo $_scripts; ?>

<script type="text/javascript">
//<![CDATA[
	if (window == window.top && !window.location.href.match('\/auth\/')) {
		$.cookie('last_known_url', window.location.href, 0, '/');
		window.location = OpenVBX.home;
	}
//]]>

$(window).load(function() {
	 $(".loader").fadeOut("fast");		
	});

$(document).ready(function() {

	var blog_height = $(window).height() - 50;
	$("#scrollbar_height").css({
		'height': blog_height,
		'overflow-y': 'hidden'
	})
	$("#scrollbar_height_both").css({
		'height': blog_height,
		'overflow-y': 'hidden'
	})

	 $(window).resize(function() {
		var blog_height = $(window).height() - 50;
		$("#scrollbar_height").css({
			'height': blog_height,
			'overflow-y': 'hidden'
		})
		$("#scrollbar_height_both").css({
			'height': blog_height,
			'overflow-y': 'hidden'
		})
	});
	
	$('#menu-toggle').click(function(){
		
			$('#sidebar-wrapper').css({'display':'block'});
			$('.taxi_match').removeClass('toggled');
	});
	
	$('.sidebar_menu').click(function(){
		
			$('#sidebar-wrapper').css({'display':'none'});
			$('.taxi_match').addClass('toggled');
	});
	
	
	
	

});

</script>
</body>
</html>
<style>
iframe, object, embed {
        max-width: 100%;
}
#doc3{margin:0 auto !important;}
</style>
