<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../config/mongo.php';
require '../objects/songs.php';
require_once '/var/www/html/vendor/autoload.php';

// Make db and song objects
$database = new Mongo();
$db = $database->getConnection();
$song = new Song($db);

// Make results array
$songArr = array();
$songArr["records"] = array();

// query songs
$stmt = $song->read();

foreach($stmt as $row) {
    $songItem = array (
	"id" => $row["songID"],
	"name" => $row["name"],
	"img" => $row["img"], 
	"dateAdded" => $row["dateAdded"]->toDateTime()->format("Y-m-d H:i:s"),
	"addedBy" => $row["addedBy"]
    );
    array_push($songArr["records"], $songItem);
}

// If there are results
if (count($songArr["records"]) > 0) {
    // Set response to ok
    http_response_code(200);

    echo json_encode($songArr);

} else {
    // set not found response code
    http_response_code(404);

    // Tell the user no songs have been found
    echo json_encode(array("message" => "No songs found."));
}

?>
