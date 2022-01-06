<?php
// TODO: Make the search be based on populairity
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/database.php';
require '../objects/artists.php';
require '../objects/user.php';

// Make db connection and artist and user object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);
$user = new User($db);

// Result arrays
$resultsArr = array();

// Get keyword
$keyword = isset($_POST["keyword"]) ? $_POST["keyword"] : "";

// Get artist and users
$songStmt = $artist->search($keyword);
$userStmt = $user->search($keyword);

// If there are results
if ($songStmt->rowCount() > 0) {
	while ($row = $songStmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$resultItem = array(
			"id" => $artistID,
			"name" => $name,
			"img" => $img,
			"type" => "artist"
		);
		array_push($resultsArr, $resultItem);
	}
}

if ($userStmt->rowCount() > 0) {
	while ($row = $userStmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$resultItem = array(
			"name" => $name,
			"img" => $img,
			"type" => "user"
		);
		array_push($resultsArr, $resultItem);
	}
}

if (count($resultsArr) > 0) {
	// Set response to ok
	http_response_code(200);

	echo json_encode($resultsArr);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
