<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require "../config/database.php";
require "../objects/user.php";

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get input
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();
$name = isset($_GET["name"]) ? $_GET["name"] : "";
$graphID = isset($_GET["graphID"]) ? $_GET["graphID"] : "";

// Query the filterSettings
$stmt = $user->readOneFilterSetting($userID, $name, $graphID);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
    $filterSettingsArr = array();
    $filterSettingsArr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);

	$filterSetting = array(
	    "graphID" => $graphID,
	    "userID" => $userID,
	    "name" => $name,
	    "value" => $value
	);
	array_push($filterSettingsArr["records"], $filterSetting);
    }

    // Set response to ok
    http_response_code(200);

    echo json_encode($filterSettingsArr);

} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "No results found"));
}
?>
