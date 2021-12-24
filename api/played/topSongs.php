<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../system/validate_token.php";
require "../config/database.php";
require "../objects/played.php";
require "../objects/songs.php";
require "../config/core.php";

if (!$tokenUserID = validateToken()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db and songs object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);
$song = new Song($db);

// Get posted data
$userID = !empty($_GET["userID"]) ? $_GET["userID"] : $tokenUserID;
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;
$artist = isset($_GET["artist"]) && !empty($_GET["artist"]) ? $_GET["artist"] : "";
$amount = isset($_GET["amount"]) && !empty($_GET["amount"]) ? $_GET["amount"] : 10;

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
	http_response_code(200);

	echo json_encode(null);
}
