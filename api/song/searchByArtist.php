<?php
session_start();

// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/mongo.php';
require '../objects/songs.php';

//  Make db connection and new song object
$database = new Mongo();
$db = $database->getConnection();
$song = new Song($db);

// Make results array
$songsArr = array();

// Get the keywords
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$songName = isset($_GET["song"]) && !empty($_GET['song']) ? $_GET["song"] : "";
$artist = isset($_GET["artist"]) && !empty($_GET['artist']) ? $_GET["artist"] : "";

// Get songs
$stmt = $song->searchByArtist($userID, $songName, $artist);

foreach ($stmt as $row) {
    array_push($songsArr, $row["songID"]);
}

// If there are results
if (count($songsArr) > 0) {
    // Set response to ok
    http_response_code(200);

    echo json_encode($songsArr);
} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "No songs found"));
}

?>
