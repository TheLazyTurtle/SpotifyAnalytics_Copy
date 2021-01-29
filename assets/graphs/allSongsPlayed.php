<?php
// The form with the settings for all songs ever played
function allSongsForm($minPlayedAllSongs, $maxPlayedAllSongs, $minDatePlayedAllSongs, $maxDatePlayedAllSongs, $artistPlayedAllSongs) {
    startForm();

    inputForm("text", "artistPlayedAllSongs", "Artiest naam", $artistPlayedAllSongs);
    inputForm("number", "minPlayedAllSongs", "Minimaal afgespeeld", $minPlayedAllSongs);
    inputForm("number", "maxPlayedAllSongs", "Minimaal afgespeeld", $maxPlayedAllSongs);
    inputForm("date", "minDatePlayedAllSongs", "Vanaf datum", $minDatePlayedAllSongs);
    inputForm("date", "maxDatePlayedAllSongs", "Vanaf datum", $maxDatePlayedAllSongs);
    submitForm("submitAllSongs");

    endForm(); 
}

function allSongs($minPlayedAllSongs, $maxPlayedAllSongs, $minDatePlayedAllSongs, $maxDatePlayedAllSongs, $artistPlayedAllSongs) {
    global $dataPoints, $spID;

    $connection = getConnection();
    $query = "
	SELECT s.name AS name, count(p.songID) AS times 
	FROM played p 
	INNER JOIN song s ON p.songID = s.songID 
	WHERE p.songID IN (
	    SELECT songID FROM song 
	    WHERE songID IN (
		SELECT songID FROM SongFromArtist 
		WHERE artistID IN (
		    SELECT artistID FROM artist 
		    WHERE name LIKE '%$artistPlayedAllSongs%'))) 
	AND playedBy LIKE '$spID' AND datePlayed BETWEEN DATE('$minDatePlayedAllSongs') AND DATE('$maxDatePlayedAllSongs') 
	GROUP BY s.songID HAVING times BETWEEN '$minPlayedAllSongs' AND '$maxPlayedAllSongs' 
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

// Get the settings for all songs ever played
$minPlayedAllSongs = isset($savedSettings["minPlayedAllSongs"]["value"]) ? $savedSettings["minPlayedAllSongs"]["value"] : 0;
$maxPlayedAllSongs = isset($savedSettings["maxPlayedAllSongs"]["value"]) ? $savedSettings["maxPlayedAllSongs"]["value"] : 99999;
$minDatePlayedAllSongs = isset($savedSettings["minDatePlayedAllSongs"]["value"]) ? $savedSettings["minDatePlayedAllSongs"]["value"] : $minDate;
$maxDatePlayedAllSongs = isset($savedSettings["maxDatePlayedAllSongs"]["value"]) ? $savedSettings["maxDatePlayedAllSongs"]["value"] : $maxDate;
$artistPlayedAllSongs = isset($savedSettings["artistPlayedAllSongs"]["value"]) ? $savedSettings["artistPlayedAllSongs"]["value"] : "%";

if (isset($_GET["submitAllSongs"])) {
    $minPlayedAllSongs = isset($_GET["minPlayedAllSongs"]) && !empty($_GET["minPlayedAllSongs"]) ? $_GET["minPlayedAllSongs"] : 0;
    $maxPlayedAllSongs = isset($_GET["maxPlayedAllSongs"]) && !empty($_GET["maxPlayedAllSongs"]) ? $_GET["maxPlayedAllSongs"] : 99999;
    $minDatePlayedAllSongs = isset($_GET["minDatePlayedAllSongs"]) && !empty($_GET["minDatePlayedAllSongs"]) ? $_GET["minDatePlayedAllSongs"] : $minDate;
    $maxDatePlayedAllSongs = isset($_GET["maxDatePlayedAllSongs"]) && !empty($_GET["maxDatePlayedAllSongs"]) ? $_GET["maxDatePlayedAllSongs"] : $maxDate;
    $artistPlayedAllSongs = isset($_GET["artistPlayedAllSongs"]) && !empty($_GET["artistPlayedAllSongs"]) ? $_GET["artistPlayedAllSongs"] : "%";

    makeUpdateSetting("minPlayedAllSongs", $minPlayedAllSongs);
    makeUpdateSetting("maxPlayedAllSongs", $maxPlayedAllSongs);
    makeUpdateSetting("minDatePlayedAllSongs", $minDatePlayedAllSongs);
    makeUpdateSetting("maxDatePlayedAllSongs", $maxDatePlayedAllSongs);
    makeUpdateSetting("artistPlayedAllSongs", $artistPlayedAllSongs);
}
?>
