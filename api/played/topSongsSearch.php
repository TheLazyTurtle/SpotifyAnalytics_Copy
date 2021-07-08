<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/database.php";
require "../objects/played.php";
require "../config/core.php";

// Make database and song object
$database = new Database();
$db = $database->getConnection();
$song = new Played($db);

// Get posted data
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$amount = isset($_GET["amount"]) ? $_GET["amount"] : 10;

// Query the results
$stmt = $song->topSongSearch($userID, $keyword, $amount);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Make results array
	$resultsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$resultsItem = array(
			"name" => $songName,
			"artist" => $artistName,
			"songID" => $songID
		);
		array_push($resultsArr, $resultsItem);
	}

	// set response to ok
	http_response_code(200);

	echo json_encode($resultsArr);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
