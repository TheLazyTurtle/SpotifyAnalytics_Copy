var graphs = []
var buttonArrays = []

var timeframes = [
    // id && value / display name
    ["yesterday", "Yesterday"],
    ["today", "Today"],
    ["week", "This week"],
    ["month", "This month"],
    ["year", "This year"],
    ["allTime", "All time"],
]

// Get the userID form the session
var userID = '<%= Session["userID"] %>'

// TODO: Find a way to get the correct api call and the correct data it needs
// Get the data to make the graph
function getGraphData(
    containerID,
    title,
    titleX,
    titleY,
    xValueType,
    api,
    filterSettings
) {
    var graphData = {
        containerID: containerID,
        title: title,
        titleX: titleX,
        titleY: titleY,
        xValueType: xValueType,
        indexLabelFontColor: "#5a6767",
        api: api,
    }

    $.ajax({
        url: api,
        type: "post",
        //contentType: "application/json",
        data: filterSettings,
        success: function (result) {
            makeNewGraph(result["records"], graphData)
        },
        error: function (result) {
            console.error(result)
        },
    })
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
    graphs[graphData.title] = new CanvasJS.Chart(graphData.containerID, {
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
                type: "column",
                xValueType: graphData.xValueType,
                indexLabel: "{y}",
                indexLabelFontColor: graphData.indexLabelFontColor,
                indexLabelPlacement: "inside",
                dataPoints: data,
            },
        ],
    })
    graphs[graphData.title].render()
    getButtonPressed(graphData.containerID, graphData.api)
}

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
function getButtonPressed(containerID, api) {
    for (var i = 0; i < timeframes.length; i++) {
        $("#" + containerID + "-" + timeframes[i]).click(function () {
            var timeframe = $(this).val()
            updateData(containerID, timeframe, api)
        })
    }
}

// Update the data of the graph based on the timeframe change
function updateData(containerID, timeframe, api) {
    // TODO: Make maxPlayed be the max amount of played and convert that to the next round number so 00 => 100 and 1387 => 1400 || 1500
    time = convertTime(timeframe)

    var data = {
        minPlayed: "1",
        maxPlayed: "9999",
        minDate: time.minDate,
        maxDate: time.maxDate,
    }

    $.ajax({
        url: api,
        type: "POST",
        //contentType: "application/json",
        data: data,
        success: function (result) {
            graphs[containerID].options.data[0].dataPoints = []

            for (var i = 0; i < result["records"].length; i++) {
                graphs[containerID].options.data[0].dataPoints.push(
                    result["records"][i]
                )
            }
            graphs[containerID].render()
        },
        error: function (result) {
            console.error(result)
        },
    })
}

var filterSettings = {
    minPlayed: 20,
    maxPlayed: 9999,
    minDate: "2020-01-01",
    maxDate: "2099-01-01",
}

getGraphData(
    "test",
    "test",
    "",
    "",
    "string",
    "/api/graph/allSongsPlayed.php",
    filterSettings
)

getGraphData(
    "test2",
    "test2",
    "",
    "",
    "string",
    "/api/graph/allSongsPlayed.php",
    filterSettings
)
