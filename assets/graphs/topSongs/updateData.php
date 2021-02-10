<?php
session_start();

require "settings.php";
require "../../settings/settingFunctions.php";
require "../../connect.php";

function updateData() {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

    $settings = topSongsSettings($userID);
    $counter = 0;

    // set name
    if (isset($_GET["artist"])) {
	$artist = $_GET["artist"];
	makeUpdateSetting("artistTopSongs", $artist, $userID);
    } else {
	$artist = $settings["artist"];
    }

    // set amount
    if (isset($_GET["amount"])) {
	$amount = $_GET["amount"];
	makeUpdateSetting("amountTopSongs", $amount, $userID);
    } else {
	 $amount = $settings["amount"];
    }

    // set minDate
    if (isset($_GET["minDate"])) {
	$minDate = $_GET["minDate"];
	makeUpdateSetting("minDateTopSongs", $minDate, $userID);
    } else {
	$minDate = $settings["minDate"];
    }

    // set maxDate
    if (isset($_GET["maxDate"])) {
	$maxDate = $_GET["maxDate"];
	makeUpdateSetting("maxDateTopSongs", $maxDate, $userID);
    } else {
	$maxDate = $settings["maxDate"];
    }

    $connection = getconnection();
    $query = 
	"SELECT count(p.songID) AS times, s.name AS songName 
	FROM played p INNER JOIN song s ON p.songID = s.songID 
	WHERE p.songID IN (
	    SELECT songID FROM song WHERE songID IN (
		SELECT songID FROM SongFromArtist WHERE artistID IN (
		    SELECT artistID FROM artist WHERE name LIKE '%$artist%'))) 
	AND datePlayed BETWEEN DATE('$minDate') AND DATE('$maxDate') 
	AND playedBy = '$spID' 
	GROUP BY songName ORDER BY times DESC LIMIT $amount";
    
    $res = mysqli_query($connection, $query);
    $updatedTopSongs = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["songName"], "y"=>$row["times"], "x"=>$counter];
	array_push($updatedTopSongs, $data);
	$counter += 1;
    }

    mysqli_free_result($res);
    mysqli_close($connection);

    return json_encode($updatedTopSongs, JSON_NUMERIC_CHECK);
}

print_r(updateData());

?>

