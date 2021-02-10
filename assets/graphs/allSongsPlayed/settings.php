<?php 
function allSongsSettings($userID) {
    global $minDefaultDate, $maxDefaultDate;
    $savedSettings = savedSettings($userID, globalSettings());

    $minPlayed = isset($savedSettings["minPlayedAllSongs"]["value"]) ? $savedSettings["minPlayedAllSongs"]["value"] : 0;
    $maxPlayed = isset($savedSettings["maxPlayedAllSongs"]["value"]) ? $savedSettings["maxPlayedAllSongs"]["value"] : 99999;
    $minDate = isset($savedSettings["minDatePlayedAllSongs"]["value"]) ? $savedSettings["minDatePlayedAllSongs"]["value"] : $minDefaultDate;
    $maxDate = isset($savedSettings["maxDatePlayedAllSongs"]["value"]) ? $savedSettings["maxDatePlayedAllSongs"]["value"] : $maxDefaultDate;
    $artist = isset($savedSettings["artistPlayedAllSongs"]["value"]) ? $savedSettings["artistPlayedAllSongs"]["value"] : "%";

    $settings = ["minPlayed"=>$minPlayed, "maxPlayed"=>$maxPlayed, "minDate"=>"$minDate", "maxDate"=>$maxDate, "artist"=>$artist];
    return $settings;
}
?>
