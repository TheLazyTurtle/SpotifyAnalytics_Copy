$("#search-box").on("input", function () {
    let searchTerm = $(this).val()

    if (searchTerm.length >= 3) {
        if ($("#search-results").length <= 0) {
            let searchResults = document.createElement("DIV")
            searchResults.id = "search-results"

            $(".col-middel").append(searchResults)
        }

        $.ajax({
            url: "/api/system/search.php",
            type: "POST",
            data: { keyword: searchTerm },
            success: function (data) {
                // Remove all the old results
                $("#search-results").empty()

                // Add the new results
                data = data["records"]
                for (var i = 0; i <= data.length; i++) {
                    var element = makeElement(data[i])
                    $("#search-results").append(element)
                }
            },
        })
    } else if (searchTerm.length == 0) {
        $("#search-results").empty()
        $("#search-results").remove()
    }
})

function makeElement(data) {
    let wrapper = document.createElement("DIV")
    wrapper.className = "search-result"

    let imgHolder = document.createElement("DIV")
    imgHolder.className = "search-result-img-holder splitter"

    let textHolder = document.createElement("DIV")
    textHolder.className = "search-result-text-holder splitter"

    let img = document.createElement("IMG")
    img.className = "search-result-profile-picture"
    img.src = data["img"]

    let name = document.createElement("A")
    name.className = "search-result-name"
    name.innerText = data["name"]

    if (data["type"] == "user") {
        name.href = `/user?user= ${data["name"]}`
    } else {
        name.href = `/artist?artist= ${data["name"]}`
    }

    imgHolder.append(img)
    textHolder.append(name)

    wrapper.append(imgHolder)
    wrapper.append(textHolder)

    return wrapper
}
