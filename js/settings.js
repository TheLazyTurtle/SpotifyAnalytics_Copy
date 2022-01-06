// Sets the already known values into the settings vields
function setProfileItem(id, value, checkbox = false) {
	if (checkbox) {
		$("#setting-"+id)[0].checked = parseInt(value)
	} else {
		$("#setting-"+id)[0].value = value
	}
}

// This handles button presses for the settings
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

// Update the user account in the database
function updateSettings() {
	const values = extractSettings()

	$.ajax({
		url: "api/user/update_user.php",
		type: "POST",
		data: values,
		success: function(result) {
			$("#setting-status")[0].innerHTML = result["message"]
			clearPasswordFields()
		},
		error: function(result) {
			$("#setting-status")[0].innerHTML = result["responseJSON"]["message"]
			clearPasswordFields()
		}
	})
}

// Empties the password fields after a button press
function clearPasswordFields() {
	$("#setting-password").val("");
	$("#setting-repeat-password").val("");
	$("#setting-old-password").val("");
}

// Read the setting values out of the fields
function extractSettings() {
	const items = $(".setting-item")
	let values = []

	for (let i = 0; i < items.length -3; i++) {
		let input = items[i].children[2]
		const name = input.name
		const value = input.value
		values.push({name, value})
	}
	const name = "privateAccount"
	const value = items[7].children[1].checked
	values.push({name, value})

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