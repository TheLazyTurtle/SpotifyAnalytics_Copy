// TODO: If a result contains a special character it wont get the data when updating the grap because they are not escaped out
function autoComplete(graphData, inputFieldId, api) {
    $(function () {
        // This makes gives the autocomplete the filterable settings with the tags given above
        $(inputFieldId).autocomplete({
            source: function (request, response) {
                let data = { keyword: request.term, amount: 10 }

                if (userID != false) {
                    data.push({ userID: userID })
                }

                $.ajax({
                    type: "POST",
                    url: api,
                    data: data,
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
            select: function (element, event) {
                var input = event.item.value

                updateGraph(element, graphData, input)
            },

            // This should reset the graph when the input is empty
            change: function (element) {
                if ($(this).val().length <= 0) {
                    updateGraph(element, graphData, "")
                }
            },
        })
    })
}

// Updates the graph based on the given data from the autocomplete
function updateGraph(element, graphData, input) {
    var id = element.target.attributes[3].nodeValue
    id = cleanFilterSettingID(id, graphData.containerID)

    // Update change in database
    updateFilterSetting(graphData.graphID, id, input)

    // If it is a song get the songID based on the song name and artist
    if (graphData.filterSettings.hasOwnProperty("song")) {
        if (input.length == 0) {
            graphData.filterSettings[id] = null
            updateData(graphData)
        } else {
            var data = input.split(" - ")
            $.ajax({
                type: "POST",
                url: "/api/song/searchByArtist.php",
                data: { song: data[0], artist: data[1] },
                success: function (data) {
                    graphData.filterSettings[id] = data[0]
                    updateData(graphData)
                },
            })
        }
    } else {
        // Update the graphd based on given input
        graphData.filterSettings[id] = input
        updateData(graphData)
    }
}

// This will update the setting in the database when input changes
function updateFilterSetting(graphID, settingName, value) {
    $.ajax({
        type: "POST",
        url: "/api/user/updateFilterSetting.php",
        data: { graphID: graphID, settingname: settingName, value: value },
    })
}
