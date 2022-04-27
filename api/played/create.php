<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get played object
require '../system/validate_token.php';
require '../config/database.php';
require '../objects/played.php';

// Get posted data
$playeds = file_get_contents("php://input");
$playeds = json_decode($playeds, true);
$results = array();

if (!($tokenUserID = validateToken($playeds["jwt"])) || $tokenUserID != "system"){
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db connection and make new played object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);


// Check if data is not empty
foreach ($playeds as $data) {
    if (
        !empty($data["songID"]) &&
        !empty($data["datePlayed"]) &&
        !empty($data["playedBy"]) &&
        !empty($data["songName"])
    ) {
        $played->songID = $data["songID"];
        $played->datePlayed = $data["datePlayed"];
        $played->playedBy = $data["playedBy"];
        $played->songName = $data["songName"];

        if ($played->create()) {
            array_push($result, array("Succesfully added played", $data["songName"]));
        } else {
            array_push($result, array("Failed to add played", $data["songName"]));
        }
    } else {
        array_push($result, array("Data incomplete for played", $data["songName"]));
    }
}

http_response_code(200);
echo json_encode($result);
