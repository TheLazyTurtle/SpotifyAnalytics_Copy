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
	WHERE a.name LIKE ? 
	AND a.addedBy = ? AND p.playedBy = ? AND s.addedBy = ? AND sfa.addedBy = ?
	AND sfa.primaryArtist = 1
	AND datePlayed BETWEEN ? AND ? 
	GROUP BY s.songID, a.artistID
	ORDER BY times DESC
	LIMIT ?";
    
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssssssi", $artist, $spID, $spID, $spID, $spID, $minDate, $maxDate, $amount);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $topSongs = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["songName"], "y"=>$row["times"]];
	array_push($topSongs, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
    mysqli_stmt_close($stmt);

}

?>
