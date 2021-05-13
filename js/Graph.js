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

// Get the data to make the graph
function getGraphData(
    containerID,
    title,
    titleX,
    titleY,
    xValueType,
    api,
    filterSettings,
    inputFields
) {
    var graphData = {
        containerID: containerID,
        title: title,
        titleX: titleX,
        titleY: titleY,
        xValueType: xValueType,
        indexLabelFontColor: "#5a6767",
        api: api,
        filterSettings,
    }

    // If we make the graph with input fields than make the amount of input fields we have defined here with the names given in the object of input fields
    if (inputFields) {
        for (var i = 0; i < inputFields.length; i++) {
            makeInputFields(graphData, inputFields, i)
        }
    }

    // Make the api request to get the data to fill the graph
    $.ajax({
        url: api,
        type: "post",
        data: filterSettings,
        success: function (result) {
            makeNewGraph(result["records"], graphData)
        },
        error: function (result) {
            setError(graphData.containerID)
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
    readInputFields(graphData)
    getButtonPressed(graphData)
}

// Update the data of the graph based on the timeframe change
function updateData(graphData) {
    // TODO: Make maxPlayed be the max amount of played and convert that to the next round number so 00 => 100 and 1387 => 1400 || 1500

    $.ajax({
        url: graphData.api,
        type: "POST",
        //contentType: "application/json",
        data: graphData.filterSettings,
        success: function (result) {
            console.log(graphs[graphData.containerID])
            graphs[graphData.containerID].options.data[0].dataPoints = []

            for (var i = 0; i < result["records"].length; i++) {
                graphs[graphData.containerID].options.data[0].dataPoints.push(
                    result["records"][i]
                )
            }
            graphs[graphData.containerID].options.title.text = graphData.title
            graphs[graphData.containerID].render()
        },
        error: function (result) {
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

// TODO: Make different fileterSettings based on the graph
var filterSettings = {
    minPlayed: 20,
    maxPlayed: 9999,
    minDate: "2020-01-01",
    maxDate: "2099-01-01",
}

var inputFields = []
inputFields[0] = {
    name: "minPlayed",
    placeholder: "Minimaal afgespeeld",
    type: "number",
}
inputFields[1] = {
    name: "maxPlayed",
    placeholder: "Maximaal afgespeeld",
    type: "number",
}

// TODO: Might have to make it that it will make its own div for everyting instead of makeing a div in index.php and than passing the name in here
getGraphData(
    "test",
    "test",
    "",
    "",
    "string",
    "/api/graph/allSongsPlayed.php",
    filterSettings,
    inputFields
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
