<?php
session_start();
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();

// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/core.php';
require '../config/database.php';
require '../objects/songs.php';

// Make db connection and new played object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);

// Get keywords
$minDate = isset($_GET["minDate"]) ? $_GET["minDate"] : $minDate;
$maxDate = isset($_GET["maxDate"]) ? $_GET["maxDate"] : $maxDate;

// Get the data
$stmt = $played->search($minDate, $maxDate, $userID);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
    $playedArr = array();
    $playedArr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);

	$playedItem = array(
	    "songID" => $songID,
	    "datePlayed" => $datePlayed,
	    "playedBy" => $playedBy 
	);
	array_push($playedArr["records"], $playedItem);
    }

    // Set response to ok
    http_response_code(200);

    echo json_encode($playedArr);
} else {
    // Set response to not found
    http_response_code(404);
    
    echo json_encode(array("message" => "No data found"));
}
?>
