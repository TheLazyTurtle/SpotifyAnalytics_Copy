<?php
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
include_once '../config/mongo.php';
include_once '../objects/artists.php';

// Make db and artist object
$database = new Mongo();
$db = $database->getConnection();
$artist = new Artist($db);

// Result arrays
$artistArr = array();
$artistArr["records"] = array();

// Get the keywords
$keywords = isset($_GET["keyword"]) ? $_GET["keyword"] : "";

// Get the artist
$stmt = $artist->search($keywords);

foreach ($stmt as $row) {
    $artistItem = array (
	"artistID" => $row["artistID"],
	"name" => $row["name"],
	"url" => $row["url"],
	"dateAdded" => $row["dateAdded"],
	"addedBy" => $row["addedBy"],
	"img" => $row["img"],
    );
    array_push($artistArr["records"], $artistItem);
}

// If there are results
if (count($artistArr["records"]) > 0) {
    // Set ok response
    http_response_code(200);

    echo json_encode($artistArr);
} else {
    // set not found response
    http_response_code(404);

    echo json_encode(array("message" => "No artist found"));
}
?>

