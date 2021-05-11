<?php
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
include_once '../config/database.php';
include_once '../objects/artists.php';

// Make db and artist object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);

// Get the keywords
$keywords = isset($_GET["keyword"]) ? $_GET["keyword"] : "";

// Get the artist
$stmt = $artist->search($keywords);
$num = $stmt->rowCount();

// If there are results
if ($num > 0) {
    $artistArr = array();
    $artistArr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);

	$artistItem = array (
	    "artistID" => $id,
	    "name" => $name,
	    "url" => $url,
	    "dateAdded" => $dateAdded,
	    "addedBy" => $addedBy,
	    "img" => $img,
	);
	array_push($artistArr["records"], $artistItem);
    }
    // Set ok response
    http_response_code(200);

    echo json_encode($artistArr);
} else {
    // set not found response
    http_response_code(404);

    echo json_encode(array("message" => "No artist found"));
}
?>

