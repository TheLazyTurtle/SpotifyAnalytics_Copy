import os
from shutil import copy

from functions import printMsg
import creds


class Caching:
    def __init__(self, spID):
        self.spID = spID

    def checkFile(self):
        try:
            # If it can open the cache file than it exists and returns true
            filename = ".cache-" + self.spID
            f = open(filename)
            return True
        except IOError:
            # If it can't find the file throw an error and return false
            printMsg("Failed to find cachefile for:", "yellow", self.spID,
                     "white")
            return False

    def makeFile(self):
        try:
            # Check if the user has a auth token
            if self.checkAuthToken():
                # Get the current directory to place the new file in
                curPath = os.path.dirname(os.path.realpath(__file__))
                tempfile = curPath + "/.cache-template"
                destfile = curPath + "/.cache-" + self.spID

                # Make a copy of the template file
                copy(tempfile, destfile)

                printMsg("Made a cache file for:", "green", self.spID, "white")
                return (destfile, self.spID)
        except Exception as e:
            printMsg("Failed to make cache file for:", "red", self.spID,
                     "white", e, "red")
            return False

    def checkAuthToken(self):
        try:
            connection = creds.connection()
            cursor = connection().cursor()

            getAuthToken = "SELECT spotifyAuth FROM users WHERE spotifyID = %s"
            data = (self.spID, )

            cursor.execute(getAuthToken, data)
            result = cursor.fetchone()

            return result[0]
        except Exception as e:
            printMsg("User does not have auth tokens", "red", self.spDI,
                     "white", e, "red")
            return False

    def getAuthInfo(self):
        try:
            # Gets all the info to make the cache file
            connection = creds.connection()
            cursor = connection.cursor()

            getInfo = "SELECT spotifyAuth, spotifyRefresh, spotifyExpire FROM users WHERE spotifyID = %s"
            data = (self.spID, )
            cursor.execute(getInfo, data)
            printMsg("Getting auth tokens from database for:", "yellow",
                     self.spID, "white")

            result = cursor.fetchone()
            cursor.close()

            return result
        except Exception as e:
            printMsg("Failed to get auth tokens from database for user:",
                     "red", self.spID, "white", e, "red")

    def editFile(self, inp):
        try:
            with open(inp[0], 'r') as file:
                fileData = file.read()

        except Exception as e:
            func.printMsg("Failed to to open cache file", "red", self.spID,
                          "white", e, "red")

        # Gets the auth info and putting it in variables so it can be put in the new cache file
        tokens = self.getAuthInfo()
        Atoken = tokens[0]
        Rtoken = tokens[1]
        Xtime = tokens[2]

        # Replaces the placeholders to the tokens in the cache file
        fileData = fileData.replace("__ATOKEN__", str(Atoken))
        fileData = fileData.replace("__RTOKEN__", str(Rtoken))
        fileData = fileData.replace("__XTIME__", str(Xtime))

        try:
            # writes the data to the file
            f = open(inp[0], "w")
            f.write(fileData)
            f.close()
            printMsg("Added tokens to cache file for:", "green", self.spID,
                     "white")

        except Exception as e:
            func.printMsg("Failed to write to cache file", "red", self.spID,
                          "white", e, "red")

        return True
