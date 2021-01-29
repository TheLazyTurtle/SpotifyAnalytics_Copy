<?php
function playedPerDayForm($playedPerDaySong, $minDatePlayedPerDay, $maxDatePlayedPerDay) {
    startForm();

    inputForm("text", "playedPerDaySong", "Nummer naam", $playedPerDaySong);
    inputForm("date", "minDatePlayedPerDay", "Vanaf datum", $minDatePlayedPerDay);
    inputForm("date", "maxDatePlayedPerDay", "Vanaf datum", $maxDatePlayedPerDay);
    submitForm("submitPlayedPerDay");

    endForm();
}

//Gets the data
function playedPerDay($playedPerDaySong, $minDatePlayedPerDay, $maxDatePlayedPerDay) {
    global $playedPerDay, $spID;

    $connection = getConnection();
    $query = 
	"SELECT count(*) AS times, unix_timestamp(p.datePlayed) * 1000 AS date, s.name 
	FROM played p 
	INNER JOIN song s ON p.songID = s.songID 
	WHERE playedBy = '$spID' 
	AND s.name LIKE '$playedPerDaySong' AND p.datePlayed BETWEEN '$minDatePlayedPerDay' 
	AND '$maxDatePlayedPerDay' 
	GROUP BY Day(p.datePlayed), p.songID 
	ORDER BY date DESC";

    $res = mysqli_query($connection, $query);
    $playedPerDay = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["x"=>$row["date"], "y"=>$row["times"]];
	array_push($playedPerDay, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

// Gets the settings
$playedPerDaySong = isset($savedSettings["playedPerDaySong"]["value"]) ? $savedSettings["playedPerDaySong"]["value"] : "%";
$minDatePlayedPerDay = isset($savedSettings["minDatePlayedPerDay"]["value"]) ? $savedSettings["minDatePlayedPerDay"]["value"] : $minDate;
$maxDatePlayedPerDay = isset($savedSettings["maxDatePlayedPerDay"]["value"]) ? $savedSettings["maxDatePlayedPerDay"]["value"] : $maxDate;

if (isset($_GET["submitPlayedPerDay"])) {
    $playedPerDaySong = isset($_GET["playedPerDaySong"]) && !empty($_GET["playedPerDaySong"]) ? $_GET["playedPerDaySong"] : "%";
    $minDatePlayedPerDay = isset($_GET["minDatePlayedPerDay"]) && !empty($_GET["minDatePlayedPerDay"]) ? $_GET["minDatePlayedPerDay"] : $minDate;
    $maxDatePlayedPerDay = isset($_GET["maxDatePlayedPerDay"]) && !empty($_GET["maxDatePlayedPerDay"]) ? $_GET["maxDatePlayedPerDay"] : $maxDate;

    makeUpdateSetting("PlayedPerDaySong", $playedPerDaySong);
    makeUpdateSetting("minDatePlayedPerDay", $minDatePlayedPerDay);
    makeUpdateSetting("maxDatePlayedPerDay", $maxDatePlayedPerDay);
}

?>
