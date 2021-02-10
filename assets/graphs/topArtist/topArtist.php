<?php
session_start();

require "fetchData.php";
require "settings.php";
require "../../connect.php";
require "../../settings/formBuilder.php";
require "../../settings/settingFunctions.php";

$spID = $_SESSION["spID"];
$userID = $_SESSION["userID"];

topArtistForm(topArtistSettings($userID));

echo '<div id="topArtists" class="graphs"></div>';
fetchData($spID, topArtistSettings($userID));

topArtistSettings($userID);

// Form with settings
function topArtistForm($settings) {
    $amount = $settings["amount"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    startForm();

    inputForm("number", "amountTopArtist", "Top hoeveel", $amount);
    inputForm("date", "minDateTopArtist", "Vanaf datum", $minDate);
    inputForm("date", "maxDateTopArtist", "Vanaf datum", $maxDate);

    endForm(); 
}

require "graph.php";
?>

<script src="./assets/graphs/topArtist/liveSearch.js"></script>
