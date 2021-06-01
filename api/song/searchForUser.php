<?php
session_start();
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/database.php';
require '../objects/songs.php';

// Make db connection and new song object
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);

// Get the keyword
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$limit = isset($_GET["limit"]) ? $_GET["limit"] : 10;

// Get the songs
$stmt = $song->searchForUser($userID, $keyword, $limit);
$num = $stmt->rowCount();

// If result
if ($num > 0) {
    $songsArr = array();
    $songsArr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);

	$songItem = array (
	    "songID" => $id,
	    "name" => $name,
	    "length" => $length,
	    "url" => $url,
	    "img" => $img,
	    "dateAdded" => $dateAdded,
	    "addedBy" => $addedBy,
	    "preview" => $preview
	);
	array_push($songsArr["records"], $songItem);
    }
    // Set response to ok
    http_response_code(200);

    echo json_encode($songsArr);
} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "No songs found"));
}
?>
