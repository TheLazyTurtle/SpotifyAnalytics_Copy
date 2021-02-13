<?php
session_start();

require "settings.php";
require "../../settings/settingFunctions.php";
require "../../connect.php";

function updateData() {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

    $settings = playedPerDaySettings($userID);

    $song = isset($_GET["song"]) ? $_GET["song"] : $settings["song"];
    $minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $settings["minDate"];
    $maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $settings["maxDate"];

    // Set song
    if (isset($_GET["song"])) {
	$song = $_GET["song"];
	makeUpdateSetting("playedPerDaySong", $song, $userID);
    } else {
	$song = $settings["song"];
    }

    // Set min date 
    if (isset($_GET["minDate"])) {
	$minDate = $_GET["minDate"];
	makeUpdateSetting("minDatePlayedPerDay", $minDate, $userID);
    } else {
	$minDate = $settings["minDate"];
    }

    // Set max date 
    if (isset($_GET["maxDate"])) {
	$maxDate = $_GET["maxDate"];
	makeUpdateSetting("maxDatePlayedPerDay", $maxDate, $userID);
    } else {
	$maxDate = $settings["maxDate"];
    }

    $connection = getConnection();
    $query = 
	"SELECT count(*) AS times, unix_timestamp(p.datePlayed) * 1000 AS date
	FROM played p 
	INNER JOIN song s ON p.songID = s.songID 
	WHERE playedBy = '$spID' 
	AND s.name = '$song' 
	AND p.datePlayed BETWEEN '$minDate' AND '$maxDate' 
	GROUP BY Day(p.datePlayed), p.songID 
	ORDER BY date DESC";

    $res = mysqli_query($connection, $query);
    $updatedPlayedPerDay = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["x"=>$row["date"], "y"=>$row["times"]];
	array_push($updatedPlayedPerDay, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);

    return json_encode($updatedPlayedPerDay, JSON_NUMERIC_CHECK);

}

print_r(updateData());

?>
