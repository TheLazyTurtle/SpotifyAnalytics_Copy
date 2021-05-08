<?php
// Require headers
// TODO: Check if i have to change this header in all pages
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';
include_once 'objects/user.php';

// database connection
$database = new Database();
$db = $database->getConnection();

// Make user object
$user = new User($db);

// Get posted data
// Check if this acutally works or not. So far i can not test this as it does annoying with post and shit
$data = json_decode(file_get_contents("php://input"));

// set user values
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;

// Create the user
if (
    !empty($user->firstname) &&
    !empty($user->lastname) &&
    !empty($user->password) &&
    $user->create()
) {
    // Set respones code to ok
    http_response_code(200);

    echo json_encode(array("message" => "user was created"));
} else {
    // set response code to bad request
    http_response_code(400);

    echo json_encode(array("message" => "unable to create user"));
}
?>
