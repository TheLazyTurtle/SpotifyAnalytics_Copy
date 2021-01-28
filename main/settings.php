<?php
// Defines all the settings
$settings = [
    "minPlayedAllSongs",
    "maxPlayedAllSongs",
    "minDatePlayedAllSongs",
    "maxDatePlayedAllSongs",

    "artistPlayedAllSongs",
    "artistTopSongs",
    "minDateTopSongs",
    "maxDateTopSongs",

    "playedPerDaySong",
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
// This will get the setting for min and max played for the graphs all songs ever played
$minPlayedAllSongs = isset($savedSettings["minPlayedAllSongs"]["value"]) ? $savedSettings["minPlayedAllSongs"]["value"] : 0;
$maxPlayedAllSongs = isset($savedSettings["maxPlayedAllSongs"]["value"]) ? $savedSettings["maxPlayedAllSongs"]["value"] : 99999;
$minDatePlayedAllSongs = isset($savedSettings["minDatePlayedAllSongs"]["value"]) ? $savedSettings["minDatePlayedAllSongs"]["value"] : "2020-01-01";
$maxDatePlayedAllSongs = isset($savedSettings["maxDatePlayedAllSongs"]["value"]) ? $savedSettings["maxDatePlayedAllSongs"]["value"] : "2099-12-31";
$artistPlayedAllSongs = isset($savedSettings["artistPlayedAllSongs"]["value"]) ? $savedSettings["artistPlayedAllSongs"]["value"] : "%";

if (isset($_GET["submitAllSongs"])) {
    $minPlayedAllSongs = isset($_GET["minPlayedAllSongs"]) && !empty($_GET["minPlayedAllSongs"]) ? $_GET["minPlayedAllSongs"] : 0;
    $maxPlayedAllSongs = isset($_GET["maxPlayedAllSongs"]) && !empty($_GET["maxPlayedAllSongs"]) ? $_GET["maxPlayedAllSongs"] : 99999;
    $minDatePlayedAllSongs = isset($_GET["minDatePlayedAllSongs"]) && !empty($_GET["minDatePlayedAllSongs"]) ? $_GET["minDatePlayedAllSongs"] : "2020-01-01";
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
$minDateTopSongs = isset($savedSettings["minDateTopSongs"]["value"]) ? $savedSettings["minDateTopSongs"]["value"] : "2020-01-01";
$maxDateTopSongs = isset($savedSettings["maxDateTopSongs"]["value"]) ? $savedSettings["maxDateTopSongs"]["value"] : "2099-12-31";

if (isset($_GET["submitTopSongs"])) {
    $artistTopSongs = isset($_GET["artistTopSongs"]) && !empty($_GET["artistTopSongs"]) ? $_GET["artistTopSongs"] : "%";
    $minDateTopSongs = isset($_GET["minDateTopSongs"]) && !empty($_GET["minDateTopSongs"]) ? $_GET["minDateTopSongs"] : "2020-01-01";
    $maxDateTopSongs = isset($_GET["maxDateTopSongs"]) && !empty($_GET["maxDateTopSongs"]) ? $_GET["maxDateTopSongs"] : "2099-12-31";
    echo $minDateTopSongs;

    makeUpdateSetting("artistTopSongs", $artistTopSongs);
    makeUpdateSetting("minDateTopSongs", $minDateTopSongs);
    makeUpdateSetting("maxDateTopSongs", $maxDateTopSongs);
}

//=============================================================================
$playedPerDaySong = isset($savedSettings["playedPerDaySong"]["value"]) ? $savedSettings["playedPerDaySong"]["value"] : "%";

if (isset($_GET["submitPlayedPerDay"])) {
    $playedPerDaySong = isset($_GET["playedPerDaySong"]) && !empty($_GET["playedPerDaySong"]) ? $_GET["playedPerDaySong"] : "%";

    makeUpdateSetting("playedPerDaySong", $playedPerDaySong); 
}


?>
