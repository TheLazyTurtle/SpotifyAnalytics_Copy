<?php
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
    }

    // Get all songs from the db
    function read() {
	$query = "SELECT songID as id, name, img, dateAdded, addedBy FROM " . $this->tableName . " LIMIT 10";
	$stmt = $this->conn->prepare($query);
	$stmt->execute();
	return $stmt;
    }

    // This will only read one song from the database based on the id given
    //TODO: Might have to throw in a userID or filter it that it will not check userID
    function readOne() {
	$query = "SELECT songID as id, name, img, length, url, dateAdded, addedBy, preview FROM " . $this->tableName . " WHERE songID = ? LIMIT 1";

	$stmt = $this->conn->prepare($query);
	$stmt->bindParam(1, $this->id);
	$stmt->execute();

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	$this->name = $row["name"];
	$this->length = $row["length"];
	$this->url = $row["url"];
	$this->img = $row["img"];
	$this->dateAdded = $row["dateAdded"];
	$this->addedBy = $row["addedBy"];
	$this->preview = $row["preview"];
    }

    // Add a song to the db
    function create() {
	$query = "INSERT INTO " . $this->tableName . "SET songID=:id, name=:name, length=:length, url=:url, img=:img, dateAdded=:dateAdded, addedBy=:addedBy, preview=:preview";
	$stmt = $this->con->prepare($query);

	// Clean data from specialchars
	$this->id=htmlspecialchars(strip_tags($this->id));
	$this->name=htmlspecialchars(strip_tags($this->name));
	$this->length=htmlspecialchars(strip_tags($this->length));
	$this->url=htmlspecialchars(strip_tags($this->url));
	$this->img=htmlspecialchars(strip_tags($this->img));
	$this->dateAdded=htmlspecialchars(strip_tags($this->dateAdded));
	$this->addedBy=htmlspecialchars(strip_tags($this->addedBy));
	$this->preview=htmlspecialchars(strip_tags($this->preview));

	if ($stmt->execute()) {
	    return true;
	}
	return false;
    }

    // TODO: Fix this query so it OR only shows the song with no artist OR it shows every version with an artist name OR it wil show one line with all artists included
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
}

?>
