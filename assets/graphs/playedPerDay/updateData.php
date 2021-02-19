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
	WHERE playedBy = ? AND s.addedBy = ? 
	AND s.name = ? 
	AND p.datePlayed BETWEEN ? AND ? 
	GROUP BY DAY(p.datePlayed), MONTH(p.datePlayed), YEAR(p.datePlayed), p.songID 
	ORDER BY date DESC";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $spID, $spID, $song, $minDate, $maxDate);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $updatedPlayedPerDay = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["x"=>$row["date"], "y"=>$row["times"]];
	array_push($updatedPlayedPerDay, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
    mysqli_stmt_close($stmt);

    return json_encode($updatedPlayedPerDay, JSON_NUMERIC_CHECK);

}

print_r(updateData());

?>
