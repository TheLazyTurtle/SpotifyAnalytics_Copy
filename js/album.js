class Album {
	static State = {
		open: "open",
		closed: "closed"
	}

	constructor(albumData, state) {

		this.albumID = albumData.albumID
		this.img = albumData.img
		this.name = albumData.name
		this.primaryArtist = albumData.primaryArtist 
		this.songs = albumData.songs
		this.url = albumData.url

		this.state = state 
		this.songElements = []
	}

	makeAlbumElement() {
		// This will contain everything for the album
		let albumWrapper = document.createElement("div")
		albumWrapper.className = "album-wrapper"
		albumWrapper.id = this.name 

		albumWrapper.append(this.makeAlbumInfoElement())
		albumWrapper.append(this.makeDivider())

		albumWrapper.append(this.makeSongWrapper())
		albumWrapper.append(this.makeExpander())

		return albumWrapper
	}

	makeAlbumInfoElement() {
		// This will hold all the info about the album like the img and the title
		let infoWrapper = document.createElement("div")
		infoWrapper.className = "album-info-wrapper"

		// Add the image element
		infoWrapper.append(this.makeImageElement())
		infoWrapper.append(this.makeTextWrapperElement())

		return infoWrapper
	}

	// This builds the image element
	makeImageElement() {
		// The wrapper element holding the image
		let imgWrapper = document.createElement("div")
		imgWrapper.className = "album-info-img-wrapper"

		// The image it self
		let image = document.createElement("img")
		image.className = "album-info-img"
		image.src = this.img 
		imgWrapper.append(image)

		return imgWrapper
	}

	// This builds the title element
	makeTextWrapperElement() {
		// This will hold all the title info
		let infoTextWrapper = document.createElement("div")
		infoTextWrapper.className = "album-info-text-wrapper"
		infoTextWrapper.append(this.makeTitleElement())
		infoTextWrapper.append(this.makeArtistTextElement())

		return infoTextWrapper
	}

	// This builds the title element that will be placed in the text wrapper
	makeTitleElement() {
		// Title
		let titleLink = document.createElement("a")

		// Check if the url has to direct internaly or externaly
		if (this.state == Album.State.open) {
			titleLink.href = this.url 
			titleLink.target = "_blank"
		} else {
			titleLink.href = "/album.php?album=" + this.albumID
		}

		// Make the actual title
		let title = document.createElement("h1")
		title.className = "album-info-title"
		title.innerHTML = this.name 

		titleLink.append(title)
		return titleLink
	}

	makeArtistTextElement() {
		// Make a link for the artist name
		let artistLink = document.createElement("a")
		artistLink.src = ""

		// Make actual display text for the artist
		let artistText = document.createElement("p")
		artistText.className = "album-info-artist"

		// If we do have a primary artist for the album set the name else get the name
		if (this.primaryArtist != null) {
			artistText.innerHTML = this.primaryArtist.name 
		} else {
			artistText.innerHTML = getArtistName()
		}

		artistLink.append(artistText)
		return artistLink
	}

	// This will build a divider to separate the header from the body of the album 
	makeDivider() {
		// Divider
		let divider = document.createElement("hr")
		divider.className = "album-divider"

		return divider
	}

	// This will make the expander button which will allow a user to open and close albums
	makeExpander() {
		let expanderWrapper = document.createElement("div")
		expanderWrapper.className = "expander-wrapper"

		let expander = document.createElement("div")
		expander.className = "expander"
		expander.innerHTML = '<i class="fas fa-sort-down"></i>'

		expanderWrapper.append(expander)
		this.onExpanderClick(expanderWrapper)

		return expanderWrapper
	}

	// This will expand and fold the albums
	onExpanderClick(expander) {
		let that = this

		// Click event handler
		$(expander).click(function () {
			let expandingDiv = $(this)[0].parentElement.children[2]
			let iconParent = $(this)[0].parentElement.children[3].children[0]

			// Change the state of the album
			that.state = that.state == Album.State.open ? Album.State.closed : Album.State.open

			// Add all the songs to the album
			that.loadSongElements(expandingDiv)

			// Change the expander icon based on its state
			if (that.state == Album.State.closed) {
				expandingDiv.className = expandingDiv.className.replace("closed", "open")
				iconParent.innerHTML = '<i class="fas fa-sort-up"></i>'
			} else {
				expandingDiv.className = expandingDiv.className.replace("open", "closed")
				iconParent.innerHTML = '<i class="fas fa-sort-down"></i>'
			}
		})
	}

	// SONG STUFF
	// This makes the wrapper to hold all the songs of the album
	makeSongWrapper() {
		let albumSongsWrapper = document.createElement("div")
		albumSongsWrapper.className = `album-songs-wrapper ${this.state}`

		// When an album is already open directly insert all songs
		if (this.state = Album.State.open) {
			console.log(albumSongsWrapper)
			this.loadSongElements(albumSongsWrapper)
		} 

		return albumSongsWrapper
	}

	// This will generate all song items for the album
	loadSongElements(parentElement) {
		if (this.songElements.length > 0) return

		for (let songIndex in this.songs) {
			this.songElements.push(this.makeSongElement(this.songs[songIndex], parseInt(songIndex) + 1))
			$(parentElement).append(this.songElements[songIndex])
		}
	}

	// This makes a song element in the album
	makeSongElement(songData, placeInAlbum) {
		let songWrapper = document.createElement("div")
		songWrapper.className = "album-song-wrapper"
		songWrapper.id = songData.name

		songWrapper.append(this.makeSongIndexElement(placeInAlbum))
		songWrapper.append(this.makeSongImageElement())
		songWrapper.append(this.makeSongTextElement(songData.url, songData.name, songData.artists))
		songWrapper.append(this.makeSongPreviewElement(songData.preview))

		return songWrapper
	}

	// This will add the index of the song in the album
	makeSongIndexElement(index) {
		let indexWrapper = document.createElement("div")
		indexWrapper.className = "album-song-index-wrapper"

		let indexElement = document.createElement("p")
		indexElement.className = "album-song-index"
		indexElement.innerHTML = `${index}.`

		indexWrapper.append(indexElement)
		return indexWrapper
	}

	// This will make the element to hold the image of the song
	makeSongImageElement() {
		let imageWrapper = document.createElement("div")
		imageWrapper.className = "album-song-img-wrapper"

		let image = document.createElement("img")
		image.className = "album-song-img"
		image.src = this.img

		imageWrapper.append(image)
		return imageWrapper
	}

	// This makes the text wrapper for the title
	makeSongTextElement(url, name, artists) {
		let textWrapper = document.createElement("div")
		textWrapper.className = "album-song-text"

		// Title
		let titleWrapper = document.createElement("div")
		titleWrapper.className = "album-song-title"

		let titleLink = document.createElement("a")
		titleLink.href = url
		titleLink.target = "_blank"

		let title = document.createElement("h3")
		title.className = "album-song-title"
		title.innerHTML = name

		titleLink.append(title)
		textWrapper.append(titleLink)
		textWrapper.append(this.makeArtistElements(artists))

		return textWrapper
	}

	// This will add all the artists to the song text wrapper
	makeArtistElements(artists) {
		let artistWrapper = document.createElement("div")
		artistWrapper.className = "album-song-artists-wrapper"

		for (let artistIndex in artists) {
			artistWrapper.append(this.makeArtistElement(artists[artistIndex]))
		}

		return artistWrapper
	}

	// This makes an artist element
	makeArtistElement(artistData) {
		let artistLink = document.createElement("a")
		artistLink.className = "album-song-artist"
		artistLink.href = `/artist.php?artist=${artistData.name}`

		let artist = document.createElement("p")
		artist.className = "album-song-artist"
		artist.innerHTML = `${artistData.name},`

		artistLink.append(artist)
		return artistLink
	}

	// This makes the element where the preview bar will be visible
	makeSongPreviewElement(previewUrl) {
		let previewWrapper = document.createElement("div")
		previewWrapper.className = "album-song-preview-wrapper"

		let preview = document.createElement("audio")
		preview.src = previewUrl
		preview.controls = "controls"

		previewWrapper.append(preview)
		return previewWrapper
	}
 
	static async getAlbum(albumID, songName) {
		const data = await this.getAlbumData(albumID, songName)
		return new Album(data[0], Album.State.open)
	}

	static async getAlbumData(albumID, songName) {
		return $.ajax({
			url: "/api/album/readOne.php",
			type: "GET",
			async: true,
			data: {
				albumID: albumID,
				songName: songName
			}, 
		})
	}

	static async loadArtistAlbums(artistID) {
		const albums = await Album.getArtistAlbums(artistID)
		for (let albumIndex in albums) {
			const album = new Album(albums[albumIndex], Album.State.closed)
			$(".albums-wrapper").append(album.makeAlbumElement())
		}
	}

	static async getArtistAlbums(artistID) {
		return $.ajax({
			url: "/api/album/search.php",
			type: "GET",
			async: true,
			data: { artistID: artistID },
		})
	}
}
