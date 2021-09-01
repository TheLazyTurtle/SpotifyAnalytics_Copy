<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../config/database.php';
require '../objects/album.php';
require '../objects/songs.php';
require '../objects/artists.php';

// Make db and album object
$database = new Database();
$db = $database->getConnection();
$album = new Album($db);
$song = new Song($db);
$artist = new Artist($db);

$name = isset($_POST["name"]) ? $_POST["name"] : Null;
$albumID = isset($_POST["albumID"]) ? $_POST["albumID"] : Null;

if ($name != Null) {
	$album->name = $name;
} else if ($albumID != Null) {
	$album->id = $albumID;
} else {
	http_response_code(400);

	echo json_encode(array("message" => "No data supplied"));
	die();
}

// Query album
$stmt = $album->readOne();
$num = $stmt->rowcount();

if ($num > 0) {
	// Make result array
	$albumArr = array();
	$albumArr["records"] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		$song->albumID = $albumID;

		// Get the primary artist of the album
		$artist->id = $primaryArtistID;
		$artist->readOne();

		$albumItem = array(
			"albumID" => $albumID,
			"name" => $name,
			"url" => $url,
			"img" => $img,
			"primaryArtist" => array(
				"artistID" => $artist->id,
				"name" => $artist->name,
				"url" => $artist->url,
				"img" => $artist->img
			),
			"songs" => $song->getAlbumSongs($artist)
		);
		array_push($albumArr["records"], $albumItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($albumArr);
} else {
	// Set response to not found
	http_response_code(404);

	echo json_encode(array("message" => "No albums found"));
}
