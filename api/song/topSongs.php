<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/mongo.php";
require "../objects/songs.php";
require "../config/core.php";

// Make db and songs object
$database = new Mongo();
$db = $database->getConnection();
$song = new Song($db);

// Make results array
$resultsArr = array();
$resultsArr["records"] = array();

// Get posted data
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;
$artist = isset($_GET["artist"]) && !empty($_GET["artist"]) ? $_GET["artist"] : "";
$amount = isset($_GET["amount"]) && !empty($_GET["amount"]) ? $_GET["amount"] : 10;

// Query the results
$stmt = $song->topSongs($userID, $artist, $minDate, $maxDate, $amount);

// Load results
foreach ($stmt as $row) {
    $resultItem = array(
	"label" => $row["name"],
	"y" => $row["count"],
    );
    array_push($resultsArr["records"], $resultItem);
}

// If results
if (count($resultsArr["records"])) {
    // Set response to ok
    http_response_code(200);

    echo json_encode($resultsArr);
} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "No results found"));
}
?>
