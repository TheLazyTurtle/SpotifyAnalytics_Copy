<?php
// Defines all the possible settings
$settings = [
    "minPlayedAllSongs",
    "maxPlayedAllSongs",
    "playedPerDaySong",
];

// Gets all the saved settings
$savedSettings = savedSettings($userID, $settings);

// This will get the setting for min and max played for the graphs all songs ever played
$minPlayedAllSongs = isset($savedSettings["minPlayedAllSongs"]["value"]) ? $savedSettings["minPlayedAllSongs"]["value"] : 0;
$maxPlayedAllSongs = isset($savedSettings["maxPlayedAllSongs"]["value"]) ? $savedSettings["maxPlayedAllSongs"]["value"] : 99999;

if (isset($_GET["submitAllSongs"])) {
    $minPlayedAllSongs = isset($_GET["minPlayedAllSongs"]) ? $_GET["minPlayedAllSongs"] : 0;
    $maxPlayedAllSongs = isset($_GET["maxPlayedAllSongs"]) ? $_GET["maxPlayedAllSongs"] : 0;

    if (checkSettingExists($userID, "minPlayedAllSongs")) {
	updateSetting($userID, "minPlayedAllSongs", $minPlayedAllSongs);
    } else {
	makeSetting($userID, "minPlayedAllSongs", $minPlayedAllSongs);
    }

    if (checkSettingExists($userID, "maxPlayedAllSongs")) {
	updateSetting($userID, "maxPlayedAllSongs", $maxPlayedAllSongs);
    } else {
	makeSetting($userID, "maxPlayedAllSongs", $maxPlayedAllSongs);
    }
}

//=============================================================================
$playedPerDaySong = isset($savedSettings["playedPerDaySong"]["value"]) ? $savedSettings["playedPerDaySong"]["value"] : "%";

if (isset($_GET["submitPlayedPerDay"])) {
    $playedPerDaySong = isset($_GET["playedPerDaySong"]) ? $_GET["playedPerDaySong"] : "%";

    if (checkSettingExists($userID, "playedPerDaySong")) {
	updateSetting($userID, "playedPerDaySong", $playedPerDaySong);
    } else {
	makeSetting($userID, "playedPerDaySong", $minPlayedAllSongs);
    }
}
?>
