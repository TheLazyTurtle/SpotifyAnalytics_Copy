from termcolor import colored

import creds
import functions as func


# Insert a artist into the database if it doesn't exist yet
# Takes a artist ID, artist name, link to artist account, and person who listened to it
def insertArtist(artistID, name, link, addedBy):
    try:
        cursor = creds.db.cursor()
        InsertArtist = """
        INSERT IGNORE INTO artist 
        (artistID, name, link, addedBy) 
        VALUES (%s, %s, %s, %s)
        """

        data = (artistID, name, link, addedBy)
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
def insertSong(songID, name, link, addedBy, img, duration):
    try:
        cursor = creds.db.cursor()
        InsertSong = """
        INSERT IGNORE INTO song 
        (songId, name, link, addedBy, img, duration) 
        VALUES (%s, %s, %s, %s, %s, %s)
        """

        data = (songID, name, link, addedBy, img, duration)
        cursor.execute(InsertSong, data)

        creds.db.commit()

        if cursor.rowcount > 0:
            func.printMsg("Inserted song:", "green", name, "white", addedBy,
                          "white")
        cursor.close()

    except Exception as e:
        func.printMsg("Failed to insert song:", "red", e, "red")


def insertAsPlayed(songID, playedBy, playedAt, songName):
    try:
        cursor = creds.db.cursor()

        insertAsPlayed = """
        INSERT IGNORE INTO played 
        (songID, playedBy, playedAt) 
        VALUES (%s, %s, %s)
        """
        data = (songID, playedBy, playedAt)
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
        getUsersQuery = "SELECT spotifyID FROM users"
        cursor.execute(getUsersQuery)

        func.printMsg("Got all the users", "green")
        # Cant close the connection because than I lose the output

        return cursor.fetchall()

    except Exception as e:
        func.printMsg("Couldn't get user |", "red", e, "red")
