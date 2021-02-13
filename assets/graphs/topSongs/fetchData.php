<?php
function fetchData($spID, $settings) {
    global $topSongs;

    $artist = $settings["artist"];
    $amount = $settings["amount"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    $connection = getconnection();


    $query = "
	SELECT distinct s.name as songName, count(p.songID) as times
	FROM played p
	INNER JOIN song s ON s.songID = p.songID
	INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID
	RIGHT JOIN artist a on sfa.artistID = a.artistID
	WHERE a.name LIKE '$artist'
	AND a.addedBy = '$spID' AND p.playedBy = '$spID'
	AND datePlayed BETWEEN '$minDate' AND '$maxDate'
	GROUP BY s.songID, a.artistID
	ORDER BY times DESC
	LIMIT $amount";

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
