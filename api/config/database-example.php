<?php
class Database {
    // DB creds
    private $username = "USERNAME";
    private $password = "PASSWORD";
    private $host = "HOST";
    private $db_name = "DATABASE";
    public $conn;

    // Make db connection
    public function getConnection() {
	$this->conn = null;

	try {
	    $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
	    $this->conn->exec("set names utf8");
	} catch (PDOException $e) {
	    echo "Connection error: " . $e->getMessage();
	}
	return $this->conn;
    }
}

?>
