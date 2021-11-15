<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/database.php";
require "../objects/played.php";
require "../config/core.php";

// Make db and graphs object
$database = new Database();
$db = $database->getConnection();
$graph = new Played($db);

// Get posted data 
$userID = isset($_POST["userID"]) ? $_POST["userID"] : $_SESSION["userID"];
$minPlayed = isset($_POST["minPlayed"]) && !empty($_POST["minPlayed"]) ? $_POST["minPlayed"] : $minPlayed_def;
$maxPlayed = isset($_POST["maxPlayed"]) && !empty($_POST["maxPlayed"]) ? $_POST["maxPlayed"] : $maxPlayed_def;
$minDate = isset($_POST["minDate"]) ? $_POST["minDate"] : $minDate_def;
$maxDate = isset($_POST["maxDate"]) ? $_POST["maxDate"] : $maxDate_def;

// Query results
$stmt = $graph->allSongsPlayed($userID, $minPlayed, $maxPlayed, $minDate, $maxDate);
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
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
