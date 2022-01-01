var selectors = ["graphs", "memories"]
let userID = false

$(document).ready(async function () {
    await getUserInfo()
})

// Get the username from the url
function getUsername() {
    let addr = window.location.search
    let params = new URLSearchParams(addr)
    let username = params.get("user")

    return username
}

// Get the username from the cookies
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

// Gets the info of the user you are viewing
async function getUserInfo() {
    let username = getUsername()

    await $.ajax({
        url: "/api/user/read_one.php",
        type: "GET",
        async: true,
        data: { username: username },
        success: function (result) {
			console.table(result)
			// Set the correct data on screen for this person
            setUserInfo(result)

			// Check if you are already following this person
            checkIfFollowing(result["hasFollowRequestOpen"])

			// Check if you are viewing your own account
            checkIfSelf(result["username"])

			// Set click listener for follow button
			followButton(result["privateAccount"])

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

// Shows the correct user info on screen
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

// This handels button presses for viewing graphs or memories
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

// If you are not allowed to view this account than show a lock screen
function setNoAccess() {
	// Remove all data
	$(".content").empty()
	$(".content")[0].className = "content-locked"

	$(".content-locked").append("<i class='far fa-lock'></i>")
	$(".content-locked").append("<p style='color:white'>Please follow this person to view their account</p>")
}

// If you have viewing rights show the content
function setContent() {
	$(".content").empty()
	buildGraphs(userID)
}

// This handles the follow button
function followButton(privateAccount) {
    $("#follow").click(function () {
		let button = $(this)[0]
		let className = button.className

		if (className.includes("following")) {
			unfollowUser(userID)
		} else if (className.includes("request-pending")) {
			retractRequest(userID)
		} else {
			followUser(userID, privateAccount)
		}
    })
}

// This will cancel the follow request
function retractRequest(userID) {
	$.ajax({
		url: "api/notification/delete.php",
		type: "post",
		data: {
			receiverUserID: userID
		},
		success: function() {
			location.reload()
		}
	})
}

// This will make the user unfollow the user they are viewing
function unfollowUser(userID) {
	$.ajax({
		url: "api/user/unFollow.php",
		type: "post",
		data: { userToUnFollow: userID },
		success: function () {
			location.reload()
		},
		error: function (error) {
			console.error(error)
		},
	})
}

// This will make the user follow the user they are viewing
function followUser(userID, privateAccount) {
	if (privateAccount) {
		$.ajax({
			url: "api/notification/create.php",
			type: "post",
			data: {
				receiverUserID: userID,
				typeID: "1"
			},
			success: function() {
				location.reload()
			}
		})
	} else {
		$.ajax({
			url: "api/user/follow.php",
			type: "post",
			data: { userToFollow: userID },
			success: function () {
				notifyOfFollow(userID)
				location.reload()
			},
			error: function (error) {
				console.error(error)
			},
		})
	}
}

// Send the notification to the receiving user
function notifyOfFollow(userID) {
	$.ajax({
		url: "api/notification/create.php",
		type: "post",
		data: {
			receiverUserID: userID,
			typeID: "2"
		}
	})
}

// This will check if you are looking at your own profile. If you are then send you to the profile page.
function checkIfSelf(username) {
	try {
		if (username.toLowerCase() == parseCookie().toLowerCase()) {
			window.location.href = "/profile.php"
		}
	} catch (err) {
		console.error(err)
	}
}

// This will check if the user is following the person they are visiting
function checkIfFollowing(hasFollowRequestOpen) {
	let followButton = $("#follow")[0]

	if (hasFollowRequestOpen) {
		followButton.className = followButton.className.replace(
			"follow",
			"request-pending"
		)
		followButton.innerHTML = "Request pending"
	} else {
		$.ajax({
			url: "api/user/isFollowing.php",
			type: "GET",
			data: { user: userID },
			success: function () {
				followButton.className = followButton.className.replace(
					"follow",
					"following"
				)
				followButton.innerHTML = "Unfollow"
			}, 
		})
	}
}

function showMemories() {
    // TODO: make it show the memory stuff
}

