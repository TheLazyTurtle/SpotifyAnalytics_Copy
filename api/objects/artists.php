<?php
require '../config/check_cookie.php';

class Artist {
    // DB connection
    private $conn;

    // Object properties
    public $id;
    public $name;
    public $url;
    public $dateAdded;
    public $addedBy;
    public $img;

    public function __construct($db) {
	$this->conn = $db;
	checkCookie();
    }

    // Get all the artists form the db
    function read() {
	$collection = $this->conn->artist;
	$cursor = $collection->find();

	return $cursor;
    }

    // This will only read one artist from the db
    function readOne() {
	$collection = $this->conn->artist;

	$query = ['artistID' => $this->id];
	$cursor = $collection->find($query);

	foreach ($cursor as $row) {
	    $this->id = $row["artistID"];
	    $this->name = $row["name"];
	    $this->url = $row["url"];
	    $this->dateAdded = $row["dateAdded"];
	    $this->addedBy = $row["addedBy"];
	    $this->img = $row["img"];
	}
    }

    // Add artist to db
    // Might have to make create batch where you can input an object
    // of artist and it will insert them one by one
    function create() {
	$query = "INSERT INTO artist SET artistID = :id, name = :name, url = :url, dateAdded = :dateAdded, img = :img";
	$stmt = $this->conn->prepare($query);

	// Clean data of specialchars
	$this->id = htmlspecialchars(strip_tags($this->id));
	$this->name = htmlspecialchars(strip_tags($this->name));
	$this->url = htmlspecialchars(strip_tags($this->url));
	$this->dateAdded = htmlspecialchars(strip_tags($this->dateAdded));
	$this->addedBy = htmlspecialchars(strip_tags($this->addedBy));
	$this->img = htmlspecialchars(strip_tags($this->img));

	if ($stmt->execute()) {
	    return true;
	}
	return false;
    }

    // Get artist based on keywords
    // Decide what is we want to return for artists
    // Will we only give the name etc or also all the songs they are part of
    function search($keywords) {
	$collection = $this->conn->artist;
	
	// Clean the keywords
	$keywords = htmlspecialchars(strip_tags($keywords));

	if (strlen($keywords) > 0) {
	    $query = ["name" => new \MongoDB\BSON\Regex($keywords, 'i')];
	    $cursor = $collection->find($query);
	    return $cursor;
	}

	return array();
    }

    // This wil search all artist from a user
    function serachForuser($userID, $keyword, $limit) {
	$collection = $this->conn->artist;

	// Clean input
	$keyword = htmlspecialchars(strip_tags($keyword));
	$userID = htmlspecialchars(strip_tags($userID));

	if (strlen($keyword) > 0) {
	    $query = [
		"name" => new \MongoDB\BSON\Regex($keyword, 'i'),
		"addedBy" => $userID
	    ];
	    $cursor = $collection->find($query, ['limit' => (int)$limit]);
	    return $cursor;
	}

	return array();
    }

    // Gets the top artist of a user
    function topArtist($userID, $minDate, $maxDate, $amount) {
	$collection = $this->conn->played;

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$amount = htmlspecialchars(strip_tags($amount));
	$minDate = new MongoDB\BSON\UTCDateTime(strtotime($minDate) * 1000);
	$maxDate = new MongoDB\BSON\UTCDateTime(strtotime($maxDate) * 1000);

	$query = [
	    ['$unwind' => '$artists'],
	    ['$match' => ['playedBy' => $userID]],
	    ['$group' => [
		'_id' => '$artists',
		'count' => ['$sum' => 1],
		'date' => ['$first' => '$datePlayed']
	    ]],
	    ['$match' => [
		'date' => [
		    '$gte' => $minDate,
		    '$lte' => $maxDate
		]
	    ]],
	    ['$sort' => ['count' => -1]],
	    ['$limit' => (int)$amount]
	];

	$cursor = $collection->aggregate($query);

	return $cursor;
    }

    // Gets the top artist of a user for search result
    function topArtistSearch($userID, $keyword, $amount) {
	$collection = $this->conn->played;

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$keyword = htmlspecialchars(strip_tags($keyword));
	$amount = htmlspecialchars(strip_tags($amount));

	$query = [
	    ['$unwind' => '$artists'],
	    ['$match' => ['playedBy' => $userID]],
	    ['$group' => [
		'_id' => '$artists',
		'count' => ['$sum' => 1],
		'date' => ['$first' => '$datePlayed']
	    ]],
	    ['$match' => [
		'_id' => new \MongoDB\BSON\Regex($keyword, 'i')
	    ]],
	    ['$sort' => ['count' => -1]],
	    ['$limit' => (int)$amount]
	];

	$cursor = $collection->aggregate($query);

	return $cursor;
    }
}
?>
