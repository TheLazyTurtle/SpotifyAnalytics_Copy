<?php
session_start();
$url = $_SERVER["REQUEST_URI"];
$url = str_replace("/", '', $url);
$url = str_replace(".php", '', $url);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/slider.css">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Spotify analytics</title>

	<!-- jQuery & Bootstrap 4 JavaScript libraries -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script src="https://kit.fontawesome.com/7fe26f8076.js" crossorigin="anonymous"></script>

	<script src="../js/check_jwt.js"></script>
	<script src="js/dateProcessing.js"></script>
</head>

<?php
if ($url != "login" && $url != "register") {
?>
	<header>
		<div class="header">
			<div class="col col-left">
				<button class="btn" onclick="window.location.href='/index.php'">Home</button>
			</div>
			<div class="col col-middel">
				<input autocomplete="off" class="search-box" type="text" name="Search" placeholder="search">
			</div>
			<div class="col col-right">
				<!--<a href='feed.php'><i class="far fa-images"></i></a> -->
				<a href='profile.php'><i class="fas fa-user-alt"></i></a>
				<button class="btn" id="login-btn" onclick="window.location.href='logout.php'">Uitloggen</button>
			</div>
		</div>

		<div class="mobile-header">
			<div class="col col-left">
				<a href='/index.php'><i class="fas fa-home"></i></a>
			</div>
			<div class="col col-middel">
				<a href='/src/search.php'><i class="fas fa-search"></i></a>
			</div>
			<div class="col col-middel">
				<a href='/src/feed.php'><i class="far fa-images"></i></a>
			</div>
			<div class="col col-right">
				<a href='/src/profile.php'><i class="fas fa-user-alt"></i></a>
			</div>

		</div>
	</header>
	<script src="/js/search.js"></script>
<?php
}
?>
