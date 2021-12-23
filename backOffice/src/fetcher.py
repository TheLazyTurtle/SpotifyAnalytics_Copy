import spotipy
import spotipy.util as util
import requests as req
import creds
import caching
from printer import printc


class Fetcher():
    def __init__(self, userID, username):
        self.userID = userID
        self.username = username
        self.cacher = caching.Caching(self.userID, username)

    # Get the auth token from the cache file
    # (Might not need this as we might check this before running this)
    def getToken(self):
        try:
            if self.cacher.fileExists():
                token = util.prompt_for_user_token(
                    self.userID,
                    creds.scope,
                    client_id=creds.clientID,
                    client_secret=creds.clientSec,
                    redirect_uri="http://localhost/auth.php"
                )

                return spotipy.Spotify(auth=token)
            else:
                printc("Failed to get cache file for user:",
                       "red", self.username, "white")
                return False

        except Exception as e:
            printc("Failed to get token for user:", "red",
                   self.username, "white", e, "white")
            return False

    # Get the songs
    def getResult(self, amount):
        try:
            self.token = self.getToken()
            if self.token:
                result = self.token.current_user_recently_played(limit=amount)
            else:
                self.cacher.makeFile()
                printc("Failed to get results for user:",
                       "red", self.username, "white")

            if result:
                return result
        except AttributeError as ae:
            if ae == "current_user_recently_played":
                getResult()
        except Exception as e:
            return False

    # Extract the usefull info from the returned data
    def createSongObject(self, data):
        songs = []
        try:
            for song in data["items"]:
                songData = self.extractSong(song)
                album = self.extractAlbum(song)

                artists = []
                for artist in song["track"]["artists"]:
                    artists.append(self.extractArtist(artist))

                # Remove the letters from the date so that the site won't kill itself
                playedAt = songData["playedAt"]
                playedAt = playedAt.replace("T", " ")
                playedAt = playedAt.replace("Z", "")
                playedAt = playedAt[:-4]

                song = {
                    "songID": songData["songID"],
                    "url": songData["url"],
                    "name": songData["name"],
                    "img": songData["img"],
                    "length": songData["length"],
                    "preview": songData["preview"],
                    "playedAt": playedAt,
                    "explicit": songData["explicit"],
                    "trackNumber": songData["trackNumber"],
                    "album": album,
                    "artists": artists
                }

                songs.append(song)

            return songs
        except Exception as e:
            print(e)
            return False

    # Get the song info from the song object provided by spotify
    def extractSong(self, song):
        # Get the time the song was played at
        playedAt = song["played_at"]

        # Get song info
        songInfo = song["track"]
        songID = songInfo["id"]
        url = songInfo["external_urls"]["spotify"]
        name = songInfo["name"]
        img = songInfo["album"]["images"][0]["url"]
        length = songInfo["duration_ms"]
        explicit = songInfo["explicit"]
        trackNumber = songInfo["track_number"]

        # Try to get song preview url
        # Needs to be in try catch because not every song has a preview
        if not songInfo["preview_url"] == None:
            preview = songInfo["preview_url"]
        else:
            preview = "NULL"

        return {
            'songID': songID,
            'url': url,
            'name': name,
            'img': img,
            'length': length,
            'preview': preview,
            'playedAt': playedAt,
            'explicit': explicit,
            'trackNumber': trackNumber
        }

    # Get the album data from the object provided by spotify
    def extractAlbum(self, song):
        album = song['track']['album']

        albumID = album["id"]
        name = album["name"]
        releaseDate = album["release_date"]
        url = album["external_urls"]["spotify"]
        img = album["images"][0]["url"]
        albumType = album['album_type']

        # Primary artist is only needed when albumType = album, because when it's a single all artist are primary
        if albumType == 'album':
            primaryArtist = song["track"]["album"]["artists"][0]["id"]
        else:
            primaryArtist = "NULL"

        return {
            "albumID": albumID,
            "name": name,
            "releaseDate": releaseDate,
            "url": url,
            "img": img,
            "albumType": albumType,
            "primaryArtist": primaryArtist
        }

        # Get the artists from the song object provided by spotify
    def extractArtist(self, artist):
        artistID = artist["id"]
        name = artist["name"]
        url = artist["external_urls"]["spotify"]

        object = {
            'artistID': artistID,
            'name': name,
            'url': url,
        }

        # Check if artist already has an img
        # If not than get an img else do nothing
        if not self.artistHasImg(artistID):
            object.update({"img": self.getArtistImg(name)})
        else:
            object.update(
                {"img": "http://www.techspot.com/images2/downloads/topdownload/2016/12/spotify-icon-18.png"})

        return object

    # Checks if the artists already has an image. If the artist already have an image than don't add the artist because we already have it in the database
    # TODO: If the artist exists but doesn't yet have an image than try to update the img
    def artistHasImg(self, artistID):
        data = {
            "artistID": artistID
        }

        r = req.get(creds.apiUrl + "artist/getImage.php", data)
        httpResponse = r.status_code

        if httpResponse == 200:
            return True
        else:
            return False

    # This will get the image for an artist. If it can't find and image return a default image
    def getArtistImg(self, name):
        result = self.token.search(q="artist: " + name, type="artist")
        item = result["artists"]["items"]

        try:
            artist = item[0]
            return artist["images"][0]["url"]
        except Exception as e:
            return "http://www.techspot.com/images2/downloads/topdownload/2016/12/spotify-icon-18.png"

    def getAlbumSongs(self, albumID, albumImg):
        self.token = self.getToken()
        albumRaw = self.token.album_tracks(albumID, limit=50)
        songs = []

        for song in albumRaw["items"]:
            artists = []
            for artist in song["artists"]:
                artists.append(self.extractArtist(artist))

            songObject = {
                "songID": song["id"],
                "name": song["name"],
                "length": song["duration_ms"],
                "url": song["external_urls"]["spotify"],
                "img": albumImg,
                "preview": song["preview_url"],
                "explicit": song["explicit"],
                "album": {"albumID": albumID},
                "trackNumber": song["track_number"],
                "artists": artists
            }
            songs.append(songObject)
        return songs

    def run(self, amount):
        return self.createSongObject(self.getResult(amount))
