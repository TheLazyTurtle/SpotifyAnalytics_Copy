<?php
require "connect.php";
session_start();

?>

<html>
<head>

<link rel="stylesheet" href="./assets/css/style.css">
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>


<div class="header-container">
    <div class="header">

<?php 
if (isset($_SESSION["loggedIn"])) {

    if ($_SESSION["loggedIn"]) {
	echo '<a class="btn btn-right" href="./logout.php">Uitloggen</a>';
    } else {
	echo '<a class="btn btn-left" href="./login.php">Inloggen</a>';
    }
}

?>
    </div>
</div>

<body>
