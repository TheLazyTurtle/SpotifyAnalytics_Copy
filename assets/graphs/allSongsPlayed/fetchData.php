<?php
function fetchData($spID, $settings) {
    global $dataPoints;

    $minPlayed = $settings["minPlayed"];
    $maxPlayed = $settings["maxPlayed"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];
    $artist = $settings["artist"];

    $connection = getConnection();
    $query = "
	SELECT distinct s.name as name, count(p.songID) as times 
	FROM played p 
	INNER JOIN song s ON s.songID = p.songID 
	INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID 
	RIGHT JOIN artist a ON a.artistID = sfa.artistID 
	WHERE a.name LIKE ?
	AND a.addedBy = ? AND p.playedBy = ? AND s.addedBy = ?
	AND datePlayed BETWEEN ? AND ?
	GROUP BY s.name, a.artistID 
	HAVING times between ? AND ?
	ORDER BY name";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssii', $artist, $spID, $spID, $spID, $minDate, $maxDate, $minPlayed, $maxPlayed);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $dataPoints = array();

    // Turns all the songs into dataPoints
    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["name"], "y"=>$row["times"]];
	array_push($dataPoints, $data);
    }
    mysqli_stmt_close($stmt);
    mysqli_free_result($res);
    mysqli_close($connection);
}

?>
