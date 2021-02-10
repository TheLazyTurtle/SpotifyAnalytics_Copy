<?php
session_start();

require "fetchData.php";
require "settings.php";
require "../../connect.php";
require "../../settings/formBuilder.php";
require "../../settings/settingFunctions.php";

$spID = $_SESSION["spID"];
$userID = $_SESSION["userID"];

playedPerDayForm(playedPerDaySettings($userID));

echo "<div id='playedPerDay' class='graphs'></div>";
fetchData($spID, playedPerDaySettings($userID));

playedPerDaySettings($userID);

function playedPerDayForm($settings) {
    $song = $settings["song"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    startForm();

    inputForm("text", "songPlayedPerDay", "Nummer naam", $song, True);
    inputForm("date", "minDatePlayedPerDay", "Vanaf datum", $minDate);
    inputForm("date", "maxDatePlayedPerDay", "Tot datum", $maxDate);

    endForm();
}

require "graph.php";

?>

<script src="assets/graphs/playedPerDay/liveSearch.js"></script>

