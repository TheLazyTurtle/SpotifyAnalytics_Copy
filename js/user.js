var selectors = ["graphs", "memories"]
let userID = false

$(document).ready(function () {
    getUserInfo()
    followButton()
})

function getUsername() {
    let addr = window.location.search
    let params = new URLSearchParams(addr)
    let username = params.get("user")

    return username
}

function getUserInfo() {
    let username = getUsername()

    $.ajax({
        url: "/api/user/read_one.php",
        type: "POST",
        data: { username: username },
        success: function (result) {
            setUserInfo(result)
            checkIfFollowing()
            checkIfSelf(result["username"])
        },
        error: function (error) {
            console.warn(error)
            // TODO: Show a user not found thingy
        },
    })
}

function setUserInfo(data) {
    // This will set the profile picture
    $(".user-info-img").attr("src", data["img"])

    // This will set the name
    $(".user-info-text").text(data["username"])

    // Set followers and following
    $(".followers").html("<b>" + data["followers"] + "</b> followers")
    $(".following").html("<b>" + data["following"] + "</b> following")

    // Set the userID
    userID = data["id"]
}

function getButtonPressed() {
    for (var i = 0; i < selectors.length; i++) {
        $("#" + selectors[i]).click(function () {
            let button = $(this)
            let buttonID = button[0].attributes[1].nodeValue

            // Might have to do this differently where it will use the array to switch or something like that
            switch (buttonID) {
                case "memories":
                    showMemories()
                default:
                    // TODO: Fix that when you press the button it won't remake the graphs when they are already there
                    getGraphs()
            }
        })
    }
}

// This handles the follow button
function followButton() {
    $("#follow").click(function () {
        let button = $(this)[0]
        let className = button.className

        if (className.includes("following")) {
            $.ajax({
                url: "api/user/unFollow.php",
                type: "post",
                data: { userToUnFollow: userID },
                success: function () {
                    button.innerHTML = "follow"
                    button.className = className.replace("following", "follow")

                    // Update followers count
                    let followers = $(".followers")[0]
                    followers.children[0].innerHTML =
                        parseInt(followers.children[0].innerHTML) - 1
                },
                error: function (error) {
                    console.error(error)
                },
            })
        } else {
            $.ajax({
                url: "api/user/follow.php",
                type: "post",
                data: { userToFollow: userID },
                success: function () {
                    button.innerHTML = "Unfollow"
                    button.className = className.replace("follow", "following")

                    // Update followers count
                    let followers = $(".followers")[0]
                    followers.children[0].innerHTML =
                        parseInt(followers.children[0].innerHTML) + 1
                },
                error: function (error) {
                    console.error(error)
                },
            })
        }
        className = button.className
    })
}

// This will check if you are looking at your own profile. If you are then send you to the profile page.
function checkIfSelf(username) {
    let name = "username="
    let decodedCookie = decodeURIComponent(document.cookie)
    let ca = decodedCookie.split(";")

    for (var i = 0; i < ca.length; i++) {
        let c = ca[i]
        while (c.charAt(0) == " ") {
            c = c.substring(1)
        }
        if (c.indexOf(name) == 0) {
            let user = c.substring(name.length, c.length)

            // Send user to their profile page
            if (user == username) {
                window.location = "/profile.php"
            }
        }
    }
}

// This will check if the user is following the person they are visiting
function checkIfFollowing() {
    $.ajax({
        url: "api/user/isFollowing.php",
        type: "post",
        data: { user: userID },
        success: function () {
            let followButton = $("#follow")[0]

            followButton.className = followButton.className.replace(
                "follow",
                "following"
            )
            followButton.innerHTML = "Unfollow"
        },
    })
}

function showMemories() {
    // TODO: make it show the memory stuff
}

getButtonPressed()
