<?php
require_once '../config/check_cookie.php';

class Song
{
	// Db connection and table
	private $conn;

	// Object properties
	public $id;
	public $name;
	public $length;
	public $url;
	public $img;
	public $preview;
	public $albumID;
	public $releaseDate;
	public $explicit;
	public $artists = array();

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
	function createOne()
	{
		$query = "INSERT INTO song (songID, name, length, url, img, preview, albumID, releaseDate, explicit) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		// Clean data from specialchars
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->length = htmlspecialchars(strip_tags($this->length));
		$this->url = htmlspecialchars(strip_tags($this->url));
		$this->img = htmlspecialchars(strip_tags($this->img));
		$this->preview = htmlspecialchars(strip_tags($this->preview));
		$this->albumID = htmlspecialchars(strip_tags($this->albumID));
		$this->releaseDate = htmlspecialchars(strip_tags($this->releaseDate));
		$this->explicit = htmlspecialchars(strip_tags($this->explicit));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->name);
		$stmt->bindParam(3, $this->length);
		$stmt->bindParam(4, $this->url);
		$stmt->bindParam(5, $this->img);
		$stmt->bindParam(6, $this->preview);
		$stmt->bindParam(7, $this->albumID);
		$stmt->bindParam(8, $this->releaseDate);
		$stmt->bindParam(9, $this->explicit, PDO::PARAM_BOOL);

		return $stmt->execute();
	}

	// Update the song
	function update()
	{
		$query = "UPDATE song SET songID = ?, name = ?, length = ?, url = ?, img = ?, preview = ?, album = ?, releaseDate = ?, explicit = ? WHERE songID = ?";
		$stmt = $this->conn->prepare($query);

		// Clean data from specialchars
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->length = htmlspecialchars(strip_tags($this->length));
		$this->url = htmlspecialchars(strip_tags($this->url));
		$this->img = htmlspecialchars(strip_tags($this->img));
		$this->preview = htmlspecialchars(strip_tags($this->preview));
		$this->album = htmlspecialchars(strip_tags($this->album));
		$this->releaseDate = htmlspecialchars(strip_tags($this->releaseDate));
		$this->explicit = htmlspecialchars(strip_tags($this->explicit));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->name);
		$stmt->bindParam(3, $this->length);
		$stmt->bindParam(4, $this->url);
		$stmt->bindParam(5, $this->img);
		$stmt->bindParam(6, $this->preview);
		$stmt->bindParam(7, $this->album);
		$stmt->bindParam(8, $this->releaseDate);
		$stmt->bindParam(9, $this->explicit, PDO::PARAM_BOOL);
		$stmt->bindParam(10, $this->id);

		return $stmt->execute();
	}

	// Link the artist to the song
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

	// This will get all the songs from an album
	function getAlbumSongs($artist)
	{
		$query = "SELECT * FROM song WHERE albumID = ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$this->albumID = htmlspecialchars(strip_tags($this->albumID));
		$stmt->bindParam(1, $this->albumID);
		$stmt->execute();

		$songArr = array();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			$songItem = array(
				"songID" => $songID,
				"name" => $name,
				"img" => $img,
				"url" => $url,
				"releaseDate" => $releaseDate,
				"length" => $length,
				"preview" => $preview,
				"explicit" => $explicit,
				"artists" => $artist->getSongArtists($songID)
			);
			array_push($songArr, $songItem);
		}
		return $songArr;
	}
}
