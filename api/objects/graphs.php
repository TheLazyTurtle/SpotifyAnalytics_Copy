<?php
require "../config/check_cookie.php";

class Graph {
    // DB connection
    private $conn;

    // Object properties
    public $label;
    public $y;

    public function __construct($db) {
	$this->conn = $db;
	checkCookie();
    }

    // This will get all the data for the all songs played graph
    function allSongsPlayed($userID, $minPlayed, $maxPlayed, $minDate, $maxDate) {
	$query = "SELECT s.name AS label, count(p.songID) as y FROM played p INNER JOIN song s ON p.songID = s.songID WHERE p.playedBy LIKE ? AND s.addedBy LIKE ? AND p.datePlayed >= ? AND p.datePlayed <= ? GROUP BY s.name HAVING y BETWEEN ? AND ? ORDER BY name";
	$stmt = $this->conn->prepare($query);

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$minPlayed = htmlspecialchars(strip_tags($minPlayed));
	$maxPlayed = htmlspecialchars(strip_tags($maxPlayed));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));

	$userID = "%$userID%";

	// Bind params
	$stmt->bindParam(1, $userID);
	$stmt->bindParam(2, $userID);
	$stmt->bindParam(3, $minDate);
	$stmt->bindParam(4, $maxDate);
	$stmt->bindParam(5, $minPlayed);
	$stmt->bindParam(6, $maxPlayed);

	$stmt->execute();
	return $stmt;
    }
}
?>
