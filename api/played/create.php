
<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get played object
require '../config/database.php';
require '../objects/played.php';

// Make db connection and make new played object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Check if data is not empty
if (
    !empty($data->songID) &&
    !empty($data->dateAdded) &&
    !empty($data->addedBy)
) {
    $played->songID = $data->songID;
    $played->dateAdded = $data->dateAdded;
    $played->addedBy = $data->addedBy;

    if ($played->create()) {
	// Set response to created
	http_response_code(201);

	echo json_encode(array("message" => "entry added"));
    } else {
	echo json_encode(array("message" => "Unable to add entry"));
    }
} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "Data incomplete"));
}
?>
