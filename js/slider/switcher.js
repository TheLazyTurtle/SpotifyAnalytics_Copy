var sliderItems = []

sliderItems["topSongs"] = {
    api: "/api/played/topSongs.php",
    id: "#topSong",
    text: `Top song {timeFrame}: {name} - {amount}`,
    dataType1: "y",
    dataType2: "label",
}

sliderItems["topArtist"] = {
    api: "/api/artist/topArtist.php",
    id: "#topArtist",
    text: `Top artist {timeFrame}: {name} - {amount}`,
    dataType1: "y",
    dataType2: "label",
}

sliderItems["timelistend"] = {
    api: "/api/played/timeListend.php",
    id: "#timeListend",
    text: `Time listend {timeFrame}: {amount}`,
    dataType1: "y",
}

sliderItems["amountSongs"] = {
    api: "/api/played/amountSongs.php",
    id: "#amountSongs",
    text: `Total songs listend {timeFrame}: {amount}`,
    dataType1: "y",
}

sliderItems["amountNewSongs"] = {
    api: "/api/played/amountNewSongs.php",
    id: "#amountNewSongs",
    text: `New songs {timeFrame}: {amount}`,
    dataType1: "y",
}

// On document load, load the slider in
$(document).ready(function () {
    // Makes sure to load default info on page load
    var dates = convertTime("today")
    for (let i in sliderItems) {
        fetchSliderData(dates, sliderItems[i], "today", i)
    }

    $("#yesterday").click(function () {
        var dates = convertTime("yesterday")
        for (let i in sliderItems) {
            fetchSliderData(dates, sliderItems[i], "yesterday", i)
        }
    })
    $("#day").click(function () {
        var dates = convertTime("today")
        for (let i in sliderItems) {
            fetchSliderData(dates, sliderItems[i], "today", i)
        }
    })
    $("#week").click(function () {
        var dates = convertTime("week")
        for (let i in sliderItems) {
            fetchSliderData(dates, sliderItems[i], "week", i)
        }
    })
    $("#month").click(function () {
        var dates = convertTime("month")
        for (let i in sliderItems) {
            fetchSliderData(dates, sliderItems[i], "month", i)
        }
    })
    $("#year").click(function () {
        var dates = convertTime("year")
        for (let i in sliderItems) {
            fetchSliderData(dates, sliderItems[i], "year", i)
        }
    })
    $("#allTime").click(function () {
        var dates = convertTime("allTime")
        for (let i in sliderItems) {
            fetchSliderData(dates, sliderItems[i], "allTime", i)
        }
    })
})

// Fetch the data to fill the slider with
function fetchSliderData(dates, sliderInfo, timeFrame, type) {
    var api = sliderInfo.api
    var id = sliderInfo.id
    var minDate = dates.minDate
    var maxDate = dates.maxDate
    var userId = null

    $.ajax({
        type: "POST",
        url: api,
        data: { minDate: minDate, maxDate: maxDate, amount: 1, userID: userId},
        success: function (data) {
            // If the result contains time in ms convert it to usable date (hh:mm:ss)
			if ("totalTime" in data[0]) {
				data[0].y = msToTime(
					data[0].y
				)
			}

            setSliderItem(id, data[0], timeFrame, type, sliderInfo)
        },
        error: {
            function() {
                setSliderItem(id, "error", timeFrame, type, sliderInfo)
            },
        },
    })
}

// Put the received data in the slider
function setSliderItem(id, data, timeFrame, type, sliderInfo) {
    if (data == "error") {
        $(id).html("No data found")
    } else {
        var imgID = id + "Img"
        var string = sliderInfo.text
            .replace("{timeFrame}", timeFrame)
            .replace("{amount}", data[sliderInfo.dataType1])
            .replace("{name}", data[sliderInfo.dataType2])

        // Set the text
        $(id).html(string)

        // Set the img
        $(imgID).attr("src", data["img"])
    }
}
