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
require "../objects/artists.php";
require "../config/core.php";

$tokenUserID = validateToken();

// Make db and artists object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);
$user = new User($db);

// Get posted data
$userID = !empty($_GET["userID"]) ? $_GET["userID"] : $tokenUserID;
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;
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
$stmt = $artist->topArtist($userID, $minDate, $maxDate, $amount, $relative);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Result array
	$resultsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$resultItem = array(
			"label" => $name,
			"y" => (int)$times,
			"img" => $img,
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
