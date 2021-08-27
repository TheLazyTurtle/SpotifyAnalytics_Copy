<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/database.php";
require "../objects/artists.php";
require "../objects/played.php";
require "../config/core.php";

// Make db and artist object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);
$played = new Played($db);

// Get posted data
$artistID = isset($_POST["artistID"]) ? $_POST["artistID"] : die();
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : "";

// Query the results
$stmt = $artist->topSongs($artistID);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Make result arrays
	$resultsArr = array();
	$resultsArr["records"] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		$resultItem = array(
			"title" => $title,
			"preview" => $preview,
			"img" => $img,
			"count" => $count,
			"url" => $url,
			"userCount" => $played->songCount($userID, $songID)
		);
		array_push($resultsArr["records"], $resultItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($resultsArr);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No songs found"));
}
