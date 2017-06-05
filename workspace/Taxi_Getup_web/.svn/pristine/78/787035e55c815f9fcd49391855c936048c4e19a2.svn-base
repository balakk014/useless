<?php defined('SYSPATH') OR die("No direct access allowed."); ?>
<?php 
$split = explode('/',$_SERVER['REQUEST_URI']); 
$upgrade_userid = $split[3];
?>
<!--- Package Information -->
<div class="container_content fl clr">
    <div class="cont_container mt15 mt10">
       <div class="content_middle"> 
<form name="purchase_form" id="purchase_form" action="" class="form" method="post" >
			<p><input type="hidden" name="upgrade_type" value='1' checked></p>
			<!--<p><input type="radio" name="upgrade_type" value='2'><?php echo __('renew'); ?></p>-->
                        <p style="margin-left:20px;margin-top:20px;"><?php echo __('select_package'); ?></p>
			<?php 
			$i = 1;
			$package_count = count($package_details);
			foreach($package_details as $package_list) { ?>

                                <p>
                                <input type="radio" name="pack" id="pack_<?php echo $package_list['package_id']; ?>" value="<?php echo $package_list['package_id']; ?>" <?php if($current_packageid == $package_list['package_id']){ echo 'CHECKED'; } elseif ($i == 1) { echo 'CHECKED'; }?>/>
                                <strong><?php echo wordwrap(ucfirst($package_list['package_name']),30,'<br/>',1); ?></strong>
                                <a href="javascript:;" id="view_<?php echo $package_list['package_id']; ?>" onclick="show_detail('<?php echo $i; ?>','<?php echo $package_count; ?>')" title="<?php echo __('details'); ?>"><?php echo '('.__('details').')'; ?></a>
                                </p>
                                <div class="clr mt10" id="pack_det_<?php echo $i; ?>" style="display:none;padding:20px;">
                                <table border="0" cellpadding="5" cellspacing="0" width="100%">      
				<tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('package_name').' :'.'</h1>'; ?></td>
                                <td><?php echo wordwrap(ucfirst($package_list['package_name']),30,'<br/>',1); ?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('package_description').' :'.'</h1>'; ?></td>
                                <td><?php echo wordwrap(ucfirst($package_list['package_description']),30,'<br/>',1); ?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('package_price').' :'.'</h1>'; ?></td>
                                <td><?php echo $package_list["package_price"];?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('no_of_taxi').' :'.'</h1>'; ?></td>
                                <td><?php echo $package_list["no_of_taxi"];?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('no_of_driver').' :'.'</h1>'; ?></td>
                                <td><?php echo $package_list["no_of_driver"];?></td>
                                </tr>                                
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('no_of_days_expire').' :'.'</h1>'; ?></td>
                                <td><?php echo $package_list["days_expire"];?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('package_type').' :'.'</h1>'; ?></td>
                                <td><?php if($package_list['package_type'] == 'T' ) { echo __('transaction_based_commission'); } else if($package_list['package_type'] == 'P' ) { echo __('package_based_commission'); } else if($package_list['package_type'] == 'N' ) { echo __('package_based_no_commission'); } ?></td>
                                </tr>
                                <tr>
                                <td width="20%" valign="top">
                                <?php echo '<h1 style="font-size:bold !important;">'.__('driver_tracking').' :'.'</h1>'; ?></td>
                                <td><?php if($package_list['driver_tracking'] == 'N' ) { echo __('no'); } else { echo __('yes'); } ?></td>
                                </tr>
                                </table>
                                </div>
                               <?php 
                               $i++;
                               } ?> 



		<div class="fl clr">			

	 <table width="100%" cellpadding="5" cellspacing="0" border="0" class="fl clr" style="margin-left:15px;">		
			 
	   <tr>
			<td>&nbsp;<input type="hidden" name="upgrade_userid" id="upgrade_userid" value="<?php echo $upgrade_userid; ?>" ></td>
                        <td colspan="">
                            <br />
                    
                           <div class="button greenB">  <input type="submit" value="<?php echo __('submit' );?>" name="submit_upgradepackage" title="<?php echo __('submit' );?>" /></div>
                            <div class="clr">&nbsp;</div>
                        </td>
                    </tr> 			
			
			</table>    
                    </div>                				
			</div>

        </form>
<script type="text/javascript">
function show_detail(id,tot)
{
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

