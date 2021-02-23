from termcolor import colored
from datetime import datetime

import creds


def getFetchAmount(username):
    try:
        connection = creds.connection()
        cursor = connection.cursor()

        query = "SELECT fetchAmount FROM users WHERE spotifyID = %s"
        data = (username, )

        cursor.execute(query, data)
        res = cursor.fetchone()
        cursor.close()

        return res[0]
    except Exception as e:
        printMsg("Failed to get fetchAmount for user", "red", username,
                 "white", e, "red")


def getArtistImg(token, name):
    results = token.search(q="artist: " + name, type="artist")
    item = results["artists"]["items"]

    try:
        artist = item[0]
        return artist["images"][0]["url"]
    except Exception as e:
        printMsg("Couldn't get image for artist", "yellow", name, "white", e,
                 "red")


# Custom print statement for colored print messages
def printMsg(msg1, color1, msg2="", color2="white", msg3="", color3="white"):
    print(colored(datetime.now().strftime("%H:%M:%S") + " -", "white"),
          colored(msg1, color1), colored(msg2, color2), colored(msg3, color3))
