<?php
require "header.php";

function allSongs() {
	global $dataPoints, $spID;
	// TODO: Make a slider to adjust time
	// Returns all the names from all the songs i have ever listened to and how many times i have listened to it.
	$query =  "SELECT s.name AS name, count(p.songID) AS times FROM played p INNER JOIN song s ON p.songID = s.songID WHERE p.playedBy = '$spID' GROUP BY s.songID ORDER BY name ASC";
	$connection = getConnection();

	$res = mysqli_query($connection, $query);
	$dataPoints = array();

	// Turns all the songs into data points
	while ($row = mysqli_fetch_assoc($res)) {
		$data = ["label"=>$row["name"], "y"=>$row["times"]];	
		array_push($dataPoints, $data);	
	}
	mysqli_free_result($res);
	mysqli_close($connection);
}

function topSongs() {
	global $topSongs, $spID;

	$connection = getConnection();
	$query = "SELECT count(p.songID) as times, s.name as songName FROM played p INNER JOIN song s ON p.songID = s.songID WHERE p.playedBy = '$spID' GROUP BY songName ORDER BY times DESC LIMIT 10";

	$res = mysqli_query($connection, $query);
	$topSongs = array();

	while ($row = mysqli_fetch_assoc($res)) {
		$data = ["label"=>$row["songName"], "y"=>$row["times"]];	
		array_push($topSongs, $data);
	}
	mysqli_free_result($res);
	mysqli_close($connection);
}

function topArtists() {
	global $topArtists, $spID;

	$connection = getConnection();
	$query = "SELECT count(p.songID) AS times, a.name AS artistName, a.artistID FROM played p INNER JOIN SongFromArtist sfa ON p.songID = sfa.songID RIGHT JOIN artist a ON sfa.artistID = a.artistID WHERE p.playedBy = '$spID' GROUP BY a.artistID ORDER BY times DESC LIMIT 10";

	$res = mysqli_query($connection, $query);
	$topArtists = array();

	while ($row = mysqli_fetch_assoc($res)) {
		$data = ["label"=>$row["artistName"], "y"=>$row["times"]];
		array_push($topArtists, $data);
	}
	mysqli_free_result($res);
	mysqli_close($connection);
}
if (isset($_SESSION["loggedIn"])) {
//	$spID = $_SESSION["spID"];
	$spID = "11182819693";
	allSongs();
	topSongs();
	topArtists();
} else {
	header("Location: login.php");
}
?>

<html>
<head>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<head>

<body>
<!-- The html from the graph -->
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<div id="topSongs" style="height: 370px; width: 100%;"></div>
<div id="topArtists" style="height: 370px; width: 100%;"></div>

<script>
// This will create the all songs graph. Maybe make a file for each graph and load it in separately
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "dark2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "To all songs ever played"
	},
	axisY:{
		includeZero: true
	},
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		indexLabel: "{y}", //Shows y value on all Data Points
		indexLabelFontColor: "#5A5757",
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
	axisY:{
		includeZero: true
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
		text: "Top 10 artists"
	},
	axisY:{
		includeZero: true
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



}

</script>
</body>
<html>
