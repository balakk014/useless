<?php
	$current_year=date ('Y');
	if(isset($post_values)){
		$sdate=$post_values['startdate'];
		$edate=$post_values['enddate'];
		$for_date = Commonfunction::getDateTimeFormat($sdate,1)." to ".Commonfunction::getDateTimeFormat($edate,1);
		if($post_values['company'] ==''){
			$company_name="All";
		}else{
			$company_name = (count($get_transaction) > 0) ? $get_transaction[0]['company']:'';
			/*foreach($get_company_details as $gcd){
				if($gcd['cid']==$post_values['company']){
					$company_name=ucfirst($gcd['company_name']);
				}
			} */
		}
	}else{
		$for_date=$current_year;
	}
	//print_r($get_transaction);exit;
	$fare = array();
	$admincommision = array();
	$month = array();
	$trips = array();
	$webtrips = 0;
	$mobiletrips = 0;
	$display ="display:none;";
	
	if($get_transaction){
	
		foreach($get_transaction as $vl)
		{
			
			if($vl['fare'] != NULL){
				$trips[] = $vl['trips'];
				$fare[] = $vl['fare'];
				$admincommision[] = $vl['admincommission'];
				$webtrips = isset($get_transaction[0]['webtrips']) ? ($get_transaction[0]['webtrips']+ $webtrips): 0;
				$mobiletrips = isset($get_transaction[0]['mobiletrips']) ? ($get_transaction[0]['mobiletrips']+$mobiletrips) : 0;
				$month[] = "'".$vl['date']." ".date('M', strtotime($vl['month']))."'";
			}
		}
		
		if($trips != NULL){
			$trips = implode(",",$trips);
		}
		if($fare != NULL){
			$fare = implode(",",$fare);
		}
		if($admincommision != NULL){
			$admincommision = implode(",",$admincommision);
		}
		if($month != NULL){
			$month = implode(",",$month);
		}
		$display ="display:block;";
	}
	
?>
<?php if($display == 'display:none;'){ echo "<div class='no_data'>".__('no_data')."</div>"; } else{ ?> 
<div id="total_trips_details" style="min-width: 400px; height: 400px; margin: 0 auto<?php echo $display;?>">
	<script>
		$('#total_trips_details').highcharts({			
			chart: {
				shortMonths:true,
				zoomType: 'xy'
			},
			title: {
					text: 'Total Trip Details [<?php echo $company_name; ?>]'
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

				},{
					name: 'Admin Commmision',
					type: 'column',
					yAxis: 1,
					data : [<?php echo $admincommision;?>],
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
				}, {
					type: 'pie',
					name: '<?php echo __('trip_counts'); ?>',
					data: [{name: 'Mobile App',y: <?php echo $mobiletrips; ?>,color: Highcharts.getOptions().colors[0]},{name: 'Web App',y: <?php echo $webtrips; ?>,color: Highcharts.getOptions().colors[1] }],
					center: [600, 60],
					size: 100,
					showInLegend: false,
					dataLabels: {
						enabled: false
					}
				}]
			});
	</script>
</div>
<?php } ?>
