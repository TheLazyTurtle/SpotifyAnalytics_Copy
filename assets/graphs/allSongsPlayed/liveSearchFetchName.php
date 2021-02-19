<?php
session_start();

require "../../connect.php";

$connection = getConnection();

if(isset($_REQUEST["term"])) {
    $spID = $_SESSION["spID"];
    $term = '%'.$_REQUEST["term"].'%';

    $sql = "SELECT * FROM artist WHERE name LIKE ? AND addedBy LIKE ? ORDER BY name ASC LIMIT 10";

    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $term, $spID);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);


    if (mysqli_num_rows($res) > 0) {
	while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
	    echo "<p>" . $row["name"] . "</p>";
	}
    }
    mysqli_free_result($res);
    mysqli_close($connection);
    mysqli_stmt_close($stmt);
}
?>
