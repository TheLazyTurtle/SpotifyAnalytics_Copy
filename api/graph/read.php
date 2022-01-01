<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require "../config/database.php";
require "../objects/graph.php";

// Make db and graph object
$database = new Database();
$db = $database->getConnection();
$graph = new Graph($db);

// Query the graph
$stmt = $graph->read();
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	$graphArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$graphItem = array(
			"id" => $graphID,
			"title" => $title,
			"titleX" => $titleX,
			"titleY" => $titleY,
			"api" => $api,
			"type" => $type,
			"xValueType" => $xValueType,
			"containerID" => $containerID,
			"dataType" => $dataType
		);
		array_push($graphArr, $graphItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($graphArr);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No graphs found"));
}
