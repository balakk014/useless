<ul>
    <li class="color_code1">
	<div class="dash_active_left">
	    <img src="<?php echo IMGPATH ?>dashboard_icons/1.png" class="image" alt="<?php echo __('payment_label'); ?>"  />
	</div>
	<div class="dashboard_detail_right">	   
	    <h2>  <?php echo __('total_driver_balance'); ?></h2>
	    <p><?php if ($driver_id > 0) { echo CURRENCY . ($dashboardData["total_amount"]); } else { echo CURRENCY . $dashboardData["total_amount"]; } ?></p>	  
	</div>	
    </li>
    <li class="color_code2">
	<div class="dash_active_left">
	    <img src="<?php echo IMGPATH ?>dashboard_icons/2.png" class="image" alt="<?php echo __('total_driver_count'); ?>"  />
	</div>
	<div class="dashboard_detail_right">	   
	    <h2><?php echo __('total_driver_count'); ?></h2>
	    <p><?php if ($driver_id > 0) { echo CURRENCY . ($dashboardData["total_driver_count"]); } else { echo CURRENCY . $dashboardData["total_driver_count"]; } ?></p>
	</div>
    </li>
   
        <li class="color_code3">
    	<div class="dash_active_left">
    	    <img src="<?php echo IMGPATH ?>dashboard_icons/3.png" class="image" alt="<?php echo __('trips_count'); ?>"  />
    	</div>
    	<div class="dashboard_detail_right">	   
    	    <h2><?php echo __('trips_count'); ?></h2>
    	    <p><?php echo CURRENCY . $dashboardData["trips_count"]; ?></p>
    	</div>    	
        </li>
   
    <li class="color_code4">
	<div class="dash_active_left">
	    <img src="<?php echo IMGPATH ?>dashboard_icons/4.png" class="image" alt="<?php echo __('cancel_trips_count'); ?>"  />
	</div>
	<div class="dashboard_detail_right">	   
	    <h2><?php echo __('cancel_trips_count'); ?></h2>
	    <p><?php if ($driver_id > 0) { echo CURRENCY . ($dashboardData["cancel_trips_count"]); } else { echo CURRENCY . $dashboardData["cancel_trips_count"]; } ?></p>
	</div>	
    </li>
    <li class="color_code5">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/5.png" class="image" alt="<?php echo __('coupon_recharge_amount'); ?>"  /></div>
	<div class="dashboard_detail_right">	   
	    <h2> <?php echo __('coupon_recharge_amount'); ?></h2>
	    <p><?php if ($driver_id > 0) { echo CURRENCY . ($dashboardData["coupon_recharge_amount"]); } else { echo CURRENCY . $dashboardData["coupon_recharge_amount"]; } ?></p>
	</div>
    </li>
    <li class="color_code6">
	<div class="dash_active_left">  <img src="<?php echo IMGPATH ?>dashboard_icons/6.png" class="image" alt="<?php echo __('admin_recharge_amount'); ?>"  /></div>
	<div class="dashboard_detail_right">	   
	    <h2><?php echo __('admin_recharge_amount'); ?></h2>
	    <p><?php echo $dashboardData["admin_recharge_amount"]; ?></p>	    
	</div>	
    </li>
    <li class="color_code7">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/7.png" class="image" alt="<?php echo __('total_active_driver'); ?>"  /></div>
	<div class="dashboard_detail_right">	   
	    <h2><?php echo __('total_active_driver'); ?></h2>
	    <p><?php echo $dashboardData["total_active_driver"]; ?></p>	  
	</div>	
    </li>
    <li class="color_code8">
	<div class="dash_active_left"> <img src="<?php echo IMGPATH ?>dashboard_icons/8.png" class="image" alt="<?php echo __('total_inactive_driver'); ?>"  /></div>
	<div class="dashboard_detail_right">	  
	    <h2><?php echo __('total_inactive_driver'); ?></h2>
	    <p><?php echo $dashboardData["total_inactive_driver"]; ?></p>	   
	</div>
    </li>
    <li class="color_code9">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/9.png" class="image" alt="<?php echo __('total_online_driver'); ?>"  /></div>
	<div class="dashboard_detail_right">	  
	    <h2><?php echo __('total_online_driver'); ?></h2>
	    <p><?php echo $dashboardData["total_online_driver"]; ?></p>	    
	</div>	
    </li>

    <li class="color_code10">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/10.png" class="image" alt="<?php echo __('total_offline_driver'); ?>"  /></div>
	<div class="dashboard_detail_right">	  
	    <h2><?php echo __('total_offline_driver'); ?></h2>
	    <p><?php echo $dashboardData["total_offline_driver"]; ?></p>	    
	</div>	
    </li>
    

</ul>
