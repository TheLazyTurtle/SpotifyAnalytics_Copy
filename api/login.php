<?php
// Require headers
header("Access-Control-Alow-Origin: http://localhost");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// Files needed to connect to db and get the user object
include_once 'config/database.php';
include_once 'objects/user.php';

// Get db connection
$database = new Database();
$db = $database->getConnection();

// Make user object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->email = $data->email;
$email_exists = $user->emailExists();

// Genereate json web token (jwt)
include_once 'config/core.php';
include_once 'libs/php-jwt/src/BeforeValidException.php';
include_once 'libs/php-jwt/src/ExpiredException.php';
include_once 'libs/php-jwt/src/SignatureInvalidException.php';
include_once 'libs/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

// Check if email exists and if password is correct
if ($email_exists && password_verify($data->password, $user->password)) {
    $token = array (
	"iat" => $issued_at,
	"exp" => $expiration_time,
	"iss" => $issuer,
	"data" => array (
	    "id" => $user->firstname,
	    "lastname" => $user->lastname,
	    "email" => $user->email,
	)
    );

    // Set respones code to ok
    http_response_code(200);

    // Generate jwt token
    $jwt = JWT::encode($token, $key);
    echo json_encode(
	array(
	    "message" => "successful login",
	    "jwt" => $jwt
	)
    );
} else {
    // set respones code to failed
    http_response_code(401);
    echo json_encode(array("message" => "failed to login"));
}
?>
