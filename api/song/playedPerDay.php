<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/mongo.php";
require "../objects/songs.php";
require "../config/core.php";

// Make db and graphs object
$database = new Mongo();
$db = $database->getConnection();
$graph = new Song($db);

// This will make results array
$resultsArr = array();
$resultsArr["records"] = array();

// Get posted data
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$song = isset($_GET["song"]) && !empty($_GET["song"])? $_GET["song"] : "";
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;

// Query the results
$stmt = $graph->playedPerDay($userID, $song, $minDate, $maxDate);

foreach ($stmt as $row) {

    $resultItem = array(
	"x" => $row["date"]->toDateTime()->format("U.u")*1000,
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

    json_encode(array("message" => "No results found"));
}
?>
