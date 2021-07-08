// This will make the input fields like min played and maxplayed or names,
// these will be maded based on the amount of given inputFields in the makeGraph function
function makeInputFields(graphData, index) {
    var containerID = graphData.containerID

    var mainDiv = "#" + containerID + "-main"
    var id = containerID + "-input-array"
    var appendID = "#" + id
    var inputFields = graphData.inputFields

    // This will make in div to put all the input fields in
    makeInputFieldArray(appendID, mainDiv, id)

    var inputField = document.createElement("input")
    inputField.className = containerID + "-input inputField"
    inputField.type = inputFields[index].type
    inputField.placeholder = inputFields[index].placeholder
    inputField.id = containerID + "-" + inputFields[index].name

    if (inputFields[index] == null) {
        inputField.value = graphData.filterSettings[inputFields[index].name]
    } else {
        inputField.value = inputFields[index].value
    }

    // If the field should be a number set the min value to 0
    if (graphData.inputFields[index].type == "number") {
        inputField.min = 0
    }

    // Add the input field to input field array
    $(appendID).append(inputField)
}

// This runs when a input field is updated
function readInputFields(graphData) {
    var containerID = graphData.containerID
    var inputFieldArrayDiv = "#" + containerID + "-input-array"
    var inputFields = $(inputFieldArrayDiv).children()
    var amountOfFields = Object.keys(graphData.inputFields).length

    // Check if the fields have changed
    // If there was change go through all the inputfields
    for (var i = 0; i < amountOfFields; i++) {
        $(inputFields[i]).on("input", function () {
            var settingName = $(this)[0].attributes[3].value
            var inputFieldId = "#" + settingName

            // Remove the graph identifier from the id so that you are left
            // with the name of the filter setting you want to change
            settingName = settingName.replace(containerID + "-", "")
            var api = chooseApi(settingName)

            if (api !== "no API") {
                autoComplete(graphData, inputFieldId, api)
            } else {
                var value = $(this).val()
                graphData.filterSettings[settingName] = value
                updateData(graphData)

                updateFilterSetting(graphData.graphID, settingName, value)
            }
        })
    }
}

// This will remove the prefix of the given id so that it can update the correct filter setting
function cleanFilterSettingID(rawID, containerID) {
    return rawID.replace(containerID + "-", "")
}

// If the div does not yet exist than make the div else ignore this because the div already
// exists and you can just append to the existing one
function makeInputFieldArray(appendID, mainDiv, id) {
    if ($(appendID).length == 0) {
        $(mainDiv).append("<div class='input-array' id=" + id + "></div>")
    }
}

// This will based on the settingName choose what api to return
function chooseApi(settingName) {
    if (settingName == "song") {
        return "/api/played/topSongsSearch.php"
    } else if (settingName == "artist") {
        return "/api/artist/topArtistSearch.php"
    } else {
        return "no API"
    }
}
