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
	WHERE a.name LIKE '%$artist%' 
	AND a.addedBy = '$spID' AND playedBy = '$spID' 
	AND datePlayed BETWEEN '$minDate' AND '$maxDate'
	GROUP BY s.name, a.artistID 
	HAVING times between '$minPlayed' AND '$maxPlayed'
	ORDER BY name";

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
