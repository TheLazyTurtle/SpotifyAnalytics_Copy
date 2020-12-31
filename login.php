<?php
require "header.php";

$error;

if (isset($_POST["submit"])) {
	$name = $_POST["name"];
	$pass = $_POST["pass"];

	if (empty($name) || empty($pass)) {
		$error = "Niet alle velden zijn ingevuld";
	} else {
		login($name, $pass);
	}
}

function login($name, $pass) {
	$connection = getConnection();

	$pass = md5($pass);

	$query = mysqli_query($connection, "SELECT * FROM users WHERE name = '$name' AND pass = '$pass'");
	$res = mysqli_fetch_assoc($query);
	if (mysqli_num_rows($query) == 1) {
		$_SESSION["spID"] = $res["userID"];
		$_SESSION["loggedIn"] = True;
	} else {
		echo "Er is iets mis gegaan probeer het opnieuw";
	}
}

if (isset($_SESSION["loggedIn"])) {
	header("Location: index.php");
}

?>

<html>

<form action="#" method="POST">
	<label>Naam</label>
	<input type="text" name="name">
	<br>
	<label>Wachtwoord</label>
	<input type="password" name="pass">
	<br>
	<input type="submit" name="submit" value="Login">
	<br>
</form>

<h4><?php if (isset($error)) {echo $error;}?> </h4>

</html>