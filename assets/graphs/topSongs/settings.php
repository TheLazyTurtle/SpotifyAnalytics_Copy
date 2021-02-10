<?php
function topSongsSettings($userID) {
    global $minDefaultDate, $maxDefaultDate;

    $savedSettings = savedSettings($userID, globalSettings());

    $artist = isset($savedSettings["artistTopSongs"]["value"]) ? $savedSettings["artistTopSongs"]["value"] : "%";
    $minDate = isset($savedSettings["minDateTopSongs"]["value"]) ? $savedSettings["minDateTopSongs"]["value"] : $minDefaultDate;
    $maxDate = isset($savedSettings["maxDateTopSongs"]["value"]) ? $savedSettings["maxDateTopSongs"]["value"] : $maxDefaultDate;
    $amount = isset($savedSettings["amountTopSongs"]["value"]) ? $savedSettings["amountTopSongs"]["value"] : 10;

    $settings = ["artist"=>$artist, "amount"=>$amount, "minDate"=>$minDate, "maxDate"=>$maxDate];
    return $settings;
}

?>
