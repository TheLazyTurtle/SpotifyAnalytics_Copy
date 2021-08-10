<?php
require '../config/check_cookie.php';

class Artist
{
	// DB connection
	private $conn;

	// Object properties
	public $id;
	public $name;
	public $url;
	public $dateAdded;
	public $addedBy;
	public $img;

	public function __construct($db)
	{
		$this->conn = $db;
		//checkCookie();
	}

	// Get all the artists form the db
	function read()
	{
		$collection = $this->conn->artist;
		$cursor = $collection->find();

		return $cursor;
	}

	// This will only read one artist from the db
	function readOne()
	{
		$query = "SELECT * FROM artist WHERE name LIKE ? LIMIT 1";
		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->name);
		$stmt->execute();


		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$this->id = $row["artistID"];
			$this->name = $row["name"];
			$this->url = $row["url"];
			$this->img = $row["img"];
		}
	}

	// Add artist to db
	// Might have to make create batch where you can input an object
	// of artist and it will insert them one by one
	function create()
	{
		$query = "INSERT INTO artist (artistID, name, url, img) VALUES (?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		// Clean data of specialchars
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->url = htmlspecialchars(strip_tags($this->url));
		$this->img = htmlspecialchars(strip_tags($this->img));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->name);
		$stmt->bindParam(3, $this->url);
		$stmt->bindParam(4, $this->img);

		return $stmt->execute();
	}

	// Get artist based on keywords
	// Decide what is we want to return for artists
	// Will we only give the name etc or also all the songs they are part of
	function search($keyword)
	{

		$query = "SELECT * FROM artist s WHERE s.name LIKE ?";
		$stmt = $this->conn->prepare($query);

		// Clean the keywords
		$keyword = htmlspecialchars(strip_tags($keyword));
		$keyword = "%$keyword%";

		$stmt->bindParam(1, $keyword);
		$stmt->execute();

		return $stmt;
	}

	// This wil search all artist from a user
	function serachForuser($userID, $keyword, $limit)
	{
		$query = "SELECT artistID, name 
			FROM artist
			WHERE name LIKE ?
			LIMIT ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$keyword = htmlspecialchars(strip_tags($keyword));
		$keyword = "%$keyword%";

		$stmt->bindParam(1, $keyword);
		$stmt->bindParam(2, $limit, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt;
	}

	// Gets the top artist of a user
	function topArtist($userID, $minDate, $maxDate, $amount)
	{
		$query = "SELECT a.name, COUNT(*) times, a.img 
			FROM played p
			INNER JOIN artist_has_song ahs ON p.songID = ahs.songID
			RIGHT JOIN artist a ON ahs.artistID = a.artistID
			WHERE p.playedBy LIKE ?
			AND p.datePlayed BETWEEN ? AND ?
			GROUP BY a.artistID
			ORDER BY times DESC
			LIMIT ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$userID = htmlspecialchars(strip_tags($userID));
		$minDate = htmlspecialchars(strip_tags($minDate));
		$maxDate = htmlspecialchars(strip_tags($maxDate));
		$amount = htmlspecialchars(strip_tags($amount));

		$stmt->bindParam(1, $userID);
		$stmt->bindParam(2, $minDate);
		$stmt->bindParam(3, $maxDate);
		$stmt->bindParam(4, $amount, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt;
	}

	// Gets the top artist of a user for search result
	function topArtistSearch($userID, $keyword, $amount)
	{
		$query = "SELECT a.name, COUNT(*) AS times
			FROM played p
			INNER JOIN artist_has_song ahs ON p.songID = ahs.songID
			RIGHT JOIN artist a ON ahs.artistID = a.artistID
			WHERE p.playedBy LIKE ?
			AND a.name LIKE ?
			GROUP BY a.artistID
			ORDER BY times DESC
			LIMIT ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$userID = htmlspecialchars(strip_tags($userID));
		$keyword = htmlspecialchars(strip_tags($keyword));
		$amount = htmlspecialchars(strip_tags($amount));
		$keyword = "%$keyword%";

		$stmt->bindParam(1, $userID);
		$stmt->bindParam(2, $keyword);
		$stmt->bindParam(3, $amount, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt;
	}

	// This will get the img of an artist
	function getImage($artistID)
	{
		$query = "SELECT img FROM artist WHERE artistID = ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$artistID = htmlspecialchars(strip_tags($artistID));

		$stmt->bindParam(1, $artistID);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			return $img;
		}

		return false;
	}

	// This will get the top song of an artist
	function topSongs($artistID)
	{
		$query = "SELECT count(*) as count, s.preview as preview, s.img as img, s.name as title
				FROM played p 
				INNER JOIN song s ON p.songID = s.songID
				INNER JOIN artist_has_song ahs ON p.songID = ahs.songID
				INNER JOIN artist a ON ahs.artistID = a.artistID
				WHERE a.artistID = ? AND ahs.artistID = ?
				GROUP BY p.songID 
				ORDER BY count(*) DESC
				LIMIT 10";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$artistID = htmlspecialchars(strip_tags($artistID));

		$stmt->bindParam(1, $artistID);
		$stmt->bindParam(2, $artistID);
		$stmt->execute();

		return $stmt;
	}
}
