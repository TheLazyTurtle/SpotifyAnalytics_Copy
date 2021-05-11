<?php
session_start();
// Might be able to get userID using the jwt
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../config/database.php';
require '../objects/played.php';

// Make db and played object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);

// Query played
$stmt = $played->read($userID);
$num = $stmt->rowCount();

// If result
if ($num > 0) {
    $playedArr = array();
    $playedArr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);

	$playedItem = array(
	    "songID" => $songID,
	    "dateAdded" => $dateAdded,
	    "addedBy" => $addedBy
	);

	array_push($playedArr["records"], $playedItem);
    }
    // Set ok response
    http_response_code(200);

    echo json_encode($playedArr);
} else {
    // Set not found response
    http_response_code(404);

    echo json_encode(array("message" => "No results found"));
}
?>
