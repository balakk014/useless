<?php defined('SYSPATH') OR die("No direct access allowed."); ?>
<?php $find_url = explode('/',$_SERVER['REQUEST_URI']);
$split = explode('?',$find_url[3]);  	
$list = $split[0];
?>
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo URL_BASE;?>public/js/transaction.js"></script>
<?php
//For search values
//=================
$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
$company_val = isset($srch["filter_company"]) ? $srch["filter_company"] :''; 
$startdate = isset($srch["startdate"]) ? $srch["startdate"] :date('Y-m-d 00:00:00'); 	
$enddate = isset($srch["enddate"]) ? $srch["enddate"] :date('Y-m-d H:i:s'); 	
$taxiid = isset($srch["taxiid"]) ? $srch["taxiid"] :'';
$passengerid = isset($srch["passengerid"]) ? $srch["passengerid"] :'';
$driver_id = isset($srch["driver_id"]) ? $srch["driver_id"] :'';
$manager_id = isset($srch["manager_id"]) ? $srch["manager_id"] :'';
$status_val = isset($srch["status"]) ? $srch["status"] :''; 							
$keyword = isset($srch["keyword"]) ? $srch["keyword"] :''; 
$s_date = isset($srch["startdate"]) ? 2:1; 	
$e_date = isset($srch["enddate"]) ? 2:1; 
$payment_type = isset($srch["payment_type"]) ? $srch["payment_type"] :''; 
$transaction_id = isset($srch["transaction_id"]) ? $srch["transaction_id"] :''; 
$form_action = '';
$form_action = URL_BASE.'transaction/admintransaction_list/'.$list.'/';
$back_action = URL_BASE.'transaction/admintransaction/'.$list.'/';



//For CSS class deefine in the table if the data's available
//===========================================================
$total_transaction=count($all_transaction_list);

$table_css=$export_excel_button="";
if($total_transaction>0)
{ 
	$table_css='class="table_border"'; 
	$startdate_export = isset($srch["startdate"]) ? $srch["startdate"] :'';
	$enddate_export = isset($srch["enddate"]) ? $srch["enddate"] :'';
	$export_excel_button='
        				<input type="button"  title="'.__('button_export').'" class="button" value="'.__('button_export').'" 
        				onclick="location.href=\''.URL_BASE.'transaction/export/'.$list.'/?filter_company='.$company_val.'&startdate='.$startdate_export.'&enddate='.$enddate_export.'&taxiid='.$taxiid.'&driver_id='.$driver_id.'&manager_id='.$manager_id.'&passengerid='.$passengerid.'&transaction_id='.$transaction_id.'\'" />
    				';
}

?>




</script>
<div class="container_content fl clr">
	<div class="cont_container mt15 mt10">
		<div class="content_middle"> 
        <form method="get" class="form" name="managedriver" id="managedriver" action="<?php echo $form_action; ?>" onsubmit="return validatetranaction_form();">
		<table class="list_table1" border="0" width="100%" cellspacing="0" cellpadding="5" >
				<tr>				
					<td valign="middlle"><label><?php echo __('company'); ?></label></td>
					<td valign="top">
						<div class="selector" id="uniform-user_type">
							<select class="select2" name="filter_company" id="filter_company" onchange="getcompanymanager(this.value),getcompanytaxi(this.value),getcompanydriver(this.value),getcompanypassengers(this.value)">
								
									<option value="All"><?php echo __('all_label'); ?></option>    
								<?php 
									foreach ($get_allcompany as $comapany_list) {
									$selected_status = ($company_val == $comapany_list['cid']) ? ' selected="selected" ' : " ";
								?>  
									<option value="<?php echo $comapany_list['cid']; ?>"  <?php echo $selected_status; ?> ><?php echo ucfirst($comapany_list['company_name']); ?></option>
									<?php }?>
							</select>
						</div>
						<div id="filter_company_error" class="error"></div>
					</td>  
					
				<td valign="middlle"><label><?php echo __('manager_name'); ?></label></td>
				<td id="manager_list">
					<div class="selector" id="uniform-user_type">
						<select name="manager_id" id="manager_id" onchange="getmanagertaxi(this.value),getmanagerdriver(this.value),getcompanypassengers(filter_company.value)">
							<?php if(count($managerlist) > 0) { ?>
							<option value="All"><?php echo __('all_label');?></option>
							<?php
							foreach($managerlist as $values) { 
								$managername = $values["name"].' '.$values["lastname"];
								$selected_status = ($manager_id == $values['id']) ? ' selected="selected" ' : " ";
							echo '<option value="'.$values["id"].'"'.$selected_status.'>'.ucfirst($managername).'</option>';
						 } } else { 
									  echo '<option value="">'.__('select_label').'</option>';
								  }?>
						</select>
					</div>
				</td>
										   
				<td valign="middlle"><label><?php echo __('taxi'); ?></label></td>
				<td id="taxi_list">
					<div class="selector" id="uniform-user_type">
						<select name="taxiid" id="taxiid" class="select2">
						<?php if(count($taxilist) > 0) { ?>
						<option value="All"><?php echo __('all_label');?></option>
						<?php
						foreach($taxilist as $values) { 
							$selected_status = ($taxiid == $values['taxi_id']) ? ' selected="selected" ' : " ";
						echo '<option value="'.$values["taxi_id"].'"'.$selected_status.'>'.$values["taxi_no"].'</option>';
						 } } else { 
									  echo '<option value="">'.__('select_label').'</option>';
								  }?>
						</select>
					</div>
				</td>
				
				<td valign="middlle"><label><?php echo __('driver_name'); ?></label></td>
				<td id="driver_list">
					<div class="selector" id="uniform-user_type">
						<select name="driver_id" id="driver_id">
							<?php if(count($driverlist) > 0) { ?>
							<option value="All"><?php echo __('all_label');?></option>
							<?php
							foreach($driverlist as $values) { 
								$drivername = $values["name"].' '.$values["lastname"];
								$selected_status = ($driver_id == $values['id']) ? ' selected="selected" ' : " ";
							echo '<option value="'.$values["id"].'"'.$selected_status.'>'.ucfirst($drivername).'</option>';
						 } } else { 
									  echo '<option value="">'.__('select_label').'</option>';
								  }?>
						</select>
					</div>
				</td>
				 </tr>  
				<tr>
					<td valign="middlle"><label><?php echo __('passenger_name'); ?></label></td>
					<td id="passenger_list">
						<div class="selector" id="uniform-user_type">
							<select name="passengerid" id="passengerid" class="select2">
							<?php if(count($passengerlist) > 0) { ?>
							<option value="All"><?php echo __('all_label');?></option>
							<?php
							foreach($passengerlist as $values) { 
								$passengername = ucfirst($values["name"]).'-'.ucfirst($values["company_name"]);
								$selected_status = ($passengerid == $values['id']) ? ' selected="selected" ' : " ";
							echo '<option value="'.$values["id"].'"'.$selected_status.'>'.$passengername.'</option>';
							 } } else { 
										  echo '<option value="">'.__('select_label').'</option>';
									  }?>
							</select>
						</div>
					</td>  
					<td valign="middle"><label><?php echo __('from_date'); ?></label></td>
                        <td valign="top">
						<div class="new_input_field_transaction">
								  <input type="text"  readonly title="<?php echo __('select_datetime'); ?>" id="startdate" name="startdate" value="<?php echo $startdate;?>"  />
						 <span id="startdate_error" class="error"></span>		 
						 </div>
						
                        </td>       

                        <td valign="middle"><label><?php echo __('end_date'); ?></label></td>
                        <td valign="top">
						<div class="new_input_field_transaction">
								  <input type="text"  readonly title="<?php echo __('select_datetime'); ?>" id="enddate" name="enddate" value="<?php echo $enddate;?>"  />
								  						 <span id="enddate_error" class="error"></span>								

						</div>
                        </td>   
  
			<td valign="middle"><label><?php echo __('transactionid_label'); ?></label></td>
                        <td valign="top">
						<div class="new_input_field_transaction">
								  <input type="text"  title="<?php echo __('enter_the_transaction_id'); ?>" id="transaction_id" name="transaction_id" value="<?php echo $transaction_id;?>"  />
						 <span id="startdate_error" class="error"></span>		 
						 </div>
						
                        </td>   
                 </tr>
				<tr>
					<td valign="middlle"><label><?php echo __('payment_type'); ?></label></td>
					<td id="payment_list">
						<div class="selector" id="uniform-user_type">
							<select name="payment_type" id="payment_type" class="select2">
							<?php if(count($gateway_details) > 0) { ?>
							<option value="All"><?php echo __('all_label');?></option>
							<?php
							foreach($gateway_details as $values) { 
								$pay_mod_name = $values["pay_mod_name"];
								$selected_status = ($payment_type == $values['pay_mod_id']) ? ' selected="selected" ' : " ";
							echo '<option value="'.$values["pay_mod_id"].'"'.$selected_status.'>'.ucfirst($pay_mod_name).'</option>';
							 } } else { 
										  echo '<option value="">'.__('select_label').'</option>';
									  }?>
							</select>
						</div>
					</td>  

                        <td>&nbsp;</td>
                        <td>&nbsp;</td>  
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                 </tr>
				
                 <tr>
                        <td valign="top"><label>&nbsp;</label></td>
                        <td>
                            <div class="button brownB">
                                <input type="submit" value="<?php echo __('button_search'); ?>" name="search_user" title="<?php echo __('button_search'); ?>" />
                            </div>
                            <div class="button blueB">
                                <input type="button" value="<?php echo __('button_cancel'); ?>" title="<?php echo __('button_cancel'); ?>" onclick="location.href = '<?php echo $back_action; ?>'" />
                            </div>
                        </td>
                 </tr>
                </table>
		<?php if($list !='rejected' ) { ?>		
                <div class="widget chartWrapper">
            <div class="title"><img src="<?php echo IMGPATH; ?>/icons/dark/stats.png" alt="" class="titleIcon" /><h6><?php echo __('chart'); ?></h6></div>
            <div class="body">
		<?php 
		if(count($grpahdata)>0)
		{
			foreach($grpahdata as $gdata)
			{
				$fare[] = $gdata['amount'];
				//echo date( "d",strtotime($gdata['createdate']))."<br>";
				$month[] = "'".date( "d",strtotime($gdata['createdate']))." ".date( "M",strtotime($gdata['createdate']))."'";
			}
				if($fare){
					$fare = implode(",",$fare);
				}
				if($month){
					$month = implode(",",$month);
				}
		?>
		
		 <div class="chart" id="container"></div>
			</div>
<?php } else { echo "<div class='nodata' style='padding:0px 10px 8px 0;'>".__('no_data')."</div></div>"; } ?>
		
        </div>
		<?php } ?>
                		<div class="widget">
		<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo $page_title; ?></h6>
		<div style="width:auto; float:right; margin: 4px 3px;">
		<div class="button greyishB"> <?php echo $export_excel_button; ?></div>                       

		</div>
				
		</div>


<?php if($total_transaction > 0){ ?>
<div class= "overflow-block">
<?php } ?>		
<table cellspacing="1" cellpadding="10" width="100%" align="center" class="sTable responsive">
<?php if($total_transaction > 0){ ?>
<thead>
	<tr>
		<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
		<?php if($list != 'rejected') { ?>
		<td align="left" width="15%"><?php echo __('transactionid_label'); ?></td>
		<td align="left" width="10%"><?php echo __('payment_type'); ?></td>
		<td align="left" width="10%"><?php echo __('track_id'); ?></td>
		<?php } ?>
		<td align="left" width="10%"><?php echo __('passenger_name'); ?></td>
		<td align="left" width="10%"><?php echo ucfirst(__('driver_name')); ?></td>
		<td align="left" width="15%"><?php echo __('companyname'); ?></td>
		<td align="left" width="10%"><?php echo __('journey_date'); ?></td>
		<td align="left" width="10%"><?php echo __('Current_Location'); ?></td>
		<td align="left" width="10%"><?php echo __('Drop_Location'); ?></td>
		<td align="left" width="10%"><?php echo __('No_Passengers'); ?></td>
		<?php if($list != 'rejected') { ?>
		<td align="left" width="10%"><?php echo __('admin_commision'); ?></td>
		<td align="left" width="10%"><?php echo __('company_commision'); ?></td>
		<td align="left" width="10%"><?php echo __('package_type'); ?></td>
		<?php } ?>
		<?php if($list != 'rejected') { ?>
		<?php if($list != 'cancelled') { ?>
		<td align="left" width="10%"><?php echo __('distance_km'); ?></td>
		<td align="left" width="10%"><?php echo __('nightfare'); ?></td>
		<?php } ?>

		<td align="left" width="10%"><?php if($list == 'cancelled') { echo __('cancel_fare').'('.CURRENCY.')'; } else { echo __('trip_total_fare').'('.CURRENCY.')'; }?></td>

		<?php if($list != 'success') { ?>
		<td align="left" width="10%"><?php echo __('travel_status'); ?></td>
		<?php } ?>
		<!--<td align="left" width="10%"><?php echo __('rating_points');?></td>
		<td align="left" width="10%"><?php echo __('comments');?></td>-->
		<?php } 
		else {	?>
		<td align="left" width="10%"><?php echo __('travel_status');?></td>
		<td align="left" width="10%"><?php echo __('reason');?></td>
		<?php } ?>

	</tr>
</thead>
<tbody>	
		<?php
		/* For Serial No */
		$sno=$Offset; 
		$totalfare="";
		 foreach($all_transaction_list as $listings) { 

		 //S.No Increment
		 //==============
		 $sno++;
        
         //For Odd / Even Rows
         //===================
         $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';  
		 
        ?>     

		<tr class="<?php echo $trcolor; ?>">
			<td><?php echo $sno; ?></td>
			<?php if($list != 'rejected') { ?>
			<td>
				<?php if($listings['transaction_id'] != "")
				{?>
				<a href="<?php echo URL_BASE.'transaction/transaction_details/'.$listings['passengers_log_id'];?>"><?php echo $listings['transaction_id']; ?></a>
				<?php } else { ?>
				- <?php } ?></td>
			<td><?php if($listings['payment_type'] == 2) { echo 'Credit Card Using Paypal'; } else { echo 'Cash'; } ?></td>
			<td><a href="<?php echo URL_BASE.'transaction/transaction_details/'.$listings['passengers_log_id'];?>"><?php echo $listings['passengers_log_id']; ?></a></td>
			<?php } ?>

			
			<td><?php echo ucfirst($listings['passenger_name']); ?></td>
			<td><a href="<?php echo URL_BASE.'manage/driverinfo/'.$listings['driver_id'];?>"><?php echo wordwrap(ucfirst($listings['driver_name']),30,'<br/>',1); ?></a></td>
			<td>
				<a href="<?php echo URL_BASE.'manage/companydetails/'.$listings['userid'];?>">
				<?php  /* if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/uploads/company/'.$listings['userid'].'.png')){  ?> 
					<img width="32" height="32" src="<?php echo URL_BASE.COMPANY_IMG_IMGPATH.$listings['userid'].'.png';?>"/>
				<?php }else{ ?>
					<img width="32" height="32"  src="<?php echo URL_BASE;?>public/images/company_noimage.png"/>
				<?php }  */ ?>				
			<?php echo ucfirst($listings['company_name']); ?></a></td>
			<td><?php echo $listings['createdate'];?></td>
			<td><?php echo $listings['current_location'];?></td>
			<td><?php echo $listings['drop_location'];?></td>
			<td><?php echo $listings['no_passengers'];?></td>
			<?php if($list != 'rejected') { ?>		
			<td><?php echo $listings['admin_amount'];?></td>
			<td><?php echo $listings['company_amount'];?></td>

			<td><?php if($listings['trans_packtype'] == 'T' ) { echo __('transaction_based_commission'); } else if($listings['trans_packtype'] == 'P' ) { echo __('package_based_commission'); } else if($listings['trans_packtype'] == 'N' ) { echo __('package_based_no_commission'); } ?> </td>

			<?php } ?>
			<?php if($list != 'rejected') { ?>
			<?php if($list != 'cancelled') { ?>
			<td><?php if($listings['distance'] == 0) { echo '-'; } else { echo round($listings['actual_distance'],2);}?></td>
			<td><?php if($listings['nightfare'] == 0) { echo '-'; } else { echo round($listings['nightfare'],2);}?></td>
			<?php } ?>
			
			<td><?php if($listings['fare'] == 0) { echo '-'; } else { echo round($listings['fare'],2);}?></td>

			<?php if($list != 'success') { ?>
			<td><?php if($listings['travel_status'] == 0) { echo __('not_completed'); } else if($listings['travel_status'] == 1) { echo __('completed'); } else if($listings['travel_status'] == 2) { echo __('inprogress'); } else if($listings['travel_status'] == 3) { echo __('start_to_pickup'); } else if($listings['travel_status'] == 4) { echo __('cancel_by_passenger'); } ?> </td>
			<?php } ?>

			<!--<td><?php if($listings['rating'] == 0) { echo '-'; } else { echo $listings['rating']; }?></td>
			<td><?php echo $listings['comments']; ?></td>-->
			<?php }
			else { ?>
			<td><?php if($listings['driver_reply'] == 'C') { echo __('cancelled_by_driver'); } else { echo __('rejected_by_driver'); }?></td>
			<td><?php if($listings['driver_comments'] == '') { echo '-'; } else { echo $listings['driver_comments']; }?></td>	
			<?php } ?>
		</tr>
		<?php
			if($list != 'rejected') { 
				$totalfare +=$listings['fare'];
			}

			} ?>
		<?php if($list != 'rejected') { ?>
		<tr>
			<?php if($list != 'cancelled') { ?>	
			<td></td>
			<?php } ?>
			<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
			<td></td><td></td><td></td>
			<?php if($list != 'success') { ?>
			<td></td>
			<?php } ?>

			<?php if($list == 'success') { ?>
			<td></td>
			<?php } ?>

			<td></td><td><?php echo __('total').'('.CURRENCY.')';?></td><td><?php echo $totalfare; ?></td>

			<?php if($list == 'all') { ?>
			<td></td>
			<?php } ?>

		</tr>
		<?php } 
	
 		 } 
		 
		//For No Records
		//==============
	     else{ ?>
       	<tr>
        	<td class="nodata"><?php echo __('no_data'); ?></td>
        </tr>
		<?php } ?>
		</tbody>
</table>
<?php if ($total_transaction > 0) { ?>
</div>
<?php } ?>
</form>
</div>
</div>
<div class="clr">&nbsp;</div>
<div class="pagination">
		<?php if($total_transaction > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

</div>
</div>
       
<script type="text/javascript" language="javascript">

//For Delete the users
//=====================
function frmdel_user(userid)
{
   var answer = confirm("<?php echo __('delete_alert2');?>");
    
	if (answer){
        window.location="<?php echo URL_BASE;?>admin/delete/"+userid;
    }
    
    return false;  
}  
function frmblk_user(userid,status)
{   
    window.location="<?php echo URL_BASE;?>admin/blkunblk/"+userid+"/"+status;    
    return false;  
}  


$(document).ready(function(){

$("#startdate").datetimepicker( {
showTimepicker:true,
showSecond: true,
timeFormat: 'hh:mm:ss',
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
maxDateTime : new Date("<?php echo date('Y m d,H:i:s'); ?>"),
stepSecond: 1
} );

$("#enddate").datetimepicker( {
showTimepicker:true,
showSecond: true,
timeFormat: 'hh:mm:ss',
dateFormat: 'yy-mm-dd',
stepHour: 1,
stepMinute: 1,
maxDateTime : new Date("<?php echo date('Y m d,H:i:s'); ?>"),
stepSecond: 1
} );
} );
function validatetranaction_form()
{
	valid = true;
	var filter_company = $('#filter_company').val();
	var startdate = $('#startdate').val();
	var enddate = $('#enddate').val();
	if(filter_company =="")
	{
		$('#filter_company_error').html("<?php echo __('select_company');?>");
		$('#filter_company').focus();
		return false;		
	}	
	else if(startdate =="")
	{
		$('#filter_company_error').html('');
		$('#startdate_error').html("<?php echo __('select_company');?>");
		$('#startdate').focus();
		return false;		
	}	
	else if(enddate =="")
	{
		$('#startdate_error').html('');
		$('#enddate_error').html("<?php echo __('select_company');?>");
		$('#enddate').focus();
		return false;		
	}
		return true;	
}
</script>
<?php 
//echo $fare;
$milliseconds = strtotime($startdate) * 1000;
//echo $startdate;
if(isset($_GET['startdate']) && isset($_GET['startdate'])){

	if($_GET['startdate'] !='' && $_GET['startdate'] !='')
	{
		$text = __('transactions').' '.__('from').' '.$startdate.' '.__('to').' '.$enddate;
	}
	else
	{
		$text = __('transactions');	
	}

}else{
	$text = __('transactions');
}
if(count($grpahdata)>0)
{

	if($list != 'rejected') { ?>
<script type="text/javascript">
	var startdate = $('#startdate').val();
	if(startdate != '')
	{
	var temp = new Array();
	temp = startdate.split("-");
	var year = temp[0];
	var month = temp[1];
	var dates = temp[2].substring(0,2);
	var month = month-1;
	}

$(function () {
        $('#container').highcharts({
		title: {
			text: '<?php echo $text;?>',
			x: -20 //center
		},
		subtitle: {
			text: '',
			x: -20 //center
		},
		xAxis: {
			categories: [<?php echo $month;?>]
		},
		yAxis: {
			title: {
				text: 'Amount (Rs)'
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]
		},
		tooltip: {
			valueSuffix: ''
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		},
		series: [{
			name: 'Transaction',
			data: [<?php echo $fare;?>]
		}]
	});
	
    });
</script>
		<?php }
} ?>
<script src="<?php echo SCRIPTPATH; ?>highcharts.js"></script>
