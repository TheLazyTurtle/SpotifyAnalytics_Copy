var timeframes = [
    // id && value / display name
    ["yesterday", "Yesterday"],
    ["today", "Today"],
    ["week", "This week"],
    ["month", "This month"],
    ["year", "This year"],
    ["allTime", "All time"],
]

// Make the array of buttons that are used to select the timeframe
function buttonArray(mainDiv, containerID) {
    var id = containerID + "-array"
    $(mainDiv).append("<div class='button-array' id=" + id + "></div>")

    for (var i = 0; i < timeframes.length; i++) {
        var button = document.createElement("button")
        button.className = containerID + "-button btn timeFrameBtn"
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
