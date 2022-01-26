// This will run on page load
$(document).ready(function () {
    getAlbumInfo()
})

// This will get one album of an artist
async function getAlbumInfo() {
	// This gets the album and the data needed for the album
	const albumData = getAlbumData()
	const data = await Album.getAlbum(albumData.albumID, albumData.songName)

	// This adds the album to the page
	$(".albums-wrapper").append(data.makeAlbumElement())

	let selectedSong = document.getElementById(albumData.songName)
	selectedSong.scrollIntoView()
}

// This gets the album data from the url
function getAlbumData() {
    const addr = window.location.search
    const params = new URLSearchParams(addr)
    const albumID = params.get("album")
    const songName = params.get("song")

	return {
		albumID: albumID,
		songName: songName
	}
}

