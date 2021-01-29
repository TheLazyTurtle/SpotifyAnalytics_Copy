<?php
// Defines all the settings
$minDate = "2020-01-01";
$maxDate = "2099-12-31";

$settings = [
    "minPlayedAllSongs",
    "maxPlayedAllSongs",
    "minDatePlayedAllSongs",
    "maxDatePlayedAllSongs",

    "artistPlayedAllSongs",
    "artistTopSongs",
    "minDateTopSongs",
    "maxDateTopSongs",
    "amountTopSongs",

    "amountTopArtist",
    "minDateTopArtist",
    "maxDateTopArtist",

    "playedPerDaySong",
    "minDatePlayedPerDay",
    "maxDatePlayedPerDay",
];

function makeUpdateSetting($settingName, $settingValue) {
    global $userID;

    if (checkSettingExists($userID, $settingName)) {
	updateSetting($userID, $settingName, $settingValue);
    } else {
	makeSetting($userID, $settingName, $settingValue);
    }
}

// Gets all the saved settings
$savedSettings = savedSettings($userID, $settings);

//=============================================================================
$minPlayedAllSongs = isset($savedSettings["minPlayedAllSongs"]["value"]) ? $savedSettings["minPlayedAllSongs"]["value"] : 0;
$maxPlayedAllSongs = isset($savedSettings["maxPlayedAllSongs"]["value"]) ? $savedSettings["maxPlayedAllSongs"]["value"] : 99999;
$minDatePlayedAllSongs = isset($savedSettings["minDatePlayedAllSongs"]["value"]) ? $savedSettings["minDatePlayedAllSongs"]["value"] : $minDate;
$maxDatePlayedAllSongs = isset($savedSettings["maxDatePlayedAllSongs"]["value"]) ? $savedSettings["maxDatePlayedAllSongs"]["value"] : $maxDate;
$artistPlayedAllSongs = isset($savedSettings["artistPlayedAllSongs"]["value"]) ? $savedSettings["artistPlayedAllSongs"]["value"] : "%";

if (isset($_GET["submitAllSongs"])) {
    $minPlayedAllSongs = isset($_GET["minPlayedAllSongs"]) && !empty($_GET["minPlayedAllSongs"]) ? $_GET["minPlayedAllSongs"] : 0;
    $maxPlayedAllSongs = isset($_GET["maxPlayedAllSongs"]) && !empty($_GET["maxPlayedAllSongs"]) ? $_GET["maxPlayedAllSongs"] : 99999;
    $minDatePlayedAllSongs = isset($_GET["minDatePlayedAllSongs"]) && !empty($_GET["minDatePlayedAllSongs"]) ? $_GET["minDatePlayedAllSongs"] : $minDate;
    $maxDatePlayedAllSongs = isset($_GET["maxDatePlayedAllSongs"]) && !empty($_GET["maxDatePlayedAllSongs"]) ? $_GET["maxDatePlayedAllSongs"] : "2099-12-21";
    $artistPlayedAllSongs = isset($_GET["artistPlayedAllSongs"]) && !empty($_GET["artistPlayedAllSongs"]) ? $_GET["artistPlayedAllSongs"] : "%";

    makeUpdateSetting("minPlayedAllSongs", $minPlayedAllSongs);
    makeUpdateSetting("maxPlayedAllSongs", $maxPlayedAllSongs);
    makeUpdateSetting("minDatePlayedAllSongs", $minDatePlayedAllSongs);
    makeUpdateSetting("maxDatePlayedAllSongs", $maxDatePlayedAllSongs);
    makeUpdateSetting("artistPlayedAllSongs", $artistPlayedAllSongs);
}

//=============================================================================
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

//=============================================================================
$amountTopArtist = isset($savedSettings["amountTopArtist"]["value"]) ? $savedSettings["amountTopArtist"]["value"] : 10;
$minDateTopArtist = isset($savedSettings["minDateTopArtist"]["value"]) ? $savedSettings["minDateTopArtist"]["value"] : $minDate;
$maxDateTopArtist = isset($savedSettings["maxDateTopArtist"]["value"]) ? $savedSettings["maxDateTopArtist"]["value"] : $maxDate;

if (isset($_GET["submitTopArtist"])) {
    $amountTopArtist = isset($_GET["amountTopArtist"]) && !empty($_GET["amountTopArtist"]) ? $_GET["amountTopArtist"] : 10;
    $minDateTopArtist = isset($_GET["minDateTopArtist"]) && !empty($_GET["minDateTopArtist"]) ? $_GET["minDateTopArtist"] : $minDate;
    $maxDateTopArtist = isset($_GET["maxDateTopArtist"]) && !empty($_GET["maxDateTopArtist"]) ? $_GET["maxDateTopArtist"] : $maxDate;

    makeUpdateSetting("amountTopArtist", $amountTopArtist);
    makeUpdateSetting("minDateTopArtist", $minDateTopArtist);
    makeUpdateSetting("maxDateTopArtist", $maxDateTopArtist);
}

//=============================================================================
$playedPerDaySong = isset($savedSettings["playedPerDaySong"]["value"]) ? $savedSettings["playedPerDaySong"]["value"] : "%";
$minDatePlayedPerDay = isset($savedSettings["minDatePlayedPerDay"]["value"]) ? $savedSettings["minDatePlayedPerDay"]["value"] : $minDate;
$maxDatePlayedPerDay = isset($savedSettings["maxDatePlayedPerDay"]["value"]) ? $savedSettings["maxDatePlayedPerDay"]["value"] : $maxDate;

if (isset($_GET["submitPlayedPerDay"])) {
    $playedPerDaySong = isset($_GET["playedPerDaySong"]) && !empty($_GET["playedPerDaySong"]) ? $_GET["playedPerDaySong"] : "%";
    $minDatePlayedPerDay = isset($_GET["minDatePlayedPerDay"]) && !empty($_GET["minDatePlayedPerDay"]) ? $_GET["minDatePlayedPerDay"] : $minDate;
    $maxDatePlayedPerDay = isset($_GET["maxDatePlayedPerDay"]) && !empty($_GET["maxDatePlayedPerDay"]) ? $_GET["maxDatePlayedPerDay"] : $maxDate;

    makeUpdateSetting("playedPerDaySong", $playedPerDaySong); 
}


?>
