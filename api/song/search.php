<?php
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/database.php';
require '../objects/songs.php';

//  Make db connection and new song object
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);

// Get the keyword
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";

// Get the songs 
$stmt = $song->search($keyword);
$num = $stmt->rowCount();

// If there are results
if ($num > 0) {
	// Make results array
	$songsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$songItem = array(
			"songID" => $songID,
			"name" => $name,
			"length" => $length,
			"url" => $url,
			"img" => $img,
			"preview" => $preview,
		);
		array_push($songsArr, $songItem);
	}

	// Set response code to ok
	http_response_code(200);

	echo json_encode($songsArr);
} else {
	// Set response code to bad request 
	http_response_code(400);

	echo json_encode(array("message" => "No songs found"));
}
