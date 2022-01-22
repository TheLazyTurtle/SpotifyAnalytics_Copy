<?php
class Notification 
{
	// DB connection
	private $conn;

	// Object properties
	public $id;
	public $notificationTypeID;
	public $receiverUserID;
	public $senderUserID;
	public $name;
	public $message;

	public function __construct($db) {
		$this->conn = $db;
	}

	function create() {
		$query = "INSERT INTO notifications (notificationTypeID, receiverUserID, senderUserID) VALUES (?, ?, ?)";
		$stmt = $this->conn->prepare($query);

		$this->notificationTypeID = htmlspecialchars(strip_tags($this->notificationTypeID));
		$this->receiverUserID = htmlspecialchars(strip_tags($this->receiverUserID));
		$this->senderUserID = htmlspecialchars(strip_tags($this->senderUserID));

		$stmt->bindParam(1, $this->notificationTypeID);
		$stmt->bindParam(2, $this->receiverUserID);
		$stmt->bindParam(3, $this->senderUserID);

		return $stmt->execute();
	}

	function deleteNotification() {
		$query = "DELETE FROM notifications ";

		// Delete by notification id or userIDs
		if (isset($this->id)) {
			$query = $query . "WHERE notificationID = ?";
			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(1, $this->id);
		} else {
			$query = $query . "WHERE receiverUserID = ? AND senderUserID = ?";
			$this->receiverUserID = htmlspecialchars(strip_tags($this->receiverUserID));
			$this->senderUserID = htmlspecialchars(strip_tags($this->senderUserID));

			$stmt = $this->conn->prepare($query);

			$stmt->bindParam(1, $this->receiverUserID);
			$stmt->bindParam(2, $this->senderUserID);
		}

		return $stmt->execute();
	}

	function readUserNotifications() {
		$query = "SELECT n.*, nt.*, u.username FROM notifications n
			INNER JOIN notificationtypes nt ON n.notificationTypeID = nt.notificationTypeID
			LEFT JOIN user u ON u.userID = n.senderUserID
			WHERE receiverUserID = ?";
		$stmt = $this->conn->prepare($query);

		$this->receiverUserID = htmlspecialchars(strip_tags($this->receiverUserID));

		$stmt->bindParam(1, $this->receiverUserID);
		$stmt->execute();

		return $stmt;
	}
}

?>
