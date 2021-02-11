<?php
require "./assets/header.php";

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

function getUserID($spID) {
    $connection = getConnection();
    $sql = "SELECT userID FROM users WHERE spotifyID = '$spID'";
    $query = mysqli_query($connection, $sql);
    $res = mysqli_fetch_assoc($query);

    mysqli_free_result($query);
    mysqli_close($connection);
    return $res["userID"];
}

function login($name, $pass) {
	$connection = getConnection();

	$pass = md5($pass);

	$query = mysqli_query($connection, "SELECT * FROM users WHERE name = '$name' AND pass = '$pass'");
	$res = mysqli_fetch_assoc($query);
	mysqli_close($connection);
	
	if (mysqli_num_rows($query) == 1) {
		$_SESSION["spID"] = $res["spotifyID"];
		if (userHasAuthtoken($res["spotifyID"])) {
			$_SESSION["loggedIn"] = True;
			$_SESSION["userID"] = getUserID($_SESSION["spID"]);
			header("Location: ./index.php");
		} else {
			header("Location: ../browser/auth.php");
		}
	} else {
		$error = "Er is iets mis gegaan probeer het opnieuw";
	}
}

function userHasAuthToken($spID) {
	$connection = getConnection();
	$query = mysqli_query($connection, "SELECT * FROM users WHERE spotifyID = '$spID'");

	$res = mysqli_fetch_assoc($query);
	mysqli_close($connection);

	if (empty($res["spotifyAuth"]) || empty($res["spotifyRefresh"]) || empty($res["spotifyExpire"])){
		return False; 
	} else {
		return True;
	}
}

?>

<div class="container">
    <form action="#" method="POST">
	<label>Naam</label>
	<input type="text" name="name">
	<br>
	<label>Wachtwoord</label>
	<input type="password" name="pass">
	<br>
	<input class="btn" type="submit" name="submit" value="Login">
	<br>
    </form>
</div>

<h4><?php if (isset($error)) {echo $error;}?> </h4>

</html>
