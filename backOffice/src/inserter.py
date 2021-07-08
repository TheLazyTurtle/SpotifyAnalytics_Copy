import requests as req
from datetime import datetime
from printer import printc


class Inserter():
    def __init__(self, userID, baseUrl):
        self.url = baseUrl
        self.userID = userID
        self.songs = []

    def insertSong(self):
        for song in self.songs:
            values = {
                "songID": song["songID"],
                "name": song["name"],
                "length": song["length"],
                "url": song["url"],
                "img": song["img"],
                "preview": song["preview"]
            }

            r = req.get(self.url + "song/create.php", params=values)
            httpResponse = r.status_code

            if httpResponse == 201:
                printc("Added song:", "green", song["name"])
            elif httpResponse == 503:
                pass
            else:
                printc("Failed to add song:", "red",
                       song["name"], "white", httpResponse, "white")

    # TODO: add img to artist
    def insertArtist(self):
        for song in self.songs:
            for artist in song["artists"]:
                values = {
                    "artistID": artist["artistID"],
                    "name": artist["name"],
                    "url": artist["url"],
                    "img": artist["img"]
                }

                r = req.get(self.url + "artist/create.php", params=values)
                httpResponse = r.status_code

                if httpResponse == 201:
                    printc("Added artist:", "green", artist["name"])
                elif httpResponse == 503:
                    pass
                else:
                    print(artist)
                    printc("Failed to add artist:", "red",
                           artist["name"], "white", httpResponse)

    def markAsPlayed(self):
        for song in self.songs:
            values = {
                "songID": song["songID"],
                "datePlayed": song["playedAt"],
                "playedBy": self.userID,
                "songName": song["name"]
            }

            r = req.get(self.url + '/played/create.php', params=values)
            httpResponse = r.status_code

            if httpResponse == 201:
                printc("Added song as played", "green",
                       song["name"], "white", self.userID, "white")
            elif httpResponse == 503:
                pass
            else:
                printc("Failed to add song as played:", "red",
                       song["name"], "white", self.userID, "white")

    def linkArtistToSong(self):
        for song in self.songs:
            for artist in song["artists"]:
                values = {
                    "songID": song["songID"],
                    "artistID": artist["artistID"]
                }

                r = req.get(self.url + "song/linkArtistToSong.php",
                            params=values)
                httpResponse = r.status_code

                if httpResponse == 201:
                    printc("Linked artist to song", "green",
                           artist["name"] + " - " + song["name"], "white")
                elif httpResponse == 503:
                    pass
                else:
                    printc("Failed to link artist to song", "red",
                           artist["name"] + " - " + song["name"], "white")

    def run(self, songs):
        if not isinstance(songs, bool):
            self.songs = songs
        else:
            printc("Failed to get songs:", "red", self.userID, "white")

        self.insertSong()
        self.insertArtist()
        self.linkArtistToSong()
        self.markAsPlayed()
