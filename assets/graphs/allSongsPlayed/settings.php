<?php 
function allSongsSettings($userID) {
    global $minDefaultDate, $maxDefaultDate;
    $savedSettings = savedSettings($userID, globalSettings());

    $minPlayed = isset($savedSettings["minPlayedAllSongs"]["value"]) ? $savedSettings["minPlayedAllSongs"]["value"] : 0;
    $maxPlayed = isset($savedSettings["maxPlayedAllSongs"]["value"]) ? $savedSettings["maxPlayedAllSongs"]["value"] : 99999;
    $minDate = isset($savedSettings["minDatePlayedAllSongs"]["value"]) ? $savedSettings["minDatePlayedAllSongs"]["value"] : $minDefaultDate;
    $maxDate = isset($savedSettings["maxDatePlayedAllSongs"]["value"]) ? $savedSettings["maxDatePlayedAllSongs"]["value"] : $maxDefaultDate;
    $artist = isset($savedSettings["artistPlayedAllSongs"]["value"]) ? $savedSettings["artistPlayedAllSongs"]["value"] : "%";

    if (isset($_GET["submitAllSongs"])) {
	$minPlayed = isset($_GET["minPlayedAllSongs"]) && !empty($_GET["minPlayedAllSongs"]) ? $_GET["minPlayedAllSongs"] : 0;
	$maxPlayed = isset($_GET["maxPlayedAllSongs"]) && !empty($_GET["maxPlayedAllSongs"]) ? $_GET["maxPlayedAllSongs"]: 99999;
	$minDate = isset($_GET["minDatePlayedAllSongs"]) && !empty($_GET["minDatePlayedAllSongs"]) ? $_GET["minDatePlayedAllSongs"] : $minDefaultDate;
	$maxDate = isset($_GET["maxDatePlayedAllSongs"]) && !empty($_GET["maxDatePlayedAllSongs"]) ? $_GET["maxDatePlayedAllSongs"] : $maxDefaultDate;
	$artist = isset($_GET["artistPlayedAllSongs"]) && !empty($_GET["artistPlayedAllSongs"]) ? $_GET["artistPlayedAllSongs"] : "%";

	makeUpdateSetting("minPlayedAllSongs", $minPlayed, $userID);
	makeUpdateSetting("maxPlayedAllSongs", $maxPlayed, $userID);
	makeUpdateSetting("minDatePlayedAllSongs", $minDate, $userID);
	makeUpdateSetting("maxDatePlayedAllSongs", $maxDate, $userID);
	makeUpdateSetting("artistPlayedAllSongs", $artist, $userID);
    }

    $settings = ["minPlayed"=>$minPlayed, "maxPlayed"=>$maxPlayed, "minDate"=>"$minDate", "maxDate"=>$maxDate, "artist"=>$artist];
    return $settings;
}
?>
