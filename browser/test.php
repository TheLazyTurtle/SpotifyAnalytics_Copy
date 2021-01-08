<?php
require "connect.php";
$connection = getConnection();

mysqli_query($connection, "INSERT INTO tokens (accessToken, refreshToken) VALUES ($accessToken, $refreshToken);")
mysqli_close($connection);