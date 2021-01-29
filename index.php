<?php
require "./assets/header.php";
require "./assets/graphs/dataFetcher.php";
require "./assets/settings/userSettings.php";
require "./assets/settings/settingForms.php";

if (isset($_SESSION["loggedIn"])) {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

    // Load the settings
    require "./assets/settings/settings.php";

    allSongs();
    topSongs();
    topArtists();
    playedPerDay();

} else {
    header("Location: ./login.php");
}


?>

<div class="test">
    <?php allSongsForm($minPlayedAllSongs, $maxPlayedAllSongs, $minDatePlayedAllSongs, $maxDatePlayedAllSongs, $artistPlayedAllSongs); ?>
    <div id="chartContainer" class="graphs"></div>

    <?php topSongsForm($artistTopSongs, $minDateTopSongs, $maxDateTopSongs, $amountTopSongs); ?>
    <div id="topSongs" class="graphs"></div>

    <?php topArtistForm($amountTopArtist, $minDateTopArtist, $maxDateTopArtist); ?>
    <div id="topArtists" class="graphs"></div>

    <?php playedPerDayForm($playedPerDaySong, $minDatePlayedPerDay, $maxDatePlayedPerDay); ?>
    <div id="playedPerDay" class="graphs"></div>

<div>

<?php 
    require "./assets/graphs/graphs.php";
?>

</body>
<html>
