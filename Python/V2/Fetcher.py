import spotipy
import spotipy.util as util
import time

import Inserter
import Caching
import functions as func
import creds


class Fetcher:
    def __init__(self, spID):
        self.spID = spID
        self.inserter = Inserter.InsertData(self.spID)
        self.cacher = Caching.Caching(self.spID)

    def getToken(self):
        try:
            # check if there is a cache file for the username
            if self.cacher.checkFile():
                # Get and return auth token
                token = util.prompt_for_user_token(
                    self.spID,
                    creds.scope,
                    client_id=creds.clientID,
                    client_secret=creds.clientSec,
                    redirect_uri="http://localhost/Spotify/callback.php")
                return spotipy.Spotify(auth=token)
            else:
                # If there is no cache file make a new cache file for the user and rerun the function
                if self.cacher.editFile(self.cacher.makeFile()):
                    self.getToken()
        except Exception as e:
            # If it failed to make a new cache file or failed to refresh access token => throw error
            func.printMsg("Failed to get auth token for user:", "red",
                          self.spID, "white", e, "red")
            return False

    def getResult(self):
        try:
            # If it can fetch the latets songs from spotify than return the list of songs
            result = self.token.current_user_recently_played(
                limit=func.getFetchAmount(self.spID))

            if result:
                return result
        except AttributeError as ae:
            # Not sure what this does
            if ae == "current_user_recently_played":
                getResult()
        except Exception as e:
            # If everything fails throw error
            func.printMsg("Failed to get results for user:", "red", self.spID,
                          "white", e, "red")

    def parseData(self):
        try:
            for song in self.getResult()["items"]:
                # Get the time the song was played at
                playedAt = song["played_at"]
                playedAt = playedAt.replace("T", " ")
                playedAt = playedAt.replace("Z", "")
                playedAt = playedAt[:-4]

                # Get song info
                songInfo = song["track"]
                songID = songInfo["id"]
                songURL = songInfo["external_urls"]["spotify"]
                songName = songInfo["name"]
                songImg = songInfo["album"]["images"][0]["url"]
                songLength = songInfo["duration_ms"]

                # Try to get song preview url
                # Needs to be in try catch because not every song has a preview
                try:
                    songPreview = songInfo["preview_url"]
                except Exception as e:
                    songPreview = None

                self.inserter.insertSong(songID, songName, songURL, self.spID,
                                         songImg, songLength, songPreview)
                self.inserter.insertAsPlayed(songID, self.spID, playedAt,
                                             songName)

                # Get artist info
                for artist in songInfo["artists"]:
                    artistID = artist["id"]
                    artistName = artist["name"]
                    artistURL = artist["external_urls"]["spotify"]
                    # If the artist does not yet exists than make the extra call on the api for the artist img
                    if not inserter.artistExists(artistName):
                        artistImg = func.getArtistImg(self.token, artistName)

                    self.inserter.insertArtist(artistID, artistName, artistURL,
                                               self.spID, artistImg)
                    self.inserter.linkSongToArtist(songID, artistID, songName,
                                                   artistName)

        except Exception as e:
            func.printMsg("Failed to get song data for user:", "red",
                          self.spID, "white", e, "red")

    def run(self):
        while True:
            # Gets a new token for the user
            self.token = self.getToken()

            # If there is a token parse the data
            if self.token:
                self.parseData()

            # 300 secs = 5 minutes
            time.sleep(300)
