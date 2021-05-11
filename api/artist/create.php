<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection
include_once '../config/database.php';
include_once '../objects/artists.php';

// Make connection and make artist object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);

// Get posted data
$data= json_decode(file_get_contents("php://input"));

// Check if data is not empty
if (
    !empty($data->artistID) && 
    !empty($data->name) && 
    !empty($data->url) && 
    !empty($data->dateAdded) && 
    !empty($data->addedBy) && 
    !empty($data->img)
) {
    $artist->id = $data->artistID;
    $artist->name = $data->name;
    $artist->url = $data->url;
    $artist->dateAdded = $data->dateAdded;
    $artist->addedBy = $data->addedBy;
    $artist->img = $data->img;

    if ($artist->create()) {
	http_response_code(201);

	echo json_encode(array("message" => "artist added"));
    } else {
	echo json_encode(array("message" => "unable to add artist"));
    }
} else {
    // bad request
    http_response_code(401);

    echo json_encode(array("message" => "Data incomplete"));
}
?>

