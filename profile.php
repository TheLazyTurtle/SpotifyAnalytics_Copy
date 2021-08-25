<?php
require "header.php";

?>
<link rel="stylesheet" type="text/css" href="/css/social.css">
<script src="/js/profile.js"></script>

<div class="container">
	<div class="user-info">
		<div class="user-info-img-wrapper">
			<form id="fileForm" class="user-info-img-uploader">
				<input type="file" name="file" />
			</form>
			<img class="user-info-img"></img>
		</div>
		<div class="user-info-text-wrapper">
			<h1 class="user-info-text"></h1>
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
