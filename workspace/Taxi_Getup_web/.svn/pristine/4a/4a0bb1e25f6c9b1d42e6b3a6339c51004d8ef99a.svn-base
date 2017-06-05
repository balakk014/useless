<?php defined('SYSPATH') OR die("No direct access allowed."); ?>
<?php $find_url = explode('/',$_SERVER['REQUEST_URI']);
$split = explode('?',$find_url[2]);  	
$list = $split[0];
$totalfare = "";
$adminfare = "";
$companyfare = "";
?> 
<link rel="stylesheet" href="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
<?php /* <script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-1.5.1.min.js"></script> */ ?>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-1.8.11.custom/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="<?php echo URL_BASE;?>public/js/datetimehrspicker/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo URL_BASE;?>public/js/transaction.js"></script>
<?php
//For search values
//=================
$user_type_val = isset($srch["user_type"]) ? $srch["user_type"] :''; 
$company_val = isset($srch["filter_company"]) ? $srch["filter_company"] :''; 
$startdate = isset($srch["startdate"]) ? $srch["startdate"] : date('Y-m-d 00:00:00');
$enddate = isset($srch["enddate"]) ? $srch["enddate"] : convert_timezone('now',$_SESSION['timezone']);
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
$form_action = URL_BASE.'admin/account_report_list/';
$back_action = URL_BASE.'admin/account_report/';



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
        				onclick="location.href=\''.URL_BASE.'transaction/accountexport/?filter_company='.$company_val.'&startdate='.$startdate_export.'&enddate='.$enddate_export.'&taxiid='.$taxiid.'&driver_id='.$driver_id.'&manager_id='.$manager_id.'&passengerid='.$passengerid.'&transaction_id='.$transaction_id.'\'" />
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
						<div class="selector ser_input_field" id="uniform-user_type">
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
					
				<?php /* <td valign="middlle"><label><?php echo __('manager_name'); ?></label></td>
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
				</tr>
				<tr>
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
				
				<td valign="middlle"><label><?php echo __('passenger_name'); ?></label></td>
					<td id="passenger_list">
						<div class="selector" id="uniform-user_type">
							<select name="passengerid" id="passengerid" class="select2">
							<?php if(count($passengerlist) > 0) { ?>
							<option value="All"><?php echo __('all_label');?></option>
							<?php
							foreach($passengerlist as $values) { 
								$passengername = $values["name"];
								$selected_status = ($passengerid == $values['id']) ? ' selected="selected" ' : " ";
							echo '<option value="'.$values["id"].'"'.$selected_status.'>'.ucfirst($passengername).'</option>';
							 } } else { 
										  echo '<option value="">'.__('select_label').'</option>';
									  }?>
							</select>
						</div>
					</td> */ ?>
					<td valign="middlle"><label><?php echo __('payment_type'); ?></label></td>
					<td id="payment_list">
						<div class="selector ser_input_field" id="uniform-user_type">
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
			</tr>
			<tr>
			<td valign="middle"><label><?php echo __('from_date'); ?></label></td>
                        <td valign="top">
						<div class="ser_input_field">
								  <input type="text"  readonly title="<?php echo __('select_datetime'); ?>" id="startdate" name="startdate" value="<?php echo $startdate;?>"  />
						 <span id="startdate_error" class="error"></span>		 
						 </div>	
                        </td>       
                        <td valign="middle"><label><?php echo __('end_date'); ?></label></td>
                        <td valign="top">
						<div class="ser_input_field">
								  <input type="text"  readonly title="<?php echo __('select_datetime'); ?>" id="enddate" name="enddate" value="<?php echo $enddate;?>"  />
								  <span id="enddate_error" class="error"></span>								
						</div>
                        </td>   
			<?php /*<td valign="middle"><label><?php echo __('transactionid_label'); ?></label></td>
                        <td valign="top">
						<div class="new_input_field_transaction">
								  <input type="text"  title="<?php echo __('enter_the_transaction_id'); ?>" id="transaction_id" name="transaction_id" value="<?php echo $transaction_id;?>"  />
						 <span id="startdate_error" class="error"></span>		 
						 </div>
						
                        </td>   */ ?>
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
		
                		<?php /*<div class="widget">
		<div class="title"><img src="<?php echo IMGPATH; ?>icons/dark/frames.png" alt="" class="titleIcon" /><h6><?php echo $page_title; ?></h6>
		<div style="width:auto; float:right; margin: 0 3px;">
		<div class="button greyishB"> <?php echo $export_excel_button; ?></div>                       

		</div>
				
		</div> */ ?>

<?php if($total_transaction > 0){ ?>
<?php /* <thead>
	<tr>
		<td align="left" width="5%"><?php echo __('sno_label'); ?></td>
		<td align="left" width="15%"><?php echo __('transactionid_label'); ?></td>
		<td align="left" width="10%"><?php echo __('payment_type'); ?></td>
		<td align="left" width="10%"><?php echo __('track_id'); ?></td>
		<td align="left" width="15%"><?php echo __('companyname'); ?></td>
		<td align="left" width="10%"><?php echo __('journey_date'); ?></td>
		<td align="left" width="10%"><?php echo __('admin_commision'); ?></td>
		<td align="left" width="10%"><?php echo __('company_commision'); ?></td>
		<?php /*<td align="left" width="10%"><?php echo __('package_type'); ?></td> * ?>
		<td align="left" width="10%"><?php echo __('trip_total_fare'); ?></td>
		<td align="left" width="10%"><?php echo __('equivalent_to_usd').CURRENCY_FORMAT; ?></td>
	</tr>
</thead> 
<tbody>	*/ ?>
		<?php
		/* For Serial No */
		$sno=$Offset; 
		$totalfare = "";
		$adminfare = "";
		$companyfare = "";
		$per_fare="";
		 foreach($all_transaction_list as $listings) { 

		 /*//S.No Increment
		 //==============
		 $sno++;
         //For Odd / Even Rows
         //===================
         $trcolor=($sno%2==0) ? 'oddtr' : 'eventr';   ?>     
		<tr class="<?php echo $trcolor; ?>">
			<td><?php echo $sno; ?></td>
			<td><?php if($listings['transaction_id'] != "")
				{?>
				<a href="<?php echo URL_BASE.'transaction/transaction_details/'.$listings['passengers_log_id'];?>"><?php echo $listings['transaction_id']; ?></a>
				<?php } else { ?>
				- <?php } ?>
			</td>
			<td><?php if($listings['payment_type'] == 2) { echo 'Credit Card Using Paypal'; } else { echo 'Cash'; } ?></td>
			<td><a href="<?php echo URL_BASE.'transaction/transaction_details/'.$listings['passengers_log_id'];?>"><?php echo $listings['passengers_log_id']; ?></a></td>
			<td>
				<a href="<?php echo URL_BASE.'manage/companydetails/'.$listings['userid'];?>">
				<?php  if(file_exists($_SERVER["DOCUMENT_ROOT"].'/public/uploads/company/'.$listings['userid'].'.png')){  ?> 
					<img width="32" height="32" src="<?php echo URL_BASE.COMPANY_IMG_IMGPATH.$listings['userid'].'.png';?>"/>
				<?php }else{ ?>
					<img width="32" height="32"  src="<?php echo URL_BASE;?>public/images/company_noimage.png"/>
				<?php } ?>				
			<?php echo '<br/>'.ucfirst($listings['company_name']); ?></a></td>
			<td><?php echo $listings['createdate'];?></td>
			<td><?php $adminfare +=$listings['admin_amount'];  echo $listings['admin_amount'];?></td>
			<td><?php $companyfare +=$listings['company_amount']; echo $listings['company_amount'];?></td>
			<?php /*
			<td><?php if($listings['trans_packtype'] == 'T' ) { echo __('transaction_based_commission'); } else if($listings['trans_packtype'] == 'P' ) { echo __('package_based_commission'); } else if($listings['trans_packtype'] == 'N' ) { echo __('package_based_no_commission'); } ?> </td>
			*/?>	

			<?php  if($listings['fare'] == 0) { /*echo '-';*/ $convet_amt=0; } else { 
				//$company_currency = findcompany_currency($listings['cid']);
				$company_currency = $listings['currency_symbol'];
				 $company_currency.round($listings['fare'],2); } ?>
				<?php
					if($listings['fare'] == 0) { /*echo '-';*/ } else {
						//$company_currency_format = findcompany_currencyformat($listings['cid']);
						$company_currency_format = $listings['currency_code'];
						$convet_amt = currency_conversion($company_currency_format,$listings['fare']);
						round($convet_amt,2);
					}
				?>
		
		<?php
			$totalfare +=$convet_amt;

			} ?>
		<?php /* <tr>

			<td></td><td></td><td></td>
			<td></td><td></td><td></td>
			<td></td><td></td><td><?php echo __('total').'('.CURRENCY.')';?></td><td><?php echo $totalfare; ?></td>
		</tr> */ ?>
		<?php
	
 		 } 
		//For No Records
		//==============
	     else{ /* <div class="nodata"><?php echo __('no_data'); ?></div> */ ?>
       	
        	
        
		<?php } ?>	
</form>

                    <div class="over_all">
<?php if($totalfare>0) { ?>
<div class="account_report" style="width:100%; clear:both;float:left;"><?php echo __('total_revenue').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.CURRENCY.round($totalfare,2); ?></div>
<div class="account_report" style="width:100%; clear:both;float:left;"><?php echo __('total_trips').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.count($all_transaction_list); ?></div>
<div class="account_report" style="width:100%; clear:both;float:left;"><?php $per_fare=$totalfare/count($all_transaction_list); echo __('fare_per_trip').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.CURRENCY.number_format($per_fare, 2, '.', ''); ?></div>
<?php } ?>
                    </div>

<?php /*
<div class="pagination">
		<?php if($total_transaction > 0): ?>
		 <p><?php echo $pag_data->render(); ?></p>  
		<?php endif; ?> 
  </div>
  <div class="clr">&nbsp;</div>

</div> */ ?>
<div class="over_all">
<?php if($list !='rejected' ) { ?>		
                <div class="widget chartWrapper">
            <div class="title"><h6><?php echo __('chart'); ?></h6></div>
            <div class="body">
		<?php 
		$current_year=date ('M');
		$company_name="";
		if(isset($_GET['startdate']) && isset($_GET['enddate']) ){
		$sdate=trim(Html::chars($_GET['startdate']));
		$edate=trim(Html::chars($_GET['enddate']));
		$for_date = Commonfunction::getDateTimeFormat($sdate,1)." to ".Commonfunction::getDateTimeFormat($edate,1);
		if($_GET['filter_company'] ==''){
			$company_name="All";
		}else{
			$company_name=$_GET['filter_company'];
		}
	}else{
		$for_date=$current_year;
	}
		if(count($grpahdata)>0)
		{ 
			foreach($grpahdata as $gdata)
			{
				$trips[]= $gdata['trips'];
				$fare[] = $gdata['amount'];
				//echo date( "d",strtotime($gdata['createdate']))."<br>";
				$month[] = "'".date( "d",strtotime($gdata['createdate']))." ".date( "M",strtotime($gdata['createdate']))."'";
			}
				if($trips != NULL){
					$trips = implode(",",$trips);
				}
				if($fare){
					$fare = implode(",",$fare);
				}
				if($month){
					$month = implode(",",$month);
				}
		?>
		<script src="<?php echo SCRIPTPATH; ?>highcharts.js"></script>
		 <div id="total_trips_details" style="min-width: 400px; height: 400px; margin: 0 auto ;display:block;">
	<script>
		$('#total_trips_details').highcharts({			
			chart: {
				shortMonths:true,
				zoomType: 'xy'
			},
			title: {
					text: 'Total Trip Details '//[<?php echo $company_name; ?>]
				},
				subtitle: {
					text: "<?php echo __('for_label') . ' ' . $for_date; ?>",
				},
				xAxis: [{
					shortMonths:true,
					categories: [<?php echo $month;?>]
				}],
				yAxis: [{ // Primary yAxis
					labels: {
						format: '{value} Trips',
						style: {
							color: Highcharts.getOptions().colors[2]
						}
					},
					title: {
						text: 'Trip Counts',
						style: {
							color: Highcharts.getOptions().colors[2]
						}
					},
					opposite: true

				}, { // Secondary yAxis
					gridLineWidth: 0,
					title: {
						text: 'Trip Revenues',
						style: {
							color: Highcharts.getOptions().colors[0]
						}
					},
					labels: {
						format: '{value} <?php echo CURRENCY; ?>',
						style: {
							color: Highcharts.getOptions().colors[0]
						}
					}

				}, ],
				tooltip: {
					shared: true
				},
				legend: {
					layout: 'vertical',
					align: 'left',
					x: 120,
					verticalAlign: 'top',
					y: 80,
					floating: true,
					backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
				},
				series: [{
					name: 'Trip Revenues',
					type: 'column',
					yAxis: 1,
					data : [<?php echo $fare;?>],
					tooltip: {
						valueSuffix: ' <?php echo CURRENCY; ?>'
					}

				},
				 {
					name: 'Trip Counts',
					type: 'spline',
					data : [<?php echo $trips;?>],
					tooltip: {
						valueSuffix: ' Trips'
					}
				}]
			});
	</script>
</div>
			</div>
<?php } else {echo "<div class='nodata' style='padding:0px 10px 8px 0;'>".__('no_data')."</div>"; } ?>

        </div>
		<?php } ?>
</div>

</div>
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


$(document).ready(function() {
	$("#startdate").datetimepicker( {
		showTimepicker:DEFAULT_TIME_SHOW,
		showSecond: true,
		timeFormat: DEFAULT_TIME_FORMAT_SCRIPT,
		dateFormat: DEFAULT_DATE_FORMAT_SCRIPT,
		stepHour: 1,
		stepMinute: 1,
		maxDateTime : new Date("<?php echo date('Y m d,H:i:s'); ?>"),
		stepSecond: 1
	});

	$("#enddate").datetimepicker( {
		showTimepicker:DEFAULT_TIME_SHOW,
		showSecond: true,
		timeFormat: DEFAULT_TIME_FORMAT_SCRIPT,
		dateFormat: DEFAULT_DATE_FORMAT_SCRIPT,
		stepHour: 1,
		stepMinute: 1,
		maxDateTime : new Date("<?php echo date('Y m d,H:i:s'); ?>"),
		stepSecond: 1
	});
});
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
?>

