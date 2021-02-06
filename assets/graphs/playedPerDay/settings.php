<?php
function playedPerDaySettings($userID) {
    global $minDefaultDate, $maxDefaultDate;
    $savedSettings = savedSettings($userID, globalSettings());

    $song = isset($savedSettings["playedPerDaySong"]["value"]) ? $savedSettings["playedPerDaySong"]["value"] : "%";
    $minDate = isset($savedSettings["minDatePlayedPerDay"]["value"]) ? $savedSettings["minDatePlayedPerDay"]["value"] : $minDefaultDate;
    $maxDate = isset($savedSettings["maxDatePlayedPerDay"]["value"]) ? $savedSettings["maxDatePlayedPerDay"]["value"] : $maxDefaultDate;

if (isset($_GET["submitPlayedPerDay"])) {
    $song = isset($_GET["playedPerDaySong"]) && !empty($_GET["playedPerDaySong"]) ? $_GET["playedPerDaySong"] : "%";
    $minDate = isset($_GET["minDatePlayedPerDay"]) && !empty($_GET["minDatePlayedPerDay"]) ? $_GET["minDatePlayedPerDay"] : $minDefaultDate;
    $maxDate = isset($_GET["maxDatePlayedPerDay"]) && !empty($_GET["maxDatePlayedPerDay"]) ? $_GET["maxDatePlayedPerDay"] : $maxDefaultDate;

    makeUpdateSetting("PlayedPerDaySong", $song, $userID);
    makeUpdateSetting("minDatePlayedPerDay", $minDate, $userID);
    makeUpdateSetting("maxDatePlayedPerDay", $maxDate, $userID);

}

    $settings = ["song"=>$song, "minDate"=>$minDate, "maxDate"=>$maxDate];
    return $settings;
}

?>
