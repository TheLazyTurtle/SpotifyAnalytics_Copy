<?php
// Required headers
header("Acess-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
include_once '../config/database.php';
include_once '../objects/songs.php';

// Make db and product objects
$database = new Database();
$db = $database->getConnection();

// Initialize object
$song = new Song($db);

// query songs
$stmt = $song->read();
$num = $stmt->rowCount();

// Check if there are more than 0 results found
if ($num > 0) {
    $songArr = array();
    $songArr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);

	$songItem = array (
	    "id" => $id,
	    "name" => $name,
	    "img" => $img,
	    "dateAdded" => $dateAdded,
	    "addedBy" => $addedBy
	);

	array_push($songArr["records"], $songItem);
    }
    // set ok response code
    http_response_code(200);

    // Sohw the songs in json format
    echo json_encode($songArr);
} else {
    // set not found response code
    http_response_code(404);

    // Tell the user no songs have been found
    echo json_encode(array("message" => "No songs found."));
}

?>
