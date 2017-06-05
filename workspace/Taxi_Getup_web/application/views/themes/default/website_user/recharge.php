<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="dash_details">
    <?php echo View::factory(USERVIEW . 'website_user/left_menu'); ?>
    <section id="right_side_part">
	<div class="top_part">
	    <div class="bread_com">
		<ul>
		    <li><a href="<?php echo URL_BASE; ?>" title="<?php echo __("home_breadcrumb"); ?>"><?php echo __("home_breadcrumb"); ?></a><i class="fa fa-angle-double-right"></i></li>
		    <li><p><?php echo __("recharge_voucher"); ?></p></li>
		</ul>
	    </div>
	    <?php echo View::factory(USERVIEW . 'website_user/upcoming_trip_alert'); ?>
	</div>
	<div class="white_bg">
	<div class="recharge_left_box">
	    <form method="post" name="recharge_voucher" id="recharge_voucher">
		    <ul>
			<li>
			    <div class="full_name">
				<input type="text" name="recharge_code" onpaste="return false;" placeholder="<?php echo ucwords(__("enter_recharge_code")); ?>" value="<?php echo isset($validator['recharge_code']) ? $validator['recharge_code'] : ''; ?>" maxlength="24"/>
				<label class="error"> </label>
			    </div>
			</li>
			<input type="hidden" name="passenger_id" value="<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : ""; ?>"> 
			<li>
			    <div class="submit">
				<input type="button" name="submit_change_pass" id="voucher_submit" title="<?php echo __('recharge'); ?>" value="<?php echo __('recharge'); ?>"/>
			    </div>
			</li>
		    </ul>
		</form>
	    <div class="voucher_table">
	    <table cellpadding="10" cellspacing="0" border="0" width="100%" >
		<thead>
			<tr>
			    <th align="left"><?php echo __("sno"); ?></th>
			    <th align="left"><?php echo __("Amount"); ?></th>
			    <th align="left"><?php echo __("Date & Time"); ?></th>
			    <th align="left"><?php echo __("Coupon Code"); ?></th>
			</tr>
		</thead>
			<?php
			if (count($passenger_recharge_history) > 0) {
			    $sno = $Offset;
			    foreach ($passenger_recharge_history as $c) {
				$sno++;
				?>
				<tr>
				    <td><?php echo $sno; ?></td>
				    <td><?php echo CURRENCY.$c['amount']; ?></td>
				    <td><?php echo $c['used_date']; ?></td>
				    <td><?php if($c['added_by']==0){ echo $c['coupon_code']; } else { echo "Added by admin"; } ?></td>
				</tr>
			    <?php }
			} else { ?>
    			<tr><td colspan="9"><?php echo __("no_data"); ?></td></tr>
<?php } ?>
		    </table>
	    <?php if (count($passenger_recharge_history) > 0): ?>
    	    <div class="pagination">
		<?php echo $pag_data->render(); ?>
    	    </div>
<?php endif; ?> 
	    </div>
	</div>
	<!--recharge right box-->
	<div class="recharge_right_box">
	    <div class="wallet_image"></div>
	    <div class="wallet_price">
		<h2>Wallet Ballance</h2>
		<h3><?php echo CURRENCY." ".$passenger_balance ?></h3>
	    </div>
	    
	</div>
	</div>
	
    </section>
</div>
<script>
    $("#voucher_submit").click( function() {	
	$.ajax({
	    url:'<?php echo URL_BASE; ?>users/passenger_recharge',
	    type:'get',
	    dataType:'json',
	    data: $("#recharge_voucher").serialize(),
	    success: function(response) {
		if(response.status !=1)
		{
		    $(".error").html(response.message);
		}
		else
		{
		    location.reload();
		}			
	    }
	});
    });
</script>
