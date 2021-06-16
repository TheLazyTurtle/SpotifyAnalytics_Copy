<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/mongo.php";
require "../objects/artists.php";
require "../config/core.php";

// Make database and artist object
$database = new Mongo();
$db = $database->getConnection();
$artist = new Artist($db);

// Results array
$resultsArr = array();

// Get posted data
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "%";
$amount = isset($_GET["amount"]) ? $_GET["amount"] : 10;

// Query the results
$stmt = $artist->topArtistSearch($userID, $keyword, $amount);

foreach ($stmt as $row) {
    array_push($resultsArr, $row["_id"]);
}

// If results
if (count($resultsArr) > 0) {
    // Set response to ok
    http_response_code(200);

    echo json_encode($resultsArr);
} else {

    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "No results found"));
}
?>
