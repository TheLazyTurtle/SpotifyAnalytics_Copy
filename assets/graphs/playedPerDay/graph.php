<script>
// This is the played per day graphs
var playedPerDay = new CanvasJS.Chart("playedPerDay", {
    animationEnabled: true,
    theme: "dark2",
    title: {
	text: "Played per day",
    },
    asixX: {
	title: "Time",
    }, 
    axisY: {
	title: "Times",
	includeZero: true,
    },
    data: [{
	type: "line",
	name: "Played Per Day",
	connectNullData: true,
	xValueType: "dateTime",
	xValueFormatString: "MM DD YYYY",
	dataPoints: <?php echo json_encode($playedPerDay, JSON_NUMERIC_CHECK); ?>
    }]
});
playedPerDay.render();


</script>
