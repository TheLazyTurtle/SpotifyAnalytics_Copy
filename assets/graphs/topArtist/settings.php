<?php
function topArtistSettings($userID) {
    global $minDefaultDate, $maxDefaultDate;

    $amount = isset($savedSettings["amountTopArtist"]["value"]) ? $savedSettings["amountTopArtist"]["value"] : 10;
    $minDate = isset($savedSettings["minDateTopArtist"]["value"]) ? $savedSettings["minDateTopArtist"]["value"] : $minDefaultDate;
    $maxDate = isset($savedSettings["maxDateTopArtist"]["value"]) ? $savedSettings["maxDateTopArtist"]["value"] : $maxDefaultDate;

    $settings = ["amount"=>$amount, "minDate"=>$minDate, "maxDate"=>$maxDate];

    return $settings;
}
?>
