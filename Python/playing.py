import time
from datetime import datetime

import spotipy
import spotipy.util as util

import creds
import functions as func
import queries as q


def getAuthToken(username):
    try:
        # If there is a cache file than run the auth process. Else make a file
        if func.checkCachefile(username):
            token = util.prompt_for_user_token(
                username,
                creds.scope,
                client_id=creds.clientID,
                client_secret=creds.clientSec,
                redirect_uri="http://localhost/Spotify/callback.php")
            func.printMsg("Got a new token for:", "green", username, "white")
            return spotipy.Spotify(auth=token)
        else:
            if func.editCachefile(func.makeCachefile(username)):
                getAuthToken(username)
    except Exception as e:
        func.printMsg("Couldn't get/refresh access token for user:", "red",
                      username, "white", e, "red")
        return False


def getResult(sp):
    try:
        if sp.current_user_recently_played(limit=func.getFetchAmount(username[0])):
            func.printMsg("Got last 4 songs for:", "green", username[0],
                          "white")
            return sp.current_user_recently_played(limit=func.getFetchAmount(username[0]))
    except AttributeError as ae:
        if ae == "current_user_recently_played":
            getResult(sp)

    except Exception as e:
        func.printMsg("Couldn't get results for user:", "red", username[0],
                      "white", e, "red")


def getdata(result, username, token):
    try:
        for song in result["items"]:

            # Get played at
            # keep in mind that played_at is end time
            playedAt = song["played_at"]
            playedAt = playedAt.replace("T", " ")
            playedAt = playedAt.replace("Z", "")

            # Get song info
            songID = song["track"]["id"]
            songUrl = song["track"]["external_urls"]["spotify"]
            songName = song["track"]["name"]
            songImg = song["track"]["album"]["images"][0]["url"]
            songDuration = song["track"]["duration_ms"]
            
            try:
                songPreview = song["track"]["preview_url"]
            except Exception as e:
                pass
            
            q.insertSong(songID, songName, songUrl, username, songImg,
                         songDuration, songPreview)
            q.insertAsPlayed(songID, username, playedAt, songName)

            # Get artists
            for artist in song["track"]["artists"]:
                artistID = artist["id"]
                artistName = artist["name"]
                artistUrl = artist["external_urls"]["spotify"]
                artistImg = func.getArtistImg(token, artistName)

                q.insertArtist(artistID, artistName, artistUrl, username,
                               artistImg)
                q.linkSongToArtist(songID, artistID, songName, artistName)

        # Show the time of last update so you can calculate how long it will take for the next update to come
        print("Last updated at:", datetime.now().strftime("%H:%M:%S"))
        print("----------------")

    except Exception as e:
        func.printMsg("Failed by getting song data for user:", "red", username,
                      "white", e, "red")


def authAndFetch(username):
    # Gets the authorization for the user
    token = getAuthToken(username)

    # If the user is authorized get the results
    if token:
        getdata(getResult(token), username, token)


while True:
    for username in q.getUsers():
        authAndFetch(username[0])

    # 300 secs = 5 minutes
    time.sleep(300)
