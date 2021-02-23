import creds
import functions as func


class InsertData:
    def __init__(self, spID):
        self.spID = spID

    def artistExists(self, artistID):
        try:
            connection = creds.connection()
            cursor = connection.cursor()

            artistExists = "SELECT count(artistID) FROM artist WHERE artistID = %s"
            data = (artistID, )

            cursor.execute(artistExists, data)
            rowCount = cursor.fetchone()[0]

            if rowCount > 0:
                return True
            else:
                return False

        except Exception as e:
            func.printMsg("Failed to check if artist exists", "red",
                          (artistID + " - ", self.spID), "white", e, "red")

    def songExists(self, songID):
        try:
            connection = creds.connection()
            cursor = connection.cursor()

            songExists = "SELECT count(songID) FROM song WHERE songID = %s"
            data = (songID, )

            cursor.execute(songExists, data)
            rowCount = cursor.fetchone()[0]

            if rowCount > 0:
                return True
            else:
                return False

        except Exception as e:
            func.printMsg("Failed to check if song exists", "red",
                          (songID + " - " + self.spID), "white", e, "red")

    def songMarkedPlayed(self, songID, datePlayed):
        try:
            connection = creds.connection()
            cursor = connection.cursor()

            markedPlayed = "SELECT count(songID) FROM played WHERE songID = %s AND datePlayed = %s"
            data = (songID, datePlayed)

            cursor.execute(markedPlayed, data)
            rowCount = cursor.fetchone()[0]

            if rowCount > 0:
                return True
            else:
                return False

        except Exception as e:
            func.printMsg(
                "Failed to check if song was already marked as played", "red",
                (songID + " - " + datePlayed + " - ", self.spID), "white", e,
                "red")

    def linkedSFA(self, songID, artistID):
        try:
            connection = creds.connection()
            cursor = connection.cursor()

            linked = "SELECT count(songID) FROM SongFromArtist WHERE songID = %s AND artistID = %s"
            data = (songID, artistID)

            cursor.execute(linked, data)
            rowCount = cursor.fetchone()[0]

            if rowCount > 0:
                return True
            else:
                return False

        except Exception as e:
            func.printMsg("Failed to check link", "red", e, "red",
                          (songID + " - " + artistID + " - " + self.spID),
                          "white")

    # Inserts the artist into the db
    def insertArtist(self, artistID, name, url, addedBy, img):
        try:
            if not self.artistExists(artistID):
                connection = creds.connection()
                cursor = connection.cursor()

                insertArtist = "INSERT INTO artist (artistID, name, url, addedBy, img) VALUES (%s, %s, %s, %s, %s)"
                data = (artistID, name, url, addedBy, img)

                cursor.execute(insertArtist, data)
                connection.commit()

                if cursor.rowcount > 0:
                    func.printMsg("Inserted artist into db:", "green", name, "white",
                                  self.spID, "white")

                cursor.close()

        except Exception as e:
            func.printMsg("Failed to insert artist:", "red",
                          (name + " - " + self.spID), "white", e, "red")

    # Inserte the song into the db
    def insertSong(self, songID, name, url, addedBy, img, length, preview):
        try:
            if not self.songExists(songID):
                connection = creds.connection()
                cursor = connection.cursor()

                insertSong = "INSERT INTO song (songID, name, url, addedBy, img, length, preview) VALUES (%s, %s, %s, %s, %s, %s, %s)"
                data = (songID, name, url, addedBy, img, length, preview)

                cursor.execute(insertSong, data)
                connection.commit()

                if cursor.rowcount > 0:
                    func.printMsg("Inserted song into db:", "green", name, "white",
                                  self.spID, "white")

                cursor.close()

        except Exception as e:
            func.printMsg("Failed to insert song for:", "red",
                          (name + " - " + self.spID), "white", e, "red")

    # Inserts the song as played into the db
    def insertAsPlayed(self, songID, playedBy, datePlayed, songName):
        try:
            if not self.songMarkedPlayed(songID, datePlayed):
                connection = creds.connection()
                cursor = connection.cursor()

                insertAsPlayed = "INSERT INTO played (songID, playedBy, datePlayed) VALUES (%s, %s, %s)"
                data = (songID, playedBy, datePlayed)
                cursor.execute(insertAsPlayed, data)

                connection.commit()

                if cursor.rowcount > 0:
                    func.printMsg("Added song as played:", "green", songName, "white", self.spID, "white")

                cursor.close()

        except Exception as e:
            func.printMsg("Failed to add song as played for:", "red",
                          (songName + " - " + self.spID), "white", e, "red")

    # links artists to songs
    def linkSongToArtist(self, songID, artistID, songName, artistName):
        try:
            if not self.linkedSFA(songID, artistID):
                connection = creds.connection()
                cursor = connection.cursor()

                linkSongToArtist = "INSERT IGNORE INTO SongFromArtist (songID, artistID) VALUES (%s, %s)"
                data = (songID, artistID)

                cursor.execute(linkSongToArtist, data)
                connection.commit()

                if cursor.rowcount > 0:
                    func.printMsg(
                        "Linked artist to song:", "green",
                        (songName + " - " + artistName),
                        "white", self.spID, "white")

                cursor.close()

        except Exception as e:
            func.printMsg("Failed to link artist to song", "red",
                          (songName + " - " + artistName + " - " + self.spID),
                          "white", e, "red")
