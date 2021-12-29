class InputField{
	constructor(name, value, type, graphName, graphId, userId = null) {
		this.name = name
		this.value = value
		this.type = type
		this.graphName = graphName
		this.graphId = graphId
		this.userId = userId
		this.settingValue
		this.field
	}

	async create() {
		var setting = await this.getFilterSettings()
		var field = document.createElement("input")
		field.className = this.graphName + "_input inputField"
		field.type = this.type
		field.placeholder = this.value
		field.id = this.graphName + "_" + this.name

		// Add the filter setting to the field if it has one
		if (setting[0].value) {
			field.value = setting[0].value
			this.settingValue = setting[0].value
		}

		// If the field is a number than prevent it from going lower than 0
		if (this.type == "number") {
			field.min = 0
		}

		this.field = field
	}

	async getFilterSettings() {
		var data = await $.ajax({
			url: "/api/user/readOneFilterSetting.php",
			type: "GET",
			async: true,
			data: {
				graphID: this.graphId, 
				name: this.name,
				userID: this.userId
			}
		})

		// If request is from user page than set reset the values
		if (this.userId != null) {
			for (var i = 0; i < data.length; i++) {
				data[i]["value"] = ""
			}
		}

		return data
	}
}
