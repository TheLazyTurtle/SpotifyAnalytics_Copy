$(document).ready(function () {
    // Validates the jwt token
    $.ajax({
        url: "/api/system/validate_login.php",
        type: "POST",
        success: function () {
            // If a user has a valid token than return true
            return true
        },
        error: function () {
            // If a users token has expired or is invalid than send them to the login page
            if (
                document.URL.includes("index.php") ||
				document.URL.slice(-1) == "/"
            ) {
                window.location.href = "login.php"
            }

            return false
        },
    })
})
