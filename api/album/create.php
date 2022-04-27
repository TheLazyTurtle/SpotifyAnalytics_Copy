<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get song object
require '../system/validate_token.php';
require '../config/database.php';
require '../objects/album.php';

// Get posted data
$albums = file_get_contents("php://input");
$albums = json_decode($albums, true);
$results = array();

if(!($userID = validateToken($albums["jwt"])) || $userID != "system") {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db connection and make new album object
$database = new Database();
$db = $database->getConnection();
$album = new Album($db);

// Check if data is complete
foreach ($albums as $data) {
    if (
        !empty($data["albumID"]) &&
        !empty($data["name"]) &&
        !empty($data["url"]) &&
        !empty($data["img"]) &&
        !empty($data["albumType"]) &&
        !empty($data["primaryArtistID"]) &&
        !empty($data["releaseDate"])
    ) {
        $album->id = $data["albumID"];
        $album->name = $data["name"];
        $album->url = $data["url"];
        $album->img = $data["img"];
        $album->type = $data["albumType"];
        $album->primaryArtistID = $data["primaryArtistID"];
        $album->releaseDate = $data["releaseDate"];

        if ($stmt = $album->create()) {
            array_push($result, array("Succesfully added album", $data["name"]));
        } else {
            array_push($result, array("Failed to add album", $data["name"]));
        }
    } else {
        array_push($result, array("Data incomplete for album", $data["name"]));
    }
}

http_response_code(200);
echo json_encode($result);
