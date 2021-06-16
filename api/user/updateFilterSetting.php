<?php
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
$settingname = isset($_GET["settingname"]) && !empty($_GET["settingname"]) ? $_GET["settingname"] : die();
$value = isset($_GET["value"]) && !empty($_GET["value"]) ? $_GET["value"] : die();
$graphID = isset($_GET["graphID"]) && !empty($_GET["graphID"]) ? $_GET["graphID"] : die();

if ($user->updateFilterSetting($userID, $settingname, $value, $graphID)) {
    // Set response to ok
    http_response_code(200);

    echo json_encode(array("message" => "FilterSetting updated"));
} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "Unable to update filterSetting"));
}
?>
