<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../config/database.php';
require '../objects/memory.php';
require '../objects/artists.php';

// Make db and memory object
$database = new Database();
$db = $database->getConnection();
$memory = new Memory($db);
$artist = new Artist($db);

// Get postID
$postID = isset($_POST["postID"]) ? $_POST["postID"] : die();

// Query the posts
$stmt = $memory->readOne();
$num = $stmt->rowcount();

if ($num > 0) {
	// Make result array
	$memoryArr = array();
	$memoryArr["records"] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$memoryItem = array(
			"postID" => $postID,
			"userID" => $userID,
			"description" => $description,
			"datePosted" => $datePosted,
			"img" => $img,
			"songs" => $memory->getPostSongs($artist)
		);
		array_push($memoryArr["records"], $memoryItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($memoryArr);
} else {
	// Set response to not found
	http_response_code(404);

	echo json_encode(array("message" => "No memories found"));
}
