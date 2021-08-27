$(document).ready(function () {
    var jwt = getCookie("jwt")

    // Validates the jwt token
    $.ajax({
        url: "/api/system/validate_token.php",
        type: "POST",
        data: { jwt: jwt },
        success: function () {
            // If a user has a valid token than return true
            return true
        },
        error: function () {
            // If a users token has expired or is invalid than send them to the login page
            if (
                !document.URL.includes("login.php") &&
                !document.URL.includes("register.php") &&
                !document.URL.includes("album.php") &&
                !document.URL.includes("search.php") &&
                !document.URL.includes("user.php") &&
                !document.URL.includes("artist.php")
            ) {
                window.location.href = "/login.php"
            }

            // Change logout button to login button when you are not logged in
            $("#login-btn")[0].innerHTML = "Inloggen"
            $("#login-btn")[0].onclick = "window.location.href='/login.php'"

            $("#follow").remove()

            return false
        },
    })

    // get or read cookie
    function getCookie(cname) {
        var name = cname + "="
        var decodedCookie = decodeURIComponent(document.cookie)
        var ca = decodedCookie.split(";")

        for (var i = 0; i < ca.length; i++) {
            var c = ca[i]
            while (c.charAt(0) == " ") {
                c = c.substring(1)
            }

            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length)
            }
        }
        return ""
    }
})
