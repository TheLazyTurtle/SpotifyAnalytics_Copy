let selectors = ["songs", "graphs"]
let artistID = false

// This will load on page load
$(document).ready(function () {
    getArtistInfo()
})

// Get the artist name from the url
function getArtistName() {
    let addr = window.location.search
    let params = new URLSearchParams(addr)
    let artist = params.get("artist")

    return artist
}

// Get all the info from the artist
function getArtistInfo() {
    let artist = getArtistName()

    $.ajax({
        url: "/api/artist/read_one.php",
        type: "POST",
        data: { artist: artist },
        success: function (result) {
            setArtistInfo(result)
            showArtistsTopSongs()
        },
        error: function (jqXHR, textStatus, error) {
            // TODO: show a artist not found thingy
        },
    })
}

// Set basic things from the artist on the page like:
// * url to spotify page
// * artist img
// * name
function setArtistInfo(result) {
    // This sets the img
    $(".artist-info-img").attr("src", result["img"])

    // This sets the name
    $(".artist-info-text").text(result["name"])

    // This sets the artistID
    artistID = result["artistID"]

    // This will add the link to go to the official spotify page of the artist
    $(".artist-link").attr("href", result["url"])
}

// This will show do everything to show the songs thingys
function showSongs() {
    showArtistsTopSongs()
}

// This will show the top Artists songs
function showArtistsTopSongs() {
    // Make a table where all the songs will be placed in
    let table = document.createElement("table")
    table.className = "top-songs"

    table.append(makeTableHeader())

    // Get the top songs
    $.ajax({
        url: "/api/artist/topSongs.php",
        type: "POST",
        data: { artistID: artistID },
        success: function (result) {
            topSongs = result["records"]
            for (var i = 0; i < topSongs.length; i++) {
                let song = topSongs[i]

                let row = document.createElement("tr")
                row.id = song["title"]

                // Add preview
                row.append(
                    addElement(
                        "audio",
                        "top-song-preview",
                        "audio",
                        song["preview"]
                    )
                )

                // Add img
                row.append(
                    addElement("img", "top-song-img", "src", song["img"])
                )

                // Add title (with link to official spotify)
                row.append(
                    addElement(
                        "p",
                        "top-song-title",
                        "innerHTML",
                        song["title"],
                        song["url"]
                    )
                )

                // Add the count
                row.append(
                    addElement(
                        "p",
                        "top-song-amount",
                        "innerHTML",
                        song["count"]
                    )
                )

                // Only show the first 4 items and a show more button
                if (i >= 5) {
                    row.className = "top-song-hidden"
                }

                table.append(row)
            }
        },
    })

    $(".top-songs-wrapper").append(table)

    showMoreSongs()
}

// This will make the header for the top songs table
function makeTableHeader() {
    let row = document.createElement("tr")
    row.className = "top-song-header"

    let preview = document.createElement("th")
    preview.className = "top-song-header-preview"
    preview.innerHTML = "preview"
    row.append(preview)

    let img = document.createElement("th")
    img.className = "top-song-header-img"
    img.innerHTML = "image"
    row.append(img)

    let title = document.createElement("th")
    title.className = "top-song-header-title"
    title.innerHTML = "title"
    row.append(title)

    let amount = document.createElement("th")
    amount.className = "top-song-header-amount"
    amount.innerHTML = "amount"
    row.append(amount)

    return row
}

// This will add an top song row to the table of top songs
function addElement(elementType, className, contentType, value, url = null) {
    const tdClass = className.replace("top-song-", "")

    let cell = document.createElement("td")
    cell.className = tdClass

    let element = document.createElement(elementType)
    element.className = className

    if (contentType == "innerHTML") {
        element.innerHTML = value

        // If there is a url give add it to the text
        if (url !== null) {
            let link = document.createElement("a")
            link.href = url
            link.target = "_blank"

            link.append(element)
            cell.append(link)
            return cell
        }
    } else if (contentType == "src") {
        element.src = value
    } else if (contentType == "audio") {
        element.controls = "controls"
        element.src = value
        element.type = "audio/mpeg"
    }
    cell.append(element)

    return cell
}

// This will make the show more button
function showMoreSongs() {
    // Add a show more button
    let showMore = document.createElement("p")
    showMore.innerHTML = "Show more"
    showMore.id = "top-songs-show-more"
    $(".top-songs-wrapper").append(showMore)

    $("#top-songs-show-more").click(function () {
        let curText = $(this)[0].innerHTML

        // Switch the text on the button when pressed
        if (curText === "Show more") {
            $(this).text("Show less")

            $(".top-song-hidden").each(function () {
                $(this)[0].className = "top-song-display"
            })
        } else {
            $(this).text("Show more")

            $(".top-song-display").each(function () {
                $(this)[0].className = "top-song-hidden"
            })
        }
    })
}

// This will check if a selector button is pressed
function getButtonPressed() {
    for (var i = 0; i < selectors.length; i++) {
        $("#" + selectors[i]).click(function () {
            let button = $(this)
            let buttonID = button[0].attributes[1].nodeValue

            // Might have to do this differently where it will use the array to switch or something like that
            switch (buttonID) {
                case "graphs":
                //getGraphs()
                default:
                    showSongs()
            }
        })
    }
}
