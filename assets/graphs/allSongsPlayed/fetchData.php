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
	SELECT s.name AS name, count(p.songID) AS times
	FROM played p
	INNER JOIN song s ON p.songID = s.songID
	WHERE p.songID IN (
		SELECT songID FROM SongFromArtist
		WHERE artistID IN (
		    SELECT artistID FROM artist
		    WHERE name LIKE '%$artist%'))
	AND playedBy LIKE '$spID' 
	AND datePlayed BETWEEN DATE('$minDate') AND DATE('$maxDate')
	GROUP BY s.songID 
	HAVING times BETWEEN '$minPlayed' AND '$maxPlayed'
	ORDER BY name ASC";

    $res = mysqli_query($connection, $query);
    $dataPoints = array();

    // Turns all the songs into dataPoints
    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["name"], "y"=>$row["times"]];
	array_push($dataPoints, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

?>
