class Button {
	static timeframeButtons = []

	constructor(buttonData) {
		this.className = buttonData.class
		this.value = buttonData.value
		this.innerHTML = buttonData.innerHTML
		this.id = buttonData.id
		this.button = null
	}

	create() {
		let button = document.createElement("button")
		button.className = this.className
		button.value = this.value
		button.innerHTML = this.innerHTML
		
		if (this.id != null) {
			button.id = this.id
		}

		this.button = button
	}

	// This will get all the timeframe buttons
	static getTimeframeButtons() {
		if (this.timeframeButtons.length > 0) return

		$.ajax({
			url: "/api/element/getTimeframeButtons.php",
			type: "get",
			success: function(result) {
				for (let i = 0; i < result.length; i++) {
					const buttonData = result[i]
					Button.timeframeButtons.push(
						new Button(buttonData)
					)
				}
			}
		})
	}
}
