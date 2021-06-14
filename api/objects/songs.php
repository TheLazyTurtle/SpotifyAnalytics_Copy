<?php
require '../config/check_cookie.php';
require_once '/var/www/html/vendor/autoload.php';

class Song {
    // Db connection and table
    private $conn;

    // Object properties
    public $id;
    public $name;
    public $length;
    public $url;
    public $img;
    public $dateAdded;
    public $addedBy;
    public $preview;

    public function __construct($db) {
	$this->conn = $db;
	checkCookie();
    }

    // Get all songs from the db
    function read() {
	$collection = $this->conn->song;
	$cursor = $collection->find();

	return $cursor;
    }

    // This will only read one song from the database based on the id given
    function readOne() {
	$collection = $this->conn->song;
	$query = ['songID' => $this->id];

	$cursor = $collection->find($query);

	foreach ($cursor as $row) {
	    $this->id = $row["songID"];
	    $this->name = $row["name"];
	    $this->length = $row["length"];
	    $this->url = $row["url"];
	    $this->img = $row["img"];
	    $this->preview = $row["preview"];
	}
    }

    // Add a song to the db
    // TODO: Might have to make create batch where you can input an object
    // of artist and it will insert them one by one
    function create() {
	$query = "INSERT INTO song 
	    SET 
	    songID = :id, 
	    name = :name, 
	    length = :length, 
	    url = :url, 
	    img = :img, 
	    dateAdded = :dateAdded,
	    addedBy = :addedBy, 
	    preview = :preview";
	$stmt = $this->conn->prepare($query);

	// Clean data from specialchars
	$this->id = htmlspecialchars(strip_tags($this->id));
	$this->name = htmlspecialchars(strip_tags($this->name));
	$this->length = htmlspecialchars(strip_tags($this->length));
	$this->url = htmlspecialchars(strip_tags($this->url));
	$this->img = htmlspecialchars(strip_tags($this->img));
	$this->dateAdded = htmlspecialchars(strip_tags($this->dateAdded));
	$this->addedBy = htmlspecialchars(strip_tags($this->addedBy));
	$this->preview = htmlspecialchars(strip_tags($this->preview));

	if ($stmt->execute()) {
	    return true;
	}
	return false;
    }

    // This will search for a song by keyword
    // If there is no keyword it returns an empty array to prevent loading all songs
    function search($keywords) {
	$collection = $this->conn->song;

	// Clean the keywords
	$keywords = htmlspecialchars(strip_tags($keywords));

	if (strlen($keywords) > 0) {
	    $query = ["name" => new \MongoDB\BSON\Regex($keywords, 'i')];
	    $cursor = $collection->find($query);
	    return $cursor;
	}

	return array();
    }

    // This will return the songID from the song by searching by name and a artist name
    function searchByArtist($userID, $song, $artist) {
	$collection = $this->conn->song;

	// Clean input
	$song = htmlspecialchars(strip_tags($song));
	$artist = htmlspecialchars(strip_tags($artist));

	$query = [
	    'name' => $song,
	    'addedBy' => $userID,
	    'artists' => ['$elemMatch' => ['name' => $artist]]
	];

	$cursor = $collection->find($query);
	return $cursor;
    }

    // This will search all songs from a user
    function searchForUser($userID, $keyword, $limit) {
	$collection = $this->conn->song;

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$keyword = htmlspecialchars(strip_tags($keyword));

	if (strlen($keyword) > 0) {
	    $query = [
		"name" => new \MongoDB\BSON\Regex($keyword, 'i'),
		"addedBy" => $userID,
	    ];
	    $cursor = $collection->find($query)->limit($limit);
	    return $cursor;
	}

	return array();
    }

    // This will get all songs played by a user
    function allSongsPlayed($userID, $minPlayed, $maxPlayed, $minDate, $maxDate) {
	$collection = $this->conn->played;

	// Clean the input
	$userID = htmlspecialchars(strip_tags($userID));
	$minPlayed = htmlspecialchars(strip_tags($minPlayed));
	$maxPlayed = htmlspecialchars(strip_tags($maxPlayed));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$minDate = new MongoDB\BSON\UTCDateTime(strtotime($minDate) * 1000);
	$maxDate = new MongoDB\BSON\UTCDateTime(strtotime($maxDate) * 1000);

	$query = [
	    ['$match' => ['playedBy' => $userID]],
	    ['$group' => [
		'_id' => '$songID', 
		'count' => ['$sum' => 1], 
		'name' => ['$first' => '$name'],
		'date' => ['$first' => '$datePlayed']
	    ]],
	    ['$match' => [
		'count' => ['$gte' => (int)$minPlayed, '$lte' => (int)$maxPlayed],
		'date' => [
		    '$gte' => $minDate,
		    '$lte' => $maxDate
		]
	    ]],
	    ['$sort' => ['name' => 1]]
	];

	$cursor = $collection->aggregate($query);

	return $cursor;
    }

    // This will get the top songs of a user
    function topSongs($userID, $artist, $minDate, $maxDate, $amount) {
	$collection = $this->conn->played;

	// Clean the input
	$userID = htmlspecialchars(strip_tags($userID));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$artist = htmlspecialchars(strip_tags($artist));
	$amount = htmlspecialchars(strip_tags($amount));
	$minDate = new MongoDB\BSON\UTCDateTime(strtotime($minDate) * 1000);
	$maxDate = new MongoDB\BSON\UTCDateTime(strtotime($maxDate) * 1000);

	$query = [
	    ['$match' => ['playedBy' => $userID]],
	    ['$group' => [
		"_id" => '$songID',
		'count' => ['$sum' => 1],
		'date' => ['$first' => '$datePlayed'],
		'artist' => ['$last' => '$artists'],
		'name' => ['$first' => '$name']
		]],
		['$match' => [
		    'artist' => new \MongoDB\BSON\Regex($artist, 'i'),
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

    function topSongSearch($userID, $keyword, $amount) {
	$collection = $this->conn->played;

	// Clean the input
	$userID = htmlspecialchars(strip_tags($userID));
	$amount = htmlspecialchars(strip_tags($amount));

	$query = [
	    ['$match' => ['playedBy' => $userID]],
	    ['$group' => [
		"_id" => '$songID',
		'count' => ['$sum' => 1],
		'name' => ['$first' => '$name'],
		'artist' => ['$first' => ['$first' => '$artists']]
		]],
		['$match' => [
		    'name' => new \MongoDB\BSON\Regex($keyword, 'i'),
		]],
	    ['$sort' => ['count' => -1]],
	    ['$limit' => (int)$amount]
	];

	$cursor = $collection->aggregate($query);

	return $cursor;
    }

    // Gets the played per day graph
    function playedPerDay($userID, $song, $minDate, $maxDate) {
	// TODO: Intergrate a timewindows feature where if the selected timeframe is day or yesterday (and mabye week)
	// also group by hour so you don't just have a dot but can see the difference between hours of the day
	$collection = $this->conn->played;

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$song = htmlspecialchars(strip_tags($song));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$minDate = new MongoDB\BSON\UTCDateTime(strtotime($minDate) * 1000);
	$maxDate = new MongoDB\BSON\UTCDateTime(strtotime($maxDate) * 1000);

	$query = [
	    ['$match'=>
		['songID' => new \MongoDB\BSON\Regex($song, 'i')]
	    ],
	    ['$group' =>[
		'_id' => [
		    'month' => ['$month' => '$datePlayed'],
		    'day' => ['$dayOfMonth' => '$datePlayed'],
		    'year' => ['$year' => '$datePlayed']
		],
		'count' => ['$sum' => 1],
		'date' => ['$first' => '$datePlayed'],
		'name' => ['$first' => '$name']
	    ]],
	    ['$match' => [
		'date' => [
		    '$gte' => $minDate,
		    '$lte' => $maxDate
		]
	    ]],
	    ['$sort' => ['date' => -1]]
	];

	$cursor = $collection->aggregate($query);
	return $cursor;
    }

    // Gets the amount of songs you have listend to in the time frame specified
    function amountOfSongs($userID, $minDate, $maxDate) {
	//$query = "SELECT count(p.songID) AS times 
	    //FROM played p 
	    //INNER JOIN song s ON p.songID = s.songID 
	    //WHERE p.playedBy LIKE ? AND s.addedBy LIKE ? 
	    //AND p.datePlayed BETWEEN ? AND ?";
	$collection = $this->conn->played;	

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$minDate = new MongoDB\BSON\UTCDateTime(strtotime($minDate) * 1000);
	$maxDate = new MongoDB\BSON\UTCDateTime(strtotime($maxDate) * 1000);

	$query = [
	    ['$match' => [
		'datePlayed' => [
		    '$gte' => $minDate, 
		    '$lte' => $maxDate
		]
	    ]],
	    ['$group'=> [
		'_id' => 'datePlayed',
		'times' => ['$sum' => 1]
	    ]]
	];

	$cursor = $collection->aggregate($query);
	return $cursor;
    }

    // Gets the time you have listend to music in the given time frame
    // Might have to move it to another place but not sure where
    function timeListend($userID, $minDate, $maxDate) {
	$collection = $this->conn->played;

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$minDate = new MongoDB\BSON\UTCDateTime(strtotime($minDate) * 1000);
	$maxDate = new MongoDB\BSON\UTCDateTime(strtotime($maxDate) * 1000);

	$query = [
	    ['$match' => [
		'playedBy' => $userID,
		'datePlayed' => [
		    '$gte' => $minDate,
		    '$lte' => $maxDate
		]
	    ]],
	    ['$group' => [
		'_id' => "time Played",
		'time' => ['$sum' => '$length'],
	    ]],
	];

	$cursor = $collection->aggregate($query);
	return $cursor;
    }

    // Get the new songs for the given timeframe
    function newSongs($userID, $minDate, $maxDate) {
	//$query = "SELECT count(*) AS new, img FROM song
	    //WHERE addedBy LIKE ?
	    //AND dateAdded BETWEEN ? AND ?";
	$collection = $this->conn->song;

	// Clean input
	$userID = htmlspecialchars(strip_tags($userID));
	$minDate = htmlspecialchars(strip_tags($minDate));
	$maxDate = htmlspecialchars(strip_tags($maxDate));
	$minDate = new MongoDB\BSON\UTCDateTime(strtotime($minDate) * 1000);
	$maxDate = new MongoDB\BSON\UTCDateTime(strtotime($maxDate) * 1000);

	$query = [
	    ['$match' => [
		'addedBy' => $userID,
		'dateAdded' => [
		    '$gte' => $minDate,
		    '$lte' => $maxDate
		]
	    ]],
	    ['$group' => [
		'_id' => 'new songs',
		'items' => ['$sum' => 1],
		'img' => ['$first' => '$img']
	    ]]
	];

	$cursor = $collection->aggregate($query);
	return $cursor;
    }
}
