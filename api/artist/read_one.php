<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow_Methods: GET");
header("Acces-Control-Allow_credentials: true");
header("Content-Type: application/json");

// Include objects
include_once '../config/mongo.php';
include_once '../objects/artists.php';

// Make db connection
$database = new Mongo();
$db = $database->getConnection();

// Make new artist object
$artist = new Artist($db);
$artist->id = isset($_GET["artistID"]) ? $_GET["artistID"] : die();

// Fetch the one artist
$artist->readOne();

// When data is found
if ($artist->name != null) {
    $artistArr = array(
	"artistID" => $artist->id,
	"name" => $artist->name,
	"url" => $artist->url,
	"dateAdded" => $artist->dateAdded,
	"addedBy" => $artist->addedBy,
	"img" => $artist->img,
    );

    // Set response code to ok
    http_response_code(200);

    echo json_encode($artistArr);
} else {
    // Set response code to not found
    http_response_code(404);

    echo json_encode(array("message" => "Artist does not exist"));
}
?>
