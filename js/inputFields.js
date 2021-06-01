var artistTags = []
var songTags = []
getTags("/api/artist/topArtist.php", artistTags)

// Get the tags for searching
function getTags(api, tagType) {
    $.ajax({
        url: api,
        type: "GET",
        data: { amount: 200 },
        success: function (results) {
            loadTags(tagType, results)
        },
    })
}

function loadTags(tagType, tags) {
    // This will load artists and songs so they can be used as tags
    if (tagType.length == 0) {
        tagType = tags
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
        $(inputFields).on("input", function () {
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
                var dropDown = $(this).siblings(".played_Per_Day_results")

                autoComplete(graphData)
            }
        })
    }
}

function autoComplete(graphData) {
    $(function () {
        // This makes gives the autocomplete the filterable settings with the tags given above
        $("#top_Songs-artist").autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: "GET",
                    url: "/api/artist/topArtistSearch.php",
                    data: { keyword: request.term, amount: 10 },
                    success: function (data) {
                        response(data)
                    },
                })
            },

            select: function (event) {
                // TODO: make graph update
                var id = event.target.attributes[3].nodeValue
                id = cleanID(id)
                var input = $(this).val()
                graphData.filterSettings[id] = input
                updateData(graphData)
            },
        })
    })
}

function cleanID(rawID) {
    return rawID.replace("top_Songs-", "")
}

// If a dropdown item has been clicked update the graph based on those values
function getResultClicked(graphData, settingName) {
    $(document).on("click", ".top_Songs_results p", function () {
        // TODO: The problem is that its using the wrong settingName
        $(this)
            .parents(".top_Songs-artist")
            .find("input[type='text']")
            .val($(this).text())
        $(graphData.containerID + "_results").empty()

        graphData.filterSettings[settingName] = $(this).text()
        updateData(graphData)
    })
}
