<?php
require '../config/check_cookie.php';

class Artist {
    // DB connection
    private $conn;

    // Object properties
    public $id;
    public $name;
    public $url;
    public $dateAdded;
    public $addedBy;
    public $img;

    public function __construct($db) {
	$this->conn = $db;
	checkCookie();
    }

    // Get all the artists form the db
    function read() {
	$query = "SELECT artistID as id, name, url, dateAdded, addedBy, img FROM artist";
	$stmt = $this->conn->prepare($query);
	$stmt->execute();
	return $stmt;
    }

    // This will only read one artist from the db
    function readOne() {
	$query = "SELECT artistID as id, name, url, dateAdded, addedBy, img FROM artist WHERE artistID = ? OR name = ? LIMIT 0,1";

	$stmt = $this->conn->prepare($query);
	$stmt->bindParam(1, $this->id);
	$stmt->bindParam(2, $this->name);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$this->id = $row["id"];
	$this->name = $row["name"];
	$this->url = $row["url"];
	$this->dateAdded = $row["dateAdded"];
	$this->addedBy = $row["addedBy"];
	$this->img = $row["img"];
    }

    // Add artist to db
    // Might have to make create batch where you can input an object
    // of artist and it will insert them one by one
    function create() {
	$query = "INSERT INTO artist SET artistID = :id, name = :name, url = :url, dateAdded = :dateAdded, img = :img";
	$stmt = $this->conn->prepare($query);

	// Clean data of specialchars
	$this->id = htmlspecialchars(strip_tags($this->id));
	$this->name = htmlspecialchars(strip_tags($this->name));
	$this->url = htmlspecialchars(strip_tags($this->url));
	$this->dateAdded = htmlspecialchars(strip_tags($this->dateAdded));
	$this->addedBy = htmlspecialchars(strip_tags($this->addedBy));
	$this->img = htmlspecialchars(strip_tags($this->img));

	if ($stmt->execute()) {
	    return true;
	}
	return false;
    }

    // Get artist based on keywords
    // Decide what is we want to return for artists
    // Will we only give the name etc or also all the songs they are part of
    function search($keywords) {
	$query = "SELECT a.artistID as id, a.name, a.url, a.img, a.dateAdded, a.addedBy 
	FROM song s 
	INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID 
	RIGHT JOIN artist a ON a.artistID = sfa.artistID 
	WHERE s.name like ? OR a.name like ?";	
	
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

    // This wil search all artist from a user
    function serachForuser($userID, $keyword, $limit) {
	$query = "SELECT artistID as id, name, url, img, dateAdded, addedBy 
	    FROM artist 
	    WHERE addedBy LIKE ? 
	    AND name LIKE ? 
	    LIMIT ?";

	$stmt = $this->conn->prepare($query);

	// Clean input
	$keyword = htmlspecialchars(strip_tags($keyword));
	$userID = htmlspecialchars(strip_tags($userID));
	$userID = "%$userID%";
	$keyword = "%$keyword%";

	// Bind params
	$stmt->bindParam(1, $userID);
	$stmt->bindParam(2, $keyword);
	$stmt->bindParam(3, $limit, PDO::PARAM_INT);

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

    // Gets the top artist of a user for search result
    function topArtistSearch($userID, $keyword, $amount) {
	$query = "SELECT a.name AS name
	    FROM played p
	    INNER JOIN song s ON p.songID = s.songID
	    INNER JOIN SongFromArtist sfa ON sfa.songID = s.songID
	    RIGHT JOIN artist a ON sfa.artistID = a.artistID
	    WHERE p.playedBy LIKE ? AND a.addedBy LIKE ? AND s.addedBy LIKE ? AND sfa.addedBy LIKE ?
	    AND a.name LIKE ?
	    GROUP BY a.artistID
	    ORDER BY count(p.songID) DESC
	    LIMIT ?";
	$stmt = $this->conn->prepare($query);

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$keyword = htmlspecialchars(strip_tags($keyword));
	$amount = htmlspecialchars(strip_tags($amount));

	$userID = "%$userID%";
	$keyword = "%$keyword%";

	// Bind params
	$stmt->bindParam(1, $userID);
	$stmt->bindParam(2, $userID);
	$stmt->bindParam(3, $userID);
	$stmt->bindParam(4, $userID);
	$stmt->bindParam(5, $keyword);
	$stmt->bindParam(6, $amount, PDO::PARAM_INT);

	$stmt->execute();
	return $stmt;
    }
}
?>
