<?php 
$no_data = 0;
if(count($result) > 0) {
	$no_data = 1;
}
?>
<div class="scroll_inner">
<div id="cityByCount" <?php if($no_data) { ?> style="min-width: 200px; height: auto; margin: 0 auto" <?php } ?>><div class="nodata_found">No Data Found</div></div>
</div>
<?php if($no_data) { ?>
<script type="text/javascript" language="javascript">
$(function () {
    $('#cityByCount').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 65,
                beta: 0
            }
        },
        title: {
            text: ''
        },
        credits: {
			enabled: false
		},
        tooltip: {
			enabled: false,
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 20,
               // size: 50,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}<br> <b>{point.y}</b>'
                },
                showInLegend: true
            }
        },
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal'
                    },
                    yAxis: {
                        labels: {
                            align: 'left',
                            x: 0,
                            y: -5
                        },
                        title: {
                            text: null
                        }
                    },
                    subtitle: {
                        text: null
                    },
                    credits: {
                        enabled: false
                    }
                }
            }]
        },
         series: [{
            type: 'pie',
       //     name: 'Browser share',
            data: [<?php foreach($result as $t=>$v) { ?>
                ['<?php echo ucfirst(__($t)); ?>', <?php echo $v; ?>],
            <?php } ?>
            ]
        }]
    });
});
</script>
<?php } ?>
