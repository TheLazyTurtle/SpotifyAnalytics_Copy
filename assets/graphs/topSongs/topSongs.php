<?php
session_start();

require "fetchData.php";
require "settings.php";
require "../../connect.php";
require "../../settings/formBuilder.php";
require "../../settings/settingFunctions.php";

$spID = $_SESSION["spID"];
$userID = $_SESSION["userID"];

topSongsForm(topSongsSettings($userID));

echo '<div id="topSongs" class="graphs"></div>';
fetchData($spID, topSongsSettings($userID));

topSongsSettings($userID);

// The form with the settings 
function topSongsForm($settings) {
    $artist = $settings["artist"];
    $amount = $settings["amount"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    startForm();

    inputForm("text", "artistTopSongs", "Artiest naam", $artist, True);
    inputForm("number", "amountTopSongs", "Top hoeveel", $amount);
    inputForm("date", "minDateTopSongs", "Vanaf datum", $minDate);
    inputForm("date", "maxDateTopSongs", "Vanaf datum", $maxDate);

    endForm();
}

require "graph.php";
?>

<script src="./assets/graphs/topSongs/liveSearch.js"></script>

