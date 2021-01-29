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

?>
