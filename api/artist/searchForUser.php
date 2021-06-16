<?php
session_start();
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/mongo.php';
require '../objects/artists.php';

// Make db connecton and new artist object
$database = new Mongo();
$db = $database->getConnection();
$artist = new Artist($db);

// Result array
$artistArr = array();
$artistArr["records"] = array();

// Get the keywords
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$limit = isset($_GET["limit"]) ? $_GET["limit"] : 10;

// Get the artist
$stmt = $artist->serachForuser($userID, $keyword, $limit);

foreach ($stmt as $row) {

    $artistItem = array(
	"artistID" => $row["artistID"],
	"name" => $row["name"],
    );

    array_push($artistArr["records"], $artistItem);
}

// If result
if (count($artistArr["records"]) > 0) {
    // Set response to ok
    http_response_code(200);

    echo json_encode($artistArr);
} else {
    // Set response code to bad request
    http_response_code(400);

    echo json_encode(array("message" => "No artist found"));
}
?>

