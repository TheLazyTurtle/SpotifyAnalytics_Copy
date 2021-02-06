
<script>
var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    theme: "dark2",
    title: {
	text: "All songs ever played",
    }, 
    axisY: {
	includeZero: true,
	scaleBreaks: {
	    autoCalculate: true,
	},
    },
    data: [{
	type: "column",
	indexLabel: "{y}",
	indexLabelFontColor: "#5a6767",
	indexLabelPlacement: "inside",
	dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();
</script>

