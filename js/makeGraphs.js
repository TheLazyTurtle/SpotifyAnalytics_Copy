// This will hold all the graphs
let graphs = []

async function getGraphs() {
    return $.ajax({
        url: "/api/graph/read.php",
        type: "GET",
        async: true
    })
}

async function buildGraphs() {
    let graphsData = await getGraphs();
    console.table(graphsData)

    for (let i = 0; i < graphsData.length; i++) {
        let gd = graphsData[i];
        graphs[gd.title] = new Graph(gd.id, gd.containerID, gd.title, gd.titleX, gd.titleY, gd.api, gd.type, gd.xValueType)
        graphs[gd.title].buildGraph()
    }
}

buildGraphs()
