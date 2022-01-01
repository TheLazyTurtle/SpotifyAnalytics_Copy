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

if (!($senderUserID = validateToken())) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db connection and make new notification object
$database = new Database();
$db = $database->getConnection();
$notification = new Notification($db);

$data = $_POST;

if (
	!empty($senderUserID) &&
	!empty($data["receiverUserID"]) &&
	!empty($data["typeID"])
) {
	$notification->senderUserID = $senderUserID;
	$notification->receiverUserID = $data["receiverUserID"];
	$notification->notificationTypeID = $data["typeID"];

	if ($notification->create()) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "Notification send"));
	} else {
		// Set response to service unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to send notification"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}

?>
