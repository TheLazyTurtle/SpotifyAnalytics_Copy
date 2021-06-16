<?php
require "../config/check_cookie.php";

class User {
    // Database connection
    private $conn;

    // Object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    public function __construct($db) {
	$this->conn = $db;
	//checkCookie();
    }

    function create() {
	$query = "INSERT INTO user SET firstname = :firstname, lastname = :lastname, email = :email, password = :password";

	$stmt = $this->conn->prepare($query);

	// Clean input values
	$this->firstname = htmlspecialchars(strip_tags($this->firstname));
	$this->lastname = htmlspecialchars(strip_tags($this->lastname));
	$this->email = htmlspecialchars(strip_tags($this->email));
	$this->password = htmlspecialchars(strip_tags($this->password));

	// Bind values
	$stmt->bindParam(':firstname', $this->firstname);
	$stmt->bindParam(':lastname', $this->lastname);
	$stmt->bindParam(':email', $this->email);

	// Hash the password
	$passwordHash = password_hash($this->password, PASSWORD_BCRYPT);
	$stmt->bindParam(':password', $passwordHash);

	// Execute query and check if success
	if ($stmt->execute()) {
	    return true;
	} else {
	    return false;
	}
    }

    // Update the user record
    function update() {
	// If password needs to be updated
	$passwordSet = !empty($this->password) ? ", password = :password": "";

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

    // Check if the email exists in the db
    function emailExists () {
	$query = "SELECT * FROM user WHERE email = ? LIMIT 0,1";

	$stmt = $this->conn->prepare($query);

	// Clean input
	$this->email=htmlspecialchars(strip_tags($this->email));

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

    // Get all user filterSettings
    function readFilterSettings($userID) {
	$query = "SELECT * FROM filterSetting WHERE userID = ?";
	$stmt = $this->conn->prepare($query);
	$stmt->bindParam(1, $userID);
	$stmt->execute;
	return $stmt;
    }

    // Get one user filterSetting
    function readOneFilterSetting($userID, $name, $graphID) {
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
    function updateFilterSetting($userID, $settingName, $value, $graphID) {
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

}

?>
