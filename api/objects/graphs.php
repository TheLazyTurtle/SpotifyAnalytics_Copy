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

    // This will get the top songs of a user
    function topSongs($userID, $artist, $minDate, $maxDate, $amount) {
	$query = "SELECT DISTINCT s.name as label, count(p.songID) as y 
	    FROM played p 
	    INNER JOIN song s ON s.songID = p.songID
	    INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID
	    RIGHT JOIN artist a ON sfa.artistID = a.artistID
	    WHERE a.name LIKE ?
	    AND a.addedBy LIKE ? AND p.playedBy LIKE ? AND s.addedBy LIKE ? AND sfa.addedBy LIKE ?
	    AND datePlayed BETWEEN ? AND ?
	    GROUP BY s.name, a.artistID
	    ORDER BY y DESC
	    LIMIT ?";
	$stmt = $this->conn->prepare($query);
	
	// clean input	
	$userID = htmlspecialchars(strip_tags($userID));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$artist = htmlspecialchars(strip_tags($artist));
	$amount = htmlspecialchars(strip_tags($amount));

	$userID = "%$userID%";
	$artist = "%$artist%";

	// Bind params
	$stmt->bindParam(1, $artist);
	$stmt->bindParam(2, $userID);
	$stmt->bindParam(3, $userID);
	$stmt->bindParam(4, $userID);
	$stmt->bindParam(5, $userID);
	$stmt->bindParam(6, $minDate);
	$stmt->bindParam(7, $maxDate);
	// Force an int because otherwise mysql will panic becuase you can't limit with an string
	$stmt->bindParam(8, $amount, PDO::PARAM_INT);

	$stmt->execute();
	return $stmt;
    }

    // Gets the top artist of a user
    function topArtist($userID, $minDate, $maxDate, $amount) {
	$query = "SELECT count(p.songID) AS y, a.name AS label
	    FROM played p
	    INNER JOIN song s ON p.songID = s.songID
	    INNER JOIN SongFromArtist sfa ON sfa.songID = s.songID
	    RIGHT JOIN artist a ON sfa.artistID = a.artistID
	    WHERE p.playedBy LIKE ? AND a.addedBy LIKE ? AND s.addedBy LIKE ? AND sfa.addedBy LIKE ?
	    AND p.datePlayed BETWEEN ? AND ?
	    GROUP BY a.artistID
	    ORDER BY y DESC
	    LIMIT ?";
	$stmt = $this->conn->prepare($query);

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$amount = htmlspecialchars(strip_tags($amount));

	$userID = "%$userID%";

	// Bind params
	$stmt->bindParam(1, $userID);
	$stmt->bindParam(2, $userID);
	$stmt->bindParam(3, $userID);
	$stmt->bindParam(4, $userID);
	$stmt->bindParam(5, $minDate);
	$stmt->bindParam(6, $maxDate);
	$stmt->bindParam(7, $amount, PDO::PARAM_INT);

	$stmt->execute();
	return $stmt;
    }

    // Gets the played per day graph
    function playedPerDay($userID, $song, $minDate, $maxDate) {
	// TODO: Intergrate a timewindows feature where if the selected timeframe is day or yesterday (and mabye week)
	// also group by hour so you don't just have a dot but can see the difference between hours of the day
	$query = "SELECT count(p.songID) AS y, unix_timestamp(p.datePlayed) * 1000 AS x 
	    FROM played p
	    INNER JOIN song s ON p.songID = s.songID
	    WHERE playedBy LIKE ? AND s.addedBy LIKE ?
	    AND s.name LIKE ?
	    AND p.datePlayed BETWEEN ? AND ?
	    GROUP BY DAY(p.datePlayed), MONTH(p.datePlayed), YEAR(p.datePlayed)
	    ORDER BY x DESC";
	$stmt = $this->conn->prepare($query);

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$song = htmlspecialchars(strip_tags($song));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));

	$userID = "%$userID%";
	$song = "%$song%";

	// Bind params
	$stmt->bindParam(1, $userID);
	$stmt->bindParam(2, $userID);
	$stmt->bindParam(3, $song);
	$stmt->bindParam(4, $minDate);
	$stmt->bindParam(5, $maxDate);

	$stmt->execute();
	return $stmt;
    }
}
?>
