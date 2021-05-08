<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection
include_once '../config/database.php';

// make song object
include_once '../objects/songs.php';

$database = new Database();
$db = $database->getConnection();

$song = new Song($db);

// Get posted data
// No clue what this does (yet)
$data = json_decode(file_get_contents("php://input"));

// Check if data is not empty
if (
    !empty($data->songID) &&
    !empty($data->name) &&
    !empty($data->length) &&
    !empty($data->url) &&
    !empty($data->img) &&
    !empty($data->dateAdded) &&
    !empty($data->addedBy) &&
    !empty($data->preview)
) {
    $song->id = $data->songID;
    $song->name = $data->name;
    $song->length = $data->length;
    $song->url = $data->url;
    $song->img = $data->img;
    $song->dateAdded = $data->dateAdded;
    $song->addedBy = $data->addedBy;
    $song->preview = $data->preview;

    if ($song->create()) {
	// Set response code created
	http_response_code(201);

	// Tell the user
	echo json_encode(array("message" => "song added"));
    } else {
	// Set response code service unabailable
	echo json_encode(array("message" => "Unable to add song"));
    }
} else {
    // set response code bad request
    http_response_code(400);

    echo json_encode(array("message" => "Data incomplete"));
}
?>

