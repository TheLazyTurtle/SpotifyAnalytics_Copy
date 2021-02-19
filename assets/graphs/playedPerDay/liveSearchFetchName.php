<?php
session_start();

require "../../connect.php";

$connection = getConnection();

if (isset($_REQUEST["term"])) {
    $spID = $_SESSION["spID"];
    $term = "%" . $_REQUEST["term"] . "%";

    $sql = "SELECT * FROM song WHERE name LIKE ? AND addedBy = ? ORDER BY name ASC LIMIT 10";

    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $term, $spID);
    $result = mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	    echo "<p>" . $row["name"] . "</p>";
	}
    }
    mysqli_free_result($result);
    mysqli_close($connection);
}

?>
