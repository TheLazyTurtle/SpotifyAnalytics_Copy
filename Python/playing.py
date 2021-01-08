import spotipy
import spotipy.util as util
import time

import creds
import functions as func
import queries as q


def auth(username):
    try:
        token = util.prompt_for_user_token(
            username,
            creds.scope,
            client_id=creds.clientID,
            client_secret=creds.clientSec,
            redirect_uri="http://localhost/Spotify/callback.php")
        func.printMsg("Got a new token for:", "green", username, "white")
        return spotipy.Spotify(auth=token)
    except Exception as e:
        func.printMsg("Couldn't get/refresh access token for user:", "red",
                      username, "white", e, "red")


def getResult(sp):
    try:
        # I have chosen 4 because if a song is just a bit longer than 1 minute it might otherwise be skipped so now it will (hopefully) get all songs
        func.printMsg("Got last 4 songs for:", "green", username[0], "white")
        return sp.current_user_recently_played(limit=4)
    except Exception as e:
        func.printMsg("Couldn't get results for user:", "red", username,
                      "white", e, "red")


def getdata(result):
    for song in result["items"]:

        # Get played at
        # keep in mind that playedAt is end time
        playedAt = song["played_at"]

        # Get song info
        songID = song["track"]["id"]
        songUrl = song["track"]["external_urls"]["spotify"]
        songName = song["track"]["name"]
        songImg = song["track"]["album"]["images"][0]["url"]
        songDuration = song["track"]["duration_ms"]

        q.insertSong(songID, songName, songUrl, username[0], songImg,
                     songDuration)
        q.insertAsPlayed(songID, username[0], playedAt, songName)

        # Get artists
        for artist in song["track"]["artists"]:
            artistID = artist["id"]
            artistName = artist["name"]
            artistUrl = artist["external_urls"]["spotify"]

            q.insertArtist(artistID, artistName, artistUrl, username[0])
            q.linkSongToArtist(songID, artistID, songName, artistName)

while True:
    for username in q.getUsers():
        getdata(getResult(auth(username[0])))

    # 300 secs = 5 minutes
    time.sleep(300)
