<?php
require "./assets/header.php";

if (isset($_SESSION["loggedIn"])) {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

    // Load the settings
    //topSongs($artistTopSongs, $minDateTopSongs, $maxDateTopSongs, $amountTopSongs);
    //topArtists($amountTopArtist, $minDateTopArtist, $maxDateTopArtist);
    //playedPerDay($playedPerDaySong, $minDatePlayedPerDay, $maxDatePlayedPerDay);

} else {
    // If the user is not logged in than send them to the login page
    header("Location: ./login.php");
}

?>

<div class="test">
<script>
$(document).ready(function() {
    $("#allSongsPlayedJ").load("./assets/graphs/allSongsPlayed/allSongsPlayed.php");
    //$("#playedPerDayJ").load("./assets/graphs/playedPerDay/playedPerDay.php");
})

</script>


    <?php //topSongsForm($artistTopSongs, $minDateTopSongs, $maxDateTopSongs, $amountTopSongs); ?>
    <!--<div id="topSongs" class="graphs"></div>-->

    <?php //topArtistForm($amountTopArtist, $minDateTopArtist, $maxDateTopArtist); ?>
<!--    <div id="topArtists" class="graphs"></div>-->

<div id="allSongsPlayedJ"></div>
<div id="playedPerDayJ"></div>

<div>

<?php 
    //require "./assets/settings/graphSettings.php";
?>

</body>
<html>
