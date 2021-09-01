import requests as req
from datetime import datetime
from printer import printc
import fetcher
from creds import beUser
from creds import bePass


class Inserter():
    def __init__(self, userID, baseUrl):
        self.url = baseUrl
        self.userID = userID
        self.songs = []
        self.auth = (beUser, bePass)

    def insertSong(self, song):
        values = {
            "songID": song["songID"],
            "name": song["name"],
            "length": song["length"],
            "url": song["url"],
            "img": song["img"],
            "preview": song["preview"],
            "albumID": song["album"]["albumID"],
            "explicit": str(song["explicit"]),
            "trackNumber": song["trackNumber"]
        }

        r = req.post(self.url + "song/create.php", data=values, auth=self.auth)
        httpResponse = r.status_code

        if httpResponse == 201:
            printc("Added song:", "green", song["name"])
        elif httpResponse == 503:
            pass
        else:
            printc("Failed to add song:", "red",
                   song["name"], "white", httpResponse, "white")

    def insertArtist(self, song):
        for artist in song["artists"]:
            values = {
                "artistID": artist["artistID"],
                "name": artist["name"],
                "url": artist["url"],
                "img": artist["img"]
            }

            r = req.post(self.url + "artist/create.php",
                         data=values, auth=self.auth)
            httpResponse = r.status_code

            if httpResponse == 201:
                printc("Added artist:", "green", artist["name"])
            elif httpResponse == 503:
                pass
            else:
                printc("Failed to add artist:", "red",
                       artist["name"], "white", httpResponse)

    def markAsPlayed(self, song):
        values = {
            "songID": song["songID"],
            "datePlayed": song["playedAt"],
            "playedBy": self.userID,
            "songName": song["name"]
        }

        r = req.post(self.url + 'played/create.php',
                     data=values, auth=self.auth)
        httpResponse = r.status_code

        if httpResponse == 201:
            printc("Added song as played:", "green",
                   song["name"], "white", self.userID, "white")
            return True
        elif httpResponse == 503:
            pass
        else:
            printc("Failed to add song as played:", "red",
                   song["name"], "white", self.userID, "white")

    def linkArtistToSong(self, song):
        for artist in song["artists"]:
            values = {
                "songID": song["songID"],
                "artistID": artist["artistID"]
            }

            r = req.post(self.url + "song/linkArtistToSong.php",
                         data=values, auth=self.auth)
            httpResponse = r.status_code

            if httpResponse == 201:
                printc("Linked artist to song:", "green",
                       artist["name"] + " - " + song["name"], "white")
            elif httpResponse == 503:
                pass
            else:
                printc("Failed to link artist to song", "red",
                       artist["name"] + " - " + song["name"], "white")

    def insertAlbum(self, song):
        album = song["album"]
        values = {
            "albumID": album["albumID"],
            "name": album["name"],
            "releaseDate": album["releaseDate"],
            "primaryArtistID": album["primaryArtist"],
            "url": album["url"],
            "img": album["img"],
            "albumType": album["albumType"]
        }

        r = req.post(self.url + "album/create.php",
                     data=values, auth=self.auth)
        httpResponse = r.status_code

        if httpResponse == 201:
            printc("Added album:", "green", album["name"], "white")

            songs = fetcher.Fetcher(
                self.userID).getAlbumSongs(values["albumID"], values["img"])
            self.runAlbumSongs(songs)
        elif httpResponse == 503:
            pass
        else:
            printc("Failed to add album:", "red",
                   album["name"], "white")

    def runAlbumSongs(self, songs):
        self.songs = songs

        for song in self.songs:
            self.insertSong(song)
            self.insertArtist(song)
            self.linkArtistToSong(song)

    def run(self, songs):
        # If songs is an boolean than it didn't get an result and print an error
        if isinstance(songs, bool):
            printc("Failed to run inserters:", "red", self.userID, "white")
        else:
            self.songs = songs
            added = 0

            for song in self.songs:
                if self.markAsPlayed(song):
                    added += 1
                self.insertSong(song)
                self.insertArtist(song)
                self.linkArtistToSong(song)
                self.insertAlbum(song)

            if added == len(self.songs):
                return True
