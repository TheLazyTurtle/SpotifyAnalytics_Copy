<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection
include_once '../system/validate_token.php';
include_once '../config/database.php';
include_once '../objects/artists.php';

// Get posted data
$artists = file_get_contents("php://input");
$artists = json_decode($artists, true);
$results = array();

if(!($userID = validateToken($artists["jwt"])) || $userID != "system") {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make connection and make artist object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);

// Check if data is not empty
foreach ($artists as $data) {
    if (
        !empty($data["artistID"]) &&
        !empty($data["name"]) &&
        !empty($data["url"]) &&
        !empty($data["img"])
    ) {
        $artist->id = $data["artistID"];
        $artist->name = $data["name"];
        $artist->url = $data["url"];
        $artist->img = $data["img"];

        if ($artist->create()) {
            array_push($result, array("Succesfully added artist", $data["name"]));
        } else {
            array_push($result, array("Failed to add artist", $data["name"]));
        }
    } else {
        array_push($result, array("Data incomplete for artist", $data["name"]));
    }
}

http_response_code(200);
echo json_encode($result);
