<?php
require "../header.php";
$spID = $_SESSION["spID"];
$connection = getConnection();
$song = "%";
$artist = "%";

if (isset($_GET["song"])) {
    $song = $_GET["song"]; 
    $song = "%".$song."%";
    $displaySong = str_replace("%", "", $song);
}

if (isset($_GET["artist"])) {
    $artist = $_GET["artist"];
    $artist = "%".$artist."%";
    $displayArtist = str_replace("%", "", $artist);
}

// Get all the artist from all the songs that have more than one artist
$query = "
    SELECT sfa.primaryArtist, s.songID, a.artistID, s.img, a.name AS aName, s.name AS sName, preview 
    FROM song s 
    INNER JOIN SongFromArtist sfa ON s.songID = sfa.songID 
    RIGHT JOIN artist a ON sfa.artistID = a.artistID 
    WHERE sfa.addedBy = ? AND a.addedBy = ? AND s.addedBy = ? AND 
    s.songID IN (
	SELECT songID 
	FROM SongFromArtist 
	WHERE addedBy like ? 
	GROUP BY songID 
	HAVING count(*) > 1)
    AND s.name LIKE ? AND a.name LIKE ?
    GROUP BY a.name, s.name 
    ORDER BY s.dateAdded DESC, sName, aName";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ssssss", $spID, $spID, $spID, $spID, $song, $artist);
$res = mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);

?>
<script src="/assets/js/addRemovePrimaryArtist.js"></script>

<div class="test">
    <form method="GET" action="#">
	<input type="text" name="song" placeholder="song" value="<?php if (isset($displaySong)) {echo $displaySong;} ?>" class="filter">
	<input type="text" name="artist" placeholder="artist" value="<?php if (isset($displayArtist)){echo $displayArtist;} ?>" class="filter">
	<input type="submit">
    </form>

    <table>
    <tr>
    <th>Song Img</th>
    <th>Song name</th>
    <th>Artist name (in songs)</th>
    <th>Preview</th>
    <th>Add</th>
    <th>Remove</th>
    <th>Currently</th>
    </tr>

    <?php
    while ($row = mysqli_fetch_assoc($res)) {
	echo "<tr>";
	$songImg = $row["img"];
	$songName = $row["sName"];
	$artistName = $row["aName"];
	$artistID = $row["artistID"];
	$preview = $row["preview"];
	$value = $row["songID"] . "-" . $row["artistID"];
	$primaryArtist = $row["primaryArtist"];

	// Gets how often many songs the person has with this artist 
	$getAmount = "
	SELECT count(*) 
	FROM SongFromArtist 
	WHERE addedBy = ? AND artistID = ?
	GROUP BY artistID";

	$stmt = mysqli_prepare($connection, $getAmount);
	mysqli_stmt_bind_param($stmt, "ss", $spID, $artistID);
	$getAmountRes = mysqli_stmt_execute($stmt);
	$getAmountRes = mysqli_stmt_get_result($stmt);

	$amount = mysqli_fetch_row($getAmountRes)[0];

	echo "<td><img src=$songImg style='width: 200px; heigth: 200px'></img></td>";
	echo "<td>$songName</td>";
	echo "<td>$artistName ($amount)</td>";
	echo "<td><audio controls><source src=$preview type='audio/mpeg'></audio></td>";
	echo "<td><button class='primary-add-btn' value='$value'>Make primary</button></td>";
	echo "<td><button class='primary-remove-btn' value='$value'>Remove primary</button></td>";

	// If the artist is primary show the checkmark otherwise show cross
	if ($primaryArtist == 1) {
	    echo "<td><img src='https://openclipart.org/image/2400px/svg_to_png/202732/checkmark.png' class='primary-check-$value' style='width: 50px;'></img></td>";
	} else {
	    echo "<td><img src='http://www.pngall.com/wp-content/uploads/2016/04/Red-Cross-Mark-PNG.png' class='primary-check-$value' style='width: 50px;'></img></td>";
	}

	echo "</tr>";
    }

    mysqli_close($connection);
    mysqli_stmt_close($stmt);
    mysqli_free_result($res);
    mysqli_free_result($getAmountRes);
    ?>

    </table>
</div>
