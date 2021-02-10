<?php
require "./assets/header.php";

if (isset($_SESSION["loggedIn"])) {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

} else {
    // If the user is not logged in than send them to the login page
    header("Location: ./login.php");
}

?>

<div class="test">
<script>
$(document).ready(function() {
    $("#allSongsPlayedJ").load("./assets/graphs/allSongsPlayed/allSongsPlayed.php");
    $("#topArtistsJ").load("./assets/graphs/topArtist/topArtist.php");
    $("#playedPerDayJ").load("./assets/graphs/playedPerDay/playedPerDay.php");
})

</script>

<div id="allSongsPlayedJ"></div>
<div id="topArtistsJ"></div>
<div id="playedPerDayJ"></div>

<div>

<?php 
    //require "./assets/settings/graphSettings.php";
?>

</body>
<html>
