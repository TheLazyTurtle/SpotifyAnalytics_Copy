<?php
require "src/header.php";

?>
<link rel="stylesheet" type="text/css" href="/css/social.css">
<link rel="stylesheet" type="text/css" href="/css/settings.css">
<script src="js/settings.js"></script>
<script src="js/profile.js"></script>
<script src="js/elements/Button.js"></script>
<script src="js/elements/InputField.js"></script>
<script src="js/Graph.js"></script>
<script src="js/makeGraphs.js"></script>

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
			<button id="settings-button" name="settingsButton" class="btn">settings</button>
		</div>
	</div>

	<hr class="divider">
	<div class="selector-wrapper">
		<div class="selector-item">
			<button class="btn selector" id="graphs">graphs</button>
		</div>
	<!--  	
		<div class="selector-item">
			<button class="btn selector" id="memories">memories</button>
		</div>
	-->
	</div>

	<div class="content"></div>
</div>

<!-- Setting thingy -->
<div id="settings-wrapper" class="hidden">
	<div class="settings-holder">
		<div class="settings">
			<div class="setting-item">
				<label>Username:</label><br>
				<input class="form-field" id="setting-username" name="username" placeholder="Username" type="text"/>
			</div>
			<div class="setting-item">
				<label>Firstname:</label><br>
				<input class="form-field" id="setting-firstname" name="firstname" placeholder="Firstname" type="text"/>
			</div>
			<div class="setting-item">
				<label>Lastname:</label><br>
				<input class="form-field" id="setting-lastname" name="lastname" placeholder="Lastname" type="text"/>
			</div>
			<div class="setting-item">
				<label>Email:</label><br>
				<input class="form-field" id="setting-email" name="email" placeholder="Email" type="email"/>
			</div>
			<div class="setting-item">
				<label>Old password:</label><br>
				<input class="form-field" id="setting-old-password" name="oldPassword" placeholder="Old Password" type="password"/>
			</div>
			<div class="setting-item">
				<label>Password:</label><br>
				<input class="form-field" id="setting-password" name="password" placeholder="Password" type="password"/>
			</div>
			<div class="setting-item">
				<label>Repeat password:</label><br>
				<input class="form-field" id="setting-repeat-password" name="repeatPassword" placeholder="Repeat Password" type="password"/>
			</div>
			<div class="setting-item">
				<label>Private Account</label>
				<input id="setting-private" name="privateAccount" type="checkbox"/>
			</div>
			<div class="setting-item">
				<input class="btn" name="cancel" type="submit" value="Cancel"/>
				<input class="btn" name="submitChanges" type="submit" value="Submit"/>
			</div>	
			<div class="setting-item">
				<label id="setting-status"></label>
			</div>	
		</div>
	</div>
</div>
