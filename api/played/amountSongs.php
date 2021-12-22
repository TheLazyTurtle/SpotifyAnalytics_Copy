<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../system/validate_token.php";
require "../config/database.php";
require "../objects/played.php";
require "../config/core.php";

if (!$tokenUserID = validateToken()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db and graphs object
$database = new Database();
$db = $database->getConnection();
$song = new Played($db);

// Get posted data
$userID = !empty($_GET["userID"]) ? $_GET["userID"] : $tokenUserID;
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;

// Query the results
$stmt = $song->amountOfSongs($userID, $minDate, $maxDate);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Make result array
	$resultsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$resultItem = array(
			"y" => $times,
			"img" => $issuer . "assets/img/onRepeat.jpg",
			"label" => ""
		);
		array_push($resultsArr, $resultItem);
	}

	// set response to ok
	http_response_code(200);

	echo json_encode($resultsArr);
} else {
	// set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
