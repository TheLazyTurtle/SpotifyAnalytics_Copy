import spotipy
import requests as req
import json
from spotipy.oauth2 import SpotifyOAuth
from creds import apiUrl
from creds import beUser, bePass

scope = "user-read-recently-played"

sp = spotipy.Spotify(auth_manager=SpotifyOAuth(
    scope=scope, username="11182819693"))


def getThingy(sp):
    Allsongs = req.post(apiUrl + "song/read.php")
    songIDs = []
    jsonObject = json.loads(Allsongs.text)

    num = 0
    for songID in jsonObject["records"]:
        songIDs.append(songID["id"])
        num += 1

        if num % 50 == 0:
            songs = sp.tracks(songIDs)

            for song in songs["tracks"]:
                try:
                    songObject = {
                        "songID": song["id"],
                        "name": song["name"],
                        "length": song["duration_ms"],
                        "url": song["external_urls"]["spotify"],
                        "img": song["album"]["images"][0]["url"],
                        "preview": str(song["preview_url"]),
                        "albumID": song["album"]["id"],
                        "trackNumber": song["track_number"],
                        "explicit": str(song["explicit"])
                    }
                    updateThingy(songObject)
                except Exception as e:
                    print(songObject, "failed", e)
            songIDs = []


def updateThingy(songObject):
    try:
        r = req.post(apiUrl+"song/update.php",
                     data=songObject)
        http_response = r.status_code

        if http_response == 400:
            print("Bad request:", songObject["name"])
    except Exception:
        print(songObject)


getThingy(sp)
