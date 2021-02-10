<?php
function topSongsSettings($userID) {
    global $minDefaultDate, $maxDefaultDate;

    $savedSettings = savedSettings($userID, globalSettings());

    $artist = isset($savedSettings["artistTopSongs"]["value"]) ? $savedSettings["artistTopSongs"]["value"] : "%";
    $minDate = isset($savedSettings["minDateTopSongs"]["value"]) ? $savedSettings["minDateTopSongs"]["value"] : $minDefaultDate;
    $maxDate = isset($savedSettings["maxDateTopSongs"]["value"]) ? $savedSettings["maxDateTopSongs"]["value"] : $maxDefaultDate;
    $amount = isset($savedSettings["amountTopSongs"]["value"]) ? $savedSettings["amountTopSongs"]["value"] : 10;

    if (isset($_GET["submitTopSongs"])) {
	$artist = isset($_GET["artistTopSongs"]) && !empty($_GET["artistTopSongs"]) ? $_GET["artistTopSongs"] : "%";
	$minDate = isset($_GET["minDateTopSongs"]) && !empty($_GET["minDateTopSongs"]) ? $_GET["minDateTopSongs"] : $minDefaultDate;
	$maxDate = isset($_GET["maxDateTopSongs"]) && !empty($_GET["maxDateTopSongs"]) ? $_GET["maxDateTopSongs"] : $maxDefaultDate;
	$amount = isset($_GET["amountTopSongs"]) && !empty($_GET["amountTopSongs"]) ? $_GET["amountTopSongs"] : 10;

	makeUpdateSetting("artistTopSongs", $artist);
	makeUpdateSetting("minDateTopSongs", $minDate);
	makeUpdateSetting("maxDateTopSongs", $maxDate);
	makeUpdateSetting("amountTopSongs", $amount);
    }

    $settings = ["artist"=>$artist, "amount"=>$amount, "minDate"=>$minDate, "maxDate"=>$maxDate];
    return $settings;
}

?>
