<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include db and object files
require '../config/mongo.php';
require '../objects/played.php';
require_once '/var/www/html/vendor/autoload.php';

$database = new Mongo();
$db = $database->getConnection();
$played = new Played($db);

$data = $_GET;
$res = json_decode(json_decode($_GET['song']));
print_r($res);

if (!empty($_GET)) {
	//if ($played->insertBatch($_GET)) {
	//// Set response to create
	//http_response_code(201);

	//echo json_encode(array("message" => "Songs have been marked as played"));
	//} else {
	////set response to unavailable 
	//echo json_encode(array("message" => "Unable to mark as played"));
	//}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
