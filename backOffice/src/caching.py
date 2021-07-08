import os
import requests
import json
from shutil import copy


class Caching():
    def __init__(self, userID):
        self.userID = userID

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
            destFile = curPath + "/.cache-" + self.userID

            copy(tempFile, destFile)

            self.editFile(".cache-"+self.userID, self.getAuthTokens())

        except Exception as e:
            return False

    # Checks if a user has cache files
    def getAuthTokens(self):
        try:
            r = requests.get(
                "http://localhost/api/user/getAuthTokens.php", params={"userID": self.userID})
            result = json.loads(r.text)

            return result["records"][0]
        except Exception as e:
            return False

    # Edits a new cache file and adds the correct tokens from the user in the file
    def editFile(self, cacheFile, tokens):
        try:
            with open(cacheFile, 'r') as file:
                fileData = file.read()

        except Exception as e:
            print(e)
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
