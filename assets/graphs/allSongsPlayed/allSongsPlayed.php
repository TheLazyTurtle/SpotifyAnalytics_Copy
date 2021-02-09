<?php
session_start();

require "fetchData.php";
require "settings.php";
require "../../connect.php";
require "../../settings/formBuilder.php";
require "../../settings/settingFunctions.php";

$spID = $_SESSION["spID"];
$userID = $_SESSION["userID"];

allSongsForm(allSongsSettings($userID)); 

echo '<div id="chartContainer" class="graphs"></div>';
fetchData($spID, allSongsSettings($userID));

allSongsSettings($userID);

// The form with the settings for all songs ever played
function allSongsForm($settings) {
    $artist = $settings["artist"];
    $minPlayed = $settings["minPlayed"];
    $maxPlayed = $settings["maxPlayed"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    startForm();

    inputForm("text", "artistPlayedAllSongs", "Artiest naam", $artist, True);
    inputForm("number", "minPlayedAllSongs", "Minimaal afgespeeld", $minPlayed);
    inputForm("number", "maxPlayedAllSongs", "Minimaal afgespeeld", $maxPlayed);
    inputForm("date", "minDatePlayedAllSongs", "Vanaf datum", $minDate);
    inputForm("date", "maxDatePlayedAllSongs", "Tot datum", $maxDate);
    submitForm("submitAllSongs");

    endForm(); 
}

require "graph.php";

?>

<script src="./assets/graphs/allSongsPlayed/liveSearch.js"></script>

