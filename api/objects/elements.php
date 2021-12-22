<?php
require "../config/check_cookie.php";

class Element
{
	private $conn;

	public $elementId;
	public $name;
	public $type;
	public $class;
	public $id;
	public $innerHTML;
	public $value;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	function getTimeframeButtons() {
		$query = "SELECT * FROM element WHERE name = 'TimeframeButton' ORDER BY elementID ASC";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}
}

?>