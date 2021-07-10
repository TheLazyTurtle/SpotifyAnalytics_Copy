// Get the userID form the session
var userID = '<%= Session["userID"] %>'

// This will contain all the graphs
var graphs = []

// TODO: Refactor these things so that there is not a huge list of params passed
// ... in but just an object
// This will get all the graphs from the database
function getGraphs() {
    $.ajax({
        url: "/api/graph/read.php",
        type: "POST",
        success: function (result) {
            for (var i = 0; i <= result["records"].length; i++) {
                var res = result["records"][i]
                var containerID = res["containerID"]
                var title = res["title"]
                var titleX = res["titleX"]
                var titleY = res["titleY"]
                var xValueType = res["xValueType"]
                var api = res["api"]
                var type = res["type"]
                var graphID = res["id"]

                getFilterSettings(
                    containerID,
                    title,
                    titleX,
                    titleY,
                    xValueType,
                    api,
                    type,
                    graphID
                )
            }
        },
    })
}

// This will get all the settings from the user for the graph thats passed in
function getFilterSettings(
    containerID,
    title,
    titleX,
    titleY,
    xValueType,
    api,
    type,
    graphID
) {
    $.ajax({
        url: "/api/user/readOneFilterSetting.php",
        type: "POST",
        data: { graphID: graphID },
        success: function (results) {
            var filterSettings = {}

            for (var i = 0; i < results["records"].length; i++) {
                var res = results["records"][i]

                filterSettings[res["name"]] = res["value"]
            }

            getInputFields(
                containerID,
                title,
                titleX,
                titleY,
                xValueType,
                api,
                filterSettings,
                type,
                graphID
            )
        },
    })
}

// This will get the input fields that are part of the graph
function getInputFields(
    containerID,
    title,
    titleX,
    titleY,
    xValueType,
    api,
    filterSettings,
    type,
    graphID
) {
    $.ajax({
        url: "/api/graph/readInputfield.php",
        data: { graphID: graphID },
        success: function (results) {
            var inputFields = {}

            for (var i = 0; i < results["records"].length; i++) {
                res = results["records"][i]
                inputFields[i] = {}
                inputFields[i]["index"] = i
                inputFields[i]["name"] = res["name"]
                inputFields[i]["placeholder"] = res["value"]
                inputFields[i]["type"] = res["type"]
                inputFields[i]["value"] = filterSettings[res["name"]]
            }

            getGraphData(
                graphID,
                containerID,
                title,
                titleX,
                titleY,
                xValueType,
                api,
                filterSettings,
                inputFields,
                type
            )
        },
    })
}

getGraphs()
