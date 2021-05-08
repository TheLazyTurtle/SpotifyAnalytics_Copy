<?php
class Database {
    // DB creds
    private $host = "192.168.2.7";
    private $db_name = "spotify";
    private $username = "remote";
    private $password = "***REMOVED***";
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
