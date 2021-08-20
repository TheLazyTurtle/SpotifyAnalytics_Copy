// This will get one album of an artist
function getAlbumInfo() {
    const albumID = getAlbumID()

    $.ajax({
        url: "/api/album/readOne.php",
        type: "POST",
        data: { albumID: albumID },
        success: function (result) {
            $(".albums-wrapper").append(makeAlbum(result["records"][0], "open"))
        },
        error: function (error) {
            console.log(error)
        },
    })
}

// This will show all albums and artist has
function showArtistAlbums(artistID) {
    $.ajax({
        url: "/api/album/search.php",
        type: "POST",
        data: { artistID: artistID },
        success: function (result) {
            for (var i = 0; i < result["records"].length; i++) {
                $(".albums-wrapper").append(
                    makeAlbum(result["records"][i], "closed")
                )
            }
        },
        error: function (x, y, z) {
            console.log(x)
            console.log(y)
            console.log(z)
        },
    })
}

// This will make the base of the album like title and img
function makeAlbum(album, state) {
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

    if ("primaryArtist" in album) {
        albumInfoArtist.innerHTML = album["primaryArtist"]["name"]
    } else {
        albumInfoArtist.innerHTML = getArtistName()
    }

    albumInfoArtistLink.append(albumInfoArtist)
    albumInfoTextWrapper.append(albumInfoArtistLink)

    albumInfoWrapper.append(albumInfoTextWrapper)

    // Divider
    let divider = document.createElement("hr")
    divider.className = "divider"

    // Add everything to the wrapper
    albumWrapper.append(albumInfoWrapper)
    albumWrapper.append(divider)
    albumWrapper.append(addAlbumSongs(album["songs"], state))

    // Add the folding thingy
    albumWrapper.append(addExpander())

    return albumWrapper
}

// This will add the songs of the album to the base
function addAlbumSongs(songs, state) {
    let albumSongsWrapper = document.createElement("div")
    albumSongsWrapper.className = "album-songs-wrapper " + state

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

// Button to make albums fold and expand
function addExpander() {
    let expanderWrapper = document.createElement("div")
    expanderWrapper.className = "expander-wrapper"

    let expander = document.createElement("div")
    expander.className = "expander"
    expander.innerHTML = '<i class="fas fa-sort-down"></i>'

    expanderWrapper.append(expander)
    expanderAndFolder(expanderWrapper)

    return expanderWrapper
}

// This will expand and fold the albums
function expanderAndFolder(expander) {
    $(expander).click(function () {
        let expDiv = $(this)[0].parentElement.children[2]
        let iconParent = $(this)[0].parentElement.children[3].children[0]

        if (expDiv.className.includes("closed")) {
            expDiv.className = expDiv.className.replace("closed", "open")
            iconParent.innerHTML = '<i class="fas fa-sort-up"></i>'
        } else {
            expDiv.className = expDiv.className.replace("open", "closed")
            iconParent.innerHTML = '<i class="fas fa-sort-down"></i>'
        }
    })
}
