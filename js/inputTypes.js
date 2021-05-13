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
function makeInputFields(graphData, inputFieldData, index) {
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
    inputField.type = inputFieldData[index].type
    inputField.placeholder = inputFieldData[index].placeholder
    inputField.id = graphData.containerID + "-" + inputFieldData[index].name

    if (inputFieldData[index].type == "number") {
        inputField.min = 0
    }

    $(appendID).append(inputField)
}

// TODO: Refactor this because it is a mess
function readInputFields(graphData) {
    var inputFieldArrayDiv = "#" + graphData.containerID + "-input-array"
    var inputFields = $(inputFieldArrayDiv).children()

    if (inputFields.length > 0) {
        $(inputFields).on("change", function () {
            for (var i = 0; i < inputFields.length; i++) {
                var id = "#" + inputFields[i].id
                var filterSettingName = inputFields[i].id
                filterSettingName = filterSettingName.replace(
                    graphData.containerID + "-",
                    ""
                )

                // Check if value exists and than update that value
                if (
                    graphData.filterSettings[filterSettingName] != null &&
                    $(id).val() != ""
                ) {
                    graphData.filterSettings[filterSettingName] = $(id).val()
                }

                // Actually update the graph
                updateData(graphData)
            }
        })
    }
}
