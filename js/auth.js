const store = {}
store.setJWT = function (data) {
	this.JWT = data
}

store.getJWT = function () {
	return this.JWT
}