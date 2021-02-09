<?php
session_start();

require "../../connect.php";

$connection = getConnection();

if (isset($_REQUEST["term"])) {
    $spID = $_SESSION["spID"];
    $term = $_REQUEST["term"];

    $sql = "SELECT * FROM song WHERE name LIKE '%$term%' AND addedBy = '$spID' ORDER BY name ASC LIMIT 10";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	    echo "<p>" . $row["name"] . "</p>";
	}
    }
    mysqli_free_result($result);
    mysqli_close($connection);
}

?>
