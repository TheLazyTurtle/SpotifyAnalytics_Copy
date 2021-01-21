<?php
require "header.php";
require "dataFunctions.php";

if (isset($_SESSION["loggedIn"])) {
	$spID = $_SESSION["spID"];

	allSongs();
	topSongs();
	topArtists();
	playedPerDay("Slow Down");
} else {
	header("Location: login.php");
}
?>

<div class="test">
    <?php
	require "./graphs/graphSettings.php";
    ?>
<div>

</body>
<html>
