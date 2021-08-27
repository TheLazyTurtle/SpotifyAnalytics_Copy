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
            //showArtistsTopSongs()
            showSongs()
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
    showArtistAlbums(artistID)
}

// This will show the top Artists songs
// TODO: Make it show how many times you have listend to the song compared to the total ex. 143/1023
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
            console.log(result["records"])
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
                        song["userCount"] + " / " + song["count"]
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
    amount.innerHTML = "you / total"
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

// This will that all the albums
// TODO: Get all the singles from an artist as wel. Here we have to keep in mind that for ex. smts from Alan Walker is released in different world and as single. When fetching the singles we have to keep in mind that we don't show the ones that are also in an album but the one's that were released as single
function showArtistAlbums() {
    $.ajax({
        url: "/api/album/search.php",
        type: "POST",
        data: { artistID: artistID },
        success: function (result) {
            console.table(result["records"])

            for (var i = 0; i < result["records"].length; i++) {
                $(".albums-wrapper").append(makeAlbum(result["records"][i]))
            }
        },
        error: function (x, y, z) {
            console.log(x)
            console.log(y)
            console.log(z)
        },
    })
}

function makeAlbum(album) {
    // This will contain everything for the album
    let albumWrapper = document.createElement("div")
    albumWrapper.className = "album-wrapper"
    albumWrapper.id = album["name"]

    // This will hold all the info about the album like the img and the title
    let albumInfoWrapper = document.createElement("div")
    albumInfoWrapper.className = "album-info-wrapper"

    let albumInfoImgWrapper = document.createElement("div")
    albumInfoImgWrapper.className = "album-info-img-wrapper"

    let albumInfoImg = document.createElement("img")
    albumInfoImg.className = "album-info-img"
    albumInfoImg.src = album["img"]
    albumInfoImgWrapper.append(albumInfoImg)

    albumInfoWrapper.append(albumInfoImgWrapper)

    // This will hold all the title info
    let albumInfoTextWrapper = document.createElement("div")
    albumInfoTextWrapper.className = "album-info-text-wrapper"

    // Title
    let albumInfoTitleLink = document.createElement("a")
    albumInfoTitleLink.href = album["url"]
    albumInfoTitleLink.target = "_blank"

    let albumInfoTitle = document.createElement("h1")
    albumInfoTitle.className = "album-info-title"
    albumInfoTitle.innerHTML = album["name"]
    albumInfoTitleLink.append(albumInfoTitle)
    albumInfoTextWrapper.append(albumInfoTitleLink)

    // Artist name
    let albumInfoArtistLink = document.createElement("a")
    albumInfoArtistLink.src = ""

    let albumInfoArtist = document.createElement("p")
    albumInfoArtist.className = "album-info-artist"
    albumInfoArtist.innerHTML = getArtistName()
    albumInfoArtistLink.append(albumInfoArtist)
    albumInfoTextWrapper.append(albumInfoArtistLink)

    albumInfoWrapper.append(albumInfoTextWrapper)

    // Divider
    let divider = document.createElement("hr")
    divider.className = "divider"

    // Add everything to the wrapper
    albumWrapper.append(albumInfoWrapper)
    albumWrapper.append(divider)
    albumWrapper.append(addAlbumSongs(album["songs"]))

    return albumWrapper
}

function addAlbumSongs(songs) {
    let albumSongsWrapper = document.createElement("div")
    albumSongsWrapper.className = "album-songs-wrapper"

    for (var i = 0; i < songs.length; i++) {
        song = songs[i]

        // This will hold all the song info
        let albumSongWrapper = document.createElement("div")
        albumSongWrapper.className = "album-song-wrapper"
        albumSongWrapper.id = song["name"]

        // This will hold the index of the song
        let albumSongIndexWrapper = document.createElement("div")
        albumSongIndexWrapper.className = "album-song-index-wrapper"

        let albumSongIndex = document.createElement("p")
        albumSongIndex.className = "album-song-index"
        albumSongIndex.innerHTML = i + 1 + "."

        albumSongIndexWrapper.append(albumSongIndex)
        albumSongWrapper.append(albumSongIndexWrapper)

        // This will hold the song img
        let albumSongImgWrapper = document.createElement("div")
        albumSongImgWrapper.className = "album-song-img-wrapper"

        let albumSongImg = document.createElement("img")
        albumSongImg.className = "album-song-img"
        albumSongImg.src = song["img"]
        albumSongImgWrapper.append(albumSongImg)
        albumSongWrapper.append(albumSongImgWrapper)

        // This will hold title and artists
        let albumSongText = document.createElement("div")
        albumSongText.className = "album-song-text"

        // Title
        let albumSongTitleWrapper = document.createElement("div")
        albumSongTitleWrapper.className = "album-song-title"

        let albumSongTitleLink = document.createElement("a")
        albumSongTitleLink.href = song["url"]
        albumSongTitleLink.target = "_blank"

        let albumSongTitle = document.createElement("h3")
        albumSongTitle.className = "album-song-title"
        albumSongTitle.innerHTML = song["name"]
        albumSongTitleLink.append(albumSongTitle)
        albumSongTitleWrapper.append(albumSongTitleLink)
        albumSongText.append(albumSongTitleWrapper)

        // Artist
        // TODO: This should be a loop to go trough all the artists
        let albumSongArtistsWrapper = document.createElement("div")
        albumSongArtistsWrapper.className = "album-song-artists-wrapper"

        for (var j = 0; j < song["artists"].length; j++) {
            let albumSongArtistLink = document.createElement("a")
            albumSongArtistLink.className = "album-song-artist"
            albumSongArtistLink.href = song["artists"][j]["url"]
            albumSongArtistLink.target = "_blank"

            let albumSongArtist = document.createElement("p")
            albumSongArtist.innerHTML = song["artists"][j]["name"] + ", "
            albumSongArtist.className = "album-song-artist"

            albumSongArtistLink.append(albumSongArtist)
            albumSongArtistsWrapper.append(albumSongArtistLink)
        }

        albumSongText.append(albumSongArtistsWrapper)
        albumSongWrapper.append(albumSongText)

        // This will hold the preview
        let albumSongPreviewWrapper = document.createElement("div")
        albumSongPreviewWrapper.className = "album-song-preview-wrapper"

        let albumSongPreview = document.createElement("audio")
        albumSongPreview.src = song["preview"]
        albumSongPreview.controls = "controls"
        albumSongPreviewWrapper.append(albumSongPreview)
        albumSongWrapper.append(albumSongPreviewWrapper)

        albumSongsWrapper.append(albumSongWrapper)
    }

    return albumSongsWrapper
}
