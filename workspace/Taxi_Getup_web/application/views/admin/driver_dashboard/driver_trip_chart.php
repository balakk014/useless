<?php 
$no_data = 0;
if($driver_name != "" || $trip_completed != "" || $trip_inprogress != "" ||  $trip_cancelled != "") {
$no_data = 1;
}
?>
<div id="companyWiseTrip" <?php if($no_data) { ?> style="min-width: 310px; height: auto; margin: 0 auto"<?php } ?>><div class="nodata_found">No Data Found</div></div>
<?php if($no_data) { ?>
<script>
$(function () {
    $('#companyWiseTrip').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
			enabled: false
		},
        title: {
            text: ''
        },
        xAxis: {
            categories: [<?php echo $driver_name; ?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Trip Count'
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b>	<br/>'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Trip Completed',
            data: [<?php echo $trip_completed; ?>],
            color : '#0088cc'
        }, {
            name: 'Trip Inprogress',
           data: [<?php echo $trip_inprogress; ?>],
           color : '#f39c12'
        }, {
            name: 'Trip Cancelled',
            data: [<?php echo $trip_cancelled; ?>],
            color : '#e36159'
        }]
    });
});
</script>
<?php } ?>
