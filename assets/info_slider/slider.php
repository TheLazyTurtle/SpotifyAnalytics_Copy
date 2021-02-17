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
    global $connection;

    $query = "SELECT img FROM song WHERE name LIKE '%$song%'";
    $res = mysqli_query($connection, $query);

    $img = mysqli_fetch_row($res);
    return "<img class='gallery-img' src='".$img[0]. "'>";
}

function getArtistImg($artist) {
    global $connection;

    $query = "SELECT img FROM artist WHERE name LIKE '%$artist%'";
    $res = mysqli_query($connection, $query);

    $img = mysqli_fetch_row($res);
    return "<img class='gallery-img' src='". $img[0]. "'>";
}

function amountSongs($date) {
    global $connection, $spID;

    $querySongsToday = "
    SELECT count(p.songID), AS times FROM played p 
    INNER JOIN song s ON p.songID = s.songID
    WHERE p.playedBy = '$spID' AND s.addedBy = '$spID'
    AND p.datePlayed >= '$date'
    ";

    $resSongsToday = mysqli_query($connection, $querySongsToday);
    $rowSongsToday = mysqli_fetch_array($resSongsToday, MYSQLI_ASSOC);

    print("songs played: " . $rowSongsToday["times"]);
}

function timeListend($date) {
    global $connection, $spID;

    $queryTotalTimeSongsToday = "
    SELECT SUM(s.length) AS totalTime FROM played p
    INNER JOIN song s ON p.songID = s.songID
    WHERE p.playedBy = '$spID' AND s.addedBy = '$spID'
    AND p.datePlayed >= '$date';
    ";

    $resTotalTimeSongsToday = mysqli_query($connection, $queryTotalTimeSongsToday);
    $rowTotalTimeSongsToday = mysqli_fetch_array($resTotalTimeSongsToday, MYSQLI_ASSOC);

    $time = gmdate("H:i:s", ($rowTotalTimeSongsToday["totalTime"]/1000));
    print("time played: " . $time);
}

function topSong($date) {
    global $connection, $spID;

    $query = "
    SELECT s.name, count(p.songID) AS times FROM played p 
    INNER JOIN song s on p.songID = s.songID
    WHERE p.playedBy = '$spID' AND s.addedBy = '$spID'
    AND p.datePlayed >= '$date'
    GROUP BY p.songID
    ORDER BY times DESC
    LIMIT 1;
    ";

    $res = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    return $row;
}

function topArtist($date) {
    global $connection, $spID;
    
    $query = "
    SELECT a.name AS name, count(a.name) AS times FROM played p
    INNER JOIN song s ON p.songID = s.songID
    INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID
    RIGHT JOIN artist a ON sfa.artistID = a.artistID
    WHERE p.playedBy = '$spID' AND a.addedBy = '$spID' AND s.addedBy = '$spID'
    AND p.datePlayed >= '$date'
    GROUP BY a.name
    ORDER BY times DESC
    LIMIT 1;
    ";

    $res = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    return $row;
}

function amountNewSongs($date) {
    global $connection, $spID;

    $queryNewSongsToday = "
    SELECT count(*) AS new FROM song
    WHERE addedBy = '$spID'
    AND dateAdded >= '$date'
    ";

    $resNewSongsToday = mysqli_query($connection, $queryNewSongsToday);
    $rowNewSongsToday = mysqli_fetch_array($resNewSongsToday, MYSQLI_ASSOC);

    print("New songs: " . $rowNewSongsToday["new"]);
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
