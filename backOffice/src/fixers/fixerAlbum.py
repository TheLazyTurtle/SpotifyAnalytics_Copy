import spotipy
from spotipy.oauth2 import SpotifyOAuth
import requests as req
import json
from creds import apiUrl, beUser, bePass

# TODO: KEEP IN MIND: IT IS NICE TO SAVE THE POSITON OF WHERE THEY ARE IN THE ALBUM SO WE CAN PLACE THEM IN THE SAME ORDER

scope = "user-read-recently-played"
sp = spotipy.Spotify(auth_manager=SpotifyOAuth(
    scope=scope, username="11182819693"))


def getSongs(sp):
    r = req.post(apiUrl+"song/read.php", auth=(beUser, bePass))
    songs = json.loads(r.text)
    songs = songs["records"]

    for song in songs:
        try:
            makeAlbum(sp, song["albumID"])
        except Exception:
            print("Messed up Album ID")
            continue


def makeAlbum(sp, albumID):
    albumRaw = sp.album(album_id=albumID)

    album = {
        "albumID": albumRaw["id"],
        "name": albumRaw["name"],
        "releaseDate": albumRaw["release_date"],
        "primaryArtistID": albumRaw["artists"][0]["id"],
        "url": albumRaw["external_urls"]["spotify"],
        "img": albumRaw["images"][0]["url"],
        "albumType": albumRaw["album_type"]
    }

    r = req.post(apiUrl + "album/create.php",
                 data=album, auth=(beUser, bePass))
    resp = r.status_code
    if resp == 201:
        print("Album:", album["name"])
    else:
        print("Album failed:", album["name"])

    addNewSongs(albumRaw["tracks"], album["img"],
                albumID, album["releaseDate"], sp)


def addNewSongs(songsRaw, albumImg, albumID, releaseDate, sp):
    for song in songsRaw["items"]:
        songData = {
            "songID": song['id'],
            "name": song['name'],
            "length": song['duration_ms'],
            "url": song['external_urls']["spotify"],
            "img": albumImg,
            "preview": song['preview_url'],
            "albumID": albumID,
            "explicit": song['explicit'],
            "trackNumber": song["track_number"]
        }

        r = req.post(apiUrl + "song/create.php",
                     data=songData, auth=(beUser, bePass))
        resp = r.status_code
        if resp == 201:
            print("Song:", songData["name"])
        else:
            pass
            # print("Song failed:", songData["name"])

        addArtist(song, songData)


def addArtist(song, songData):
    for artist in song["artists"]:
        artistData = {
            "artistID": artist['id'],
            "name": artist['name'],
            "url": artist['external_urls']['spotify'],
            "img": getArtistImg(sp, artist["name"]),
        }

        r = req.post(apiUrl + "artist/create.php",
                     data=artistData, auth=(beUser, bePass))
        resp = r.status_code
        if resp == 201:
            print("Artist:", artistData["name"])

        linkArtist(artistData, songData)


def linkArtist(artistData, songData):
    linkArtistToSong = {
        "artistID": artistData["artistID"],
        "songID": songData["songID"]
    }

    r = req.post(apiUrl + 'song/linkArtistToSong.php',
                 data=linkArtistToSong, auth=(beUser, bePass))


def getArtistImg(sp, name):
    result = sp.search(q="artist: " + name, type="artist")
    item = result["artists"]["items"]

    try:
        artist = item[0]
        return artist["images"][0]["url"]
    except Exception as e:
        return "http://www.techspot.com/images2/downloads/topdownload/2016/12/spotify-icon-18.png"


getSongs(sp)
