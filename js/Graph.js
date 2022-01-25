class Graph {
    constructor(
		graphData,
        userId = null
    ) {
        // Init basic vars
        this.graphId = graphData.id
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
		this.relative = false

        // Build the containers needed to place the graphs
        this.buildWrapper()
    }

	// Make the wrapper where all the graph elements will be placed in
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

	// Make the actual graph
    async buildGraph() {
        await this.addInputFields(this.inputfieldData)
        this.data = await this.getData(this.timeframe, this.filterSettings)
        this.addButtons()

        var dataType = this.dataType
        this.graph = new CanvasJS.Chart(this.name, {
            theme: "dark2",
            title: {
                text: this.makeTitle()
            },
            axisX: {
                title: this.titleX,
            },
            axisY: {
                includeZero: true,
                title: this.titleY,
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
            var field = new InputField(fd, this.name, this.graphId, this.userId)
            await field.create()

            this.readInputField(field.field, fd.name, fd.api)
            this.inputFields.push(field)

            this.filterSettings[fd.name] = field.settingValue
            $("#" + this.name + "_input_array").append(field.field)
        }
    }

	// Creates the auto complete menu
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
            select: async function(_, event) {
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

	// This gets the data to fill the autocomplete list
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
            Button.timeframeButtons[i].create()
			const button = Button.timeframeButtons[i].button
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
			if (timeframe != "relative") {
				that.timeframe = timeframe
				that.updateGraph()
			} else {
				that.relative = that.relative ? false : true 
				
				if (that.name == "all_Songs_Played") {
					await that.changeFilterSettings()
				}

				that.changeRelativeButtonText(button)
				that.updateGraph()
			}
        })
	}

	// This changes the text of the relative button when you press it
	// TODO: See if it makes more sense when it the naming is the other way around
	changeRelativeButtonText(button) {
		const newValue = button.innerHTML == "Absolute" ? "Relative" : "Absolute"
		
		button.innerHTML = newValue
	}

	// Change the filter settings when switching between relative and absolute
	async changeFilterSettings() {
		for (const key in this.filterSettings) {
			const newFilterSetting = await this.getFilterSetting(key)
			const newFilterSettingValue = newFilterSetting[0]["value"]
			this.filterSettings[key] = newFilterSettingValue

			$(`#${this.name}_${key}`)[0].value = newFilterSettingValue
		}
	}

    // Get the data to fill the graph
    async getData(timeframe, filterSettings) {
        let date = convertTime(timeframe);
        var data = {
            minDate: date.minDate,
            maxDate: date.maxDate,
            userID: this.userId,
			relative: this.relative
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
		this.graph.title.options.text = this.makeTitle()

		// Set the data
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

	// This will update the graphs title
	makeTitle() {
		return this.relative ? this.title + " (in minutes)" : this.title + " (in times)"
	}

	// This will update the filter settings
    async setFilterSetting(settingName, value) { 
        this.filterSettings[settingName] = value

        var data = {
            settingname: settingName,
            value: value,
            graphID: this.graphId,
            userID: this.userId,
			relative: this.relative
        }

        $.ajax({
            url: "/api/user/updateFilterSetting.php",
            type: "POST",
            async: true,
            data: data
        })
    }
 
	// Get the filtersetting for when switching between relative and not
	async getFilterSetting(filterSettingName) {
		return $.ajax({
			url: "/api/user/readOneFilterSetting.php",
			type: "GET",
			async: true,
			data: {
				name: filterSettingName,
				graphID: this.graphId,
				relative: this.relative
			}
		})
	}
}

// Sends you to the artists page or the album page when you click on a graph item
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
