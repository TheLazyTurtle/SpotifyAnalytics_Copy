<?php
require "header.php";
require "dataFunctions.php";
require "userSettings.php";

$settings = [
    "minPlayedAllSongs", 
];

if (isset($_SESSION["loggedIn"])) {
    $spID = $_SESSION["spID"];
    $userID = $_SESSION["userID"];
    print($userID. "user");

    $savedSettings = savedSettings($userID, $settings);
    print_r($savedSettings);

    if (isset($_GET["submitAllSongs"])) {
	$minPlayedAllSongs = isset($_GET["minPlayedAllSongs"]) ? $_GET["minPlayedAllSongs"] : 0;

	if (checkSettingExists($userID, "minPlayedAllSongs")) {
	    print("HEYHO");
	    updateSetting($userID, "minPlayedAllSongs", $minPlayedAllSongs);
	} else {
	    print("LOLZ");
	    makeSetting($userID, "minPlayedAllSongs", $minPlayedAllSongs);
	}
    }

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
    <input type="number" name="minPlayedAllSongs" placeholder="Minimaal afgespeeld" value="<?php echo $minPlayed ?>">
	<input type="submit" name="submitAllSongs" value="submit">
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
