// Get the data to make the graph
function getGraphData(
    graphID,
    containerID,
    title,
    titleX,
    titleY,
    xValueType,
    api,
    filterSettings,
    inputFields,
    type = "column"
) {
    var graphData = {
        graphID: graphID,
        containerID: containerID,
        title: title,
        titleX: titleX,
        titleY: titleY,
        xValueType: xValueType,
        indexLabelFontColor: "#5a6767",
        api: api,
        filterSettings: filterSettings,
        inputFields: inputFields,
        type: type,
    }

    makeDiv(graphData.containerID)

    // If we make the graph with input fields than make the amount of input fields we have defined here with the names given in the object of input fields
    if (graphData.inputFields) {
        for (var i = 0; i < Object.keys(graphData.inputFields).length; i++) {
            makeInputFields(graphData, i)
        }
    }

    // If the filter setting has a song in it convert the song and artist name to a songID to show the correct data
    if (filterSettings.hasOwnProperty("song") && filterSettings.song != "") {
        data = filterSettings.song.split(" - ")
        $.ajax({
            url: "/api/song/searchByArtist.php",
            type: "POST",
            data: { song: data[0], artist: data[1] },
            success: function (result) {
                filterSettings.song = result[0]

                $.ajax({
                    url: graphData.api,
                    type: "POST",
                    data: filterSettings,
                    success: function (result) {
                        makeNewGraph(result["records"], graphData)
                    },
                    error: function () {
                        setError(graphData.containerID)
                    },
                })
            },
        })
    } else {
        // Else just show the data
        $.ajax({
            url: graphData.api,
            type: "POST",
            data: filterSettings,
            success: function (result) {
                makeNewGraph(result["records"], graphData)
            },
            error: function () {
                setError(graphData.containerID)
            },
        })
    }
}

// Make the graph based on the data fetched in getGraphData
function makeNewGraph(data, graphData) {
    var mainDiv = "#" + graphData.containerID + "-main"

    // Make a button array
    buttonArray(mainDiv, graphData.containerID)

    // Make the div where the graph will be placed in
    var graphDiv = document.createElement("div")
    graphDiv.id = graphData.containerID
    $(mainDiv).append(graphDiv)

    // Make the graph
    graphs[graphData.containerID] = new CanvasJS.Chart(graphData.containerID, {
        animationEnables: true,
        theme: "dark2",
        title: {
            text: graphData.title,
        },
        axisX: {
            title: graphData.titleX,
        },
        axisY: {
            includeZero: true,
            title: graphData.titleY,
        },
        data: [
            {
                type: graphData.type,
                xValueType: graphData.xValueType,
                indexLabel: "{y}",
                indexLabelFontColor: graphData.indexLabelFontColor,
                indexLabelPlacement: "inside",
                dataPoints: data,
            },
        ],
    })
    graphs[graphData.containerID].render()
    readInputFields(graphData)
    getButtonPressed(graphData)
}

// Update the data of the graph based on the timeframe change
function updateData(graphData) {
    // TODO: Make maxPlayed be the max amount of played and convert that to the next round number so 00 => 100 and 1387 => 1400 || 1500

    $.ajax({
        url: graphData.api,
        type: "POST",
        data: graphData.filterSettings,
        success: function (result) {
            graphs[graphData.containerID].options.data[0].dataPoints = []

            for (var i = 0; i < result["records"].length; i++) {
                graphs[graphData.containerID].options.data[0].dataPoints.push(
                    result["records"][i]
                )
            }
            graphs[graphData.containerID].options.title.text = graphData.title
            graphs[graphData.containerID].render()
        },
        error: function () {
            setError(graphData.containerID)
        },
    })
}

// Empty the graph and change the title to show an error for when it can't find results
function setError(containerID) {
    graphs[containerID].options.data[0].dataPoints = []
    graphs[containerID].options.title.text = "No data found"
    graphs[containerID].render()
}

// This makes the div where the graph will be placed in
function makeDiv(containerID) {
    let div = document.createElement("div")
    div.className = "main"
    div.id = containerID + "-main"

    $(".content").append(div)
}
