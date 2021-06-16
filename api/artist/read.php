<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require "../config/database.php";
require "../objects/artists.php";

// Make db and artist object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);

// Query artist
$stmt = $artist->read();
$num = $stmt->rowCount();

// If there are results
if ($num > 0) {
    $artistArr = array();
    $artistArr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);

	$artistItem = array(
	    "id" => $id,
	    "name" => $name,
	    "url" => $url,
	    "dateAdded" => $dateAdded,
	    "addedBy" => $addedBy,
	    "img" => $img
	);

	array_push($artistArr["records"], $artistItem);
    }

    // Set ok
    http_response_code(200);

    echo json_encode($artistArr);
} else {
    // Not found
    http_response_code(400);

    echo json_encode(array("message" => "No artists found"));
}
?>
