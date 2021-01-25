<?php
// Defines all the possible settings
$settings = [
    "minPlayedAllSongs",
    "maxPlayedAllSongs",
];

// This will get the setting for min and max played for the graphs all songs ever played
$savedSettings = savedSettings($userID, $settings);
print_r($savedSettings);
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

?>
