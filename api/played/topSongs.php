<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../system/validate_token.php";
require "../system/hasViewingRights.php";
require_once "../config/database.php";
require_once "../objects/user.php";
require "../objects/played.php";
require "../objects/songs.php";
require "../config/core.php";

$tokenUserID = validateToken();

// Make db and songs object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);
$song = new Song($db);
$user = new User($db);

// Get posted data
$userID = !empty($_GET["userID"]) ? $_GET["userID"] : $tokenUserID;
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;
$artist = isset($_GET["artist"]) && !empty($_GET["artist"]) ? $_GET["artist"] : "";
$amount = isset($_GET["amount"]) && !empty($_GET["amount"]) ? $_GET["amount"] : 10;
$relative = isset($_GET["relative"]) && $_GET["relative"] == "true" ? True : False;

// Check if you have viewing rights
if (!$tokenUserID) {
	$hasViewingRights = hasViewingRights($tokenUserID, $userID);
	if (!$hasViewingRights) {
		http_response_code(400);
		echo json_encode(array("message" => "No viewing rights"));
		die();
	}
}

// Query the results
$stmt = $played->topSongs($userID, $artist, $minDate, $maxDate, $amount, $relative);
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
			"y" => (double)round($times, 2),
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
