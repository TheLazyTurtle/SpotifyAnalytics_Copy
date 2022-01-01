<?php
class Database
{
	// DB creds
	private $username = "root";
	private $password = "root";
	private $host = "localhost";
	private $db_name = "spotify";
	public $conn;

	// Make db connection
	public function getConnection()
	{
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
