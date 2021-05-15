var timeframes = [
    // id && value / display name
    ["yesterday", "Yesterday"],
    ["today", "Today"],
    ["week", "This week"],
    ["month", "This month"],
    ["year", "This year"],
    ["allTime", "All time"],
]
// Make the array of buttons that are used to select the timeframe
function buttonArray(mainDiv, containerID) {
    var id = containerID + "-array"
    $(mainDiv).append("<div class='button-array' id=" + id + "></div>")

    for (var i = 0; i < timeframes.length; i++) {
        var button = document.createElement("button")
        button.className = containerID + "-button btn"
        button.value = timeframes[i][0]
        button.id = containerID + "-" + timeframes[i][0]
        button.innerHTML = timeframes[i][1]

        var appendID = "#" + id
        $(appendID).append(button)
    }
}

// Check if the timeframe buttons are pressed
function getButtonPressed(graphData) {
    for (var i = 0; i < timeframes.length; i++) {
        $("#" + graphData.containerID + "-" + timeframes[i]).click(function () {
            var timeframe = $(this).val()
            var updatedTime = convertTime(timeframe)

            if (
                graphData.filterSettings["minDate"] != null &&
                graphData.filterSettings["maxDate"] != null
            ) {
                graphData.filterSettings["minDate"] = updatedTime.minDate
                graphData.filterSettings["maxDate"] = updatedTime.maxDate
            }

            updateData(graphData)
        })
    }
}

// This will make the input fields like min played and maxplayed or names,
// these will be maded based on the amount of given inputFields in the makeGraph function
function makeInputFields(graphData, index) {
    var mainDiv = "#" + graphData.containerID + "-main"
    var id = graphData.containerID + "-input-array"
    var appendID = "#" + id

    // If the div does not yet exist than make the div else ignore this because the div already
    // exists and you can just append to the existing one
    if ($(appendID).length == 0) {
        $(mainDiv).append("<div class='input-array' id=" + id + "></div>")
    }

    var inputField = document.createElement("input")
    inputField.className = graphData.containerID + "-input inputField"
    inputField.type = graphData.inputFields[index].type
    inputField.placeholder = graphData.inputFields[index].placeholder
    inputField.id =
        graphData.containerID + "-" + graphData.inputFields[index].name

    if (graphData.inputFields[index].type == "number") {
        inputField.min = 0
    }

    $(appendID).append(inputField)
}

// This runs when a input field is updated
function readInputFields(graphData) {
    var inputFieldArrayDiv = "#" + graphData.containerID + "-input-array"
    var inputFields = $(inputFieldArrayDiv).children()

    // If the inputFields array has input fields
    if (inputFields.length > 0) {
        // Check if the fields have changed
        $(inputFields).on("keyup", function () {
            // If there was change go through all the inputfields
            for (var i = 0; i < inputFields.length; i++) {
                var settingName = inputFields[i].id
                var inputFieldId = "#" + settingName

                // Remove the graph identifier from the id so that you are left
                // with the name of the filter setting you want to change
                settingName = settingName.replace(
                    graphData.containerID + "-",
                    ""
                )

                // Check if value exists and than update that value
                var value = $(inputFieldId).val()
                if (value != "") {
                    // Sets the data so it will be processed when updating the grap
                    graphData.filterSettings[settingName] = value
                }

                // Update the graph
                updateData(graphData)
            }
        })
    }
}
