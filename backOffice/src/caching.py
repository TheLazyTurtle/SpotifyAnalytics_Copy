import os
import requests
import json
import creds
from shutil import copy
from printer import printc


class Caching():
    def __init__(self, userID, username):
        self.userID = userID
        self.username = username

    # Checks if the user has a cache file
    def fileExists(self):
        try:
            filename = ".cache-" + self.userID
            f = open(filename)
            f.close()
            return True

        except IOError as e:
            return False

    # Makes a cache file for the user
    def makeFile(self):
        try:
            curPath = os.path.dirname(os.path.realpath(__file__))
            tempFile = curPath + "/.cache-template"
            destFilename = ".cache-" + self.userID
            destFile = curPath + "/" + destFilename

            copy(tempFile, destFile)

            self.editFile(destFilename, self.getAuthTokens())

        except Exception as e:
            return False

    # Checks if a user has cache files
    def getAuthTokens(self):
        try:
            data = {
                "userID": self.userID,
                "jwt": creds.authToken
            }

            r = requests.get(creds.apiUrl + "user/getAuthTokens.php", data)
            result = json.loads(r.text)
            print(result)

            return result[0]
        except Exception as e:
            printc("Failed to get auth tokens for user",
                   "red", self.userID, "white", e, "white")
            return False

    # Edits a new cache file and adds the correct tokens from the user in the file
    def editFile(self, cacheFile, tokens):
        try:
            with open(cacheFile, 'r') as file:
                fileData = file.read()

        except Exception as e:
            printc("Failed to edit cache file for",
                   "red", self.userID, "white", e, "white")
            return False

        # Replace the placeholder data with the users acutal auth tokens
        fileData = fileData.replace("__ATOKEN__", str(tokens["auth"]))
        fileData = fileData.replace("__RTOKEN__", str(tokens["refresh"]))
        fileData = fileData.replace("__XTIME__", str(tokens["expire"]))

        # Write the data to the file
        try:
            f = open(cacheFile, "w")
            f.write(fileData)
            f.close()
            return True

        except Exception:
            return False
