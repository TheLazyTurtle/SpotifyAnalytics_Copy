<?php
require "header.php";
require "dataFunctions.php";
require "userSettings.php";

if (isset($_SESSION["loggedIn"])) {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];

    // Load the settings
    require "settings.php";

    allSongs();
    topSongs();
    topArtists();
    playedPerDay("Slow Down");

} else {
    header("Location: login.php");
}


?>

<div class="test">

    <form action="#" method="GET">
    <input type="number" name="minPlayedAllSongs" placeholder="Minimaal afgespeeld" value="<?php echo $minPlayedAllSongs ?>">
    <input type="number" name="maxPlayedAllSongs" placeholder="Maximaal afgespeeld" value="<?php echo $maxPlayedAllSongs ?>">
	<input type="submit" name="submitAllSongs" value="update">
    </form>

    <div id="chartContainer" class="graphs"></div>
    <div id="topSongs" class="graphs"></div>
    <div id="topArtists" class="graphs"></div>
    <div id="playedPerDay" class="graphs"></div>

<div>

<?php 
    require "./graphs/graphSettings.php";
?>

</body>
<html>
