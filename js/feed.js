$(document).ready(function () {
    $.ajax({
        url: "/api/memory/read.php",
        type: "post",
        success: function (data) {
            for (let i = 0; i < data.length; i++) {
                $(".feed").append(new Post(data[i]).makePost())
            }
        },
        error: function (error) {
            console.error(error)
        },
    })
})

class Post {
    constructor(data) {
        this.user = data["poster"]
        this.songs = data["songs"]
        this.post = data
        this.amountOfLikes = parseInt(data["likes"])
        this.liked = data["userHasLikedPost"]
        this.follower = data["follower"]
    }

    makePost() {
        let post = document.createElement("div")
        post.className = "post"

        post.append(this.makeHeader())
        post.append(this.makeDivider())
        post.append(this.makeBody())
        post.append(this.makeFooter())
        post.append(this.makeSongList())
        post.append(this.makeExpander())
        return post
    }

    // This will make the header for the post
    makeHeader() {
        let postHeader = document.createElement("div")
        postHeader.className = "post-header"

        let profileImgWrapper = document.createElement("div")
        profileImgWrapper.className = "profile-img-wrapper"

        let profileImg = document.createElement("img")
        profileImg.className = "profile-img"
        profileImg.src = this.user.img
        profileImgWrapper.append(profileImg)

        let usernameWrapper = document.createElement("div")
        usernameWrapper.className = "username-wrapper"

        let username = document.createElement("a")
        username.className = "username"
        username.href = "/user.php?user=" + this.user.username
        username.innerHTML = "<p><b>" + this.user.username + "</b></p>"
        usernameWrapper.append(username)

        postHeader.append(profileImgWrapper)
        postHeader.append(usernameWrapper)
        return postHeader
    }

    // This makes the divider
    makeDivider() {
        let divider = document.createElement("hr")
        divider.className = "divider"

        return divider
    }

    // This will make the body
    makeBody() {
        let postBody = document.createElement("div")
        postBody.className = "post-body"

        let postImg = document.createElement("img")
        postImg.className = "post-img"
        postImg.src = this.post.img
        postBody.append(postImg)

        return postBody
    }

    // This will make the footer of the post
    makeFooter() {
        let postFooter = document.createElement("div")
        postFooter.className = "post-footer"

        let likes = document.createElement("div")
        likes.className = "likes"

        let likeIcon = document.createElement("i")
        if (this.liked) {
            likeIcon.className = "fas fa-heart true"
        } else {
            likeIcon.className = "far fa-heart false"
        }
        likeIcon.addEventListener("click", this.markLiked.bind(this, likeIcon))

        this.number = document.createElement("p")
        this.number.className = "number"
        this.number.innerHTML = this.amountOfLikes
        likes.append(likeIcon)
        likes.append(this.number)

        // Make description
        let descriptionWrapper = document.createElement("div")
        descriptionWrapper.className = "description-wrapper"

        let usernameWrapper = document.createElement("a")
        usernameWrapper.href = "/user.php?user=" + this.user.username
        usernameWrapper.className = "username"
        let username = document.createElement("b")
        username.innerHTML = this.user.username
        usernameWrapper.append(username)

        let description = document.createElement("p")
        description.className = "description"
        description.innerHTML = this.post.description
        descriptionWrapper.append(usernameWrapper)
        descriptionWrapper.append(description)

        postFooter.append(likes)
        postFooter.append(descriptionWrapper)

        return postFooter
    }

    makeSongList() {
        let songWrapper = document.createElement("div")
        songWrapper.className = "song-wrapper closed"

        for (let i = 0; i < this.songs.length; i++) {
            songWrapper.append(this.addSong(this.songs[i]))
        }
        return songWrapper
    }

    makeExpander() {
        let expanderWrapper = document.createElement("div")
        expanderWrapper.className = "song-expander"

        let expander = document.createElement("div")
        expander.className = "expander"
        expander.addEventListener(
            "click",
            this.controlExpander.bind(this, expanderWrapper)
        )

        let icon = document.createElement("i")
        icon.className = "fas fa-sort-down"
        expander.append(icon)
        expanderWrapper.append(expander)

        return expanderWrapper
    }

    addSong(song) {
        let songItem = document.createElement("div")
        songItem.className = "song " + song.name

        let divider = document.createElement("hr")
        divider.className = "divider song-divider"
        songItem.append(divider)

        let songImg = document.createElement("img")
        songImg.className = "song-img"
        songImg.src = song.img
        songItem.append(songImg)

        let songName = document.createElement("p")
        songName.className = "song-name"
        songName.innerHTML = song.name
        songItem.append(songName)

        let preview = document.createElement("audio")
        preview.className = "preview"
        preview.src = song.preview
        preview.controls = "controls"
        songItem.append(preview)

        let artistWrapper = document.createElement("div")
        artistWrapper.className = "artist-wrapper"

        for (let i = 0; i < song.artists.length; i++) {
            artistWrapper.append(this.addArtist(song.artists[i]))
        }

        songItem.append(artistWrapper)

        return songItem
    }

    addArtist(artist) {
        let artistWrapper = document.createElement("a")
        artistWrapper.className = "artist " + artist.name
        artistWrapper.href = "/artist.php?artist=" + artist.name

        let artistItem = document.createElement("p")
        artistItem.innerHTML = artist.name + ","
        artistWrapper.append(artistItem)

        return artistWrapper
    }

    controlExpander(expander) {
        let songWrapper = $(expander).parent()[0].children[4]
        let icon = $(expander).children()[0].children[0]

        if (songWrapper.className.includes("closed")) {
            // Open song list
            songWrapper.className = songWrapper.className.replace(
                "closed",
                "open"
            )

            // Flip the icon
            icon.className = icon.className.replace("down", "up")
        } else {
            // Close song list
            songWrapper.className = songWrapper.className.replace(
                "open",
                "closed"
            )

            // Flip the icon
            icon.className = icon.className.replace("up", "down")
        }
    }

    markLiked(button) {
        if (button.className.includes("false")) {
            button.className = button.className.replace("false", "true")
            button.className = button.className.replace("far", "fas")
            this.updateLikeStatus(true)
            this.updateLikeCount(1)
        } else {
            button.className = button.className.replace("true", "false")
            button.className = button.className.replace("fas", "far")
            this.updateLikeStatus(false)
            this.updateLikeCount(-1)
        }
    }

    updateLikeCount(amount) {
        this.amountOfLikes += amount
        this.number.innerHTML = this.amountOfLikes
    }

    updateLikeStatus(status) {
        let userID = this.follower
        let postID = this.post.postID

        $.ajax({
            url: "/api/memory/updateLikeCount.php",
            type: "post",
            data: { postID: postID, userID: userID, status: status },
            error: function (error) {
                console.error(error)
            },
        })
    }
}
