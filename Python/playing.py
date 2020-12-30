import spotipy
import spotipy.util as util
import mysql.connector
import time
from datetime import datetime, timedelta

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

# TODO: Figure out why the username
username = "1182819693"

def alreadySend():
	cursor = db.cursor()
	# TODO: Remove the order by and limit if I ever feel like improving this
	cursor.execute("SELECT * FROM played ORDER BY ID DESC LIMIT 1")
	res = cursor.fetchall()

	# TODO: Find a way to see if we had the same insert 1 or 2 seconds ago. If that is the case than DON'T insert
	if res[0][2] + timedelta(1) >= datetime.now():
		print("We were to fast")
		return True
	return False

def updateDB():
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

	# alreadySend()
	# Add song to db
	songSQL = "INSERT IGNORE INTO song (songID, name, length, url, img) VALUES (%s, %s, %s, %s, %s)"
	songVal = (songID, songName, songDuration, songUrl, songImg)
	cursor.execute(songSQL, songVal)

	# Add song to played
	playedSQL = "INSERT IGNORE INTO played (songID, datePlayed) VALUE (%s, %s)"
	playedVal = (songID, curTime)
	cursor.execute(playedSQL, playedVal)

	# Commits the data to the db. Without this it won't update the db
	db.commit()
	cursor.close()
	print("COMMITTED", songName)

# loop to keep updating
while True:
	# Gets a token for spotify and should keep it updated
	token = util.prompt_for_user_token(username, scope, client_id=clientID, client_secret=clientSec, redirect_uri="http://localhost/Spotify/callback.php")
	sp = spotipy.Spotify(auth=token)

	# Retruns all the data about the current playing song
	results = sp.currently_playing(market=None)
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
		if (progress >= 1000 and progress <= 1500):
			updateDB()
		played = datetime.fromtimestamp(progress/1000).strftime("%M:%S")	
		total = datetime.fromtimestamp(songDuration/1000).strftime("%M:%S")	
		print(songName, " - [", played, "/", total, "]", end="\r")	

	time.sleep(0.4)