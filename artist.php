<?php
require "header.php";
?>

<link rel="stylesheet" type="text/css" href="/css/social.css">
<script src="/js/artist.js"></script>

<div class="container">
	<div class="artist-info">
		<div class="artist-info-img-wrapper">
			<img class="artist-info-img"></img>
		</div>
		<div class="user-info-text-wrapper">
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

	<!-- This should kinda contain 2 things -->
	<!-- All songs from an artist. We have to change the db kinda because I would like to have them in the album they are from, and i would like to have the date the song was uploaded to spotify so that when a user is following the artist they will be notified by new uploads from the artist -->
	<!-- Graphs. Lets just have the same graphs as the user but than for the artist. So you can see that the song X from artist Y has been listend to Z amount of times -->
	<div class="content">
		<div class="top-songs-wrapper">
			<h2 class="top-songs-title">Top Songs</h2>
		</div>
	</div>
</div>
