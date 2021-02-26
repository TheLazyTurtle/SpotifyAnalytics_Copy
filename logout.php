<?php
session_start();
require "./assets/connect.php";
$connection = getConnection();
$userID = $_COOKIE["userID"];
$token = $_COOKIE["token"];

// Deletes the cookie from the database so it won't log you in after you have logged out
$query = "DELETE FROM loginToken WHERE userID = ? AND token = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ss", $userID, $token);
mysqli_stmt_execute($stmt);

// Unset cookies so it won't try to check if they exists
setcookie("userID", "", 1, "/");
setcookie("token", "", 1, "/");

session_destroy();
header("Location: /login.php");
