<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../system/validate_token.php";
require "../config/database.php";
require "../objects/artists.php";
require "../config/core.php";

if (!$tokenUserID = validateToken()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make database and artist object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);

// Get posted data
$userID = isset($_GET["userID"]) ? $_GET["userID"] : $tokenUserID;
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "%";
$amount = isset($_GET["amount"]) ? $_GET["amount"] : 10;

// Query the results
$stmt = $artist->topArtistSearch($userID, $keyword, $amount);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Results array
	$resultsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		array_push($resultsArr, $name);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($resultsArr);
} else {

	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
