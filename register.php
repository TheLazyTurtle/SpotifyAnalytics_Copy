<?php
require "header.php";

$error;

// If the form is send than run this
if (isset($_POST["submit"])) {
	$name = $_POST["name"];
	$pass = $_POST["pass"];
	$passRep = $_POST["passRep"];
	// spID means spotifyID (the 10 number account id everyone has) 
	$spID = $_POST["spID"];

	if (empty($name) || empty($pass) || empty($passRep) || empty($spID)) {
		$error = "Niet alle velden zijn ingevuld";
	} elseif ($pass !== $passRep) {
		$error = "Wachtwoorden komen niet overeen";
	} else{
		registerAccount($spID, $name, $pass);
	}
}

function registerAccount($spID, $name, $pass) {
	$connection = getConnection();
	$pass = md5($pass);
	
	// Fix query to be the correct thing
	if (mysqli_query($connection, "INSERT INTO users (userID, name, pass) VALUES ($spID, $name, $pass)")) {
		$error = "Het is geluk";

	} else {
		$error = "SAFE ME FROM MY SUFFERING";
	}
	mysqli_close($connection);
}

?>

<html>

<form action="#" method="post">
	<label>Naam</label>	
	<input type="text" name="name" placeholder="naam">
	<br>
	<label>Spotify account ID</label>
	<input type="number" name="spID" placeholder="1234567890">
	<br>
	<label>Wachtwoord</label>
	<input type="password" name="pass">
	<br>
	<label>Herhaal wachtwoord</label>
	<input type="password" name="passRep">
	<br>
	<input type="submit" name="submit" value="verstuur">
	<br>
</from>

<h4><?php if (isset($error)) {echo $error; } ?></h4>

</html>