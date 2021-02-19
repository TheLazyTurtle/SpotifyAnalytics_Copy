<?php
//TODO:
// What do I want
// * Amount of times song today (not 24H)
// * Amount of times song this week and/or month
// * Amount of time listend for today and year and week and/or month
// * Top song this week and/or month
// * Top artist this week and /or month
// * (maybe) Top artist this week and/or month
// * amount of new songs this week and/or month

session_start();
require "../connect.php";

$spID = $_SESSION["spID"];
$connection = getConnection();
$today = date('Y-m-d');
$week = strtotime("previous monday");
$week = strftime("%Y-%m-%d", $week);

function getSongImg($song) {
    $connection = getConnection();

    $query = "SELECT img FROM song WHERE name LIKE ?";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $song);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);


    try {
	$img = mysqli_fetch_row($res);

	mysqli_close($connection);
	mysqli_free_result($res);
	mysqli_stmt_close($stmt);

	return "<img class='gallery-img' src='".$img[0]. "'>";
    } catch (Exception $e) {
	return '<img src="http://fakeimg.pl/300/?text=song">';
    }
}

function getArtistImg($artist) {
    $connection = getConnection();

    $query = "SELECT img FROM artist WHERE name LIKE ?";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $artist);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    try {
	$img = mysqli_fetch_row($res);

	mysqli_close($connection);
	mysqli_free_result($res);
	mysqli_stmt_close($stmt);

	return "<img class='gallery-img' src='". $img[0]. "'>";
    } catch(Exception $e) {
	return '<img src="http://fakeimg.pl/300/?text=artist">';
    }
}

function amountSongs($date) {
    global $spID;
    $connection = getConnection();

    $query = "
    SELECT count(p.songID), AS times FROM played p 
    INNER JOIN song s ON p.songID = s.songID
    WHERE p.playedBy = ? AND s.addedBy = ? 
    AND p.datePlayed >= ?
    ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sss", $spID, $spID, $date);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    print("songs played: " . $row["times"]);

    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);
}

function timeListend($date) {
    global $spID;
    $connection = getConnection();

    $query = "
    SELECT SUM(s.length) AS totalTime FROM played p
    INNER JOIN song s ON p.songID = s.songID
    WHERE p.playedBy = ? AND s.addedBy = ? 
    AND p.datePlayed >= ?;
    ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sss", $spID, $spID, $date);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    $time = gmdate("H:i:s", ($row["totalTime"]/1000));
    print("time played: " . $time);

    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);
}

function topSong($date) {
    global $spID;
    $connection = getConnection();

    $query = "
    SELECT s.name, count(p.songID) AS times FROM played p 
    INNER JOIN song s on p.songID = s.songID
    WHERE p.playedBy = ? AND s.addedBy = ? 
    AND p.datePlayed >= ? 
    GROUP BY p.songID
    ORDER BY times DESC
    LIMIT 1;
    ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sss", $spID, $spID, $date);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    mysqli_free_result($res);

    return $row;
}

function topArtist($date) {
    global $spID;
    $connection = getConnection();
    
    $query = "
    SELECT a.name AS name, count(a.name) AS times FROM played p
    INNER JOIN song s ON p.songID = s.songID
    INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID
    RIGHT JOIN artist a ON sfa.artistID = a.artistID
    WHERE p.playedBy = ? AND a.addedBy = ? AND s.addedBy = ? 
    AND p.datePlayed >= ? 
    GROUP BY a.name
    ORDER BY times DESC
    LIMIT 1;
    ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $spID, $spID, $spID, $date);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);

    return $row;
}

function amountNewSongs($date) {
    global $spID;
    $connection = getConnection();

    $query = "
    SELECT count(*) AS new FROM song
    WHERE addedBy = '$spID'
    AND dateAdded >= '$date'
    ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $spID, $date);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    print("New songs: " . $row["new"]);

    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);
}
?>

<link rel="stylesheet" href="./slider.css">

<div class="gallery">
    <div class="gallery-container">
	<div class="gallery-item">
	    <h3 class="floatOver">Top song today: 
		<?php 
		$topSong = topSong($today);
		echo $topSong["name"] . " - " . $topSong["times"];
		?>
	    </h3>
	    <?php echo getSongImg(topSong($today)["name"]); ?>
	</div>

	<div class="gallery-item">
	    <h3 class="floatOver">Top artist today:
		<?php
		    $topArtist = topArtist($today);
		    echo $topArtist["name"] . " - " . $topArtist["times"];
		?>
	    </h3>
	    <?php echo getArtistImg(topArtist($today)["name"]) ?>
	</div>

	<div class="gallery-item">
	    <img src="http://fakeimg.pl/300/?text=3">
	</div>

	<div class="gallery-item">
	    <img src="http://fakeimg.pl/300/?text=4">
	</div>

	<div class="gallery-item">
	    <img src="http://fakeimg.pl/300/?text=5">
	</div>
    </div>
    <div class="gallery-controls"></div>
  </div>

  <script src="slider.js"></script>
