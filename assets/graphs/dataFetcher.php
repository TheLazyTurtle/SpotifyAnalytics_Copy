<?php
// This gets all the songs played. 
// TODO: Add filter for artist, date, min played
function allSongs() {
    global $dataPoints, $spID, $minPlayedAllSongs, $maxPlayedAllSongs, $minDatePlayedAllSongs, $maxDatePlayedAllSongs, $artistPlayedAllSongs;

    //$query = "SELECT s.name AS name, count(p.songID) AS times FROM played p INNER JOIN song s ON p.songID = s.songID INNER JOIN SongFromArtist sfa ON sfa.songID = p.songID RIGHT JOIN artist a ON sfa.artistID = a.artistID WHERE p.playedBy = '$spID' AND datePlayed > date('$minDatePlayedAllSongs') AND datePlayed < date('$maxDatePlayedAllSongs') AND a.name LIKE '$artistPlayedAllSongs' GROUP BY s.songID HAVING times > '$minPlayedAllSongs' AND times < '$maxPlayedAllSongs' ORDER BY name ASC";

    $query = "SELECT s.name AS name, count(p.songID) AS times FROM played p INNER JOIN song s ON p.songID = s.songID WHERE p.songID IN (SELECT songID FROM song WHERE songID IN (SELECT songID FROM SongFromArtist WHERE artistID IN (SELECT artistID FROM artist WHERE name LIKE '%$artistPlayedAllSongs%'))) AND playedBy LIKE '$spID' AND datePlayed BETWEEN DATE('$minDatePlayedAllSongs') AND DATE('$maxDatePlayedAllSongs') GROUP BY s.songID HAVING times BETWEEN '$minPlayedAllSongs' AND '$maxPlayedAllSongs' ORDER BY name ASC";

    // Before time 0.021 Sec
    // After time
    $connection = getConnection();

    $res = mysqli_query($connection, $query);
    $dataPoints = array();

    // Turns all the songs into datapoints
    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["name"], "y"=>$row["times"]];
	array_push($dataPoints, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

// Gets the top 10 songs
// TODO: Add filter to define top how many, date
function topSongs() {
    global $topSongs, $spID, $artistTopSongs, $minDateTopSongs, $maxDateTopSongs, $amountTopSongs;

    $connection = getConnection();
    $query = "SELECT count(p.songID) AS times, s.name AS songName FROM played p INNER JOIN song s ON p.songID = s.songID WHERE p.songID IN (SELECT songID FROM song WHERE songID IN (SELECT songID FROM SongFromArtist WHERE artistID IN (SELECT artistID FROM artist WHERE name LIKE '%$artistTopSongs%'))) AND datePlayed BETWEEN DATE('$minDateTopSongs') AND DATE('$maxDateTopSongs') AND p.playedBy = '$spID' GROUP BY songName ORDER BY times DESC LIMIT $amountTopSongs";
    // Before time 0.021 Sec
    // After time

    $res = mysqli_query($connection, $query);
    $topSongs = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["label"=>$row["songName"], "y"=>$row["times"]];
	array_push($topSongs, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

// Gets the top 10 artist
// TODO: Add filter to define top how many, date
// This query takes 8!!!!! seconds to load. This will only get worse!! fix this. Maybe with index
function topArtists() {
    global $topArtists, $spID, $amountTopArtist, $minDateTopArtist, $maxDateTopArtist;

    $connection = getConnection();
    $query = "SELECT count(p.songID) AS times, a.name AS artistName, a.artistID FROM played p INNER JOIN SongFromArtist sfa ON p.songID = sfa.songID RIGHT JOIN artist a ON sfa.artistID = a.artistID WHERE p.playedBy = '$spID' AND p.datePlayed BETWEEN DATE('$minDateTopArtist') AND DATE('$maxDateTopArtist') GROUP BY a.artistID ORDER BY times DESC LIMIT $amountTopArtist";

    // Before time 8.228 Sec
    // After time

    $res = mysqli_query($connection, $query);
    $topArtists = array();

    while ($row = mysqli_fetch_assoc($res)){
	$data = ["label"=>$row["artistName"], "y"=>$row["times"]];
	array_push($topArtists, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

// Shows how often you have listend to a song per day
// TODO: Make filter for songName, date
//   ... Add function for multiple songs to compare??
function playedPerDay() {
    global $playedPerDay, $spID, $playedPerDaySong, $minDatePlayedPerDay, $maxDatePlayedPerDay;

    $connection = getConnection();
    $query = "SELECT count(*) AS times, unix_timestamp(p.datePlayed) * 1000 AS date, s.name FROM played p INNER JOIN song s ON p.songID = s.songID WHERE playedBy = '$spID' AND s.name LIKE '$playedPerDaySong' AND p.datePlayed BETWEEN '$minDatePlayedPerDay' AND '$maxDatePlayedPerDay' GROUP BY DAY(p.datePlayed), p.songID ORDER BY date DESC";
    // Before time
    // After time

    $res = mysqli_query($connection, $query);
    $playedPerDay = array();

    while ($row = mysqli_fetch_assoc($res)) {
	$data = ["x"=>$row["date"], "y"=>$row["times"]];
	array_push($playedPerDay, $data);
    }
    mysqli_free_result($res);
    mysqli_close($connection);
}

?>
