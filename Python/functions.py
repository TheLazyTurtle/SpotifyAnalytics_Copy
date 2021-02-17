import os
from shutil import copy

from termcolor import colored

import creds


# Custom print statement for colored print messages
def printMsg(msg1, color1, msg2="", color2="white", msg3="", color3="white"):
    print(colored(msg1, color1), colored(msg2, color2), colored(msg3, color3))

# A function to check if a user has a cache file
def checkCachefile(username):
    try:
        filename = ".cache-" + username
        f = open(filename)
        printMsg("Found cache file for", "green", username, "white")
        return True
    except IOError:
        printMsg("Couldn't find cache file for", "yellow", username, "white")
        return False


# Make a new cache file if the user doesn't have one
def makeCachefile(username):
    try:
        # Check if the user has a auth token
        if checkUserAuthToken(username):
            # Get the current directory to place the new cache file in
            curPath = os.path.dirname(os.path.realpath(__file__))
            tempfile = curPath + "/.cache-template"
            destfile = curPath + "/.cache-" + username

            # Make a copy of the template cache file
            copy(tempfile, destfile)

            printMsg("Made cache file for", "green", username, "white")
            return (destfile, username)
        else:
            printMsg("User:", "yellow", username, "white",
                     "doesn't yet have auth token registerd", "red")
    except Exception as e:
        printMsg("Couldn't make cache file for", "red", username, "white", e,
                 "red")
        return False


def checkUserAuthToken(username):
    try:
        cursor = creds.db.cursor()

        getUserAuthToken = "SELECT spotifyAuth FROM users where spotifyID = %s"
        data = (username, )
        cursor.execute(getUserAuthToken, data)
        result = cursor.fetchone()
        return result[0]
    except Exception as e:
        printMsg("failed...", "red", e, "red")
        return False


# Gets the tokens from the database
def getUserInfo(username):
    try:
        cursor = creds.db.cursor()

        getUserInfo = "SELECT spotifyAuth, spotifyRefresh, spotifyExpire FROM users WHERE spotifyID = %s"
        data = (username, )
        cursor.execute(getUserInfo, data)
        printMsg("Getting auth tokens from database for", "yellow", username,
                 "white")

        result = cursor.fetchone()
        # creds.db.free_result()
        cursor.close()

        return result
    except Exception as e:
        printMsg("Failed to get auth tokens from database for user", "red",
                 username, "white", e, "red")


# Edits the cache file to add the users access tokens
def editCachefile(inp):
    with open(inp[0], 'r') as file:
        fileData = file.read()

    data = getUserInfo(inp[1])
    AToken = data[0]
    RToken = data[1]
    XTime = data[2]

    # Replace data
    fileData = fileData.replace("__ATOKEN__", str(AToken))
    fileData = fileData.replace("__RTOKEN__", str(RToken))
    fileData = fileData.replace("__XTIME__", str(XTime))

    f = open(inp[0], "w")
    f.write(fileData)
    f.close()
    printMsg("Added token to cache file for", "green", inp[1], "white")
    return True
