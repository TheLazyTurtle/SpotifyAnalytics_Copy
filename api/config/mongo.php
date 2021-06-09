<?php
require_once '/var/www/html/vendor/autoload.php';

class Mongo {
    // DB creds
    private $host = "***REMOVED***&w=majority";
    public $conn;

    public function getConnection() {
	$this->conn = new MongoDB\Client($this->host);

	return $this->conn->spotify;
    }
}
?>
