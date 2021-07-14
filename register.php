<?php
require "header.php";
require 'vendor/autoload.php';

// Get spotifyID and email
$api = new SpotifyWebAPI\SpotifyWebAPI();

$api->setAccessToken($_SESSION["accessToken"]);
$user = $api->me();

$_SESSION["userID"] = $user->id;
?>

<body>
	<div id="content"></div>
	<div class="register-container">
		<div class="register-box">
			<h2>Register</h2>
			<form method="POST" id="register_form">
				<input type="hidden" name="userID" placeholder="userID" class="form-field register-form" value=<?php echo $user->id; ?> readonly><br>
				<input type="text" name="username" placeholder="Username" class="form-field register-form"><br>
				<input type="text" name="firstname" placeholder="Firstname" class="form-field register-form"><br>
				<input type="text" name="lastname" placeholder="Lastname" class="form-field register-form"><br>
				<input type="email" name="email" placeholder="Email" class="form-field register-form"><br>
				<input type="password" name="password" placeholder="Password" class="form-field register-form"><br>
				<input type="password" name="repeatPassword" placeholder="Repeat password" class="form-field register-form"><br>
				<button type="submit" class="btn register-btn">Register</button>
			</form>
			<p>Already have an account? Log in <a class="login-link" href="/login.php">here</a></p>
		</div>
	</div>

	<!--This should all be placed in an seperate js file-->
	<script>
		$(document).ready(function() {
			// Trigger when Register form is submitted
			$(document).on('submit', "#register_form", function() {
				// Get form data
				var sign_up_form = $(this);
				var form_data = sign_up_form.serializeObject();
				console.log(form_data)

				// Submit form data to api
				$.ajax({
					url: "api/user/create_user.php",
					type: "POST",
					//contentType: "application/json",
					data: form_data,
					success: function(result) {
						console.log(result)
						// TODO: Do something successful
						window.location.href = "/saveTokens.php";
					},
					error: function(xhr, resp, text) {
						console.log(xhr)
						console.log(xhr + " - " + text + " - " + resp)
						// TODO: Disapoint the user
						//window.location.href = "index.php";
					}
				});
				return false;
			});

			// Cleans the data up
			$.fn.serializeObject = function() {

				var o = {};
				var a = this.serializeArray();
				$.each(a, function() {
					if (o[this.name] !== undefined) {
						if (!o[this.name].push) {
							o[this.name] = [o[this.name]];
						}
						o[this.name].push(this.value || '');
					} else {
						o[this.name] = this.value || '';
					}
				});
				return o;
			};
		});
	</script>
</body>

</html>
