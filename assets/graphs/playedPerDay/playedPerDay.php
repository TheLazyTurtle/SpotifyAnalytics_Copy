<?php
require "fetchData.php";
require "settings.php";
require "./assets/settings/settingFunctions.php";

playedPerDayForm(playedPerDaySettings(1));

echo "<div id='playedPerDay' class='graphs'></div>";
fetchData(playedPerDaySettings(1));

allSongSettings(1);

function playedPerDayForm($settings) {
    $song = $settings["song"];
    $minDate = $settings["minDate"];
    $maxDate = $settings["maxDate"];

    startForm();

    inputForm("text", "songPlayedPerDay", "Nummer naam", $song);
    inputForm("date", "minDatePlayedPerDay", "Vanaf datum", $minDate);
    inputForm("date", "maxDatePlayedPerDay", "Tot datum", $maxDate);

    endForm();
}

require "graph.php";
require "liveSearch.php";
?>

