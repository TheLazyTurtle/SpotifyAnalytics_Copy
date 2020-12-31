import spotipy
import spotipy.util as util
import mysql.connector
from time import sleep
from datetime import datetime
import threading

threads = []

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
	global username

	cursor = db.cursor()
	getUserQuery = "SELECT userID FROM users"
	cursor.execute(getUserQuery)
	return cursor.fetchall()

def updateDB(username):
	global artistID, artistName, artistUrl, songID, songName, songDuration, songUrl, songImg
	curTime = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
	cursor = db.cursor()

	# Inserts the artist info
	for x in range(len(artistID)):
		artistSQL = "INSERT IGNORE INTO artist (artistID, name, url) VALUES (%s, %s, %s)"	
		artistVal = (artistID[x], artistName[x], artistUrl[x]) 
		cursor.execute(artistSQL, artistVal)

		artistFromSongSQL = "INSERT IGNORE INTO artistfromsong (songID, artistID) VALUES (%s, %s)"
		artistFromSongVal = (songID, artistID[x])
		cursor.execute(artistFromSongSQL, artistFromSongVal)

	# Add song to db
	songSQL = "INSERT IGNORE INTO song (songID, name, length, url, img) VALUES (%s, %s, %s, %s, %s)"
	songVal = (songID, songName, songDuration, songUrl, songImg)
	cursor.execute(songSQL, songVal)

	# Add song to played
	playedSQL = "INSERT IGNORE INTO played (songID, playedBy, datePlayed) VALUE (%s, %s, %s)"
	playedVal = (songID, username, curTime)
	cursor.execute(playedSQL, playedVal)

	# Commits the data to the db. Without this it won't update the db
	db.commit()
	cursor.close()
	print("COMMITTED", songName, username)

def auth(username):
	# Gets a token for spotify and should keep it updated
	token = util.prompt_for_user_token(username, scope, client_id=clientID, client_secret=clientSec, redirect_uri="http://localhost/Spotify/callback.php")
	return spotipy.Spotify(auth=token)	

def getResults(sp):	
	# Retruns all the data about the current playing song
	return sp.currently_playing(market=None)

# Fix weird bug where a second param is needed for the thread to not crash.
def getData(username, userID):
	
	while True:	
		global artistID, artistName, artistUrl, songID, songName, songDuration, songUrl, songImg
		results = getResults(auth(username))	

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
		
			# For the insert into db
			if (progress >= 15000 and progress <= 15500 and playing):
				updateDB(username)
		sleep(0.4)

for x in getAllUsers():
	t = threading.Thread(target=getData, args=(x[0], x[0]))
	threads.append(t)
	t.start()