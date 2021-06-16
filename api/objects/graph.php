<?php
require '../config/check_cookie.php';
require_once '/var/www/html/vendor/autoload.php';

class Graph {
    private $conn;

    public $id;
    public $title;
    public $titleX;
    public $titleY;
    public $xValueType;
    public $type;
    public $api;
    public $containerID;

    public function __construct($db) {
	$this->conn = $db;
	checkCookie();
    }

    // Get all graphs
    function read() {
	$query = "SELECT * FROM graph";
	$stmt = $this->conn->prepare($query);
	$stmt->execute();

	return $stmt;
    }

    // Get specific graph
    function read_one() {
	$query = "SELECT * FROM graph WHERE graphID = ?";

	$stmt = $this->conn->prepare($query);
	$stmt->bindParam(1, $this->id);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$this->id = $row["graphID"];
	$this->title = $row["title"];
	$this->titleX = $row["titleX"];
	$this->titleY = $row["titleY"];
	$this->xValueType = $row["xValueType"];
	$this->type = $row["type"];
	$this->api = $row["api"];
    }

    // This will get inputfields
    function read_inputfield($graphID) {
	$query = "SELECT * FROM inputfield WHERE graphID = ?";
	$stmt = $this->conn->prepare($query);
	$stmt->bindParam(1, $graphID);
	$stmt->execute();

	return $stmt;
    }
}
?>
