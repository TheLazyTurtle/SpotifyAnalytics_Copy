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
        // TODO: Make these two arrays hold the full object and make the object hold the element. This will make things way easier
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
            await field.create()

            this.readInputField(field.field, fd.name, fd.api)
            this.inputFields.push(field)

            this.filterSettings[fd.name] = field.settingValue
            $("#" + this.name + "_input_array").append(field.field)
        }
    }

	async autoComplete(element, api, settingName) {
        var that = this
        $(element).autocomplete({
            source: async function(request, response) {
                let data = {keyword: request.term, amount: 10}
                var autoCompleteData = await that.getAutoCompleteData(api, data)

                response(autoCompleteData)
            },
            select: function(element, event) {
                var input = event.item.value
                that.setFilterSetting(settingName, input)
                that.updateGraph();
            },
            change: function(element) {
                if ($(this).val().length <= 0) {
                    that.setFilterSetting(settingName, "")
                    that.updateGraph()
                }
            }
        })
	}

	async getAutoCompleteData(api, data) {
		return await $.ajax({
			type: "POST",
			url: api,
			data: data,
		})
	}

    // Reads the data from the input field when the data has changed
    readInputField(inputField, name, api) {
        var that = this
        $(inputField).on("input", function() {
            var val = $(this).val()

            if (api !== null) {
                that.autoComplete(inputField, api, name)
            } else {
                that.setFilterSetting(name, val)
                that.updateGraph()
            }
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
            var button = new Button(bd.class, bd.value, "test", bd.innerHTML)
            button.create()
            this.onClick(button.button)

            // Add the button
            this.buttons.push(button.button)
            $("#" + this.name + "_array").append(button.button)
        }
    }

    // The onClick event handler for the buttons
	onClick(button) {
        var that = this
		$(button).click(async function() {
            var timeframe = $(this).val()
            that.timeframe = timeframe
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

    // This will update the graph
    async updateGraph() {
        var data = await this.getData(this.timeframe, this.filterSettings)
        this.graph.options.data[0].dataPoints = []

        for (let i = 0; i < data.length; i++) {
            this.graph.options.data[0].dataPoints.push(data[i])
        }
        this.graph.render()
    }

    async setFilterSetting(settingName, value) { 
        this.filterSettings[settingName] = value

        var data = {
            settingname: settingName,
            value: value,
            graphID: this.graphId
        }

        $.ajax({
            url: "/api/user/updateFilterSetting.php",
            type: "POST",
            async: true,
            data: data
        })
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