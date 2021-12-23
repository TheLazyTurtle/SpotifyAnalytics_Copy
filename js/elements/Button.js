class Button {
	constructor(className, value, id, innerHTML) {
		this.className = className
		this.value = value
		this.id = id
		this.innerHTML = innerHTML
		this.button
	}

	create() {
		let button = document.createElement("button")
		button.className = this.className
		button.id = this.id
		button.value = this.value
		button.innerHTML = this.innerHTML

		this.button = button
	}
}