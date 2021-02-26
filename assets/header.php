<?php
require "connect.php";
session_start();

// Add the token and userID to the database and set the cookies
function addToken($userID, $token, $validTo) {
    $connection = getConnection();
    $query = "INSERT INTO loginToken (userID, token, validTo) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sss", $userID, $token, $validTo);
    mysqli_stmt_execute($stmt);

    // Set the tokens
    setcookie("userID", $userID, time() + (86400 * 30), "/");
    setcookie("token", $token, time() + (86400 * 30), "/");

    mysqli_close($connection);
    mysqli_stmt_close($stmt);
}

?>

<html>
<head>

<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/info_slider/slider.css">

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="/assets/js/loader.js"></script>
<!-- <script src="./assets/js/primaryArtistSwitch.js"></script> -->

</head>


<div class="header-container">
    <div class="header">

<?php 
if (isset($_SESSION["loggedIn"])) {

    if ($_SESSION["loggedIn"]) {
	echo '<a class="btn btn-right" href="/logout.php">Uitloggen</a>';

	if ($_SERVER["REQUEST_URI"] != "/assets/primary_artist/selector.php") {
	    echo '<a class="btn btn-left" href="/index.php">Home</a>';
	    echo '<a class="btn btn-left" href="/assets/primary_artist/selector.php">Primary artist</a>';
	} else {
	    echo '<a class="btn btn-left" href="/index.php">Home</a>';
	    echo '<a class="btn btn-left" href="./autoArtist.php">Auto artist</a>';
	}

    } else {
	echo '<a class="btn btn-left" href="/login.php">Inloggen</a>';
    }
}

?>
    </div>
</div>

<body>
