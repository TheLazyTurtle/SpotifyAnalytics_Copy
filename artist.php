<?php
require "header.php";
?>

<link rel="stylesheet" type="text/css" href="/css/social.css">
<script src="/js/artist.js"></script>
<script src="/js/albumBuilder.js"></script>

<div class="container">
	<div class="artist-info">
		<div class="artist-info-img-wrapper">
			<img class="artist-info-img"></img>
		</div>
		<div class="artist-info-text-wrapper">
			<a class="artist-link" target="_blank">
				<h1 class="artist-info-text"></h1>
			</a>
		</div>
	</div>
	<hr class="divider">
	<div class="selector-wrapper">
		<!-- TODO: These kinda should be made automatically based on the array -->
		<div class="selector-item">
			<button class="btn selector" id="songs">songs</button>
		</div>
		<div class="selector-item">
			<button class="btn selector" id="graphs">graphs</button>
		</div>
	</div>

	<div class="content">
		<div class="top-songs-wrapper">
			<h2 class="top-songs-title">Top Songs</h2>
		</div>

		<hr class="divider">

		<div class="albums-wrapper">
		</div>
	</div>
</div>
