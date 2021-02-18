import creds
import functions as func


# Insert a artist into the database if it doesn't exist yet
# Takes a artist ID, artist name, link to artist account, and person who listened to it
def insertArtist(artistID, name, url, addedBy, img):
    try:
        cursor = creds.db.cursor()
        InsertArtist = """
        INSERT IGNORE INTO artist 
        (artistID, name, url, addedBy, img) 
        VALUES (%s, %s, %s, %s, %s)
        """

        data = (artistID, name, url, addedBy, img)
        cursor.execute(InsertArtist, data)

        creds.db.commit()

        if cursor.rowcount > 0:
            func.printMsg("Inserted artist:", "green", name, "white")
        cursor.close()

    except Exception as e:
        func.printMsg("Failed to insert artist:", "red", name, "white", e,
                      "red")

# Insert a song into the database if it doesn't exist yet
# Takes a song ID, song name, link to song, who added it, the song cover, and the song duration
def insertSong(songID, name, url, addedBy, img, length):
    try:
        cursor = creds.db.cursor()
        InsertSong = """
        INSERT IGNORE INTO song 
        (songID, name, url, addedBy, img, length) 
        VALUES (%s, %s, %s, %s, %s, %s)
        """

        data = (songID, name, url, addedBy, img, length)
        cursor.execute(InsertSong, data)

        creds.db.commit()

        if cursor.rowcount > 0:
            func.printMsg("Inserted song:", "green", name, "white", addedBy,
                          "white")
        cursor.close()

    except Exception as e:
        func.printMsg("Failed to insert song:", "red", e, "red")


def insertAsPlayed(songID, playedBy, datePlayed, songName):
    try:
        cursor = creds.db.cursor()

        insertAsPlayed = """
        INSERT IGNORE INTO played 
        (songID, playedBy, datePlayed) 
        VALUES (%s, %s, %s)
        """
        data = (songID, playedBy, datePlayed)
        cursor.execute(insertAsPlayed, data)

        creds.db.commit()

        if cursor.rowcount > 0:
            func.printMsg("Added song as played for:", "green", playedBy,
                          "white", songName, "white")
        cursor.close()

    except Exception as e:
        func.printMsg("Failed add song as played for:", "red", playedBy,
                      "white", e, "red")


def linkSongToArtist(songID, artistID, songName, artistName):
    try:
        cursor = creds.db.cursor()
        linkSongToArtist = """
        INSERT IGNORE INTO SongFromArtist 
        (songID, artistID) 
        VALUES (%s, %s)
        """

        data = (songID, artistID)
        cursor.execute(linkSongToArtist, data)

        creds.db.commit()

        if cursor.rowcount > 0:
            func.printMsg("Linked artist to song:", "green", songName, "white",
                          artistName, "white")
            cursor.close()

    except Exception as e:
        func.printMsg("Failed to link artist to song", "red", e, "red")


def getUsers():
    try:
        cursor = creds.db.cursor()
        getUsersQuery = "SELECT spotifyID FROM users where active = 1"
        cursor.execute(getUsersQuery)

        # Maybe return how many users it returned
        func.printMsg("Got all the users", "green")

        # Could close the connection if I make cursor.fetchall a var and return that

        return cursor.fetchall()

    except Exception as e:
        func.printMsg("Couldn't get user |", "red", e, "red")
