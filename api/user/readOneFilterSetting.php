<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require "../system/validate_token.php";
require "../config/database.php";
require "../objects/user.php";

$tokenUserId = validateToken();

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get input
$userID = !empty($_GET["userID"]) ? $_GET["userID"] : $tokenUserId;
$name = isset($_GET["name"]) ? $_GET["name"] : "";
$graphID = isset($_GET["graphID"]) ? $_GET["graphID"] : "";

// Query the filterSettings
$stmt = $user->readOneFilterSetting($userID, $name, $graphID);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	$filterSettingsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$filterSetting = array(
			"graphID" => $graphID,
			"userID" => $userID,
			"name" => $name,
			"value" => $value
		);
		array_push($filterSettingsArr, $filterSetting);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($filterSettingsArr);
} else {
	$user->createFilterSettings($userID);
	$filterSettingsArr = array();

	$filterSetting = array(
		"graphID" => $graphID,
		"userID" => $userID,
		"name" => $name,
		"value" => ""
	);
	array_push($filterSettingsArr, $filterSetting);

	// Set response to bad request
	http_response_code(200);

	echo json_encode($filterSettingsArr);
}
