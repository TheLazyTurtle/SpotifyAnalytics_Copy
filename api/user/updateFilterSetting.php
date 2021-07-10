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
$settingname = isset($_POST["settingname"]) && !empty($_POST["settingname"]) ? $_POST["settingname"] : "";
$value = isset($_POST["value"]) && !empty($_POST["value"]) ? $_POST["value"] : "";
$graphID = isset($_POST["graphID"]) && !empty($_POST["graphID"]) ? $_POST["graphID"] : "";

if ($user->updateFilterSetting($userID, $settingname, $value, $graphID)) {
    // Set response to ok
    http_response_code(200);

    echo json_encode(array("message" => "FilterSetting updated"));
} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "Unable to update filterSetting"));
}
