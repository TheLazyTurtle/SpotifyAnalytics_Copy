<?php
class Memory
{
	// Db connection
	private $conn;

	// Object properties
	public $id;
	public $posterID;
	public $description;
	public $img;
	public $datePosted;
	public $songs = array();

	public function __construct($db)
	{
		$this->conn = $db;
	}

	// This will get all post from the people a person follows
	function read($userID)
	{
		$query = "SELECT p.*, f.follower FROM post p INNER JOIN followers f ON p.userID = f.following WHERE f.follower = ?";
		$stmt = $this->conn->prepare($query);

		$userID = htmlspecialchars(strip_tags($userID));

		$stmt->bindParam(1, $userID);
		$stmt->execute();

		return $stmt;
	}

	// This will read a specific post
	function readOne()
	{
		$query = "SELECT * FROM post WHERE postID = ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		return $stmt;
	}

	// This will create a post
	function create()
	{
		$query = "INSERT INTO post (postID, userID, description, img) VALUES (?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->posterID = htmlspecialchars(strip_tags($this->posterID));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->img = htmlspecialchars(strip_tags($this->img));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->posterID);
		$stmt->bindParam(3, $this->description);
		$stmt->bindParam(4, $this->img);

		return $stmt->execute();
	}

	// This will link the songs to the post when a user uploads a post
	function linkSongToPost($songID)
	{
		$query = "INSERT INTO post_has_song (postID, songID) VALUES (?, ?)";
		$stmt = $this->conn->prepare($query);

		$songID = htmlspecialchars(strip_tags($songID));
		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $songID);

		return $stmt->execute();
	}

	// Get all the songs linked to the post
	function getPostSongs($artist)
	{
		$query = "SELECT s.* FROM song s INNER JOIN post_has_song phs ON s.songID = phs.songID WHERE postID = ?";
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$songsArr = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			$song = array(
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
			array_push($songsArr, $song);
		}
		return $songsArr;
	}

	// This will update if a user has like or unlike the post
	function updateLikeCount($postID, $userID, $status)
	{
		$postID = htmlspecialchars(strip_tags($postID));
		$userID = htmlspecialchars(strip_tags($userID));

		if ($status == "true") {
			// Liked
			$query = "INSERT INTO likes (postID, userID) VALUE (?, ?)";
			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(1, $postID);
			$stmt->bindParam(2, $userID);
			return $stmt->execute();
		} else {
			// Unliked
			$query = "DELETE FROM likes WHERE postID = ? AND userID = ?";
			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(1, $postID, PDO::PARAM_INT);
			$stmt->bindParam(2, $userID);
			return $stmt->execute();
		}
	}

	// This gets the amount of likes a post has
	function getAmountOfLikes()
	{
		$query = "SELECT count(*) AS likes FROM likes WHERE postID = ?";
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			return $likes;
		}
	}

	// This will check if a user has like the post or not
	function userHasLikedPost($userID)
	{
		$query = "SELECT count(*) AS liked FROM likes WHERE postID = ? AND userID = ?";
		$stmt = $this->conn->prepare($query);

		$userID = htmlspecialchars(strip_tags($userID));
		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $userID);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			if ($liked == "1") {
				return true;
			} else {
				return false;
			}
		}
	}
}
