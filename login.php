<?php
require "./assets/header.php";

// checks if the cookies are set and it than logs you in if that is the case
if (isset($_COOKIE["userID"]) && isset($_COOKIE["token"])) {
    if (!empty($_COOKIE["userID"]) && !empty($_COOKIE["token"])) {	

	$connection = getConnection();
	$userID = $_COOKIE["userID"];
	$token = $_COOKIE["token"];

	$query = "SELECT count(*), validTo FROM loginToken WHERE userID = ? AND token = ? GROUP BY userID";
	$stmt = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($stmt, "ss", $userID, $token);
	$res = mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);

	$row = mysqli_fetch_row($res);
	if ($row[0] == 1) {
	    if (date("Y-m-d") < $row[1]) {
		$_SESSION["loggedIn"] = True;
		$_SESSION["userID"] = $_COOKIE["userID"];

		if ($_SESSION["loggedIn"] == True) {
		    header("Location: /index.php");
		}
	    }
	} else {
	    header("Location: /login.php");
	}
    }
}

$error;

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $pass = $_POST["pass"];

    if (isset($_POST["checkbox"])) {
	$checkbox = $_POST["checkbox"];

	// Generates a random token and how long it is valid for
	$token = openssl_random_pseudo_bytes(16);
	$token = bin2hex($token);
	$validTo = strtotime("next month");
	$validTo = date("Y-m-d", $validTo); 
    }	

    if (empty($name) || empty($pass)) {
	$error = "Niet alle velden zijn ingevuld";
    } else {
	login($name, $pass, $token, $validTo);
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

function login($name, $pass, $token, $validTo) {
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

	    addToken($_SESSION["userID"], $token, $validTo);
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
	<label>Ingeloged blijven?</label>
	<input type="checkbox" name="checkbox">
	<br>
	<input class="btn" type="submit" name="submit" value="Login">
	<br>
    </form>
</div>

<h4><?php if (isset($error)) {echo $error;}?> </h4>

</html>
