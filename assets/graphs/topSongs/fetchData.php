<?php
function fetchData($spID, $settings) {
    global $topSongs;

    $artist = $settings["artist"];
    $amount = $settings["amount"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    $connection = getconnection();

    $query = 
	"SELECT count(p.songID) AS times, s.name AS songName 
	FROM played p INNER JOIN song s ON p.songID = s.songID 
	WHERE p.songID IN (
	    SELECT songID FROM song WHERE songID IN (
		SELECT songID FROM SongFromArtist WHERE artistID IN (
		    SELECT artistID FROM artist WHERE name LIKE '%$artist%'))) 
	AND datePlayed BETWEEN DATE('$minDate') AND DATE('$maxDate') AND playedBy = '$spID' 
	GROUP BY songName ORDER BY times DESC LIMIT $amount";
    
    $res = mysqli_query($connection, $query);
    $topSongs = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["songName"], "y"=>$row["times"]];
	array_push($topSongs, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);

}

?>
