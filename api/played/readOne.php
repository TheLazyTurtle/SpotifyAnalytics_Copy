<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow_Methods: GET");
header("Acces-Control-Allow_credentials: true");
header("Content-Type: application/json");

// Include objects
include_once '../config/database.php';
include_once '../objects/played.php';

// Make database and played object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);

// Fetch input data
$songID = isset($_GET["songID"]) && !empty($_GET["songID"]) ? $_GET["songID"] : die();

$stmt = $played->readOne($songID);
$num = $stmt->rowCount();

if ($num > 0) {
	$resultsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$resultItem = array(
			"songID" => $songID,
			"playedBy" => $playedBy,
			"datePlayed" => $datePlayed,
			"songName" => $songName
		);
		array_push($resultsArr, $resultItem);
	}

	http_response_code(200);

	echo json_encode($resultsArr);
} else {
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
