<?php
function fetchData($spID, $settings) {
    global $topArtists;

    $amount = $settings["amount"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    $connection = getConnection();
    $query = 
	"SELECT count(p.songID) AS times, a.name AS artistName
	FROM played p 
	INNER JOIN song s ON p.songID = s.songID
	INNER JOIN SongFromArtist sfa ON sfa.songID = s.songID
	RIGHT JOIN artist a ON sfa.artistID = a.artistID 
	WHERE p.playedBy = '$spID' AND a.addedBy = '$spID' AND s.addedBy = '$spID'
	AND p.datePlayed BETWEEN DATE('$minDate') AND DATE('$maxDate') 
	GROUP BY a.artistID 
	ORDER BY times DESC 
	LIMIT $amount";

    
    $res = mysqli_query($connection, $query);
    $topArtists = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["artistName"], "y"=>$row["times"]];
	array_push($topArtists, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);

}

?>
