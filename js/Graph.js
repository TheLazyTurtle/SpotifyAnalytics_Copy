class Graph {
    constructor(
		graphData,
        userId = null
    ) {
        // Init basic vars
        this.graphId = graphData.graphId
        this.name = graphData.containerID
        this.title = graphData.title
        this.titleX = graphData.titleX
        this.titleY = graphData.titleY
        this.api = graphData.api
        this.type = graphData.type
        this.xValueType = graphData.xValueType
        this.dataType = graphData.dataType
        this.userId = userId
		this.inputfieldData = graphData.inputfields

        this.data = []
        this.buttons = []
        this.inputFields = []
        this.filterSettings = {}
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
        await this.addInputFields(this.inputfieldData)
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
                    type: this.type,
                    xValueType: this.xValueType,
                    indexLabel: "{y}",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "#5a6767",
                    dataPoints: this.data,
                }
            ]
        })
        this.graph.render()
    }

    // This will add the input fieled to the screen
    async addInputFields(fields) {

        for (let i = 0; i < fields.length; i++) {
            let fd = fields[i]
            var field = new InputField(fd, this.userId)
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

				if (settingName == "song") {
					response(
						autoCompleteData.map(function(item){
							return item["name"] + " - " + item["artist"]
						})
					)
				} else {
					response(autoCompleteData)
				}
            },
            select: async function(element, event) {
                var input = event.item.value

                that.setFilterSetting(settingName, input)
                that.updateGraph();
            },
            change: function() {
                if ($(this).val().length <= 0) {
                    that.setFilterSetting(settingName, "")
                    that.updateGraph()
                }
            }
        })
	}

	async getAutoCompleteData(api, data) {
		return await $.ajax({
			type: "GET",
			url: api,
			async: true,
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

    // Create buttons and add them to the screen
    async addButtons() {
		var buttons = Array.from(Button.timeframeButtons)

        for (let i = 0; i < buttons.length; i++) {
            // Make the button
            var bd = buttons[i];
            var button = new Button(bd.className, bd.value, bd.innerHTML)
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
            userID: this.userId
        }

        // Add filter settings to the query
        for (const[key, value] of Object.entries(filterSettings)) {
			if (key == "song" && value != null) {
				var songArtist = value.split(" - ")
				data["song"] = songArtist[0]
				data["artist"] = songArtist[1]

			} else {
				data[key] = value
			}
        }

        // Fetch the data
        return await $.ajax({
            url: this.api,
            type: "GET",
            async: true,
            data: data,
        })
    }

    // This will update the graph
    async updateGraph() {
        var data = await this.getData(this.timeframe, this.filterSettings)
        this.graph.options.data[0].dataPoints = []

		// Check if data retruned
		if (data != null) {
			for (let i = 0; i < data.length; i++) {
				this.graph.options.data[0].dataPoints.push(data[i])
			}
		}
        this.graph.render()
    }

	// Update the filtersetting
    async setFilterSetting(settingName, value) { 
        this.filterSettings[settingName] = value

        var data = {
            settingname: settingName,
            value: value,
            graphID: this.graphId,
            userID: this.userId
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
