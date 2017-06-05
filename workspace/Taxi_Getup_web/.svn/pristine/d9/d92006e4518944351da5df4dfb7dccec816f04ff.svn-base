<?php 
$no_data = 0;
if($balance_driver_count != "" || $no_balance_driver_count != "") {
	$no_data = 1;
}

?>
<div class="scroll_inner">
<div id="assigned_unassigned_graph" <?php if($no_data) { ?> style="min-width: 200px; height: auto; margin: 0 auto"<?php } ?>>
    <div class="nodata_found">No Data Found</div></div></div>
<?php if($no_data) { ?>
<?php if(COMPANY_CID > 0) { ?>
<script>
$(function () {
	$('#assigned_unassigned_graph').highcharts({
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
			pointFormat: '<b>{point.y} '+' | {point.x}</b>'
		},
		plotOptions: {
			pie: {
				innerSize: 80,
				allowPointSelect: true,
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.y}',
				},
				showInLegend: true
			}
		},
		series: [{
			data: [
				{ name: 'Balance Driver', y: <?php echo $balance_driver_count; ?>,color:"#0088cc"},
				
				{ name: 'No Balance Driver', y: <?php echo $no_balance_driver_count; ?>,color:"#734ba9"},
				
			]
		}]
	});
});
</script>
<?php } else { ?>
<script>
$(function () {
    $('#assigned_unassigned_graph').highcharts({
        chart: {
            type: 'column'
        },
        credits: {
			enabled: false
		},
        exporting: { 
			enabled: false
		},
        title: {
            text: ''
        },
        xAxis: {
            categories: ["<?php echo $driver_name; ?>"]
        },
        yAxis: [{
            min: 0,
            title: {
                text: 'Count'
            }
        }, {
            title: {
                text: ''
            },
            opposite: true
        }],
        legend: {
            shadow: false
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            column: {
                grouping: false,
                shadow: false,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Balance Drivers',
            color: 'rgba(165,170,217,1)',
            data: [<?php echo $balance_driver_count; ?>],
            pointPadding: 0.3,
            pointPlacement: -0.2
        }, {
            name: 'No balance drivers',
            color: 'rgba(248,161,63,1)',
            data: [<?php echo $no_balance_driver_count; ?>],
            pointPadding: 0.3,
            pointPlacement: 0.2,
            yAxis: 1
        }]
    });
});
</script>
<?php } ?>
<?php } ?>
