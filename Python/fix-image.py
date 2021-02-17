import creds
import sys
import spotipy
import spotipy.util as util

token = util.prompt_for_user_token(
        "11182819693", 
        creds.scope,
        client_id = creds.clientID,
        client_secret=creds.clientSec,
        redirect_uri="http://localhost/Spotify/callback.php"
        )

token = spotipy.Spotify(auth=token)

def updateImage(artistID, url):
    cursor = creds.db.cursor()
    updateImageQuery = "UPDATE artist SET img = %s WHERE artistID = %s"
    data = (url, artistID) 

    cursor.execute(updateImageQuery, data)
    creds.db.commit()

cursor = creds.db.cursor()
getArtists = "SELECT artistID, name FROM artist"
cursor.execute(getArtists)
res = cursor.fetchall()

for x in res:
    results = token.search(q="artist: " + x[1], type="artist")
    items = results["artists"]["items"]

    try:
        artist = items[0]
        img = artist["images"][0]["url"]
        updateImage(x[0], img)
    except Exception:
        pass

