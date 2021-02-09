<?php
function topArtistSettings($userID) {
    global $minDefaultDate, $maxDefaultDate;

    $amount = isset($savedSettings["amountTopArtist"]["value"]) ? $savedSettings["amountTopArtist"]["value"] : 10;
    $minDate = isset($savedSettings["minDateTopArtist"]["value"]) ? $savedSettings["minDateTopArtist"]["value"] : $minDefaultDate;
    $maxDate = isset($savedSettings["maxDateTopArtist"]["value"]) ? $savedSettings["maxDateTopArtist"]["value"] : $maxDefaultDate;

    if (isset($_GET["submitTopArtist"])) {
	$amount = isset($_GET["amountTopArtist"]) && !empty($_GET["amountTopArtist"]) ? $_GET["amountTopArtist"] : 10;
	$minDate = isset($_GET["minDateTopArtist"]) && !empty($_GET["minDateTopArtist"]) ? $_GET["minDateTopArtist"] : $minDefaultDate;
	$maxDate = isset($_GET["maxDateTopArtist"]) && !empty($_GET["maxDateTopArtist"]) ? $_GET["maxDateTopArtist"] : $maxDefaultDate;

	makeUpdateSetting("amountTopArtist", $amount, $userID);
	makeUpdateSetting("minDateTopArtist", $minDate, $userID);
	makeUpdateSetting("maxDateTopArtist", $maxDate, $userID);
    }
    
    $settings = ["amount"=>$amount, "minDate"=>$minDate, "maxDate"=>$maxDate];

    return $settings;
}
?>
