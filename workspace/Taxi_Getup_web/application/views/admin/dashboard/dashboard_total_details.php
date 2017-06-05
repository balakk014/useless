<ul>
    <li class="color_code1">
	<div class="dash_active_left">
	    <img src="<?php echo IMGPATH ?>dashboard_icons/1.png" class="image" alt="<?php echo __('payment_label'); ?>"  style="margin-top:23px;" />
	</div>
	<div class="dashboard_detail_right">	   
	    <h2>  <?php echo __('payment_label'); ?></h2>
	    <p><?php if ($company_id > 0) { echo CURRENCY . ($dashboardData["commision_amount"]); } else { echo CURRENCY . $dashboardData["total_amount"]; } ?></p>	  
	</div>	
    </li>
    <li class="color_code2">
	<div class="dash_active_left">
	    <img src="<?php echo IMGPATH ?>dashboard_icons/2.png" class="image" alt="<?php echo __('commission'); ?>"  />
	</div>
	<div class="dashboard_detail_right">	   
	    <h2><?php echo __('commission'); ?></h2>
	    <p><?php if ($company_id > 0) { echo CURRENCY . ($dashboardData["commision_amount"]-$dashboardData["driver_commision"]); } else { echo CURRENCY . $dashboardData["commision_amount"]; } ?></p>
	</div>
    </li>
    <?php if ($company_id > 0) { ?>
        <li class="color_code3">
    	<div class="dash_active_left">
    	    <img src="<?php echo IMGPATH ?>dashboard_icons/3.png" class="image" alt="<?php echo __('payment_to_driver'); ?>"  />
    	</div>
    	<div class="dashboard_detail_right">	   
    	    <h2><?php echo __('payment_to_driver'); ?></h2>
    	    <p><?php echo CURRENCY . $dashboardData["driver_commision"]; ?></p>
    	</div>    	
        </li>
    <?php } else { ?>
        <li class="color_code3">
    	<div class="dash_active_left">
    	    <img src="<?php echo IMGPATH ?>dashboard_icons/3.png" class="image" alt="<?php echo __('payment_to_company'); ?>"  />
    	</div>
    	<div class="dashboard_detail_right">
    	    <h2> <?php echo __('payment_to_company'); ?></h2>
    	    <p><?php echo CURRENCY . $dashboardData["company_amount"]; ?></p>
    	</div>    	
        </li>
    <?php } ?>
    <li class="color_code4">
	<div class="dash_active_left">
	    <img src="<?php echo IMGPATH ?>dashboard_icons/4.png" class="image" alt="<?php echo __('cash_payment'); ?>"  />
	</div>
	<div class="dashboard_detail_right">	   
	    <h2><?php echo __('cash_payment'); ?></h2>
	    <p><?php if ($company_id > 0) { echo CURRENCY . ($dashboardData["company_cash_payment"]); } else { echo CURRENCY . $dashboardData["cash_payment"]; } ?></p>
	</div>	
    </li>
    <li class="color_code5">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/5.png" class="image" alt="<?php echo __('card_payment'); ?>"  /></div>
	<div class="dashboard_detail_right">	   
	    <h2> <?php echo __('card_payment'); ?></h2>
	    <p><?php if ($company_id > 0) { echo CURRENCY . ($dashboardData["company_card_payment"]); } else { echo CURRENCY . $dashboardData["card_payment"]; } ?></p>
	</div>
    </li>
    <li class="color_code6">
	<div class="dash_active_left">  <img src="<?php echo IMGPATH ?>dashboard_icons/6.png" class="image" alt="<?php echo __('new_users'); ?>"  /></div>
	<div class="dashboard_detail_right">	   
	    <h2><?php echo __('new_users'); ?></h2>
	    <p><?php echo $dashboardData["passenger_count"]; ?></p>	    
	</div>	
    </li>
    <li class="color_code7">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/7.png" class="image" alt="<?php echo __('number_of_trips'); ?>"  /></div>
	<div class="dashboard_detail_right">	   
	    <h2><?php echo __('number_of_trips'); ?></h2>
	    <p><?php echo $dashboardData["trips_count"]; ?></p>	  
	</div>	
    </li>
    <li class="color_code8">
	<div class="dash_active_left"> <img src="<?php echo IMGPATH ?>dashboard_icons/8.png" class="image" alt="<?php echo __('number_of_trips_cancelled'); ?>"  /></div>
	<div class="dashboard_detail_right">	  
	    <h2><?php echo __('number_of_trips_cancelled'); ?></h2>
	    <p><?php echo $dashboardData["cancel_trips_count"]; ?></p>	   
	</div>
    </li>
    <li class="color_code9">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/9.png" class="image" alt="<?php echo __('number_of_active_passengers'); ?>"  /></div>
	<div class="dashboard_detail_right">	  
	    <h2><?php echo __('number_of_active_passengers'); ?></h2>
	    <p><?php echo $dashboardData["active_passenger_count"]; ?></p>	    
	</div>	
    </li>

    <li class="color_code10">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/10.png" class="image" alt="<?php echo __('number_of_active_passengers'); ?>"  /></div>
	<div class="dashboard_detail_right">	  
	    <h2><?php echo __('number_of_trips_app'); ?></h2>
	    <p><?php echo $dashboardData["mobile_app_trips"]; ?></p>	    
	</div>	
    </li>
    <li class="color_code11">
	<div class="dash_active_left"><img src="<?php echo IMGPATH ?>dashboard_icons/11.png" class="image" alt="<?php echo __('number_of_active_passengers'); ?>"  /></div>
	<div class="dashboard_detail_right">	  
	    <h2><?php echo __('number_of_trips_web'); ?></h2>
	    <p><?php echo $dashboardData["web_app_trips"]; ?></p>	    
	</div>	
    </li>

</ul>
