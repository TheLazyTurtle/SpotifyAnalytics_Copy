<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get song object
require '../system/validate_token.php';
require '../config/database.php';
require '../objects/notification.php';

if (!($userID = validateToken())) {
	// Set response to bad request
	http_response_code(400);

	die(json_encode(array("message" => "Not a valid token")));
}

// Make db connection and make new song object
$database = new Database();
$db = $database->getConnection();
$notification = new Notification($db);

$data = $_POST;

// Check if data is not empty
if (!empty($data["notificationID"])) {
	$notification->id = $data["notificationID"];
} else if (!empty($data["receiverUserID"])) {
	$notification->receiverUserID = $data["receiverUserID"];
	$notification->senderUserID = $userID;
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}

if ($notification->deleteNotification()) {
	// Set response to ok
	http_response_code(200);

	echo json_encode(array("message" => "Notification deleted"));
} else {
	// Set response to unavailable
	http_response_code(503);

	echo json_encode(array("message" => "Unable to deleted notification"));
}
?>
