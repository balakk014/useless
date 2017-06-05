<?php 
$no_data = 0;
if($driver_topup[0]["in_coupon"] > 0 || $driver_topup[0]["in_admin"] > 0) {
	$no_data = 1;
}

$coupon_added_count = isset($driver_topup[0]["in_coupon"])?$driver_topup[0]["in_coupon"]:0;
$admin_added_count = isset($driver_topup[0]["in_admin"])?$driver_topup[0]["in_admin"]:0;

$total_coupon_amount = (isset($driver_topup[0]['coupon_amount']) && $driver_topup[0]['coupon_amount'] !="")?$driver_topup[0]['coupon_amount']:0;
$total_admin_amount = (isset($driver_topup[0]['admin_amount']) && $driver_topup[0]['admin_amount'] !="")?$driver_topup[0]['admin_amount']:20;

?>
<div class="scroll_inner">
<div id="payment_by_company" <?php if($no_data) { ?> style="min-width: 310px; height: auto; margin: 0 auto"<?php } ?>><div class="nodata_found">No Data Found</div></div>
</div>
<?php if($no_data) { ?>
<script>
$(function () {
	var currency = '<?php echo CURRENCY; ?>';
	$('#payment_by_company').highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		credits: {
			enabled: false
		},
		title: {
			text: ''
		},
		tooltip: {
			pointFormat: '<b>{point.y} '+' | '+currency+'{point.x}</b>'
		},
		plotOptions: {
			pie: {
				innerSize: 40,
				allowPointSelect: true,
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.y}'+' | '+currency+'{point.x}',
				},
				showInLegend: true
			}
		},
		series: [{
			data: [
				{ name: 'Added using coupon', y: <?php echo $coupon_added_count; ?> ,x: <?php echo $total_coupon_amount; ?>,color:'#0088cc' },
				{ name: 'Added by admin', y: <?php echo $admin_added_count; ?> ,x: <?php echo $total_admin_amount; ?>,color:'#f39c12' },
			]
		}]
	});
});
</script>
<div class="dashboardpayment"></div>
<?php } ?>

