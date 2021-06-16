<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require "../config/mongo.php";
require "../objects/artists.php";

// Make db and artist object
$database = new Mongo();
$db = $database->getConnection();
$artist = new Artist($db);

// Result arrays
$artistArr = array();
$artistArr["records"] = array();

// Query artist
$stmt = $artist->read();

foreach ($stmt as $row) {

    $artistItem = array(
	"id" => $row["artistID"],
	"name" => $row["name"],
	"url" => $row["url"],
	"dateAdded" => $row["dateAdded"],
	"addedBy" => $row["addedBy"],
	"img" =>$row["img"] 
    );

    array_push($artistArr["records"], $artistItem);
}

// If there are results
if (count($artistArr["records"]) > 0) {
    // Set ok
    http_response_code(200);

    echo json_encode($artistArr);
} else {
    // Not found
    http_response_code(400);

    echo json_encode(array("message" => "No artists found"));
}
?>
