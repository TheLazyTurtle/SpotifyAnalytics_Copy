<?php
    require "../header.php";
    $connection = getConnection();
    $spID = $_SESSION["spID"];

    // This gets all the artists that appear in 2 or more songs that you have listend to
    $getArtist = "
	SELECT name, img, artistID 
	FROM artist 
	WHERE addedBy = ? AND artistID IN 
	    (SELECT artistID 
	    FROM SongFromArtist 
	    WHERE addedBy = ? 
	    GROUP BY artistID 
	    HAVING count(*) > 2)";

    $stmt = mysqli_prepare($connection, $getArtist);
    mysqli_stmt_bind_param($stmt, "ss", $spID, $spID);
    $getArtistRes = mysqli_stmt_execute($stmt);
    $getArtistRes = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);
?>

<script src="../jquery.min.js"></script>
<script src="../js/autoArtist.js"></script>

<div class="autoArtist">

    <table>
	<tr>
	<th>Image</th>
	<th>Name</th>
	<th>Add</th>
	<th>Remove</th>
	<th>Currently</th>
	<tr>

	<?php
	// Goes throug all the artists that were fetched above and prints them in a organisted way
	while ($row = mysqli_fetch_assoc($getArtistRes)) {
	    $name = $row["name"];
	    $artistID = $row["artistID"];
	    $img = $row["img"];

	    // This will get check if the fetched artist is already in autoArtist table because than it will have a checkmark behind its name
	    $getIsAutoArtist = "SELECT count(*) FROM autoArtist WHERE addedBy = ? AND artistID = ?";
	    $stmt = mysqli_prepare($connection, $getIsAutoArtist);
	    mysqli_stmt_bind_param($stmt, "ss", $spID, $artistID);
	    $isAutoArtist = mysqli_stmt_execute($stmt);
	    $isAutoArtist = mysqli_stmt_get_result($stmt);
	    $isAutoArtist = mysqli_fetch_row($isAutoArtist)[0];

	    echo "<tr>";

	    echo "<td><img src='$img' width='200px'></img></td>";
	    echo "<td>$name</td>";
	    echo "<td><button class='add-btn' value='$artistID'>add</button></td>";
	    echo "<td><button class='remove-btn' value='$artistID'>remove</button></td>";

	    // If a artist is already in the auto artist table than show a checkmark otherwise show a cross
	    if ($isAutoArtist == 1) {
		echo "<td><img src='https://openclipart.org/image/2400px/svg_to_png/202732/checkmark.png' class='auto-artist-$artistID' style='width: 50px;'></img></td>";
	    } else {
		echo "<td><img src='http://www.pngall.com/wp-content/uploads/2016/04/Red-Cross-Mark-PNG.png' class='auto-artist-$artistID' style='width: 50px;'></img></td>";
	    }

	    echo "</tr>";

	}

	mysqli_stmt_close($stmt);
	mysqli_close($connection);
	mysqli_free_result($isAutoArtist);
	mysqli_free_result($getArtistRes);

	?>

    </table>
</div>

</html>
