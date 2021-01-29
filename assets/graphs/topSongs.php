<?php
// The form with the settings 
function topSongsForm($artistTopSongs, $minDateTopSongs, $maxDateTopSongs, $amountTopSongs) {
    startForm();

    inputForm("text", "artistTopSongs", "Artiest naam", $artistTopSongs);
    inputForm("number", "amountTopSongs", "Top hoeveel", $amountTopSongs);
    inputForm("date", "minDateTopSongs", "Vanaf datum", $minDateTopSongs);
    inputForm("date", "maxDateTopSongs", "Vanaf datum", $maxDateTopSongs);
    submitForm("submitTopSongs");

    endForm();
}

// This will get all the data
function topSongs($artistTopSongs, $minDateTopSongs, $maxDateTopSongs, $amountTopSongs) {
    global $topSongs, $spID;

    $connection = getConnection();
    $query = 
	"SELECT count(p.songID) AS times, s.name AS songName 
	FROM played p INNER JOIN song s ON p.songID = s.songID 
	WHERE p.songID IN (
	    SELECT songID FROM song WHERE songID IN (
		SELECT songID FROM SongFromArtist WHERE artistID IN (
		    SELECT artistID FROM artist WHERE name LIKE '%$artistTopSongs%'))) 
	AND datePlayed BETWEEN DATE('$minDateTopSongs') AND DATE('$maxDateTopSongs') AND playedBy = '$spID' 
	GROUP BY songName ORDER BY times DESC LIMIT $amountTopSongs";
    
    $res = mysqli_query($connection, $query);
    $topSongs = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["songName"], "y"=>$row["times"]];
	array_push($topSongs, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

// This will get all the settings
$artistTopSongs = isset($savedSettings["artistTopSongs"]["value"]) ? $savedSettings["artistTopSongs"]["value"] : "%";
$minDateTopSongs = isset($savedSettings["minDateTopSongs"]["value"]) ? $savedSettings["minDateTopSongs"]["value"] : $minDate;
$maxDateTopSongs = isset($savedSettings["maxDateTopSongs"]["value"]) ? $savedSettings["maxDateTopSongs"]["value"] : $maxDate;
$amountTopSongs = isset($savedSettings["amountTopSongs"]["value"]) ? $savedSettings["amountTopSongs"]["value"] : 10;

if (isset($_GET["submitTopSongs"])) {
    $artistTopSongs = isset($_GET["artistTopSongs"]) && !empty($_GET["artistTopSongs"]) ? $_GET["artistTopSongs"] : "%";
    $minDateTopSongs = isset($_GET["minDateTopSongs"]) && !empty($_GET["minDateTopSongs"]) ? $_GET["minDateTopSongs"] : $minDate;
    $maxDateTopSongs = isset($_GET["maxDateTopSongs"]) && !empty($_GET["maxDateTopSongs"]) ? $_GET["maxDateTopSongs"] : $maxDate;
    $amountTopSongs = isset($_GET["amountTopSongs"]) && !empty($_GET["amountTopSongs"]) ? $_GET["amountTopSongs"] : 10;

    makeUpdateSetting("artistTopSongs", $artistTopSongs);
    makeUpdateSetting("minDateTopSongs", $minDateTopSongs);
    makeUpdateSetting("maxDateTopSongs", $maxDateTopSongs);
    makeUpdateSetting("amountTopSongs", $amountTopSongs);
}














?>
