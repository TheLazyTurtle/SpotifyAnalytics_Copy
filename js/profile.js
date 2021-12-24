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

function setProfileItem(id, value, checkbox = false) {
	if (checkbox) {
		$("#setting-"+id)[0].checked = value
	} else {
		$("#setting-"+id)[0].value = value
	}
}

function onSettingButtonPress() {
	// If one of the buttons is pressed
	$(".btn").click(function() {
		let name = $(this)[0].name
		if (name == "cancel") {
			$("#settings-wrapper")[0].className = "hidden"
		} else if (name == "submitChanges") {
			updateSettings()
		} else if (name == "settingsButton") {
			$("#settings-wrapper")[0].className = "show"
		}
	})

	// When clicked besides the main setting part close the screen
	//$("#settings-wrapper").click(function() {
		//$("#settings-wrapper")[0].className = "hidden"
	//})
}

function updateSettings() {
	const values = extractSettings()

	$.ajax({
		url: "api/user/update_user.php",
		type: "POST",
		data: values
	})
}

function extractSettings() {
	const items = $(".setting-item")
	let values = []

	for (let i = 0; i < items.length -3; i++) {
		let input = items[i].children[2]
		const name = input.name
		const value = input.value
		values.push({name, value})
	}

	return values
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
