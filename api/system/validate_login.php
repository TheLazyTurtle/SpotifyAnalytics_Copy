<?php
// Require headers
header("Access-Control-Allow-Origin: http://localhost/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Required to decode jwt
require "../config/core.php";
require "../system/validate_token.php";

// Get jwt
if (validateToken()) {
	// set http response to ok
	http_response_code(200);

	// Show user details
	echo json_encode(array(
		"message" => "access granted"
	));
} else {
	// Set http response to denied
	http_response_code(401);

	echo json_encode(array("message" => "Access denied"));
}
?>