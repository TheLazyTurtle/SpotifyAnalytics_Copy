var selectors = ["graphs", "memories"]
let userID = false

$(document).ready(function () {
    getUserInfo()
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
        },
        error: function (jqXHR, textStatus, error) {
            // TODO: Show a user not found thingy
        },
    })
}

function setUserInfo(result) {
    // This will set the profile picture
    $(".user-info-img").attr("src", result["img"])

    // This will set the name
    $(".user-info-text").text(result["username"])

    // Set the userID
    userID = result["id"]
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

function showMemories() {
    // TODO: make it show the memory stuff
}

getButtonPressed()
