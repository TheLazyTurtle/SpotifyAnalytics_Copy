<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// required to encode json web token
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/user.php';
require_once '../system/validate_token.php';
include_once '../libs/php-jwt/src/BeforeValidException.php';
include_once '../libs/php-jwt/src/ExpiredException.php';
include_once '../libs/php-jwt/src/SignatureInvalidException.php';
include_once '../libs/php-jwt/src/JWT.php';
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

$user->id = $tokenUserId;
$username_exists = $user->usernameExists();
if (!empty($data["oldPassword"])) {
	if (empty($data["oldPassword"]) || empty($data["password"]) || empty($data["repeatPassword"])) {
		http_response_code(400);
		echo json_encode(array("message" => "Not all fields are filled in"));
		die();
	}
}

if (!empty($data["oldPassword"]) && !empty($data["password"] && !empty($data["repeatPassword"]))) {
	if(!password_verify($data["oldPassword"], $user->password)) {
		http_response_code(400);
		echo json_encode(array("message" => "Password not correct"));
		die();
	}

	if ($data["password"] != $data["repeatPassword"]) {
		http_response_code(400);
		echo json_encode(array("message" => "Passwords don't match"));
		die();
	}
}

if (
	!empty($data["username"]) &&
	!empty($data["firstname"]) &&
	!empty($data["lastname"]) &&
	!empty($data["email"]) &&
	!empty($tokenUserId)
) {
	$user->username = trim($data["username"]);
	$user->firstname = trim($data["firstname"]);
	$user->lastname = trim($data["lastname"]);
	$user->email = trim($data["email"]);
	$user->password = !empty($data["password"]) ? trim($data["password"]) : "";
	$user->privateAccount = $data["privateAccount"];
	$user->id = $tokenUserId;

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
