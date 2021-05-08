<html lang="en">
    <!--Try and put all these things in an header file (prob wiht jquery)-->
    <head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<title>Register | Spotify analytics</title>

	<!-- jQuery & Bootstrap 4 JavaScript libraries -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>

    <body>
	<div id="content"></div>


    <!--This should all be placed in an seperate js file-->
    <script>
	$(document).ready(function() {
	    // Loading the form in this way is very important because otherwise it will just lose all the data on submit and it wont work
	    var html = `
		    <div class="register-container">
			<div class="register-box">
			    <h2>Register</h2>
			    <form method="POST" id="register_form">
				<input type="text" name="firstname" placeholder="Firstname" class="form-field register-form"><br>
				<input type="text" name="lastname" placeholder="Lastname" class="form-field register-form"><br>
				<input type="email" name="email" placeholder="Email" class="form-field register-form"><br>
				<input type="password" name="password" placeholder="Password" class="form-field register-form"><br>
				<button type="submit" class="btn register-btn">Register</button>
			    </form>
			    <p>Already have an account? Log in <a class="login-link" href="/login.php">here</a></p>
			</div>
		    </div>
	    `;

	    // Actually display the form
	    $('#content').html(html);

	    // Trigger when Register form is submitted
	    $(document).on('submit', "#register_form", function(){
		// Get form data
		var sign_up_form = $(this);
		var form_data = JSON.stringify(sign_up_form.serializeObject());

		// Submit form data to api
		$.ajax({
		    url: "api/create_user.php",
		    type: "POST",
		    contentType: "application/json",
		    data: form_data,
		    success: function(result) {
			// TODO: Do something successful
			window.location.href="login.php";
		    },
		    error: function(xhr, resp, text) {
			// TODO: Disapoint the user
			window.location.href="index.html";
		    }
		});
		return false;
	    });

	    // Cleans the data up
	    $.fn.serializeObject = function(){
 
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
