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

// Read input
$graphID = isset($_GET["graphID"]) && !empty($_GET["graphID"]) ? $_GET["graphID"] : die();

// Query input fields
$stmt = $graph->read_inputfield($graphID);
$num = $stmt->rowCount();

// If result
if ($num > 0) {
    $inputfields = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	extract($row);

	$inputfield = array(
	    "graphID" => $graphID,
	    "inputFieldID" => $inputFieldID,
	    "name" => $name,
	    "value" => $value,
	    "type" => $type,
		"autoComplete" => $autoComplete,
		"api" => $api
	);

	array_push($inputfields, $inputfield);
    }

    // Set response to ok
    http_response_code(200);

    echo json_encode($inputfields);

} else {
    // Set response to bad request
    http_response_code(400);
    
    echo json_encode(array("message" => "No input fields found"));
}
?>
