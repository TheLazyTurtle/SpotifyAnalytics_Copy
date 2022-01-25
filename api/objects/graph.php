<?php
require '../config/check_cookie.php';

class Graph
{
	private $conn;

	public $id;
	public $title;
	public $titleX;
	public $titleY;
	public $xValueType;
	public $type;
	public $api;
	public $containerID;

	public function __construct($db)
	{
		$this->conn = $db;
		//checkCookie();
	}

	// Get all graphs
	function read()
	{
		$query = "SELECT * FROM graph ORDER BY graphID";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	// Get specific graph
	function read_one()
	{
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

	// This will get inputFields
	function read_inputfield($graphID, $userID)
	{
		$query = "SELECT * FROM inputfield WHERE graphID = ? ORDER BY inputFieldID ASC";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $graphID);
		$stmt->execute();

		$inputfields = array();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$inputfield = array(
				"inputfieldID" => $inputFieldID,
				"name" => $name,
				"value" => $value,
				"type" => $type,
				"autocomplete" => $autoComplete,
				"api" => $api,
				"filterSetting" => $this->read_filterSettings($name, $userID, $graphID)
			);

			array_push($inputfields, $inputfield);
		}

		return $inputfields;
	}

	function read_filterSettings($name, $userID, $graphID) {
		$query = "SELECT * FROM filterSetting WHERE name = ? AND userID = ? AND graphID = ?";
		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $name);
		$stmt->bindParam(2, $userID);
		$stmt->bindParam(3, $graphID);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$filterSetting = array (
				"name" => $name,
				"value" => $value
			);
			return $filterSetting;
		}
	}
}
