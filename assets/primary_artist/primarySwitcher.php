<?php
session_start();
require "../connect.php";

$spID = $_SESSION["spID"];

if (isset($_GET["add"])) {
    $connection = getConnection();

    $value = $_GET["add"];
    $value = str_replace("-", " ", $value);
    $values = explode(" ", $value);
    $songID = $values[0];
    $artistID = $values[1];

    $query = "
    UPDATE SongFromArtist 
    SET primaryArtist = 1 
    WHERE songID = ? 
    AND artistID = ? AND addedBy = ?";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sss", $songID, $artistID, $spID);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}

if (isset($_GET["remove"])) {
    $connection = getConnection();

    $value = $_GET["remove"];
    $value = str_replace("-", " ", $value);
    $values = explode(" ", $value);
    $songID = $values[0];
    $artistID = $values[1];
    print($value);

    $query = "
    UPDATE SongFromArtist 
    SET primaryArtist = 0 
    WHERE songID = ? 
    AND artistID = ? AND addedBy = ?";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sss", $songID, $artistID, $spID);
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>
