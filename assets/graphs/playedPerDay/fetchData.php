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
	WHERE playedBy = ? AND s.addedBy = ? 
	AND s.name LIKE ? 
	AND p.datePlayed BETWEEN ? AND ? 
	GROUP BY DAY(p.datePlayed), MONTH(p.datePlayed), YEAR(p.datePlayed), p.songID 
	ORDER BY date DESC"; 

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $spID, $spID, $song, $minDate, $maxDate);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $playedPerDay = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["x"=>$row["date"], "y"=>$row["times"]];
	array_push($playedPerDay, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

?>
