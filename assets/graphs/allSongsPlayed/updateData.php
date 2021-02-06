<?php
session_start();

require "settings.php";
require "../../settings/settingFunctions.php";
require "../../connect.php";

function updateData() {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

    $settings = allSongsSettings($userID);
    $counter = 0;

    // set minPlayed
    if (isset($_GET["minPlayed"])) {
	$minPlayed = $_GET["minPlayed"];
	makeUpdateSetting("minPlayedAllSongs", $minPlayed, $userID);
    } else {
	$minPlayed = $settings["minPlayed"];
    }

    // set maxPlayed
    if (isset($_GET["maxPlayed"])) {
	$maxPlayed = $_GET["maxPlayed"];
	makeUpdateSetting("maxPlayedAllSongs", $maxPlayed, $userID);
    } else {
	$maxPlayed = $settings["maxPlayed"];
    }

    if (isset($_GET["artist"])) {
	$artist = $_GET["artist"];
	makeUpdateSetting("artistPlayedAllSongs", $artist, $userID);
    } else {
	$artist = $settings["artist"];
    }

   // set maxPlayed
    if (isset($_GET["minDate"])) {
	$minDate = $_GET["minDate"];
	makeUpdateSetting("minDateAllSongs", $minDate, $userID);
    } else {
	$minDate = $settings["minDate"];	
    }

    // set maxPlayed
    if (isset($_GET["maxDate"])) {
	$maxDate = $_GET["maxDate"];
	makeUpdateSetting("maxDateAllSongs", $maxDate, $userID);
    } else {
	$maxDate = $settings["maxDate"];	
    }

    // TODO: Fix this shit here
    // This will set the date to the min or max date when there is no setting available because the setting fetching function is fucked
    if ($settings["minDate"] == "" || empty($settings["minDate"])) {
	$minDate = "2020-01-01";
    }

    if ($settings["maxDate"] == "" || empty($settingsp["maxDate"])) {
	$maxDate = "2099-01-01";
    }

    $connection = getConnection();
    $query = "
	SELECT s.name AS name, count(p.songID) AS times
	FROM played p
	INNER JOIN song s ON p.songID = s.songID
	WHERE s.songID IN (
	    SELECT songID FROM SongFromArtist
	    WHERE artistID IN (
		SELECT artistID FROM artist
		WHERE name LIKE '%$artist%'))
	AND playedBy LIKE '$spID' AND datePlayed BETWEEN DATE('$minDate') AND DATE('$maxDate')
	GROUP BY s.songID HAVING times BETWEEN '$minPlayed' AND '$maxPlayed'
	ORDER BY name ASC";

    $res = mysqli_query($connection, $query);
    $updatedDataPoints = array();

    // Turns all the songs into dataPoints
    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["name"], "y"=>$row["times"], "x"=>$counter];
	array_push($updatedDataPoints, $data);
	$counter += 1;
    }

    mysqli_free_result($res);
    mysqli_close($connection);

    return json_encode($updatedDataPoints, JSON_NUMERIC_CHECK);
}

print_r(updateData());

?>

