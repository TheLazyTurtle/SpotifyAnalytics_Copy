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
function getGraphData(containerID, title, titleX, titleY, xValueType) {
    var filterSettings = {
        minPlayed: 20,
        maxPlayed: 9999,
        minDate: "2020-01-01",
        maxDate: "2099-01-01",
    }

    var graphData = {
        containerID: containerID,
        title: title,
        titleX: titleX,
        titleY: titleY,
        xValueType: xValueType,
        indexLabelFontColor: "#5a6767",
    }

    $.ajax({
        url: "/api/graph/allSongsPlayed.php",
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
    getButtonPressed(graphData.containerID)
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
function getButtonPressed(containerID) {
    for (var i = 0; i < timeframes.length; i++) {
        $("#" + containerID + "-" + timeframes[i]).click(function () {
            var timeframe = $(this).val()
            updateData(timeframe)
        })
    }
}

// Update the data of the graph based on the timeframe change
function updateData(timeframe) {
    // TODO: Make maxPlayed be the max amount of played and convert that to the next round number so 00 => 100 and 1387 => 1400 || 1500
    time = convertTime(timeframe)

    var data = {
        minPlayed: "1",
        maxPlayed: "9999",
        minDate: time.minDate,
        maxDate: time.maxDate,
    }

    $.ajax({
        url: "/api/graph/allSongsPlayed.php",
        type: "POST",
        //contentType: "application/json",
        data: data,
        success: function (result) {
            graphs["test"].options.data[0].dataPoints = []

            for (var i = 0; i < result["records"].length; i++) {
                graphs["test"].options.data[0].dataPoints.push(
                    result["records"][i]
                )
            }
            graphs["test"].render()
        },
        error: function (result) {
            console.error(result)
        },
    })
}

// Converts the timeframe button values to actual dates
function convertTime(timeframe) {
    var minDate = "2020-01-01"
    var maxDate = "2099-01-01"

    if (timeframe == "yesterday") {
        minDate = formatDate(-1, 0)
        maxDate = formatDate(0, 0)
    } else if (timeframe == "today") {
        minDate = formatDate(0, 0)
        maxDate = formatDate(0, 0, false)
    } else if (timeframe == "week") {
        minDate = lastSunday()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe == "month") {
        minDate = startMonth()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe == "year") {
        minDate = startYear()
        maxDate = formatDate(0, 0, false)
    } else if (timeframe == "allTime") {
        minDate = "2020-01-01"
        maxDate = formatDate(0, 0, false)
    }

    return { minDate: minDate, maxDate: maxDate }
}

// Formats the days to a format SQL can use
function formatDate(
    plusDay = 0,
    plusMonth = 0,
    startOfDay = true,
    date = new Date()
) {
    var d = new Date(date),
        month = "" + (d.getMonth() + Number(plusMonth) + plusMonth + 1),
        day = "" + (d.getDate() + Number(plusDay)),
        year = d.getFullYear(),
        hour = 23

    if (month.length < 2) {
        month = "0" + month
    }
    if (day.length < 2) {
        day = "0" + day
    }
    if (startOfDay) {
        hour = 00
    }

    time = [year, month, day].join("-")
    time += " " + hour
    return time
}

// Get the previouse sunday
function lastSunday(startOfDay = true) {
    d = new Date()
    var day = d.getDay(),
        diff = d.getDate() - day + (day == 0 ? -6 : 0)
    return formatDate(0, 0, startOfDay, new Date(d.setDate(diff)))
}

// Get the start of the month date
function startMonth(startOfDay = true) {
    d = new Date()
    var month = d.getMonth(),
        diff = d.getMonth() - month + (month == 0 ? -12 : 1)
    return formatDate(0, 0, startOfDay, new Date(d.setDate(diff)))
}

// Get the start of the year date
function startYear(startOfDay = true) {
    d = new Date()
    year = d.getFullYear() + "-01-01"
    return formatDate(0, 0, startOfDay, new Date(year))
}

getGraphData("test", "test", "", "", "string")
