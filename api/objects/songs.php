<?php
require '../config/check_cookie.php';

class Song {
    // Db connection and table
    private $conn;
    private $tableName = "song";

    // Object properties
    public $id;
    public $name;
    public $length;
    public $url;
    public $img;
    public $dateAdded;
    public $addedBy;
    public $preview;

    public function __construct($db) {
	$this->conn = $db;
	checkCookie();
    }

    // Get all songs from the db
    function read() {
	$query = "SELECT songID as id, name, img, dateAdded, addedBy FROM song LIMIT 10";
	$stmt = $this->conn->prepare($query);
	$stmt->execute();
	return $stmt;
    }

    // This will only read one song from the database based on the id given
    //TODO: Might have to throw in a userID or filter it that it will not check userID
    function readOne() {
	$query = "SELECT songID as id, name, img, length, url, dateAdded, addedBy, preview FROM " . $this->tableName . " WHERE songID = ? OR name = ? LIMIT 0,1";

	$stmt = $this->conn->prepare($query);
	$stmt->bindParam(1, $this->id);
	$stmt->bindParam(2, $this->name);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$this->id = $row["id"];
	$this->name = $row["name"];
	$this->length = $row["length"];
	$this->url = $row["url"];
	$this->img = $row["img"];
	$this->dateAdded = $row["dateAdded"];
	$this->addedBy = $row["addedBy"];
	$this->preview = $row["preview"];
    }

    // Add a song to the db
    // TODO: Might have to make create batch where you can input an object
    // of artist and it will insert them one by one
    function create() {
	$query = "INSERT INTO song 
	    SET 
	    songID = :id, 
	    name = :name, 
	    length = :length, 
	    url = :url, 
	    img = :img, 
	    dateAdded = :dateAdded,
	    addedBy = :addedBy, 
	    preview = :preview";
	$stmt = $this->conn->prepare($query);

	// Clean data from specialchars
	$this->id = htmlspecialchars(strip_tags($this->id));
	$this->name = htmlspecialchars(strip_tags($this->name));
	$this->length = htmlspecialchars(strip_tags($this->length));
	$this->url = htmlspecialchars(strip_tags($this->url));
	$this->img = htmlspecialchars(strip_tags($this->img));
	$this->dateAdded = htmlspecialchars(strip_tags($this->dateAdded));
	$this->addedBy = htmlspecialchars(strip_tags($this->addedBy));
	$this->preview = htmlspecialchars(strip_tags($this->preview));

	if ($stmt->execute()) {
	    return true;
	}
	return false;
    }

    function search($keywords) {
	$query = "SELECT s.songID as id, s.name, s.length, s.url, s.img, s.dateAdded, s.addedBy, s.preview FROM " . $this->tableName . " s INNER JOIN SongFromArtist sfa on s.songID = sfa.songID RIGHT JOIN artist a ON a.artistID = sfa.artistID WHERE s.name like ? OR a.name like ?";

	$stmt = $this->conn->prepare($query);

	// Clean the keywords
	$keywords = htmlspecialchars(strip_tags($keywords));
	$keywords = "%{$keywords}%";

	// Bind the keywords to the query
	$stmt->bindParam(1, $keywords);
	$stmt->bindParam(2, $keywords);

	$stmt->execute();
	return $stmt;
    }

    // This will search all songs from a user
    function searchForUser($userID, $keyword, $limit) {
	$query = "SELECT songID as id, name, length, url, img, dateAdded, addedBy, preview
	    FROM song WHERE addedBy LIKE ? AND name LIKE ? LIMIT ?";

	$stmt = $this->conn->prepare($query);
	    
	// Clean keywords
	$userID = htmlspecialchars(strip_tags($userID));
	$keyword = htmlspecialchars(strip_tags($keyword));

	$userID = "%$userID%";
	$keyword = "%$keyword%";

	// Bind params
	$stmt->bindParam(1, $userID);
	$stmt->bindParam(2, $keyword);
	$stmt->bindParam(3, $limit, PDO::PARAM_INT);

	$stmt->execute();
	return $stmt;
    }

    // This will get all songs played by a user
    function allSongsPlayed($userID, $minPlayed, $maxPlayed, $minDate, $maxDate) {
	$query = "SELECT s.name AS label, count(p.songID) as y 
	    FROM played p 
	    INNER JOIN song s ON p.songID = s.songID 
	    WHERE p.playedBy LIKE ? AND s.addedBy LIKE ? AND p.datePlayed >= ? AND p.datePlayed <= ? 
	    GROUP BY s.name 
	    HAVING y BETWEEN ? AND ? 
	    ORDER BY name";
	$stmt = $this->conn->prepare($query);

	// Clean the input
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

	// Clean the input
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

    function topSongSearch($userID, $keyword, $amount) {
	$query = "SELECT DISTINCT s.name AS name 
	    FROM played p 
	    INNER JOIN song s ON s.songID = p.songID
	    INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID
	    RIGHT JOIN artist a ON sfa.artistID = a.artistID
	    WHERE s.name LIKE ?
	    AND a.addedBy LIKE ? AND p.playedBy LIKE ? AND s.addedBy LIKE ? AND sfa.addedBy LIKE ?
	    GROUP BY s.name, a.artistID
	    ORDER BY count(p.songID) DESC
	    LIMIT ?";
	$stmt = $this->conn->prepare($query);

	// Clean the input
	$userID = htmlspecialchars(strip_tags($userID));
	$amount = htmlspecialchars(strip_tags($amount));

	$userID = "%$userID%";
	$keyword = "%$keyword%";

	// Bind params
	$stmt->bindParam(1, $keyword);
	$stmt->bindParam(2, $userID);
	$stmt->bindParam(3, $userID);
	$stmt->bindParam(4, $userID);
	$stmt->bindParam(5, $userID);
	$stmt->bindParam(6, $amount, PDO::PARAM_INT);

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
