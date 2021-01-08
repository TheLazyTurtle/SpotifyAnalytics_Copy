<?php
require "vendor/autoload.php";
require "connect.php";
session_start();

function getSongData() {
	$api = $_SESSION["api"];
	
	$cps = $api->getMyCurrentTrack();
	$cps = json_decode(json_encode($cps), True);

	if (!empty($cps)) {
		$progress = $cps["progress_ms"];
		$playing = $cps["is_playing"];

		$songID = $cps["item"]["id"];
		$songUrl = $cps["item"]["external_urls"]["spotify"];
		$songName = $cps["item"]["name"];
		$songImg = $cps["item"]["album"]["images"][0]["url"];
		$songDuration = $cps["item"]["duration_ms"];
		
		$artistID = array();
		$artistUrl = array();
		$artistName = array();
		
		for ($i = 0; $i <= count($cps["item"]["artists"])-1; $i++){
			array_push($artistID, $cps["item"]["artists"][$i]["id"]);
			array_push($artistUrl, $cps["item"]["artists"][$i]["external_urls"]["spotify"]);
			array_push($artistName, $cps["item"]["artists"][$i]["name"]);
		}
		
		for ($i = 0; $i<= count($artistName)-1; $i++) {
			print($artistName[$i]. " with as url ". $artistUrl[$i]);
			echo "<br>";
		}
		
		echo "Song: " . $songName . " Duration: ". $songDuration . "ms Song Url: ". $songUrl;
		echo "<br>";
		echo "You have listend to the song for: " . $progress . " ms";
		echo "<br>";
		echo "<img src='" . $songImg. "'>";
		echo "<br>";
		
		if (($progress >= 0 && $progress <= 1000) && $playing == 1) {
			insertIntoDB($artistID, $artistName, $artistUrl, $songID, $songName, $songDuration, $songUrl, $songImg);
		}
	}
}

function insertIntoDB($artistID, $artistName, $artistUrl, $songID, $songName, $songLength, $songUrl, $songImg) {
	// Gotta make some triggers and stuff to make some queries go automatic on db level
	$connection = getConnection();
	// $res = mysqli_query($connection, "INSERT INTO played (songID) VALUE (3)");

	for ($i = 0; $i <= count($artistID)-1; $i++) {
		// Inserts the artists in the db if they do not yet exsist
		mysqli_query($connection, "INSERT IGNORE INTO artist (artistID, name, url) VALUES ('$artistID[$i]', '$artistName[$i]', '$artistUrl[$i]')");
		// Links songs to artists
		mysqli_query($connection, "INSERT IGNORE INTO artistfromsong (songID, artistID) VALUES ('$songID', '$artistID[$i]')");
	}

	// Insets the song in the db if it does not yet exist
	$songName = str_replace(chr(39), " ", $songName);
	mysqli_query($connection, "INSERT IGNORE INTO song (songID, name, length, url, img) VALUES ('$songID', '$songName', '$songLength', '$songUrl', '$songImg')");

	// Insert as played
	mysqli_query($connection, "INSERT INTO played (songID) VALUE ('$songID')");

	mysqli_close($connection);
}

echo getSongData();
die();
?>