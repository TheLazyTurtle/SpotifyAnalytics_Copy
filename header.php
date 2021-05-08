<html lang="en">
    <head>
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<title>Spotify analytics</title>

	<!-- jQuery & Bootstrap 4 JavaScript libraries -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>

<script>
$(document).ready(function() {
    var jwt = getCookie('jwt');
    $.ajax({
    url: "api/validate_token.php",
	type: "post",
	contentType: "application/json",
	data: JSON.stringify({jwt:jwt}),
	success: function(result) {
	    if (!document.URL.includes("index.php")) {
		window.location.href = "index.php";
	    }
	},
	error: function(result) {
	    if (!document.URL.includes("login.php")) {
		window.location.href = "login.php";
	    }
	}

    });

    // get or read cookie
    function getCookie(cname){
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i < ca.length; i++) {
	    var c = ca[i];
	    while (c.charAt(0) == ' '){
		c = c.substring(1);
	    }
     
	    if (c.indexOf(name) == 0) {
		console.log(c.substring(name.length, c.length));
		return c.substring(name.length, c.length);
	    }
	}
	return "";
    }
});

</script>
