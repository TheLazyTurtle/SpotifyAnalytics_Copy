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

// Make db and songs object
$database = new Database();
$db = $database->getConnection();
$song = new Played($db);

// Get posted data
$userID = !empty($_GET["userID"]) ? $_GET["userID"] : $tokenUserID;
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;

// Query the results
$stmt = $song->timeListend($userID, $minDate, $maxDate);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Results arrays
	$resultsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$time = $time != null ? $time : "00:00:00";

		$resultItem = array(
			"y" => $time,
			"img" => "https://i.pinimg.com/736x/f9/4c/95/f94c9574933ce9404f323fb58f5e7f5c.jpg",
			"label" => "",
			"totalTime" => "totalTime"
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
