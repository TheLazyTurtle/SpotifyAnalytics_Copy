<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../config/database.php';
require '../objects/memory.php';
require '../objects/artists.php';
require '../objects/user.php';

// Make db and memory and artist objects
$database = new Database();
$db = $database->getConnection();
$memory = new Memory($db);
$artist = new Artist($db);
$user = new User($db);

// Get posted data
$followerID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();

// Query the results
$stmt = $memory->read($followerID);
$num = $stmt->rowcount();

if ($num > 0) {
	// Make result array
	$memoryArr = array();
	$memoryArr["records"] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$memory->id = $postID;
		$user->id = $userID;
		$user->read_one();

		$memoryItem = array(
			"postID" => $postID,
			"follower" => $follower,
			"likes" => $memory->getAmountOfLikes(),
			"poster" => array(
				"userID" => $user->id,
				"username" => $user->username,
				"img" => $user->img
			),
			"description" => $description,
			"datePosted" => $datePosted,
			"img" => $img,
			"songs" => $memory->getPostSongs($artist),
			"userHasLikedPost" => $memory->userHasLikedPost($followerID)
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
