<?php
require_once "../config/check_cookie.php";

class Album
{
	// DB connection
	private $conn;

	// Object properties
	public $id;
	public $name;
	public $songs = array();
	public $releaseDate;
	public $primaryArtistID;
	public $url;
	public $img;
	public $type;


	public function __construct($db)
	{
		$this->conn = $db;
	}

	// Read all albums
	function read()
	{
		$query = "SELECT * FROM album";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	// Read one album
	function readOne()
	{
		$query = "SELECT * FROM album WHERE ";

		if (isset($this->id)) {
			$query = $query . "albumID = ?";
			$stmt = $this->conn->prepare($query);

			$this->id = htmlspecialchars(strip_tags($this->id));
			$stmt->bindParam(1, $this->id);
		} else if (isset($this->name)) {
			$query = $query . "name = ?";
			$stmt = $this->conn->prepare($query);

			$this->name = htmlspecialchars(strip_tags($this->id));
			$stmt->bindParam(1, $this->name);
		}
		$stmt->execute();

		return $stmt;
	}


	// Search for albums by artist or name
	function search()
	{
		$query = "SELECT * FROM album WHERE ";

		if (isset($this->name)) {
			$query = $query . "name = ? ORDER BY releaseDate DESC";
			$stmt = $this->conn->prepare($query);

			$this->name = htmlspecialchars(strip_tags($this->name));
			$stmt->bindParam(1, $this->name);
		} else if (isset($this->primaryArtistID)) {
			$query = $query . "albumID IN (
				SELECT s.albumID FROM artist_has_song ahs
				INNER JOIN song s ON s.songID = ahs.songID
				RIGHT JOIN artist a ON a.artistID = ahs.artistID
				WHERE a.artistID = ?)
				ORDER BY releaseDate DESC";
			$stmt = $this->conn->prepare($query);

			$this->primaryArtistID = htmlspecialchars(strip_tags($this->primaryArtistID));
			$stmt->bindParam(1, $this->primaryArtistID);
		}

		$stmt->execute();
		return $stmt;
	}

	// Add new album to database
	function create()
	{
		$query = "INSERT INTO album (albumID, name, releaseDate, url, primaryArtistID, img, type) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->url = htmlspecialchars(strip_tags($this->url));
		$this->releaseDate = htmlspecialchars(strip_tags($this->releaseDate));
		$this->primaryArtistID = htmlspecialchars(strip_tags($this->primaryArtistID));
		$this->img = htmlspecialchars(strip_tags($this->img));
		$this->type = htmlspecialchars(strip_tags($this->type));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->name);
		$stmt->bindParam(3, $this->releaseDate);
		$stmt->bindParam(4, $this->url);
		$stmt->bindParam(5, $this->primaryArtistID);
		$stmt->bindParam(6, $this->img);
		$stmt->bindParam(7, $this->type);

		return $stmt->execute();
	}

	// Update the album
	function update()
	{
		$query = "UPDATE album SET albumID = ?, name = ?, releaseDate = ?, primaryArtistID = ?, url = ?, img = ?, type = ? WHERE albumID = ?";
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->releaseDate = htmlspecialchars(strip_tags($this->releaseDate));
		$this->primaryArtistID = htmlspecialchars(strip_tags($this->primaryArtistID));
		$this->url = htmlspecialchars(strip_tags($this->url));
		$this->img = htmlspecialchars(strip_tags($this->img));
		$this->type = htmlspecialchars(strip_tags($this->type));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->name);
		$stmt->bindParam(3, $this->releaseDate);
		$stmt->bindParam(4, $this->primaryArtistID);
		$stmt->bindParam(5, $this->url);
		$stmt->bindParam(6, $this->img);
		$stmt->bindParam(7, $this->type);
		$stmt->bindParam(8, $this->albumID);
	}
}
