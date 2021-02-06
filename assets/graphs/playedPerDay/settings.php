<?php
function playedPerDaySettings($userID) {
    global $minDefaultDate, $maxDefaultDate;

    $song = isset($savedSettings["playedPerDaySong"]["value"]) ? $savedSettings["playedPerDaySong"]["value"] : "%";
    $minDate = isset($savedSettings["minDatePlayedPerDay"]["value"]) ? $savedSettings["minDatePlayedPerDay"]["value"] : $minDate;
    $maxDate = isset($savedSettings["maxDatePlayedPerDay"]["value"]) ? $savedSettings["maxDatePlayedPerDay"]["value"] : $maxDate;

if (isset($_GET["submitPlayedPerDay"])) {
    $playedPerDaySong = isset($_GET["playedPerDaySong"]) && !empty($_GET["playedPerDaySong"]) ? $_GET["playedPerDaySong"] : "%";
    $minDatePlayedPerDay = isset($_GET["minDatePlayedPerDay"]) && !empty($_GET["minDatePlayedPerDay"]) ? $_GET["minDatePlayedPerDay"] : $minDate;
    $maxDatePlayedPerDay = isset($_GET["maxDatePlayedPerDay"]) && !empty($_GET["maxDatePlayedPerDay"]) ? $_GET["maxDatePlayedPerDay"] : $maxDate;

    makeUpdateSetting("PlayedPerDaySong", $song);
    makeUpdateSetting("minDatePlayedPerDay", $minDate);
    makeUpdateSetting("maxDatePlayedPerDay", $maxDate);

}

    $settings = ["song"=>$song, "minDate"=>$minDate, "maxDate"=>$maxDate];
    return $settings;
}

?>
