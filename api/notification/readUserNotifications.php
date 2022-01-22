<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../system/validate_token.php';
require '../config/database.php';
require '../objects/notification.php';

// Make db and song objects
$database = new Database();
$db = $database->getConnection();
$notification = new Notification($db);

$tokenUserID = validateToken();

// Query the notifications
if (!empty($tokenUserID)) {
	$notification->receiverUserID = $tokenUserID;

	$stmt = $notification->readUserNotifications();
	$num = $stmt->rowcount();

	if ($num > 0) {
		$notificationArr = array();

		while ($row = $stmt->FETCH(PDO::FETCH_ASSOC)) {
			extract($row);

			$notificationItem = array(
				"id" => $notificationID,
				"receiverUserID" => $receiverUserID,
				"senderUserID" => $senderUserID,
				"senderUsername" => $username,
				"name" => $name,
				"message" => $message,
				"notificationTypeID" => (int)$notificationTypeID
			);

			array_push($notificationArr, $notificationItem);
		}

		// Set response to ok
		http_response_code(200);

		echo json_encode($notificationArr);
	} else {
		// Set response to ok
		http_response_code(200);

		echo json_encode(array("message" => "No notification found"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}

?>
