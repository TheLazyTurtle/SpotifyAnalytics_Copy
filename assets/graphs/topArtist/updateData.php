<?php
session_start();

require "settings.php";
require "../../settings/settingFunctions.php";
require "../../connect.php";

function updateData() {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

    $settings = topArtistSettings($userID);
    $counter = 0;

    // set amount
    if (isset($_GET["amount"])) {
	$amount = $_GET["amount"];
	makeUpdateSetting("amountTopArtist", $amount, $userID);
    } else {
	$amount = $settings["amount"];
    }

    // set minDate 
    if (isset($_GET["minDate"])) {
	$minDate = $_GET["minDate"];
	makeUpdateSetting("minDateTopArtist", $minDate, $userID);
    } else {
	$minDate = $settings["minDate"];
    }

    // set maxDate
    if (isset($_GET["maxDate"])) {
	$maxDate = $_GET["maxDate"];
	makeUpdateSetting("maxDateTopArtist", $maxDate, $userID);
    } else {
	$maxDate = $settings["maxDate"];
    }

    $connection = getConnection();
    $query = 
	"SELECT count(p.songID) AS times, a.name AS artistName
	FROM played p 
	INNER JOIN song s ON p.songID = s.songID
	INNER JOIN SongFromArtist sfa ON sfa.songID = s.songID
	RIGHT JOIN artist a ON sfa.artistID = a.artistID 
	WHERE p.playedBy = ? AND a.addedBy = ? AND s.addedBy = ?
	AND p.datePlayed BETWEEN ? AND ? 
	GROUP BY a.artistID 
	ORDER BY times DESC 
	LIMIT ?";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $spID, $spID, $spID, $minDate, $maxDate, $amount);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    $updatedTopArtist = array();

    // Turns all the artist into dataPoints
    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["artistName"], "y"=>$row["times"], "x"=>$counter];
	array_push($updatedTopArtist, $data);
	$counter++;
    }

    mysqli_free_result($res);
    mysqli_close($connection);
    mysqli_stmt_close($stmt);

    return json_encode($updatedTopArtist, JSON_NUMERIC_CHECK);
}

print_r(updateData())

?>
