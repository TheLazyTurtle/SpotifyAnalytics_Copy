// This will make the input fields like min played and maxplayed or names,
// these will be maded based on the amount of given inputFields in the makeGraph function
function makeInputFields(graphData, index) {
    var containerID = graphData.containerID

    var mainDiv = "#" + containerID + "-main"
    var id = containerID + "-input-array"
    var appendID = "#" + id

    // This will make in div to put all the input fields in
    makeInputFieldArray(appendID, mainDiv, id)

    var inputField = document.createElement("input")
    inputField.className = containerID + "-input inputField"
    inputField.type = graphData.inputFields[index].type
    inputField.placeholder = graphData.inputFields[index].placeholder
    inputField.id = containerID + "-" + graphData.inputFields[index].name

    // If the field should be a number set the min value to 0
    if (graphData.inputFields[index].type == "number") {
        inputField.min = 0
    }

    // Add the input field to input field array
    $(appendID).append(inputField)
}

// This runs when a input field is updated
// TODO: It would make life so much easier if this could just get passed an index. Would just love it to make this a class...
function readInputFields(graphData) {
    var containerID = graphData.containerID
    var inputFieldArrayDiv = "#" + containerID + "-input-array"
    var inputFields = $(inputFieldArrayDiv).children()
    var amountOfFields = Object.keys(graphData.inputFields).length

    // Check if the fields have changed
    //$(inputFields).on("input", function () {
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
                graphData.filterSettings[settingName] = $(this).val()
                updateData(graphData)
            }
        })
    }
    //})
}

// TODO: If a result contains a special character it wont get the data when updating the grap because they are not escaped out
function autoComplete(graphData, inputFieldId, api) {
    $(function () {
        // This makes gives the autocomplete the filterable settings with the tags given above
        $(inputFieldId).autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: "GET",
                    url: api,
                    data: { keyword: request.term, amount: 10 },
                    success: function (data) {
                        // If its a song do difficult route because we have to worry about IDs and not names
                        // because songs are more likely to have the same name
                        if (graphData.filterSettings.hasOwnProperty("song")) {
                            response(
                                data.map(function (item) {
                                    return item["name"] + " - " + item["artist"]
                                })
                            )
                        } else {
                            response(data)
                        }
                    },
                })
            },

            // Updates the graph when a result is clicked
            select: function (event) {
                var input = $(this).val()

                // If its a song do difficult route because we have to worry about IDs and not names
                // because songs are more likely to have the same name
                if (graphData.filterSettings.hasOwnProperty("song")) {
                    getSongID(event, graphData, input)
                } else {
                    updateGraph(event, graphData, input)
                }
            },

            // This should reset the graph when the input is empty
            change: function (event) {
                if ($(this).val().length <= 0) {
                    updateGraph(event, graphData, "")
                }
            },
        })
    })
}

// Updates the graph based on the given data from the autocomplete
function updateGraph(event, graphData, input) {
    var id = event.target.attributes[3].nodeValue
    id = cleanFilterSettingID(id, graphData.containerID)

    graphData.filterSettings[id] = input
    updateData(graphData)
}

function getSongID(event, graphData, input) {
    var data = input.split(" - ")

    $.ajax({
        type: "GET",
        url: "/api/song/searchByArtist.php",
        data: { song: data[0], artist: data[1] },
        success: function (data) {
            updateGraph(event, graphData, data[0])
        },
    })
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
        return "/api/song/topSongsSearch.php"
    } else if (settingName == "artist") {
        return "/api/artist/topArtistSearch.php"
    } else {
        return "no API"
    }
}
