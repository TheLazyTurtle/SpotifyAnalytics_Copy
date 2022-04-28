import requests as req
from printer import printc
import fetcher
import json
import creds


class Inserter():
    def __init__(self, userID, username):
        self.userID = userID
        self.username = username
        self.songs = []

    def runAlbumSongs(self, albumID, img):
        fetcherObject = fetcher.Fetcher(self.userID, self.username)
        songs = fetcherObject.getAlbumSongs(albumID, img)
        songs.update({"jwt": creds.authToken})
        songs.update({"playedBy": self.userID})

        res = req.post(creds.apiUrl + "system/insert_all.php", json=songs)

        for r in json.loads(res.text):
            if "Failed" not in r[0]:
                printc(r[0], "green", r[1], "white", self.username, 'white')

    def run(self, songs):
        # If songs is an boolean than it didn't get an result
        if isinstance(songs, bool):
            printc("Failed to run inserters", "red", self.username, "white")
        else:
            songs.update({"jwt": creds.authToken})
            songs.update({"playedBy": self.userID})

            res = req.post(creds.apiUrl + "system/insert_all.php", json=songs)

            # Print messages for added data
            for r in json.loads(res.text):
                if "Failed" not in r[0]:
                    printc(r[0], "green",
                           r[1], "white",
                           self.username, 'white')
                    if len(r) == 4:
                        self.runAlbumSongs(r[2], r[3])
