// This will run on page load
$(document).ready(function () {
    getAlbumInfo()
})

// This gets the songsID from the url
function getAlbumID() {
    const addr = window.location.search
    const params = new URLSearchParams(addr)
    const albumID = params.get("album")
    songName = params.get("song")

    return albumID
}
