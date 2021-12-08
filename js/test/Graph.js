class Graph {
    constructor(
        graphId,
        name,
        title,
        titleX,
        titleY,
    ) {
        // Init basic vars
        this.graphId = graphId
        this.name = name
        this.title = title
        this.titleX = titleX
        this.titleY = titleY
        this.data = []
        this.buttons = []
        this.inputFields = []
        this.filterSettings = {}
        this.dataType = "song"
        this.timeframe = "year"

        // Build the containers needed to place the graphs
        this.buildWrapper()
    }

    buildWrapper() {
        var wrapper = document.createElement("div")
        wrapper.className = "main"
        wrapper.id = this.name + "_main"

        var inputWrapper = document.createElement("div")
        inputWrapper.className = "input-array"
        inputWrapper.id = this.name + "_input_array"

        var buttonWrapper = document.createElement("div")
        buttonWrapper.className = "button-array"
        buttonWrapper.id = this.name + "_array"

        var graphWrapper = document.createElement("div")
        graphWrapper.id = this.name

        wrapper.appendChild(inputWrapper)
        wrapper.appendChild(buttonWrapper)
        wrapper.appendChild(graphWrapper)

        $(".content").append(wrapper)
    }

    async buildGraph() {
        this.addInputFields()
        this.data = await this.getData(this.timeframe, this.filterSettings)
        this.addButtons()

        var dataType = this.dataType
        this.graph = new CanvasJS.Chart(this.name, {
            theme: "dark2",
            title: {
                text: this.title
            },
            axisX: {
                title: this.titleX
            },
            axisY: {
                includeZero: true,
                title: this.titleY
            },
            data: [
                {
                    click: function(data) {
                        goToPage(data, dataType)
                    },
                    type: "column",
                    xValueType: "string",
                    indexLabel: "{y}",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "#5a6767",
                    dataPoints: this.data,
                }
            ]
        })
        this.graph.render()
    }

    // This will get all the input field that are part of a graph
    async getInputFields(graphId) {
        return await $.ajax({
            url: "/api/graph/readInputfield.php",
            type: "GET",
            async: true,
            data: {graphID: graphId}
        })
    }

    // This will add the input fieled to the screen
    async addInputFields() {
        var fields = await this.getInputFields(this.graphId);

        for (let i = 0; i < fields.length; i++) {
            let fd = fields[i]
            var field = new InputField(fd.name, fd.value, fd.type, this.name, this.graphId)
            var fieldElement = await field.create()
            this.readInputField(fieldElement, fd.name)
            this.inputFields.push(fieldElement)

            this.filterSettings[fd.name] = field.settingValue
            $("#" + this.name + "_input_array").append(fieldElement)
        }
    }

    // Reads the data from the input field when the data has changed
    readInputField(inputField, name) {
        var that = this
        $(inputField).on("input", function() {
            var val = $(this).val()
            that.filterSettings[name] = val
            //TODO: Make it save changes in the database
            that.updateGraph()
        })
    }

    // This will get all the buttons needed for this graph
    async getButtons() {
        return await $.ajax({
            url: "/api/element/getTimeframeButtons.php",
            type: "POST",
            async: true
        })
    }

    // Create buttons and add them to the screen
    async addButtons() {
        var buttons = await this.getButtons()
        for (let i = 0; i < buttons.length; i++) {
            // Make the button
            var bd = buttons[i];
            var button = new Button(bd.class, bd.value, "test", bd.innerHTML).create()
            this.onClick(button)

            // Add the button
            this.buttons.push(button)
            $("#" + this.name + "_array").append(button)
        }
    }

    // The onClick event handler for the buttons
	onClick(button) {
        var that = this
		$(button).click(async function() {
            var timeframe = $(this).val()
            that.timeframe = timeframe
            //var test = await that.getData(that.timeframe, that.filterSettings)
            that.updateGraph()
        })
	}

    // Get the data to fill the graph
    async getData(timeframe, filterSettings) {
        let date = convertTime(timeframe);
        var data = {
            minDate: date.minDate,
            maxDate: date.maxDate,
        }

        // Add filter settings to the query
        for (const[key, value] of Object.entries(filterSettings)) {
            data[key] = value
        }

        // Fetch the data
        return await $.ajax({
            url: "/api/played/topSongs.php",
            type: "POST",
            async: true,
            data: data,
        })
    }

    async updateGraph() {
        var data = await this.getData(this.timeframe, this.filterSettings)
        console.log(this.filterSettings)
        this.graph.options.data[0].dataPoints = []

        for (let i = 0; i < data.length; i++) {
            this.graph.options.data[0].dataPoints.push(data[i])
        }
        this.graph.render()
    }
}

function goToPage(data, dataType) {
    if(dataType == "song") {
        let albumId = data.dataPoint.albumID
        let songName = data.dataPoint.label

        location.href = `/album.php?album=${albumId}&song=${songName}`
    } else if (dataType = "artist") {
        let artist = data.dataPoint.label

        location.href = `/artist.php?artist=${artist}`
    }
}

var test = new Graph("2", "test", "test", "test", "test")
test.buildGraph()