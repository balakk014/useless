<?php defined('SYSPATH') OR die("No direct access allowed."); ?>
<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_top">
			<div class="top_left"></div>
			<div class="top_center"></div>
			<div class="top_rgt"></div>
		</div>
		<div class="content_middle">
			<div class="widget">
				<div class="title">
					<img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" />
					<h6><?php echo $page_title; ?></h6>
				</div>
					<?php
					if(count($ContactsList) > 0){
					foreach($ContactsList as $contacts_list) { ?>
					<p><?php echo __('name_label'); ?> : <?php echo $contacts_list['name'];?></p>
					<p><?php echo __('email_label'); ?> : <a href="mailto:<?php echo $contacts_list['email'];?>" title="mailto" target="_blank"><?php echo $contacts_list['email'];?></a></p>
					<p><?php echo __('companyname'); ?> : <?php echo $contacts_list['company_name'];?></p>
					<p><?php echo __('no_of_taxi'); ?> : <?php echo $contacts_list['no_of_taxi'];?></p> 
					<p><?php echo __('phone_number'); ?> : <?php echo $contacts_list['mobile_no'];?></p>
					<p><?php echo __('message'); ?> : <?php echo $contacts_list['message'];?></p>
					<p><?php echo __('city_name'); ?> : <?php echo $contacts_list['city_name'];?></p>
					<p><?php echo __('country_name'); ?> : <?php echo $contacts_list['country_name'];?></p>
					<p><?php echo __('download_date'); ?> : <?php echo $contacts_list['createdate'];?></p>
					
					
					<?php  } 
					} 
					else 
					{ ?>
					<p><?php echo __('no_data'); ?></p>
					<?php 
					} ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
 $(document).ready(function(){
	toggle(22);
});
</script>
