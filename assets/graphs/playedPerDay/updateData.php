<?php
require "../../connect.php";
require "settings.php";

function updateDataPPD() {
    $settings = playedPerDaySettings(1);
    $counter = 0;

    $song = isset($_GET["song"]) ? $_GET["song"] : $settings["song"];
    $minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $setting["minDate"];
    $maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $setting["maxDate"];
    $spID = "111%";

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
    $updatedPlayedPerDay = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["x"=>$row["date"], "y"=>$row["times"], "x"=>$counter];
	array_push($updatedPlayedPerDay, $data);
	$counter += 1;
    }
    mysqli_free_result($res);
    mysqli_close($connection);

    return json_encode($updatedPlayedPerDay, JSON_NUMERIC_CHECK);

}

print_r(updateData());
?>
