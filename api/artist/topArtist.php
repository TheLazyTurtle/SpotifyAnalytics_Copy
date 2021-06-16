<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/mongo.php";
require "../objects/artists.php";
require "../config/core.php";

// Make db and artists object
$database = new Mongo();
$db = $database->getConnection();
$artist = new Artist($db);

// Result array
$resultsArr = array();
$resultsArr["records"] = array();

// Get posted data
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;
$amount = isset($_GET["amount"]) && !empty($_GET["amount"]) ? $_GET["amount"] : 10;

// Query the results
$stmt = $artist->topArtist($userID, $minDate, $maxDate, $amount);

foreach ($stmt as $row) {

    $resultItem = array(
	"label" => $row["_id"],
	"y" => (int)$row["count"],
    );
    array_push($resultsArr["records"], $resultItem);
}

// If results
if (count($resultsArr["records"]) > 0) {
    // Set response to ok
    http_response_code(200);

    echo json_encode($resultsArr);
} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "No results found"));
}
?>
