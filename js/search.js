$(".search-box").on("input", async function () {
    let searchTerm = $(this).val()

    if (searchTerm.length >= 2) {
        if ($(".search-results").length <= 0) {
            let searchResults = document.createElement("div")
            searchResults.className = "search-results"

            $(".col-middel").append(searchResults)
        }

		// Remove all the old results
		$(".search-results").empty()
		let data = await getSearchResults(searchTerm)

		// Add the new results
		for (var i = 0; i < data.length; i++) {
			var element = makeElement(data[i])
			$(".search-results").append(element)
		}


    } else if (searchTerm.length == 0) {
        $(".search-results").empty()
        $(".search-results").remove()
    }
})

async function getSearchResults(keyword) {
	return $.ajax({
		url: "/api/system/search.php",
		type: "POST",
		async: true,
		data: { keyword: keyword }
	})
}

// This will make an search item result element
function makeElement(data) {
    let wrapper = document.createElement("div")
    wrapper.className = "search-result"

    let imgHolder = document.createElement("div")
    imgHolder.className = "search-result-img-holder splitter"

    let textHolder = document.createElement("div")
    textHolder.className = "search-result-text-holder splitter"

    let img = document.createElement("img")
    img.className = "search-result-profile-picture"
    img.src = data["img"]

    let name = document.createElement("a")
    name.className = "search-result-name"
    name.innerText = data["name"]

    if (data["type"] == "user") {
        name.href = `/user.php?user=${data["name"]}`
    } else {
        name.href = `/artist.php?artist=${data["name"]}&id=${data["id"]}`
    }

    imgHolder.append(img)
    textHolder.append(name)

    wrapper.append(imgHolder)
    wrapper.append(textHolder)

    return wrapper
}
