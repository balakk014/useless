<?php defined('SYSPATH') OR die("No direct access allowed.");

$form_action = URL_BASE.'manage/passenger_generated_coupon_list/';
$back_action = URL_BASE.'manage/passenger_generated_coupon_list/';
?>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<!--<script src="<?php echo URL_BASE; ?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>-->
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>

<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle">
			<form method="get" class="form" name="manage_coupon" id="manage_coupon" action="<?php $form_action ?>">
				<table class="list_table1" border="0" width="100%" cellpadding="5" cellspacing="0">
					<tr>
						<td valign="top"><label><?php echo __('generate_id'); ?></label></td>
                        <td>
                            <div class="ser_input_field">
                                <input type="text" name="generate_id" style="width:185px;" id="generate_id" value="<?php echo isset($srch['generate_id']) ? trim($srch['generate_id']) : ''; ?>" />
                            </div>
                        </td>
                        <td valign="top"><label><?php echo __('from'); ?></label></td>
                        <td>
							<div class="ser_input_field">
								<input type="text" readonly  title="<?php echo __('select_datetime'); ?>" id="startdate" name="startdate" value="<?php echo isset($srch['startdate']) ? trim($srch['startdate']) : ''; ?>"  />
								<span id="startdate_error" class="error"></span>		 
							</div>						
                        </td>       
                        <td valign="top"><label>To</label></td>
                        <td>
							<div class="ser_input_field">
								<input type="text"  readonly  title="<?php echo __('select_datetime'); ?>" id="enddate" name="enddate" value="<?php echo isset($srch['enddate']) ? trim($srch['enddate']) : ''; ?>"  />
								<span id="enddate_error" class="error"></span>							
							</div>
                        </td>   
					</tr>
			</tr>
			<tr>
				<td valign="top"><label>&nbsp;</label></td>
				<td>
					<div class="new_button">
						<input type="submit" value="<?php echo __('button_search'); ?>" name="search_user" title="<?php echo __('button_search'); ?>" />
					</div>
					<div class="new_button">
						<input type="button" value="<?php echo __('button_cancel'); ?>" title="<?php echo __('button_cancel'); ?>" onclick="location.href = '<?php echo $back_action; ?>'" />
					</div>
				</td>
			 </tr>
        </table>
        
		<div class="over_all">
			<div class="widget" style="margin-bottom:0px !important;">
		<div class="title"><h6><?php echo $page_title; ?></h6>
		<div style="width:auto; float:right; margin: 0;"> <?php echo $page_title; ?></div>
		</div>
		</div>        
			<?php if($total_records > 0)
			{ 
				?>
					<div class= "overflow-block">
				<?php 
			} 
			?>
			<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
			<?php if ($total_records > 0) 
			{ 
				?>
					<thead>
						<tr >
							<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
							<td align="left" width="10%"><?php echo __('generate_id'); ?></td>
							<td align="left" width="10%"><?php echo __('total_coupons'); ?></td>
							<td align="left" width="8%"><?php echo __('used_coupons'); ?></td>
							<td align="left" width="15%"><?php echo __('start_date'); ?></td>
							<td align="left" width="15%"><?php echo __('expiry_date'); ?></td>
							<td align="left" width="15%"><?php echo __('generated_date'); ?></td>
							<td align="left" width="10%"><?php echo __('action_label'); ?></td>
						</tr>
					</thead>
                  <tbody>
				<?php

				$sno = $Offset; /* For Serial No */
				//print_r($all_user_list);
				foreach($generate_id_list as $value) 
				{				 
					//S.No Increment
					//==============
					$sno++;
					
					//For Odd / Even Rows
					//===================
					$trcolor=($sno%2==0) ? 'oddtr' : 'eventr';			 
					?>     

					<tr class="<?php echo $trcolor; ?>">
						<td align="center"><?php echo $sno; ?></td>
						<td align="center"><?php echo $value['generate_id']; ?></td>
						<td align="center"><?php echo $value['total_coupons']; ?></td>
						<td align="center"><?php echo $value['used_coupons']; ?></td>
						<td align="center"><?php echo $value['start_date']; ?></td>
						<td align="center"><?php echo $value['expiry_date']; ?></td>
						<td align="center"><?php echo $value['created_date']; ?></td>
						
						<?php if($value['total_coupons'] > $value['used_coupons']) {?>
						<td align="center"><?php echo '<a href='.URL_BASE.'manage/passenger_reprint_coupon?generate_id='.$value['generate_id'].' " title ="Print Coupon" class="btn btn-sm btn-info regenerate">Print Coupon</a>' ; ?></td>
						<?php }else{?>
						<td align="center">-</td>
						<?php } ?>
					</tr>
				<?php 
			} 
 		} 
		 
		//For No Records
		//==============
	    else
	    { 
			?>
				<tr>
					<td class="nodata"><?php echo __('no_data'); ?></td>
				</tr>
			<?php 
		} 
		?>
		</tbody>	
	</table>
	</div>

	<?php if ($total_records > 0) 
	{ 
		?></div><?php 
	} 
	?>
</form>
</div>
</div>
<div class="clr">&nbsp;</div>
<div class="pagination">
	<?php if($total_records > 0): ?>
	 <p><?php echo $pag_data->render(); ?></p>  
	<?php endif; ?> 
</div>
<div class="clr">&nbsp;</div>
 
</div>
</div>
<script>
$("#startdate").datetimepicker( 
{
	showTimepicker:true,
	showSecond: true,
	timeFormat: 'hh:mm:ss',
	dateFormat: 'yy-mm-dd',
	stepHour: 1,
	stepMinute: 1,
	stepSecond: 1,
	onSelect: function (selected) 
	{
		var dt = new Date(selected);
		dt.setDate(dt.getDate() + 1);
		$("#enddate").datepicker("option", "minDate", dt);
	}
});

$("#enddate").datetimepicker( 
{
	showTimepicker:true,
	showSecond: true,
	timeFormat: 'hh:mm:ss',
	dateFormat: 'yy-mm-dd',
	stepHour: 1,
	stepMinute: 1,
	stepSecond: 1,
	onSelect: function (selected) 
	{
		var dt = new Date(selected);
		dt.setDate(dt.getDate() - 1);
		$("#startdate").datepicker("option", "maxDate", dt);
	}
});

$(".regenerate").click(function(event){
	event.preventDefault();
	
	var url = $(this).attr('href');

	var win = window.open('about:blank', '_blank', "width=800,height=800");

	$.ajax({
        url : url,
        type : "POST",
      })
    .done(function(data) {
    	console.log(data);
    	var response = $.parseJSON(data);
    	if(response.status == 1)
    	{
      		win.location.href = response.redirect_url;
      		window.location.href = URL_BASE+'manage/passenger_generated_coupon_list';
    	}
    	else
    	{
    		win.close();
    	}	
    		
    })
    .fail(function(data) {
        console.log('fails');
        win.close();
    })
		
});
</script>

