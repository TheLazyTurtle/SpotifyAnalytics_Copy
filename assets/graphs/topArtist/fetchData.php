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
	WHERE p.playedBy = ? AND a.addedBy = ? AND s.addedBy = ? 
	AND p.datePlayed BETWEEN ? AND ? 
	GROUP BY a.artistID 
	ORDER BY times DESC 
	LIMIT ?";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $spID, $spID, $spID, $minDate, $maxDate, $amount);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    $topArtists = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["artistName"], "y"=>$row["times"]];
	array_push($topArtists, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
    mysqli_stmt_close($stmt);

}

?>
