class Button {
	static timeframeButtons = []

	constructor(className, value, innerHTML) {
		this.className = className
		this.value = value
		this.innerHTML = innerHTML
		this.button
	}

	create() {
		let button = document.createElement("button")
		button.className = this.className
		button.value = this.value
		button.innerHTML = this.innerHTML

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
					const button = result[i]
					Button.timeframeButtons.push(new Button(button.class, button.value, button.innerHTML))
				}
			}
		})
	}
}
