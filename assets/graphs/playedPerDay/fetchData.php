<?php
function fetchData($spID, $settings) {
    global $playedPerDay;

    $song = $settings["song"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    $connection = getConnection();

    $query = 
	"SELECT count(*) AS times, unix_timestamp(p.datePlayed) * 1000 AS date
	FROM played p 
	INNER JOIN song s ON p.songID = s.songID 
	WHERE playedBy = '$spID' AND s.addedBy = '$spID'
	AND s.name LIKE '$song' 
	AND p.datePlayed BETWEEN '$minDate' AND '$maxDate' 
	GROUP BY DAY(p.datePlayed), MONTH(p.datePlayed), YEAR(p.datePlayed), p.songID 
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

?>
