<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow_Methods: GET");
header("Acces-Control-Allow_credentials: true");
header("Content-Type: application/json");

// Include objects
include_once '../config/database.php';
include_once '../objects/graph.php';

// Make db connection and graph object
$database = new Database();
$db = $database->getConnection();
$graph = new Graph($db);

// Get input
$graph->id = isset($_GET["id"]) && !empty($_GET["id"]) ? $_GET["id"] : die();

// Fetch one graph
$graph->read_one();

// If result
if ($graph->title != null) {
    $graphArr = array(
	"graphID" => $graph->id,
	"title" => $graph->title,
	"titleX" => $graph->titleX,
	"titleY" => $graph->titleY,
	"type" => $graph->type,
	"xValueType" => $graph->xValueType,
	"api" => $graph->api,
	"containerID" => $graph->containerID
    );

    // Set response to ok
    http_response_code(200);

    echo json_encode($graphArr);
} else {
    // Set response to bad request
    http_response_code(400);

    echo json_encode(array("message" => "Graph does not exist"));
}

?>
