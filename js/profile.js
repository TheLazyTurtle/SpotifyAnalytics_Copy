var selectors = ["graphs", "memories"]
let userID = null

$(document).ready(async function () {
    await getUserInfo()
    changeProfilePicture()
    buildGraphs(userID)
})

// This will get the info of the user
async function getUserInfo() {
    let username = getUserName()

    await $.ajax({
        url: "/api/user/read_one.php",
        type: "GET",
        data: { username: username },
        success: function (result) {
            setUserInfo(result)
        },
        error: function (error) {
            console.warn(error)
        },
    })
}

// This will get the username from cookie
function getUserName() {
    let name = "username="
    let decodedCookie = decodeURIComponent(document.cookie)
    let ca = decodedCookie.split(";")

    for (var i = 0; i < ca.length; i++) {
        let c = ca[i]
        while (c.charAt(0) == " ") {
            c = c.substring(1)
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length)
        }
    }
    return ""
}

// This will set userInfo like profile picture and username
function setUserInfo(data) {
    // Set profile picture
    $(".user-info-img").attr("src", data["img"])

    // Set username
    $(".user-info-text").text(data["username"])

    // Set followers and following
    $(".followers").html("<b>" + data["followers"] + "</b> followers")
    $(".following").html("<b>" + data["following"] + "</b> following")
}

// This will give the popup screen for a user to change their settings
function changeProfilePicture() {
    // If you click the profile picture you can upload the new img
    $(".user-info-img").click(function () {
        document.querySelector("[type=file]").click()

        // Check if there already is a submit button
        if ($("#submit").length == 0) {
            // Add submit button
            let submit = document.createElement("button")
            submit.className = "btn"
            submit.id = "submit"
            submit.innerHTML = "Submit"
            $(".user-info-img-wrapper").append(submit)

            // If button is pressed than upload img
            $("#submit").click(function () {
                let form = $("#fileForm")[0]
                let data = new FormData(form)

                $.ajax({
                    type: "post",
                    enctype: "multipart/form-data",
                    url: "/api/user/updateProfilePicture.php",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 800000,
                    success: function (data) {
                        // Reload the page on success
                        window.location = window.location.href
                        console.log(data)
                    },
                    error: function (error) {
                        console.error(error)
                    },
                })
            })
        }
    })
}
