var selectors = ["graphs", "memories"]
let userID = false

$(document).ready(async function () {
    await getUserInfo()
    followButton()
})

function getUsername() {
    let addr = window.location.search
    let params = new URLSearchParams(addr)
    let username = params.get("user")

    return username
}

function parseCookie() {
	const rawCookies = document.cookie
	const cookies = rawCookies.split("; ")
	for (let i = 0; i < cookies.length; i++) {
		const tempCookie = cookies[i].split("=")
		if (tempCookie[0] == "username") {
			return tempCookie[1]
		}
	}
}

async function getUserInfo() {
    let username = getUsername()

    await $.ajax({
        url: "/api/user/read_one.php",
        type: "GET",
        async: true,
        data: { username: username },
        success: function (result) {
            setUserInfo(result)
            checkIfFollowing()
            checkIfSelf(result["username"])

			if (result["viewingRights"]) {
				setContent()
			} else {
				setNoAccess()
			}
        },
        error: function () {
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
					buildGraphs(userID)
			}
        })
    }
}

function setNoAccess() {
	// Remove all data
	$(".content").empty()
	$(".content")[0].className = "content-locked"

	$(".content-locked").append("<i class='far fa-lock'></i>")
	$(".content-locked").append("<p style='color:white'>Please follow this person to view their account</p>")
}

function setContent() {
	$(".content").empty()
	buildGraphs(userID)
}

// This handles the follow button
function followButton() {
    $("#follow").click(function () {
        let button = $(this)[0]
        let className = button.className

        if (className.includes("following")) {
            $.ajax({
                url: "api/user/unFollow.php",
                type: "POST",
                data: { userToUnFollow: userID },
                success: function () {
                    button.innerHTML = "follow"
                    button.className = className.replace("following", "follow")

                    // Update followers count
                    let followers = $(".followers")[0]
                    followers.children[0].innerHTML =
                        parseInt(followers.children[0].innerHTML) - 1
					location.reload()
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
					location.reload()
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
	if (username.toLowerCase() == parseCookie().toLowerCase()) {
		window.location.href = "/profile.php"
	}
}

// This will check if the user is following the person they are visiting
function checkIfFollowing() {
    $.ajax({
        url: "api/user/isFollowing.php",
        type: "GET",
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

