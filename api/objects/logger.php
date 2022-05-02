<?php
class Logger {
    private $conn;

    public $id;
    public $level;
    public $message;
    public $type;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Levels explained
    // 1 = Very important/very bad
    // 2 = Item (song, artist, album, played, etc) failed to add to db
    // 3 = Normal log

    function read() 
    {
        $query = "SELECT * FROM logs";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function create($level, $message, $type)
    {
        $query = "INSERT INTO logs (level, message, type) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $level = htmlspecialchars(strip_tags($level));
        $message = htmlspecialchars(strip_tags($message));
        $type = htmlspecialchars(strip_tags($type));

        $stmt->bindParam(1, $level);
        $stmt->bindParam(2, $message);
        $stmt->bindParam(3, $type);

        return $stmt->execute();
    }
}
?>
