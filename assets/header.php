<?php
require "connect.php";
session_start();

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
