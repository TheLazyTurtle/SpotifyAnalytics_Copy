<?php
require_once "../config/check_cookie.php";

class User
{
	// Database connection
	private $conn;

	// Object properties
	public $id;
	public $username;
	public $firstname;
	public $lastname;
	public $img;
	public $email;
	public $password;
	public $isAdmin;
	public $following;
	public $followers;

	public function __construct($db)
	{
		$this->conn = $db;
		//checkCookie();
	}

	function create()
	{
		$query = "INSERT INTO user (userID, username, firstname, lastname, email, password) VALUES (?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		// Clean input values
		$this->username = htmlspecialchars(strip_tags($this->username));
		$this->firstname = htmlspecialchars(strip_tags($this->firstname));
		$this->lastname = htmlspecialchars(strip_tags($this->lastname));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->password = htmlspecialchars(strip_tags($this->password));

		// Bind values
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->username);
		$stmt->bindParam(3, $this->firstname);
		$stmt->bindParam(4, $this->lastname);
		$stmt->bindParam(5, $this->email);

		// Hash the password
		$passwordHash = password_hash($this->password, PASSWORD_BCRYPT);
		$stmt->bindParam(6, $passwordHash);

		// Execute query and check if success
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	// Update the user record
	function update()
	{
		// If password needs to be updated
		$passwordSet = !empty($this->password) ? ", password = :password" : "";

		// If no posted password, do not update the password
		$query = "UPDATE temp_user 
	    SET 
		firstname = :firstname,
		lastname = :lastname,
		email = :email
		{$passwordSet}
	    WHERE id = :id";

		$stmt = $this->conn->prepare($query);

		// Clean input
		$this->firstname = htmlspecialchars(strip_tags($this->firstname));
		$this->lastname = htmlspecialchars(strip_tags($this->lastname));
		$this->email = htmlspecialchars(strip_tags($this->email));

		// Bind values to query
		$stmt->bindParam(':firstname', $this->firstname);
		$stmt->bindParam(':lastname', $this->lastname);
		$stmt->bindParam(':email', $this->email);

		// Hash the password
		if (!empty($this->password)) {
			$this->password = htmlspecialchars(strip_tags($this->password));
			$passwordHash = password_hash($this->password, PASSWORD_BCRYPT);
			$stmt->bindParam(':password', $passwordHash);
		}

		// bind the id of the user to be edited
		$stmt->bindParam(":id", $this->id);

		// Execute query
		if ($stmt->execute()) {
			return true;
		}

		return false;
	}

	// This will get all usefull info from the user
	function read_one()
	{
		$query = "SELECT * FROM user WHERE username LIKE ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$this->username = htmlspecialchars(strip_tags($this->username));

		// Bind params
		$stmt->bindParam(1, $this->username);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$this->id = $userID;
			$this->firstname = $firstname;
			$this->lastname = $lastname;
			$this->email = $email;
			$this->isAdmin = $isAdmin;
			$this->img = $img;
		}
	}

	// Search for users
	function search($keyword)
	{
		$query = "SELECT username as name FROM user WHERE username LIKE ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$keyword = htmlspecialchars(strip_tags($keyword));
		$keyword = "%$keyword%";

		// Bind param
		$stmt->bindParam(1, $keyword);
		$stmt->execute();

		return $stmt;
	}

	// Check if the email exists in the db
	function emailExists()
	{
		$query = "SELECT * FROM user WHERE email = ? LIMIT 0,1";

		$stmt = $this->conn->prepare($query);

		// Clean input
		$this->email = htmlspecialchars(strip_tags($this->email));

		// Bind param
		$stmt->bindParam(1, $this->email);
		$stmt->execute();

		// Get row count
		$num = $stmt->rowCount();

		if ($num > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			// Asign values to object
			$this->id = $row["userID"];
			$this->firstname = $row["firstname"];
			$this->lastname = $row["lastname"];
			$this->password = $row["password"];

			return true;
		}
		return false;
	}

	// Checks if the username exists
	function usernameExists()
	{
		$query = "SELECT * FROM user WHERE username = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$this->username = htmlspecialchars(strip_tags($this->username));

		// Bind Params
		$stmt->bindParam(1, $this->username);
		$stmt->execute();
		$num = $stmt->rowCount();

		if ($num > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$this->id = $row["userID"];
			$this->firstname = $row["firstname"];
			$this->lastname = $row["lastname"];
			$this->email = $row["email"];
			$this->password = $row["password"];

			return true;
		}

		return false;
	}

	// Get all user filterSettings
	function readFilterSettings($userID)
	{
		$query = "SELECT * FROM filterSetting WHERE userID = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $userID);
		$stmt->execute;
		return $stmt;
	}

	// Get one user filterSetting
	function readOneFilterSetting($userID, $name, $graphID)
	{
		$query = "SELECT * FROM filterSetting WHERE userID = ? AND name = ? OR graphID = ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$name = htmlspecialchars(strip_tags($name));

		// Bind values
		$stmt->bindParam(1, $userID);
		$stmt->bindParam(2, $name);
		$stmt->bindParam(3, $graphID);
		$stmt->execute();
		return $stmt;
	}

	// Update filterSetting
	function updateFilterSetting($userID, $settingName, $value, $graphID)
	{
		$query = "UPDATE filterSetting SET value = ? WHERE name = ? AND graphID = ? AND userID = ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$settingName = htmlspecialchars(strip_tags($settingName));
		$value = htmlspecialchars(strip_tags($value));
		$graphID = htmlspecialchars(strip_tags($graphID));

		// Bind values
		$stmt->bindParam(1, $value);
		$stmt->bindParam(2, $settingName);
		$stmt->bindParam(3, $graphID);
		$stmt->bindParam(4, $userID);

		$stmt->execute();
		return $stmt;
	}

	// Get all users
	function getAllUsers()
	{
		$query = "SELECT * from user";
		$stmt = $this->conn->prepare($query);

		$stmt->execute();
		return $stmt;
	}

	function getAuthTokens($userID)
	{
		$query = "SELECT * FROM spotifyData WHERE userID = ?";
		$stmt = $this->conn->prepare($query);

		// Clean input
		$userID = htmlspecialchars(strip_tags($userID));

		$stmt->bindParam(1, $userID);

		$stmt->execute();
		return $stmt;
	}

	// This will set the auth tokens for the user
	function setAuthTokens($userID, $accessToken, $refreshToken, $expireTime)
	{
		$query = "INSERT INTO spotifyData (userID, authToken, refreshToken, ExpireDate) VALUES (?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		// clean input
		$userID = htmlspecialchars(strip_tags($userID));
		$accessToken = htmlspecialchars(strip_tags($accessToken));
		$refreshToken = htmlspecialchars(strip_tags($refreshToken));
		$expireTime = htmlspecialchars(strip_tags($expireTime));

		// Bind params
		$stmt->bindParam(1, $userID);
		$stmt->bindParam(2, $accessToken);
		$stmt->bindParam(3, $refreshToken);
		$stmt->bindParam(4, $expireTime);

		return $stmt->execute();
	}

	// This will get how many followers a person has
	function getFollowersCount()
	{
		$query = "SELECT count(*) as followers FROM followers WHERE following = ?";
		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			$this->followers = $followers;
		}
	}

	// This will get how many people a person follows
	function getFollowingCount()
	{
		$query = "SELECT count(*) as following FROM followers WHERE follower = ?";
		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			$this->following = $following;
		}
	}

	// This will update the profile picture location of the user in the database
	function updateProfilePicture()
	{
		$query = "UPDATE user set img = ? WHERE userID = ?";
		$stmt = $this->conn->prepare($query);

		$this->img = htmlspecialchars(strip_tags($this->img));
		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->img);
		$stmt->bindParam(2, $this->id);

		return $stmt->execute();
	}

	// Makes you follow a person
	function follow($userToFollow)
	{
		$query = "INSERT INTO followers (follower, following) VALUES (?, ?)";
		$stmt = $this->conn->prepare($query);

		$userToFollow = htmlspecialchars(strip_tags($userToFollow));
		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $userToFollow);

		return $stmt->execute();
	}

	// Makes you unfollow a person
	function unFollow($userToUnfollow)
	{
		$query = "DELETE FROM followers WHERE follower = ? AND following = ?";
		$stmt = $this->conn->prepare($query);

		$userToUnfollow = htmlspecialchars(strip_tags($userToUnfollow));
		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $userToUnfollow);

		return $stmt->execute();
	}

	// Checks if you are following a person
	function isFollowing($user)
	{
		$query = "SELECT COUNT(*) AS count FROM followers WHERE follower = ? AND following = ?";
		$stmt = $this->conn->prepare($query);

		$user = htmlspecialchars(strip_tags($user));
		$this->id = htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $user);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			if ($count > 0) {
				return True;
			} else {
				return False;
			}
		}
	}
}
