<?php
session_start();
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/mongo.php';
require '../objects/songs.php';

// Make db connection and new song object
$database = new Mongo();
$db = $database->getConnection();
$song = new Song($db);

// Make results array
$songsArr = array();
$songsArr["records"] = array();

// Get the keyword
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$keyword = isset($_POST["keyword"]) ? $_POST["keyword"] : "";
$limit = isset($_POST["limit"]) ? $_POST["limit"] : 10;

// Get the songs
$stmt = $song->searchForUser($userID, $keyword, $limit);

foreach ($stmt as $row) {
	$songItem = array(
		"songID" => $row["songID"],
		"name" => $$row["name"],
		"length" => $row["length"],
		"url" => $row["url"],
		"img" => $row["img"],
		"dateAdded" => $row["dateAdded"]->toDateTime()->format("Y-m-d H:i:s"),
		"addedBy" => $row["addedBy"],
		"preview" => $row["preview"]
	);
	array_push($songsArr["records"], $songItem);
}

if (count($songsArr["records"]) > 0) {
	// Set response to ok
	http_response_code(200);

	echo json_encode($songsArr);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No songs found"));
}
