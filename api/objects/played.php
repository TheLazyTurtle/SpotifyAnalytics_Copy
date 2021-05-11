<?php
require '../config/check_cookie.php';

class Played {
    // DB connection
    private $conn;

    // Object properties
    public $songID;
    public $datePlayed;
    // Might have to make this userID
    public $playedBy;

    public function __construct($db) {
	$this->conn = $db;
	checkCookie();
    }

    // Get all played from database
    function read($userID) {
	// See if I have to give it the userID
	$query = "SELECT songId, datePlayed, playedBy FROM played WHERE playedBy = ?";
	$stmt = $this->conn->prepare($query);
	$stmt->bindParam(1, $userID);
	return $stmt;
    }

    // Add a song as played
    // TODO: Might have to make a batch version
    function create() {
	$query = "INSERT INTO played SET songID = :songID, datePlayed = :datePlayed, playedBy = :playedBy";
	$stmt = $this->conn->prepare($query);

	// Clean the data
	$this->songID = htmlspecialchars(strip_tags($this->songID));
	$this->datePlayed = htmlspecialchars(strip_tags($this->datePlayed));
	$this->playedBy = htmlspecialchars(strip_tags($this->playedBy));

	if ($stmt->execute()) {
	    return true;
	}
	return false;
    }

    // searches between date and filters on userID
    function search($minDate, $maxDate, $userID) {
	$query = "SELECT songID, dateAdded, playedBy FROM played WHERE dateAdded BETWEEN ? AND ? AND playedBy = ?";
	$stmt = $this->conn->prepare($query);

	// Clean input
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));

	$stmt->bindParam(1, $minDate);
	$stmt->bindParam(2, $maxDate);
	$stmt->bindParam(3, $userID);

	$stmt->execute();
	return $stmt;
    }
}
?>
