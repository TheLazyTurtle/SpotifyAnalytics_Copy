<?php
// Require headers
// TODO: Check if access control allow origin has to be the url of the domain 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';

// Database connection and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get posted data
$data = $_POST;

if (
	!empty($data->id) &&
	!empty($data->username) &&
	!empty($data->firstname) &&
	!empty($data->lastname) &&
	!empty($data->email) &&
	!empty($data->password)
) {
	// set user values
	$user->id = $data['userID'];
	$user->username = $data['username'];
	$user->firstname = $data['firstname'];
	$user->lastname = $data['lastname'];
	$user->email = $data['email'];
	$user->password = $data['password'];
}

// Create the user
if (
	!empty($user->id) &&
	!empty($user->username) &&
	!empty($user->firstname) &&
	!empty($user->lastname) &&
	!empty($user->email) &&
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
