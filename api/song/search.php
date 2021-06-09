<?php
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/mongo.php';
require '../objects/songs.php';

//  Make db connection and new song object
$database = new Mongo();
$db = $database->getConnection();
$song = new Song($db);

// Make results array
$songsArr = array();
$songsArr["records"] = array();

// Get the keyword
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";

// Get the songs 
$stmt = $song->search($keyword);

foreach ($stmt as $row) {
    $songItem = array (
	"songID" => $row["songID"],
	"name" => $row["name"],
	"length" => $row["length"],
	"url" => $row["url"],
	"img" => $row["img"],
	"dateAdded" => $row["dateAdded"]->ToDateTime()->format("Y-m-d H:i:s"),
	"addedBy" => $row["addedBy"],
	"preview" => $row["preview"],
    );
    array_push($songsArr["records"], $songItem);
}

// If there are results
if (count($songsArr["records"]) > 0) {
    // Set response code to ok
    http_response_code(200);

    echo json_encode($songsArr);

} else {
    // Set response code to bad request 
    http_response_code(400);

    echo json_encode(array("message" => "No songs found"));
}
?>
