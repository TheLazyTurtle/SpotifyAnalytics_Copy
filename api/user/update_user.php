<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// required to encode json web token
include_once 'config/core.php';
require_once 'system/validate_token.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

if (!$tokenUserId = validateToken()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Files for db and user object
$database = new Database();
$db = $database->getConnection();

// Make new user object
$user = new User($db);

// Get posted data
$data = $_POST;

if (

) {
	$user->firstname = $data["firstname"];
	$user->lastname = $data["lastname"];
	$user->email = $data["email"];
	$user->password = $data["password"];
	$user->id = $data["userID"];

	// Update user record
	if ($user->update()) {
	    // remake the jwt token because the user info has been changed
	    $token = array(
			"iat" => $issued_at,
			"exp" => $expiration_time,
			"iss" => $issuer,
			"id" => $user->id
		);

	    $jwt = JWT::encode($token, $key, 'HS512');
		setcookie("jwt", $jwt, time()+(60*60*24), "/", "", "", "true");

	    // Set http respone to ok
	    http_response_code(200);

	    echo json_encode(array( "message" => "user was updated"));
	} else {
	    // Set http respone to denied
	    http_response_code(400);

	    echo json_encode(array("message" => "unable to update user"));
	}
} else {
	// Set http response to denied
	http_response_code(400);

	echo json_encode(array( "message" => "Not all fields were set"));
}
?>
