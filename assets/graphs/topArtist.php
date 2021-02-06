<?php
// Form with settings
function topArtistForm($amountTopArtist, $minDateTopArtist, $maxDateTopArtist) {
    startForm();

    inputForm("number", "amountTopArtist", "Top hoeveel", $amountTopArtist);
    inputForm("date", "minDateTopArtist", "Vanaf datum", $minDateTopArtist);
    inputForm("date", "maxDateTopArtist", "Vanaf datum", $maxDateTopArtist);
    submitForm("submitTopArtist");

    endForm(); 
}

// Get the data
function topArtists($amountTopArtist, $minDateTopArtist, $maxDateTopArtist) {
    global $topArtists, $spID;

    $connection = getConnection();
    $query = 
	"SELECT count(p.songID) AS times, a.name AS artistName, a.artistID 
	FROM played p 
	INNER JOIN SongFromArtist sfa ON p.songID = sfa.songID 
	RIGHT JOIN artist a On sfa.artistID = a.artistID 
	WHERE p.playedBy = '$spID' 
	AND p.datePlayed BETWEEN DATE('$minDateTopArtist') AND DATE('$maxDateTopArtist') 
	GROUP BY a.artistID 
	ORDER BY times DESC 
	LIMIT $amountTopArtist";
    
    $res = mysqli_query($connection, $query);
    $topArtists = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["artistName"], "y"=>$row["times"]];
	array_push($topArtists, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

// Gets the settings for top artist
$amountTopArtist = isset($savedSettings["amountTopArtist"]["value"]) ? $savedSettings["amountTopArtist"]["value"] : 10;
$minDateTopArtist = isset($savedSettings["minDateTopArtist"]["value"]) ? $savedSettings["minDateTopArtist"]["value"] : $minDate;
$maxDateTopArtist = isset($savedSettings["maxDateTopArtist"]["value"]) ? $savedSettings["maxDateTopArtist"]["value"] : $maxDate;

makeUpdateSetting("amountTopArtist", $amountTopArtist);
makeUpdateSetting("minDateTopArtist", $minDateTopArtist);
makeUpdateSetting("maxDateTopArtist", $maxDateTopArtist);
?>
