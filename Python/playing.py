import spotipy
import spotipy.util as util
import mysql.connector
from time import sleep
from datetime import datetime
import threading
import sys

threads = []
running = True

db = mysql.connector.connect(
    host="localhost",
    user="remote",
    password="***REMOVED***",
    database="spotify"
)

# My secret keys SO DON'T LEAK THEM
clientID = "***REMOVED***"
clientSec = "***REMOVED***"

# The scopes defines how much you are allowed
scope = "user-read-currently-playing"

# This will get all the users and will use the user ID's to load in the cache so it has you auth code.


def getAllUsers():
    global username, running

    try:
        cursor = db.cursor()
        getUserQuery = "SELECT userID FROM users"
        cursor.execute(getUserQuery)
        return cursor.fetchall()
    except Exception as e:
        print("Couldn't get user | ", e)
        running = False


def updateDB(username):
    global artistID, artistName, artistUrl, songID, songName, songDuration, songUrl, songImg, running
    curTime = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    cursor = db.cursor()

    for x in range(len(artistID)):
        try:
            # Inserts the artist info
            artistSQL = "INSERT IGNORE INTO artist (artistID, name, url) VALUES (%s, %s, %s)"
            artistVal = (artistID[x], artistName[x], artistUrl[x])
            cursor.execute(artistSQL, artistVal)
        except Exception as e:
            running = False
            print("Couldn't insert new artist into db | ", e)

        try:
            # Links artists to a song
            artistFromSongSQL = "INSERT IGNORE INTO artistfromsong (songID, artistID) VALUES (%s, %s)"
            artistFromSongVal = (songID, artistID[x])
            cursor.execute(artistFromSongSQL, artistFromSongVal)
        except Exception as e:
            running = False
            print("Couldn't link artist to song | ", e)

    try:
        # Inserts song to database
        songSQL = "INSERT IGNORE INTO song (songID, name, length, url, img) VALUES (%s, %s, %s, %s, %s)"
        songVal = (songID, songName, songDuration, songUrl, songImg)
        cursor.execute(songSQL, songVal)
    except Exception as e:
        running = False
        print("Couldn't add song to db | ", e)

    try:
        # Add song to played
        playedSQL = "INSERT IGNORE INTO played (songID, playedBy, datePlayed) VALUE (%s, %s, %s)"
        playedVal = (songID, username, curTime)
        cursor.execute(playedSQL, playedVal)
    except Exception as e:
        running = False
        print("Couldn't ass song to played | ", e)

    try:
        # Commits the data to the db. Without this it won't update the db
        db.commit()
        cursor.close()
        print(username, " played: ", songName, " by ", artistName[0])
    except Exception as e:
        running = False
        print("Failed to commit | ", e)


def auth(username):
    global running

    try:
        # Gets a token for spotify and should keep it updated
        token = util.prompt_for_user_token(
            username, scope, client_id=clientID, client_secret=clientSec, redirect_uri="http://localhost/Spotify/callback.php")
        return spotipy.Spotify(auth=token)
    except Exception as e:
        running = False
        print("Couldn't get/refresh token | ", e)


def getResults(sp):
    global running

    try:
        # Retruns all the data about the current playing song
        return sp.currently_playing(market=None)
    except Exception as e:
        running = False
        print("Couldn't return result | ", e)

# Fix weird bug where a second param is needed for the thread to not crash.


def getData(username, userID):
	global artistID, artistName, artistUrl, songID, songName, songDuration, songUrl, songImg, running

	while True:
		results = getResults(auth(username))

		try:
			if results:
				# Control info
				progress = results["progress_ms"]
				playing = results["is_playing"]
				
				try:
					# Song info
					songID = results["item"]["id"]
					songUrl = results["item"]["external_urls"]["spotify"]
					songName = results["item"]["name"]
					songImg = results["item"]["album"]["images"][0]["url"]
					songDuration = results["item"]["duration_ms"]

					# Artist info
					artistID = []
					artistUrl = []
					artistName = []

					# Get all the artists and put their info in the arrays
					for artistInfo in results["item"]["artists"]:
						artistID.append(artistInfo["id"])
						artistUrl.append(artistInfo["external_urls"]["spotify"])
						artistName.append(artistInfo["name"])

                # This is for custom songs that don't have all the info needed
                # Its a bit cringe because songID and artistID become the name of the song or artist. Doesn't really matter but it doesn't look great.
                # Maybe throw on encryption so it looks less obvious
				except KeyError:
					# Song info
					songUrl = " "
					songName = results["item"]["name"]
					songID = songName
					songImg = " "
					songDuration = results["item"]["duration_ms"]

					# Artist info
					artistID = []
					artistUrl = []
					artistName = []

                    # Get all the artists and put their info in the arrays
					for artistInfo in results["item"]["artists"]:
						artistUrl.append(" ")
						artistName.append(artistInfo["name"])
						artistID.append(artistInfo["name"])

                # prints the time of how far a person is in listening to the song. Their might be a problem where it is gonna crash/glitch when multiple users will use it.
				curTime = datetime.fromtimestamp(progress/1000).strftime('%M:%S')
				totalTime = datetime.fromtimestamp(songDuration / 1000).strftime('%M:%S')
				print(username, "is playing: ", songName, " by ", artistName[0], "[", curTime, "/", totalTime, "]", end="\r")

                # For the insert into db
				if (progress >= 15000 and progress <= 15500 and playing):
					updateDB(username)

			# If there is an error kill the thread
			if not running:
				threading.Thread.join()
				print("Stopping thread-", username)

			# This is a really cringe way to check and exit for keyboard interrupts
			try:
				sleep(0.4)
			except KeyboardInterrupt:
				for thread in threading.Thread:
					thread.join()
					sys.exit()

		# Shows when a person is playing podcast find a way to fix the printing message
		# Maybe make a msg str which changes based on the type of content and then gets printed in the end 
		# It shows a message but it is combined with the old print so if the old print is too long then it won't show properly so gotta find a way to clear the console once a while
		except:
			print(username, "is playing a podcast", end="\r")
			sleep(2)

for x in getAllUsers():
	t = threading.Thread(target=getData, args=(x[0], x[0]))
	threads.append(t)
	t.start()
