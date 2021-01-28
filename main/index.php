<?php
require "header.php";
require "dataFunctions.php";
require "userSettings.php";
require "settingForms.php";

if (isset($_SESSION["loggedIn"])) {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

    // Load the settings
    require "settings.php";

    allSongs();
    topSongs();
    topArtists();
    playedPerDay();

} else {
    header("Location: login.php");
}


?>

<div class="test">
    <?php allSongsForm($minPlayedAllSongs, $maxPlayedAllSongs, $minDatePlayedAllSongs, $maxDatePlayedAllSongs, $artistPlayedAllSongs); ?>
    <div id="chartContainer" class="graphs"></div>

    <?php topSongsForm($artistTopSongs, $minDateTopSongs, $maxDateTopSongs); ?>
    <div id="topSongs" class="graphs"></div>

    <div id="topArtists" class="graphs"></div>

    <?php playedPerDayForm($playedPerDaySong); ?>
    <div id="playedPerDay" class="graphs"></div>

<div>

<?php 
    require "./graphs/graphSettings.php";
?>

</body>
<html>
