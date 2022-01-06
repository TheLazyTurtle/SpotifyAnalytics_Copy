// This will hold all the graphs
let graphs = []

async function getGraphs() {
    return $.ajax({
        url: "/api/graph/read.php",
        type: "GET",
        async: true
    })
}

async function buildGraphs(userId = null) {
    let graphsData = await getGraphs();
	Button.getTimeframeButtons()

    for (let i = 0; i < graphsData.length; i++) {
        let gd = graphsData[i];
        graphs[gd.title] = new Graph(gd, userId)
        graphs[gd.title].buildGraph()
    }
}
