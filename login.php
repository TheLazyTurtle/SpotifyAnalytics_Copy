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
    $sql = "SELECT userID FROM users WHERE spotifyID = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $spID);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($res);

    mysqli_free_result($res);
    mysqli_close($connection);
    mysqli_stmt_close($stmt);
    return $row["userID"];
}

function login($name, $pass) {
    $connection = getConnection();

    $pass = md5($pass);

    $query = "SELECT * FROM users WHERE name = ? AND pass = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $name, $pass);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($res);
    
    if (mysqli_num_rows($res) == 1) {
	$_SESSION["spID"] = $row["spotifyID"];
	if (userHasAuthtoken($row["spotifyID"])) {
	    $_SESSION["loggedIn"] = True;
	    $_SESSION["userID"] = getUserID($_SESSION["spID"]);
	    header("Location: ./index.php");
	} else {
	    header("Location: ../browser/auth.php");
	}
    } else {
	$error = "Er is iets mis gegaan probeer het opnieuw";
    }
    mysqli_close($connection);
    mysqli_free_result($res);
    mysqli_stmt_close($stmt);
}

function userHasAuthToken($spID) {
    $connection = getConnection();
    $query = "SELECT * FROM users WHERE spotifyID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $spID);
    $res = mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($res);
    mysqli_close($connection);
    mysqli_stmt_close($stmt);

    if (empty($row["spotifyAuth"]) || empty($row["spotifyRefresh"]) || empty($row["spotifyExpire"])){
	mysqli_free_result($res);
	return False; 
    } else {
	mysqli_free_result($res);
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
