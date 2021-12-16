<?php
ini_set("session.cookie_httponly", True);

// Require headers
header("Access-Control-Alow-Origin: http://localhost");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Files needed to connect to db and get the user object
require '../config/database.php';
require '../objects/user.php';

// Genereate json web token (jwt)
require '../config/core.php';
require '../libs/php-jwt/src/BeforeValidException.php';
require '../libs/php-jwt/src/ExpiredException.php';
require '../libs/php-jwt/src/SignatureInvalidException.php';
require '../libs/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

// Get db connection
$database = new Database();
$db = $database->getConnection();

// Make user object
$user = new User($db);

$user->username = $_POST["username"];
$username_exists = $user->usernameExists();

// Check if email exists and if password is correct
if ($username_exists && password_verify($_POST["password"], $user->password)) {
	$token = array(
		"iat" => $issued_at,
		"exp" => $expiration_time,
		"iss" => $issuer,
		"id" => $user->id,
	);

	// Set response code to ok
	http_response_code(200);

	// Generate jwt token
	$jwt = JWT::encode($token, $key, 'HS512');
	setcookie("jwt", $jwt, time()+(60*60*24), "/", "", "", "true");
	echo json_encode(
		array(
			array(
				"message" => "successful login", 
				//"jwt" => $jwt,
				"userID" => $user->id,
				"username" => $user->username,
				"image" => $user->img
			)
		)
	);
} else {
	// set response code to failed
	http_response_code(401);
	echo json_encode(array("message" => "failed to login"));
}
