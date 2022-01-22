class InputField{
	constructor(inputfieldData, userId = null) {
		this.name = inputfieldData.name
		this.value = inputfieldData.value
		this.type = inputfieldData.type
		this.graphName = inputfieldData.graphName
		this.graphId = inputfieldData.graphId
		this.userId = userId
		this.settingValue
		this.field
		this.setting = inputfieldData.filterSetting
	}

	async create() {
		//var setting = await this.getFilterSettings()
		var field = document.createElement("input")
		field.className = this.graphName + "_input inputField"
		field.type = this.type
		field.placeholder = this.value
		field.id = this.graphName + "_" + this.name

		// Add the filter setting to the field if it has one
		if (this.setting != null) {
			if (this.setting.value) {
				field.value = this.setting["value"]
				this.settingValue = this.setting["value"]
			}
		}

		// If the field is a number than prevent it from going lower than 0
		if (this.type == "number") {
			field.min = 0
		}

		this.field = field
	}
}
