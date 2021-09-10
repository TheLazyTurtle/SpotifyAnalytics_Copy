<?php
require "src/header.php";
?>

<link rel="stylesheet" type="text/css" href="/css/social.css">
<script src="/js/user.js"></script>

<div class="container">
	<div class="user-info">
		<div class="user-info-img-wrapper">
			<img class="user-info-img"></img>
		</div>
		<div class="user-info-text-wrapper">
			<h1 class="user-info-text"></h1>
			<button class="btn btn-follow" id="follow">follow</button>
			<div class="followers-wrapper">
				<h3 class="followers"></h3>
				<h3 class="following"></h3>
			</div>
		</div>
	</div>
	<hr class="divider">
	<div class="selector-wrapper">
		<div class="selector-item">
			<button class="btn selector" id="graphs">graphs</button>
		</div>
		<div class="selector-item">
			<button class="btn selector" id="memories">memories</button>
		</div>
	</div>
	<div class="content">
	</div>
</div>
