import requests as req
from datetime import datetime
from printer import printc
import fetcher
import creds

class Inserter():
    def __init__(self, userID, username):
        self.userID = userID
        self.username = username
        self.songs = []
        self.lastInserted = []

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
            "trackNumber": song["trackNumber"],
            "jwt": creds.authToken
        }

        r = req.post(creds.apiUrl + "song/create.php", data=values)
        httpResponse = r.status_code

        if httpResponse == 201:
            printc("Added song:", "green", song["name"], "white")
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
                "img": artist["img"],
                "jwt": creds.authToken
            }

            r = req.post(creds.apiUrl + "artist/create.php", data=values)
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
            "songName": song["name"],
            "jwt": creds.authToken
        }

        r = req.post(creds.apiUrl + 'played/create.php', data=values)
        httpResponse = r.status_code

        if httpResponse == 201:
            printc("Added song as played:", "green", song["name"], "white", "-", "white", self.username, "white")
            return True
        elif httpResponse == 503:
            pass
        else:
            printc("Failed to add song as played:", "red",
                   song["name"], "white", self.username, "white")

    def linkArtistToSong(self, song):
        for artist in song["artists"]:
            values = {
                "songID": song["songID"],
                "artistID": artist["artistID"],
                "jwt": creds.authToken
            }

            r = req.post(creds.apiUrl + "song/linkArtistToSong.php", data=values)
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
            "albumType": album["albumType"],
            "jwt": creds.authToken
        }

        r = req.post(creds.apiUrl + "album/create.php", data=values)
        httpResponse = r.status_code

        if httpResponse == 201:
            printc("Added album:", "green",
                   album["name"], "white")

            songs = fetcher.Fetcher(
                self.userID, self.username).getAlbumSongs(values["albumID"], values["img"])
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
            printc("Failed to run inserters:", "red", self.username, "white")
        else:
            self.songs = songs

            for song in self.songs:
                if song["playedAt"] not in self.lastInserted:
                    self.markAsPlayed(song)
                    self.insertSong(song)
                    self.insertArtist(song)
                    self.linkArtistToSong(song)
                    self.insertAlbum(song)

                    # This is a performance thingy where we will only send the data that is needed to be send
                    if (len(self.lastInserted) >= 50):
                        # printc("empting list", "green", self.username, "white")
                        self.lastInserted.pop()
                    self.lastInserted.append(song["playedAt"]) 



