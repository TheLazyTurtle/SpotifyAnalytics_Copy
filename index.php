<?php
require 'header.php';

?>

<body>
    <?php require 'slider.php' ?>
    <div class="container">
	<div id="all_Songs_Played-main" class="main">
	    <div class="all_Songs_played_results"></div>
	</div>

	<div id="top_Songs-main" class="main">
	    <div class="top_Songs_results"></div>
	</div>

	<div id="top_Artist-main" class="main">
	    <div class="top_Artist_results"></div>
	</div>

	<div id="played_Per_Day-main" class="main">
	    <div class="played_Per_Day_results"></div>
	</div>
    </div>
</body>

<script src="js/inputFields.js"></script>
<script src="js/buttons.js"></script>
<script src="js/dateProcessing.js"></script>
<script src="js/Graph.js"></script>
<script src="js/makeGraphs.js"></script>
</html>
