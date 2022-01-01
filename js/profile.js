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

	setUpdateProfileItems(data)
}

function setUpdateProfileItems(data) {
	setProfileItem("username", data["username"])
	setProfileItem("firstname", data["firstname"])
	setProfileItem("lastname", data["lastname"])
	setProfileItem("email", data["email"])
	setProfileItem("private", data["privateAccount"], true)

	onSettingButtonPress()
}
