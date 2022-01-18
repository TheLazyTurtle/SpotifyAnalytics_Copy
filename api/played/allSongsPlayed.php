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
require "../config/core.php";

$tokenUserID = validateToken();

// Make db and graphs object
$database = new Database();
$db = $database->getConnection();
$graph = new Played($db);
$user = new User($db);

// Get posted data 
$userID = !empty($_GET["userID"]) ? $_GET["userID"] : $tokenUserID;
$minPlayed = isset($_GET["minPlayed"]) && !empty($_GET["minPlayed"]) ? $_GET["minPlayed"] : $minPlayed_def;
$maxPlayed = isset($_GET["maxPlayed"]) && !empty($_GET["maxPlayed"]) ? $_GET["maxPlayed"] : $maxPlayed_def;
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;
$relative = isset($_GET["relative"]) && $_GET["relative"] == "true" ? True : False;

if ($relative && $maxPlayed == 9999) {
	$maxPlayed = 999999;
}

// Check if you have viewing rights
if (!$tokenUserID) {
	$hasViewingRights = hasViewingRights($tokenUserID, $userID);
	if (!$hasViewingRights) {
		http_response_code(400);
		echo json_encode(array("message" => "No viewing rights"));
		die();
	}
}

// Query results
$stmt = $graph->allSongsPlayed($userID, $minPlayed, $maxPlayed, $minDate, $maxDate, $relative);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Make result array
	$resultsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		$resultItem = array(
			"label" => $name,
			"y" => (int)$times,
			"albumID" => $albumID
		);
		array_push($resultsArr, $resultItem);
	}

	// set response to ok
	http_response_code(200);

	echo json_encode($resultsArr);
} else {
	// Set response to bad request 
	http_response_code(200);

	echo json_encode(array("message" => "No data found"));
}
