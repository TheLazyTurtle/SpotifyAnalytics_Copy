<script>
var topSongs = new CanvasJS.Chart("topSongs", {
    animationEnabled: true,
    theme: "dark2",
    title: {
	text: "Top 10 songs"
    },
    axisY: {
	includeZero: true,
	scaleBreaks: {
	    autoCalculate: true
	},
    },
    data: [{
	type: "column",
	indexLabel: "{y}",
	indexLabelFontColor: "#5A5757",
	indexLabelPlacement: "inside",
	dataPoints: <?php echo json_encode($topSongs, JSON_NUMERIC_CHECK); ?>
    }]
});
topSongs.render();



</script>
