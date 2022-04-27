<?php
// Require headers
header("Access-Control-Allow-Origin: http://localhost/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Required to decode jwt
require "../config/core.php";
require '../libs/php-jwt/src/BeforeValidException.php';
require '../libs/php-jwt/src/ExpiredException.php';
require '../libs/php-jwt/src/SignatureInvalidException.php';
require '../libs/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

// Get posted data
$data = null;

function validateToken($jwt = "") {
	global $key;
	$data = "";

	// Get the token
	if (!empty($_GET["jwt"])) {
		$data = $_GET["jwt"];
    } 
    else if (!empty($_POST["jwt"])) {
		$data = $_POST["jwt"];
	}
   	else if (!empty($_COOKIE["jwt"])) {
		$data = $_COOKIE["jwt"];
    } 
    else if (!empty($jwt)) {
        $data = $jwt;
    }
	
	try {
		$decoded = JWT::decode($data, $key, array('HS512'));
		return $decoded->id;
	} catch (Exception $e) {
		return false;
	}
}
