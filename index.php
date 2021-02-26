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
    <div id="funInfoSliderJ"></div>
    <div id="allSongsPlayedJ"></div>
    <div id="topSongsJ"></div>
    <div id="topArtistsJ"></div>
    <div id="playedPerDayJ"></div>
</div>

</body>
<html>
