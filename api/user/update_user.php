<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// required to encode json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// Files for db and user object
$database = new Database();
$db = $database->getConnection();

// Make new user object
$user = new User($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

// get jwt
$jwt = isset($data->jwt) ? $data->jwt : "";

// decode jwt
if ($jwt) {
    try {
	$decoded = JWT::decode($jwt, $key, array("HS256"));

	$user->firstname = $data->firstname;
	$user->lastname = $data->lastname;
	$user->email = $data->email;
	$user->password = $data->password;
	$user->id = $decoded->data->id;

	// Update user record
	if ($user->update()) {
	    // remake the jwt token because the user info has been changed
	    $token = array(
		"iat" => $issued_at,
		"exp" => $expiration_time,
		"iss" => $issuer,
		"data" => array (
		    "id" => $user->id,
		    "firstname" => $user->firstname,
		    "lastname" => $user->lastname,
		    "email" => $user->email
		)
	    );

	    $jwt = JWT::encode($token, $key);

	    // Set http respone to ok
	    http_response_code(200);

	    echo json_encode(array(
		"message" => "user was updated",
		"jwt" => $jwt
	    ));
	} else {
	    // Set http respone to denied
	    http_response_code(401);

	    echo json_encode(array("message" => "unable to update user"));
	}

    } catch (Exception $e) {
	// Set http response to denied
	http_response_code(401);

	echo json_encode(array(
	    "message" => "access denied",
	    "error" => $e->getMessage()
	));
    }
} else {
    // Set response code to denied
    http_response_code(401);

    echo json_encode(array("message" => "Access denied"));
}
?>
