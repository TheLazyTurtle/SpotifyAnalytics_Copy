<?php
// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/database.php';
require '../objects/album.php';
require '../objects/songs.php';
require '../objects/artists.php';

// Make db connection and make album object
$database = new Database();
$db = $database->getConnection();
$album = new Album($db);
$song = new Song($db);
$artist = new Artist($db);

// Get keywords
$name = isset($_POST["name"]) ? $_POST["name"] : Null;
$primaryArtistID = isset($_POST["artistID"]) ? $_POST["artistID"] : Null;

if ($name != Null) {
	$album->name = $name;
} else if ($primaryArtistID != Null) {
	$album->primaryArtistID = $primaryArtistID;
} else {
	die();
}

// Query the albums
$stmt = $album->search();
$num = $stmt->rowcount();

if ($num > 0) {
	// Make result array
	$albumArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		$song->albumID = $albumID;

		$albumItem = array(
			"albumID" => $albumID,
			"name" => $name,
			"url" => $url,
			"img" => $img,
			"releaseDate" => $releaseDate,
			"albumArtists" => $artist->getAlbumArtists($albumID),
			"songs" => $song->getAlbumSongs($artist)
		);
		array_push($albumArr, $albumItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($albumArr);
} else {
	// Set response to not found
	http_response_code(404);

	echo json_encode(array("message" => "No albums found"));
}
