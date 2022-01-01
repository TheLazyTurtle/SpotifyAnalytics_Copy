$(document).ready(function() {
	let notificationState = false
	$("#notifications").click(function() {
		if (notificationState) {
			$(".notification-popup-screen").remove()
			notificationState = false
		} else {
			createNotificationPopup();
			notificationState = true
		}
	})
})

async function createNotificationPopup() {
	let notifications = await getNotifications()

	let notificationPopupScreen = document.createElement("div")
	notificationPopupScreen.className = "notification-popup-screen"

	for (let i = 0; i < notifications.length; i++) {
		notificationPopupScreen.append(addNotification(notifications[i]))
	}

	// Make sure there will only be one popup screen
	if ($("#notifications").find(".notification-popup-screen").length == 0) {
		$("#notifications").prepend(notificationPopupScreen)
	}
}

function addNotification(data) {
	let notificationWrapper = document.createElement("div")
	notificationWrapper.className = "notification-wrapper"
	notificationWrapper.id = data["id"]

	let message = document.createElement("p")
	message.className = "notification-message"
	message.innerHTML = data["message"].replace("__USER__", data["senderUsername"])

	// Accept button wrapper
	let acceptButtonWrapper = document.createElement("div")
	acceptButtonWrapper.className = "notification-button"

	// Deny button wrapper
	let denyButtonWrapper = document.createElement("div")
	denyButtonWrapper.className = "notification-button"

	if (data["notificationTypeID"] == 1) {
		// Accept button
		let acceptbutton = document.createElement("i")
		acceptbutton.className = "fas fa-check"
		acceptButtonWrapper.append(acceptbutton)
		notificationButtonPressed(acceptbutton, data)
	}

	// Deny button
	let denyButton = document.createElement("i")
	denyButton.className = "fas fa-ban"
	denyButtonWrapper.append(denyButton)
	notificationButtonPressed(denyButton, data)
	
	// Divider
	let divider = document.createElement("hr")
	divider.className = "divider notification-divider"

	// Add all the things to the thing
	notificationWrapper.append(message)
	notificationWrapper.append(acceptButtonWrapper)
	notificationWrapper.append(denyButtonWrapper)
	notificationWrapper.append(divider)

	return notificationWrapper
}

function notificationButtonPressed(button, data) {
	$(button).click(function() {
		let notificationId = $(this).parent().parent()[0].id
		let response = $(this)[0].className == "fas fa-check" ? "accept" : "deny"
		processRequest(notificationId, response, data)
	})
}

function processRequest(id, response, data) {
	if (response == "accept") {
		$.ajax({
			url: "api/user/follow.php",
			type: "post",
			data: {
				"userToFollow": data["receiverUserID"],
				"requesterUserID": data["senderUserID"]
			},
			success: function() {
				deleteNotification(id)
			}
		})
	} else {
		deleteNotification(id)
	}
}

function deleteNotification(id) {
	$.ajax({
		url: "api/notification/delete.php",
		data: {"notificationID": id},
		type: "post",
		success: function() {
			$("#"+id).remove()
		}
	})
}

async function getNotifications() {
	return $.ajax({
		url: "/api/notification/readUserNotifications.php",
		type: "get",
		async: true
	})
}
