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
function getButtonPressed(containerID, inputFields, api) {
    for (var i = 0; i < timeframes.length; i++) {
        $("#" + containerID + "-" + timeframes[i]).click(function () {
            var timeframe = $(this).val()
            var updatedTime = convertTime(timeframe)

            if (
                inputFields["minDate"] != null &&
                inputFields["maxDate"] != null
            ) {
                inputFields["minDate"] = updatedTime.minDate
                inputFields["maxDate"] = updatedTime.maxDate
            }

            updateData(containerID, inputFields, api)
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
function readInputFields(containerID, filterSettings, api) {
    var inputFieldArrayDiv = "#" + containerID + "-input-array"
    var inputFields = $(inputFieldArrayDiv).children()

    if (inputFields.length > 0) {
        $(inputFields).on("change", function () {
            for (var i = 0; i < inputFields.length; i++) {
                var id = "#" + inputFields[i].id
                var filterSettingName = inputFields[i].id
                filterSettingName = filterSettingName.replace(
                    containerID + "-",
                    ""
                )

                // Check if value exists and than update that value
                if (
                    filterSettings[filterSettingName] != null &&
                    $(id).val() != ""
                ) {
                    filterSettings[filterSettingName] = $(id).val()
                }

                // Actually update the graph
                updateData(containerID, filterSettings, api)
            }
        })
    }
}
