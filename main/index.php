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

function playedPerDay($songName) {
    global $playedPerDay, $spID;

    $connection = getConnection();
    $query = "SELECT count(*) AS times, p.datePlayed AS date, s.name FROM played p INNER JOIN song s ON p.songID = s.songID WHERE playedBy = '$spID' AND s.name = '$songName' GROUP BY DAY(date), p.songID;";

    $res = mysqli_query($connection, $query);
    $playedPerDay = array();

    while ($row = mysqli_fetch_assoc($res)) {
	// Some facked up code to convert the time to unix
	$date = date($row["date"]);
	$date = str_replace(" ", "-", $date);
	$date = str_replace(":", "-", $date);
	$date = explode("-", $date);
	$date = mktime($date[5], $date[4], $date[3], $date[1], $date[2], $date[0]);
	print($date);
	print("<br>");

	$data = ["x"=>$date, "y"=>$row["times"]];
	array_push($playedPerDay, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);

}

if (isset($_SESSION["loggedIn"])) {
	$spID = $_SESSION["spID"];

	allSongs();
	topSongs();
	topArtists();
	playedPerDay("Slow Down");
} else {
	header("Location: login.php");
}
?>


<div class="test">
    <?php
	require "./graphs/graphSettings.php";
    ?>
<div>

</body>
<html>
