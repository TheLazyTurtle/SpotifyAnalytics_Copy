import spotipy
import spotipy.util as util
import requests as req
import creds
import caching
from printer import printc


class Fetcher():
    def __init__(self, userID):
        self.userID = userID
        self.cacher = caching.Caching(self.userID)

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
                       "red", self.userID, "white")
                return False

        except Exception as e:
            printc("Failed to get token for user:", "red",
                   self.userID, "white", e, "white")
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
                       "red", self.userID, "white")

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

        # Try to get song preview url
        # Needs to be in try catch because not every song has a preview
        try:
            preview = songInfo["preview_url"]
        except Exception:
            preview = None

        return {
            'songID': songID,
            'url': url,
            'name': name,
            'img': img,
            'length': length,
            'preview': preview,
            'playedAt': playedAt
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
        r = req.post(creds.apiUrl + "artist/getImage.php",
                     data={"artistID": artistID})
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

    def run(self, amount):
        return self.createSongObject(self.getResult(amount))
