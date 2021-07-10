<?php
session_start();
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/database.php';
require '../objects/artists.php';

// Make db connecton and new artist object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);

// Get the keywords
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$keyword = isset($_POST["keyword"]) ? $_POST["keyword"] : "";
$limit = isset($_POST["limit"]) ? $_POST["limit"] : 10;

// Get the artist
$stmt = $artist->serachForuser($userID, $keyword, $limit);
$num = $stmt->rowCount();

// If result
if ($num > 0) {
	// Result array
	$artistArr = array();
	$artistArr["records"] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$artistItem = array(
			"artistID" => $artistID,
			"name" => $name,
		);

		array_push($artistArr["records"], $artistItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($artistArr);
} else {
	// Set response code to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No artist found"));
}
