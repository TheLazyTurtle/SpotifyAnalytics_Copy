$(document).ready(function () {
    var jwt = getCookie("jwt")

    // Validates the jwt token
    $.ajax({
        url: "api/validate_token.php",
        type: "post",
        contentType: "application/json",
        data: JSON.stringify({ jwt: jwt }),
        success: function (result) {
            if (!document.URL.includes("index.php")) {
                window.location.href = "/index.php"
                return true
            }
        },
        error: function (result) {
            if (
                !document.URL.includes("login.php") &&
                !document.URL.includes("register.php")
            ) {
                window.location.href = "/login.php"
            }
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
