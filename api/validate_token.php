<?php
// Require headers
header("Access-Control-Allow-Origin: http://localhost/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// Required to decode jwt
include_once "config/core.php";
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// Get jwt
$jwt = isset($data->jwt) ? $data->jwt : "";

if ($jwt) {
    try {
	$decoded = JWT::decode($jwt, $key, array('HS256'));

	// set http response to ok
	http_response_code(200);

	// Show user details
	echo json_encode(array(
	    "message" => "access granted",
	    "data" => $decoded->data
	));
    } catch (Exception $e) {
	// Set http response to denied
	http_response_code(401);

	echo json_encode(array(
	    "message" => "access denied",
	    "error" => $e->getMessage()
	));
    }
} else {
    // Set http response to denied
    http_response_code(401);

    echo json_encode(array("message" => "Access denied"));
}

?>

