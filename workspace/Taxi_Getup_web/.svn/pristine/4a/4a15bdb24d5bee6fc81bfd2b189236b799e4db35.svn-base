<?php defined('SYSPATH') OR die("No direct access allowed."); 
$select_pack_id ='';  ?>

<form name="upgradecompany_form" class="form" id="upgradecompany_form" action="" method="post" enctype="multipart/form-data" onsubmit="return upgrade_validation();">
	<div id="show_package" class="admin_show_package" style="display:block"> 
          
		<h2 class="tab_sub_tit"><?php echo __('select_package'); ?></h2>
		<?php 
			$i = 1;
			$package_count = count($package_details);
			foreach($package_details as $package_list) { ?>
				<p>
					<input type="radio" name="pack" id="pack_<?php echo $package_list['package_id']; ?>" value="<?php echo $package_list['package_id']; ?>" <?php if($select_pack_id){ echo 'CHECKED'; } elseif ($i == 1) { echo 'CHECKED'; }?>/>
					<strong><?php echo wordwrap(ucfirst($package_list['package_name']),30,'<br/>',1); ?></strong>
					<a href="javascript:;" id="view_<?php echo $package_list['package_id']; ?>" onclick="show_detail('<?php echo $i; ?>','<?php echo $package_count; ?>','<?php echo $package_list['package_id']; ?>')" title="<?php echo __('details'); ?>"><?php echo '('.__('details').')'; ?></a>
				</p>
                                
				<div class="upgrate_cmppackout" id="pack_det_<?php echo $i; ?>" style="display:none;">
                                      <div class="admin_show_packageinner">
					<table border="0" class="sTable responsive upgrate_cmppack" cellpadding="5" cellspacing="0">      
						<tr>
							<td width="40%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('package_name').' :'.'</h1>'; ?></td>
							<td><?php echo wordwrap(ucfirst($package_list['package_name']),30,'<br/>',1); ?>
							<input type="hidden" name="pack_id" id="pack_id" value=""/></td>
						</tr>
						
						<tr>
							<td width="40%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('package_description').' :'.'</h1>'; ?></td>
							<td><?php echo wordwrap(ucfirst($package_list['package_description']),30,'<br/>',1); ?></td>
						</tr>
						
						<tr>
							<td width="40%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('package_price').' :'.'</h1>'; ?></td>
							<td><?php echo CURRENCY.' '.$package_list["package_price"];?></td>
						</tr>
						
						<tr>
							<td width="30%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('no_of_taxi').' :'.'</h1>'; ?></td>
							<td><?php echo $package_list["no_of_taxi"];?></td>
						</tr>
						
						<tr>
							<td width="40%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('no_of_driver').' :'.'</h1>'; ?></td>
							<td><?php echo $package_list["no_of_driver"];?></td>
						</tr>
													
						<tr>
							<?php if($package_list['package_id']==5){ ?>
							<td width="30%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('no_of_days_expire').' :'.'</h1>'; ?></td>
							<td><input type="text" name="expire_days" id="exp_day" style="float: left; width: 20%;" value=""/>
							<div  class="errorvalid" id='err_exp'></div>
								</td>
							
							<?php } else { ?>
							<td width="40%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('no_of_days_expire').' :'.'</h1>'; ?></td>
							<td><?php echo $package_list["days_expire"];?>
							
							<?php } ?>
							
						</tr>
						
						<tr>
							<td width="40%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('package_type').' :'.'</h1>'; ?></td>
							<td><?php if($package_list['package_type'] == 'T' ) { echo __('transaction_based_commission'); } else if($package_list['package_type'] == 'P' ) { echo __('package_based_commission'); } else if($package_list['package_type'] == 'N' ) { echo __('package_based_no_commission'); } ?></td>
						</tr>
						
						<tr>
							<td width="40%" valign="top">
							<?php echo '<h1 style="font-size:bold !important;">'.__('driver_tracking').' :'.'</h1>'; ?></td>
							<td><?php if($package_list['driver_tracking'] == 'N' ) { echo __('no'); } else { echo __('yes'); } ?></td>
						</tr>
					</table>

                                      </div>
					
				</div>
				
		   <?php 
		   $i++;
			}
		?> 
		<table border="0" cellpadding="5" cellspacing="0" width="100%">         
			<tr>				
				<td colspan="" class="star">*<?php echo __('required_label'); ?></td>
			</tr>   
			<tr>				
				<td colspan="">
					
					<div class="new_button">     <input type="button" value="<?php echo __('button_back'); ?>" onclick="window.history.go(-1)" /></div>
                                        <div class="new_button">  <input type="submit"  value="<?php echo __('submit' );?>" name="submit_addcompany" title="<?php echo __('submit' );?>" /></div>
					<div class="new_button">   <input type="reset" value="<?php echo __('button_reset'); ?>" title="<?php echo __('button_reset'); ?>" /></div>
					<div class="clr">&nbsp;</div>
				</td>
			</tr> 
		</table>
	</div>
       
</form>


<script>

function upgrade_validation()
{
	var pack=$('#pack_id').val();
	if(pack==5){
			var exp_days=document.upgradecompany_form.expire_days.value.trim();
			if(exp_days>0){
				document.upgradecompany_form.submit();
					return true;
			}else{
				
					$("#err_exp").html("Enter Expire days");
					return false;
			}

	}else{

            return true;

	}

}

function show_detail(id,tot,pack_id)
{
	$('#pack_id').val(pack_id);
	for(var i=1; i <= tot; i++)
	{  
		var isVisible = $("#pack_det_"+i).is(":visible");
		
		if(id==i)
		{
			$("#pack_det_"+i).slideToggle("show");
		}
		else
		{	
			if(isVisible)
			{
				$("#pack_det_"+i).slideToggle("hide");
			}
		}
	}
} 


</script>
