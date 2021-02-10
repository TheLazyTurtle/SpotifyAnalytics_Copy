<?php
function playedPerDaySettings($userID) {
    global $minDefaultDate, $maxDefaultDate;
    $savedSettings = savedSettings($userID, globalSettings());

    $song = isset($savedSettings["playedPerDaySong"]["value"]) ? $savedSettings["playedPerDaySong"]["value"] : "%";
    $minDate = isset($savedSettings["minDatePlayedPerDay"]["value"]) ? $savedSettings["minDatePlayedPerDay"]["value"] : $minDefaultDate;
    $maxDate = isset($savedSettings["maxDatePlayedPerDay"]["value"]) ? $savedSettings["maxDatePlayedPerDay"]["value"] : $maxDefaultDate;

    $settings = ["song"=>$song, "minDate"=>$minDate, "maxDate"=>$maxDate];
    return $settings;
}

?>
