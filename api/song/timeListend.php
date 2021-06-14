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

// Results arrays
$resultsArr = array();
$resultsArr["records"] = array();

// Get posted data
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;

// Query the results
$stmt = $song->timeListend($userID, $minDate, $maxDate);

foreach ($stmt as $row) {
    $resultItem = array(
	"totalTime" => $row["time"],
	"img" => "https://i.pinimg.com/736x/f9/4c/95/f94c9574933ce9404f323fb58f5e7f5c.jpg"
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
