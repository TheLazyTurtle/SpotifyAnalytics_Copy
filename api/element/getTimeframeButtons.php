<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require "../config/database.php";
require "../objects/elements.php";

// Make db and element object
$database = new Database();
$db = $database->getConnection();
$element = new Element($db);

// Query the buttons
$stmt = $element->getTimeframeButtons();
$num = $stmt->rowCount();

if ($num > 0) {
	$buttonArr = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$buttonItem = array (
			"id" => $elementID,
			"name" => $name,
			"type" => $type,
			"class" => $class,
			"id" => $id,
			"innerHTML" => $innerHTML,
			"value" => $value
		);
		array_push($buttonArr, $buttonItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($buttonArr);
} else {
	// Set response to bad request
	http_response_code(400);

echo json_encode(array("message" => "No buttons found"));
}
?>