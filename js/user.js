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
            setUserID(result)
            setImg(result)
            setName(result)
        },
        error: function (jqXHR, textStatus, error) {
            // TODO: Show a user not found thingy
        },
    })
}

// This will set the profile picture of the user you are watching
function setImg(result) {
    $(".user-info-img").attr("src", result["img"])
}

// This will set the name of the user you are watching
function setName(result) {
    $(".user-info-text").text(result["username"])
}

// This function will set a global variable with the userID
function setUserID(result) {
    userID = result["id"]
}

function getButtonPressed() {
    for (var i = 0; i < selectors.length; i++) {
        $("#" + selectors[i]).click(function () {
            let button = $(this)
            let buttonID = button[0].attributes[1].nodeValue

            switch (buttonID) {
                case "memories":
                    showMemories()
                default:
                    // TODO: Fix That graphs go in random order
                    getGraphs()
            }
        })
    }
}

function showMemories() {
    // TODO: make it show the memory stuff
}

getButtonPressed()
