<?php
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
include_once '../config/database.php';
include_once '../objects/songs.php';

//  Make db
$database = new Database();
$db = $database->getConnection();

// make object
$song = new Song($db);

// Get the keyword
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";

// Get the products
$stmt = $song->search($keyword);
$num = $stmt->rowCount();

if ($num > 0)  {
    $songsArr = array();
    $songsArr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	//Extract will convert $row["x"] to $x
	extract($row);

	$songItem = array (
	    "songID" => $id,
	    "name" => $name,
	    "length" => $length,
	    "url" => $url,
	    "img" => $img,
	    "dateAdded" => $dateAdded,
	    "addedBy" => $addedBy,
	    "preview" => $preview,
	);
	array_push($songsArr["records"], $songItem);
    }

    // Set response code to ok
    http_response_code(200);

    echo json_encode($songsArr);
} else {
    // Set response code to not found
    http_response_code(404);

    echo json_encode(array("message" => "No songs found"));
}
?>