<?php
require 'header.php';
?>

<body>
	<div id="content"></div>

	<script>
		$(document).ready(function() {
			// Make the form
			var html = `
		<div class="login-container">
		    <div class="login-box">
			<h2>Login</h2>
			<form method="POST" id="login_form">
			    <input type="email" name="email" placeholder="Email" class="form-field login-form"><br>
			    <input type="password" name="password" placeholder="Password" class="form-field login-form"><br>
			    <button type="submit" class="btn login-btn">Login</button>
			</form>
			<p>Don't have an account? Make one <a class="register-link" href="/getTokens.php">here</a></p>
		    </div>
		</div>
	    `;

			// Load the form
			$("#content").html(html);

			// Process the input data
			$(document).on('submit', "#login_form", function() {
				// Get form data
				var login_form = $(this);
				var login_data = login_form.serializeObject();

				// submit data to api
				$.ajax({
					url: "api/system/login.php",
					type: "POST",
					data: login_data,
					success: function(result) {
						// Store jwt to cookie
						setCookie("jwt", result.jwt, 1);

						// Go to home page
						window.location.href = "/index.php";
					},
					error: function(xhr, resp, text) {
						console.error(resp + " " + text);
						// TODO: Do something to disapoint the user
					}
				});
				return false;
			});

			// Sets the cookie
			function setCookie(cname, cvalue, exdays) {
				var d = new Date();
				d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
				var expires = "expires=" + d.toUTCString();
				document.cookie = cname + "=" + cvalue + ";" + expires + "; path=/";
			}

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
