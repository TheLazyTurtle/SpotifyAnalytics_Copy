<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/database.php";
require "../objects/played.php";
require "../objects/songs.php";
require "../config/core.php";

// Make db and songs object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);
$song = new Song($db);

// Get posted data
$userID = isset($_POST["userID"]) && !empty($_POST["userID"]) ? $_POST["userID"] : $_SESSION["userID"];
$minDate = isset($_POST["minDate"]) ? $_POST["minDate"] : $minDate_def;
$maxDate = isset($_POST["maxDate"]) ? $_POST["maxDate"] : $maxDate_def;
$artist = isset($_POST["artist"]) && !empty($_POST["artist"]) ? $_POST["artist"] : "";
$amount = isset($_POST["amount"]) && !empty($_POST["amount"]) ? $_POST["amount"] : 10;

// Query the results
$stmt = $played->topSongs($userID, $artist, $minDate, $maxDate, $amount);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Make results array
	$resultsArr = array();

	// Load results
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		$resultItem = array(
			"label" => $songName,
			"y" => (int)$times,
			"img" => $song->getImage($songID),
			"albumID" => $albumID
		);
		array_push($resultsArr, $resultItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($resultsArr);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
