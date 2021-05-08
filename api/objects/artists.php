<?php
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
	$this->checkCoockie();
    }

    // This checks if the jwt cookie is set and is valid. 
    // If its not set or not valid than kill the connection and don't show the data.
    // This is done to prevent api requests from people who aren't autenticated 
    // to get the data they asked for.
    function checkCoockie() {
	$jwtCookie = isset($_COOKIE["jwt"]) ? $_COOKIE["jwt"] : null;

	if ($jwtCookie == null) {
	    die();
	}

	$data = array("jwt" => $jwtCookie);

	$options = array (
	    'http' => array (
		'header' => 'Content-type: application/json',
		'method' => 'POST',
		'content' => http_build_query($data)
	    )
	);
	$context = stream_context_create($options);
	$result = file_get_contents("../../api/system/validate_token.php", false, $context);

	if ($result === false) {
	    die();
	}
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
}
?>
