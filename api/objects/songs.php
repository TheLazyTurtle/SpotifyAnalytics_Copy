<?php
require_once '../config/check_cookie.php';

class Song
{
	// Db connection and table
	private $conn;
	private $collection;

	// Object properties
	public $id;
	public $name;
	public $length;
	public $url;
	public $img;
	public $dateAdded;
	public $addedBy;
	public $preview;

	public function __construct($db)
	{
		$this->conn = $db;
		//checkCookie();
	}

	// Get all songs from the db
	function read()
	{
		$query = "SELECT * FROM song";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	// This will only read one song from the database based on the id given
	function readOne()
	{
		$query = "SELECT * FROM song WHERE songID = ?";
		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);
		$stmt->execute();


		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$this->id = $songID;
			$this->name = $name;
			$this->length = $length;
			$this->url = $url;
			$this->img = $img;
			$this->preview = $preview;
		}
	}

	// Add a song to the db
	// TODO: Might have to make create batch where you can input an object
	// of artist and it will insert them one by one
	function createOne()
	{
		$query = "INSERT INTO song (songID, name, length, url, img, preview) VALUES (?, ?, ?, ?, ?, ?)";
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

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->name);
		$stmt->bindParam(3, $this->length);
		$stmt->bindParam(4, $this->url);
		$stmt->bindParam(5, $this->img);
		$stmt->bindParam(6, $this->preview);

		return $stmt->execute();
	}

	function linkArtistToSong($songID, $artistID)
	{
		$query = "INSERT INTO artist_has_song (songID, artistID) VALUES (?, ?)";
		$stmt = $this->conn->prepare($query);

		$songID = htmlspecialchars(strip_tags($songID));
		$artistID = htmlspecialchars(strip_tags($artistID));

		$stmt->bindParam(1, $songID);
		$stmt->bindParam(2, $artistID);

		return $stmt->execute();
	}

	// This will search for a song by keyword
	// If there is no keyword it returns an empty array to prevent loading all songs
	function search($keyword)
	{
		$query = "SELECT * FROM song WHERE name LIKE ?";
		$stmt = $this->conn->prepare($query);

		// Clean the keywords
		$keyword = htmlspecialchars(strip_tags($keyword));
		$keyword = "%$keyword%";

		$stmt->bindParam(1, $keyword);
		$stmt->execute();
		return $stmt;
	}

	// This will return the songID from the song by searching by name and a artist name
	function searchByArtist($song, $artist)
	{
		$query = "SELECT s.songID FROM song s 
			INNER JOIN artist_has_song ahs ON ahs.songID = s.songID
			RIGHT JOIN artist a ON ahs.artistID = a.artistID
			WHERE s.name LIKE ? AND a.name LIKE ?";

		$stmt = $this->conn->prepare($query);

		// Clean input
		$song = htmlspecialchars(strip_tags($song));
		$artist = htmlspecialchars(strip_tags($artist));

		$stmt->bindParam(1, $song);
		$stmt->bindParam(2, $artist);
		$stmt->execute();

		return $stmt;
	}

	//This will search all songs from a user
	//NOTE: This one we will ignore of a sec. This one is probably used for searching songs to filter a graph so I don't think it's needed
	function searchForUser($userID, $songName, $limit)
	{
		$query = "";

		// Clean input
		$userID = htmlspecialchars(strip_tags($userID));
		$songName = htmlspecialchars(strip_tags($songName));

		$cursor = $this->collection->find($query)->limit($limit);
		return $cursor;
	}

	// Get the image of a song
	function getImage($songID)
	{
		$query = "SELECT img FROM song WHERE songID = ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$songID = htmlspecialchars(strip_tags($songID));

		$stmt->bindParam(1, $songID);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			return $img;
		}
	}
}
