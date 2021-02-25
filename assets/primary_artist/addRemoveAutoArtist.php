<?php
session_start();
require "../connect.php";
$spID = $_SESSION["spID"];


if (isset($_GET["add"])) {
    $connection = getConnection();
    $val = $_GET["add"];

    $query = "INSERT INTO autoArtist (addedBy, artistID) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $spID, $val);
    mysqli_stmt_execute($stmt);

    mysqli_close($connection);
    mysqli_stmt_close($stmt);

}

if (isset($_GET["remove"])) {
    $connection = getConnection();
    $val = $_GET["remove"];

    $query = "DELETE FROM autoArtist WHERE addedBy = '$spID' AND artistID = '$val'";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $spID, $val);
    mysqli_stmt_execute($stmt);

    mysqli_close($connection);
    mysqli_stmt_close($stmt);
}
?>
