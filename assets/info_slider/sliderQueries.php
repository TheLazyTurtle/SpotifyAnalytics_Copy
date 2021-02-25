<?php
session_start();
require "../connect.php";

$spID = $_SESSION["spID"];

$today = date('Y-m-d');
$date = $today;

if (isset($_GET["date"])) {
    switch ($_GET["date"]) {
	case "day":
	    $date = $today;
	    break;
	case "week":
	    $week = strtotime("previous monday");
	    $date = strftime("%Y-%m-%d", $week);
	    break;
	case "month":
	    $month = strtotime("first day of this month");
	    $date = strftime("%Y-%m-%d", $month);
	    break;
	case "year":
	    $year = strtotime("first day of january");
	    $date = strftime("%Y-%m-%d", $year);
	    break;
	case "allTime":
	    $date = "1970-01-01";
	    break;
	default:
	    $date = $today;
	    break;
    }
}

// This function will format the seconds into hours
function formatSeconds($ms) {
    $hours = 0;
    $sec = $ms / 1000; 

    if ($sec > 3600) {
	$hours = floor($sec / 3600);
    }
    $sec = $sec % 3600;

    return str_pad($hours, 2, '0', STR_PAD_LEFT) . gmdate(':i:s', $sec);
}

// This will get the amount of song you played in the timeframe you pass in
function amountSongs($date) {
    global $spID;
    $connection = getConnection();

    $query = "
    SELECT count(p.songID) AS times FROM played p 
    INNER JOIN song s ON p.songID = s.songID
    WHERE p.playedBy = ? AND s.addedBy = ? 
    AND p.datePlayed >= ?
    ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sss", $spID, $spID, $date);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $row["img"] = "https://daily-mix.scdn.co/covers/on_repeat/PZN_On_Repeat_DEFAULT-en.jpg";

    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);

    return $row;
}

// This will return the amount of time you listend to music since the passed in time
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
    $row["totalTime"] = formatSeconds($row["totalTime"]);
    $row["img"] = "https://i.pinimg.com/736x/f9/4c/95/f94c9574933ce9404f323fb58f5e7f5c.jpg";

    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);

    return $row;
}

// This will return your top song since the time passed in
function topSong($date) {
    global $spID;
    $connection = getConnection();

    $query = "
    SELECT s.songID, s.img, s.name, count(p.songID) AS times FROM played p 
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

    if (empty($row)) {
	$row["img"] = "http://fakeimg.pl/300/?text=no song";
	$row["name"] = " ";
	$row["times"] = " ";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    mysqli_free_result($res);

    return $row;
}

// This returns the top artist since the passed in time
function topArtist($date) {
    global $spID;
    $connection = getConnection();
    
    $query = "
    SELECT a.artistID, a.img, a.name, count(a.name) AS times FROM played p
    INNER JOIN song s ON p.songID = s.songID
    INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID
    RIGHT JOIN artist a ON sfa.artistID = a.artistID
    WHERE p.playedBy = ? AND a.addedBy = ? AND s.addedBy = ? 
    AND sfa.primaryArtist = 1
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

    if (empty($row["img"])) {
	$row["img"] = "http://fakeimg.pl/300/?text=no artist";
	$row["name"] = " ";
	$row["times"] = " ";
    }

    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);

    return $row;
}

// returns the amount of new songs you added since the passed in time
function amountNewSongs($date) {
    global $spID;
    $connection = getConnection();

    $query = "
    SELECT count(*) AS new, songID, img FROM song
    WHERE addedBy = ? 
    AND dateAdded >= ? 
    ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $spID, $date);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);

    if (empty($row["img"])) {
	$row["img"] = "http://fakeimg.pl/300/?text=no new song";
    } 

    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);

    return $row;
}

function dataToJSON($date) {
    $amountSongs = amountSongs($date);
    $timeListend = timeListend($date);
    $topSong = topSong($date);
    $topArtist = topArtist($date);
    $amountNewSong = amountNewSongs($date);
    if ($_GET["date"] == "allTime"){
	$timeFrame = "time frame";

    } else {
	$timeFrame = $_GET["date"];
    }

    return json_encode(
	["amountSongs" => $amountSongs, 
	"timeListend" => $timeListend, 
	"topSong" => $topSong, 
	"topArtist" => $topArtist, 
	"amountNewSong" => $amountNewSong,
	"time" => $timeFrame], 
	JSON_NUMERIC_CHECK);

}

print_r(dataToJSON($date));
?>

