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
$song = new Song($db);

// Make result array
$resultsArr = array();
$resultsArr["records"] = array();

// Get posted data
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate_def;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate_def;

// Query the results
$stmt = $song->amountOfSongs($userID, $minDate, $maxDate);

foreach ($stmt as $row) {

    $resultItem = array(
	"times" => $row["times"],
	"img" => "https://daily-mix.scdn.co/covers/on_repeat/PZN_On_Repeat_DEFAULT-en.jpg"
    );
    array_push($resultsArr["records"], $resultItem);
}

// If results
if (count($resultsArr["records"]) > 0) {
    // set response to ok
    http_response_code(200);

    echo json_encode($resultsArr);
} else {
    // set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "No results found"));
}
?>
