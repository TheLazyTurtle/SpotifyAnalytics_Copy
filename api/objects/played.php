<?php
require_once '../config/check_cookie.php';

class Played
{
	// DB connection
	private $conn;

	// Object properties
	public $songID;
	public $datePlayed;
	public $playedBy;
	public $songName;

	public function __construct($db)
	{
		$this->conn = $db;
		//checkCookie();
	}

	function readOne($songID)
	{
		$query = "SELECT * FROM played WHERE songID = ?";
		$stmt = $this->conn->prepare($query);

		$songID = htmlspecialchars(strip_tags($songID));

		$stmt->bindParam(1, $songID);
		$stmt->execute();

		return $stmt;
	}

	// This will mark a song as played
	function create()
	{
		$query = "INSERT INTO played (songID, datePlayed, playedBy, songName) VALUES (?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		$this->songID = htmlspecialchars(strip_tags($this->songID));
		$this->datePlayed = htmlspecialchars(strip_tags($this->datePlayed));
		$this->playedBy = htmlspecialchars(strip_tags($this->playedBy));
		$this->songName = htmlspecialchars(strip_tags($this->songName));

		$stmt->bindParam(1, $this->songID);
		$stmt->bindParam(2, $this->datePlayed, PDO::PARAM_STR);
		$stmt->bindParam(3, $this->playedBy, PDO::PARAM_STR);
		$stmt->bindParam(4, $this->songName);

		return $stmt->execute();
	}

    function createSpecial($songID, $datePlayed, $playedBy, $songName) {
		$query = "INSERT INTO played (songID, datePlayed, playedBy, songName) VALUES (?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		$this->songID = htmlspecialchars(strip_tags($songID));
		$this->datePlayed = htmlspecialchars(strip_tags($datePlayed));
		$this->playedBy = htmlspecialchars(strip_tags($playedBy));
		$this->songName = htmlspecialchars(strip_tags($songName));

		$stmt->bindParam(1, $this->songID);
		$stmt->bindParam(2, $this->datePlayed, PDO::PARAM_STR);
		$stmt->bindParam(3, $this->playedBy, PDO::PARAM_STR);
		$stmt->bindParam(4, $this->songName);

        try {
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

	// This will get all songs played by a user
	function allSongsPlayed($userID, $minPlayed, $maxPlayed, $minDate, $maxDate, $relative)
	{
		$counter = $relative ? "SUM(s.length) / 1000 / 60" : "COUNT(p.songID)";
		$query = "SELECT DISTINCT s.albumID as albumID, p.songName as name, $counter as times
			FROM played p
			INNER JOIN artist_has_song sfa ON p.songID = sfa.songID
			INNER JOIN song s ON sfa.songID = s.songID
			RIGHT JOIN artist a ON sfa.artistID = a.artistID
			WHERE p.playedBy LIKE ?
			AND datePlayed BETWEEN ? AND ?
			GROUP BY p.songName, a.artistID
			HAVING times BETWEEN ? AND ?
			ORDER BY name";
		$stmt = $this->conn->prepare($query);

		// Clean the input
		$userID = htmlspecialchars(strip_tags($userID));
		$minPlayed = htmlspecialchars(strip_tags($minPlayed));
		$maxPlayed = htmlspecialchars(strip_tags($maxPlayed));
		$minDate = htmlspecialchars(strip_tags($minDate));
		$maxDate = htmlspecialchars(strip_tags($maxDate));

		$stmt->bindParam(1, $userID);
		$stmt->bindParam(2, $minDate);
		$stmt->bindParam(3, $maxDate);
		$stmt->bindParam(4, $minPlayed);
		$stmt->bindParam(5, $maxPlayed);
		$stmt->execute();

		return $stmt;
	}

	// This will get the top songs of a user
	function topSongs($userID, $artist, $minDate, $maxDate, $amount, $relative)
	{
		$counter = $relative ? "SUM(s.length) / 1000 / 60" : "COUNT(p.songID)";
		$query = "SELECT DISTINCT s.albumID as albumID, p.songName as songName, $counter as times, p.songID
			FROM played p
			INNER JOIN artist_has_song ahs ON ahs.songID = p.songID 
			INNER JOIN song s ON ahs.songID = s.songID
			RIGHT JOIN artist a on ahs.artistID = a.artistID
			WHERE a.name LIKE ?
			AND p.playedBy LIKE ?
			AND datePlayed BETWEEN ? AND ?
			GROUP BY p.songName, a.artistID
			ORDER BY times DESC
			LIMIT ?";
		$stmt = $this->conn->prepare($query);

		// Clean the input
		$userID = htmlspecialchars(strip_tags($userID));
		$minDate = htmlspecialchars(strip_tags($minDate));
		$maxDate = htmlspecialchars(strip_tags($maxDate));
		$artist = htmlspecialchars(strip_tags($artist));
		$amount = htmlspecialchars(strip_tags($amount));
		$artist = "%$artist%";

		$stmt->bindParam(1, $artist);
		$stmt->bindParam(2, $userID);
		$stmt->bindParam(3, $minDate);
		$stmt->bindParam(4, $maxDate);
		$stmt->bindParam(5, $amount, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt;
	}

	// TODO: Make the query that it will return the song that it searches for and also to artist of the song that you have listend most to
	function topSongSearch($userID, $keyword, $amount)
	{
		$query = "SELECT p.songID, count(*) as times, p.songName as songName, a.name as artistName
				FROM played p 
				INNER JOIN artist_has_song ahs ON p.songID = ahs.songID
				RIGHT JOIN artist a ON ahs.artistID = a.artistID
				WHERE playedBy LIKE ?
				AND p.songName LIKE ?
				GROUP BY p.songID
				ORDER BY times DESC
				LIMIT ?";
		$stmt = $this->conn->prepare($query);

		// Clean the input
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

	// Gets the played per day graph
	function playedPerDay($userID, $song, $artist, $minDate, $maxDate, $relative)
	{
		// Calculate difference between minDate and maxDate in days
		$origin = new DateTime($minDate);
		$target = new DateTime($maxDate);
		$interval = $origin->diff($target);
		$days = (int)$interval->format("%R%a");

		$counter = $relative ? "SUM(s.length) / 1000 / 60" : "COUNT(*)";

		$query = "SELECT unix_timestamp(p.datePlayed) * 1000 as date, $counter as times
			from played p
			INNER JOIN song s ON p.songID = s.songID
			WHERE s.songID IN (
				SELECT s.songID
				FROM song s
				INNER JOIN artist_has_song ahs ON s.songID = ahs.songID
				RIGHT JOIN artist a ON ahs.artistID = a.artistID
				WHERE a.name LIKE ?
				AND s.name LIKE ?
			)
			AND p.playedBy LIKE ?
			AND p.datePlayed BETWEEN ? AND ?";

		if ($days >= 0 && $days <= 31) {
			$query = $query . "GROUP BY HOUR(p.datePlayed)";
		} else {
			$query = $query . "GROUP BY MONTH(p.datePlayed), YEAR(p.datePlayed)";
		}

		$query = $query . "ORDER BY date DESC";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$userID = htmlspecialchars(strip_tags($userID));
		$song = htmlspecialchars(strip_tags($song));
		$artist = htmlspecialchars(strip_tags($artist));
		$minDate = htmlspecialchars(strip_tags($minDate));
		$maxDate = htmlspecialchars(strip_tags($maxDate));

		$stmt->bindParam(1, $artist);
		$stmt->bindParam(2, $song);
		$stmt->bindParam(3, $userID);
		$stmt->bindParam(4, $minDate);
		$stmt->bindParam(5, $maxDate);
		$stmt->execute();

		return $stmt;
	}

	// Gets the amount of songs you have listend to in the time frame specified
	function amountOfSongs($userID, $minDate, $maxDate)
	{
		$query = "SELECT count(*) as times, p.datePlayed as date
				FROM played p 
				WHERE p.playedBy LIKE ?
				AND p.datePlayed BETWEEN ? AND ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$userID = htmlspecialchars(strip_tags($userID));
		$minDate = htmlspecialchars(strip_tags($minDate));
		$maxDate = htmlspecialchars(strip_tags($maxDate));

		$stmt->bindParam(1, $userID);
		$stmt->bindParam(2, $minDate);
		$stmt->bindParam(3, $maxDate);
		$stmt->execute();

		return $stmt;
	}

	// Gets the time you have listend to music in the given time frame
	// Might have to move it to another place but not sure where
	function timeListend($userID, $minDate, $maxDate)
	{
		$query = "SELECT SUM(s.length) as time 
				FROM played p
				INNER JOIN song s ON p.songID = s.songID
				WHERE p.playedBy LIKE ?
				AND p.datePlayed BETWEEN ? AND ?";

		$stmt = $this->conn->prepare($query);

		// Clean input
		$userID = htmlspecialchars(strip_tags($userID));
		$minDate = htmlspecialchars(strip_tags($minDate));
		$maxDate = htmlspecialchars(strip_tags($maxDate));

		$stmt->bindParam(1, $userID);
		$stmt->bindParam(2, $minDate);
		$stmt->bindParam(3, $maxDate);
		$stmt->execute();

		return $stmt;
	}

	// Get the new songs for the given timeframe
	// TODO: This one is a total mess i think have to look at it
	function newSongs($userID, $minDate, $maxDate)
	{
		$query = "SELECT COUNT(*) AS new, img 
			FROM (
				SELECT songID 
				FROM played 
				WHERE playedBy LIKE ? 
				GROUP BY songID 
				HAVING MIN(datePlayed) >= ?) a 
			INNER JOIN song s ON s.songID = a.songID";
		//$query = "SELECT COUNT(new) as items, s.img as img
		//FROM (
		//SELECT p.songID as songID, MIN(p.datePlayed) new
		//FROM played p 
		//WHERE p.playedBy LIKE ?
		//AND p.datePlayed BETWEEN ? AND ?
		//GROUP BY p.songID
		//) p
		//INNER JOIN song s ON s.songID = p.songID";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$userID = htmlspecialchars(strip_tags($userID));
		$minDate = htmlspecialchars(strip_tags($minDate));
		//$maxDate = htmlspecialchars(strip_tags($maxDate));

		$stmt->bindParam(1, $userID, PDO::PARAM_STR);
		$stmt->bindParam(2, $minDate, PDO::PARAM_STR);
		$stmt->execute();

		return $stmt;
	}

	// This will get how often a person has listend to a song
	function songCount($userID, $songID)
	{
		$query = "SELECT count(*) as count FROM played WHERE playedBy = ? AND songID = ?";
		$stmt = $this->conn->prepare($query);

		$userID = htmlspecialchars(strip_tags($userID));
		$songID = htmlspecialchars(strip_tags($songID));

		$stmt->bindParam(1, $userID);
		$stmt->bindParam(2, $songID);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			return $count;
		}
	}
}
