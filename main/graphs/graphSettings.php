<!-- the html elements to show the graphs -->
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<div id ="topSongs" style="height: 370px; width: 100%;"></div>
<div id ="topArtists" style="height: 370px; width: 100%;"></div>
<div id="playedPerDay" style="height: 370px; width: 100%;"></div>

<!-- All the js scripts to fill the right tables -->
<script>
// Shows all songs ever played
var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    theme: "dark2",
    title: {
	text: "All songs ever played"
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
	indexLabelFontColor: "#5a5757",
	indexLabelPlacement: "inside",
	dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();

// This is the top 10 songs graph
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

// This is the top 10 artist graph
var topArtists = new CanvasJS.Chart("topArtists", {
    animationEnabled: true,
    theme: "dark2",
    title: {
	text: "Top 10 artist"
    },
    axisY: {
	includeZero: true,
	scaleBreaks: {
	    autoCalculate: true
	}
    },
    data: [{
	type: "column",
	indexLabel: "{y}",
	indexLabelFontColor: "#5A5757",
	indexLabelPlacement: "inside",
	dataPoints: <?php echo json_encode($topArtists, JSON_NUMERIC_CHECK); ?>
    }]
});
topArtists.render();

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
	xValueFormatString: "DD MM YYYY",
	dataPoints: <?php echo json_encode($playedPerDay, JSON_NUMERIC_CHECK); ?>
    }]
});
playedPerDay.render();

</script>
