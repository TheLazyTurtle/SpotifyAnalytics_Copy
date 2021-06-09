var sliderItems = []

sliderItems["topSongs"] = {
    api: "/api/song/topSongs.php",
    id: "#topSongs",
}

sliderItems["topArtist"] = {
    api: "/api/artist/topArtist.php",
    id: "#topArtist",
}

sliderItems["timelistend"] = {
    api: "/api/song/timeListend.php",
    id: "#timeListend",
}

sliderItems["amountSongs"] = {
    api: "/api/song/amountSongs.php",
    id: "#amountSongs",
}

sliderItems["amountNewSongs"] = {
    api: "/api/song/amountNewSongs.php",
    id: "#amountNewSongs",
}

$(document).ready(function () {
    $("#day").click(function () {
        var dates = convertTime("today")
        for (let i in sliderItems) {
            fetchSliderData(dates, sliderItems[i], "today", i)
        }
    })
})

function fetchSliderData(dates, sliderInfo, timeFrame, type) {
    var api = sliderInfo.api
    var id = sliderInfo.id
    var minDate = dates.minDate
    var maxDate = dates.maxDate

    $.ajax({
        type: "GET",
        url: api,
        data: { minDate: minDate, maxDate: maxDate, amount: 1 },
        success: function (data) {
            setSliderItem(id, data, timeFrame, type)
        },
    })
}

function setSliderItem(id, data, timeFrame, type) {
    var imgId = id + "Img"
    console.log(data)

    $(id).html(
        type +
            " " +
            timeFrame +
            ": " +
            data["records"]["label"] +
            " - " +
            data["records"]["y"]
    )
}
