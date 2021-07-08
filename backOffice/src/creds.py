import mysql.connector

# my secret keys SO DON'T LEAK THEM
clientID = "***REMOVED***"
clientSec = "***REMOVED***"

# The scope defines how much you are allowed to do
scope = "user-read-recently-played"


def connection():
    db = mysql.connector.connect(
        host="localhost",
        user="remote",
        password="***REMOVED***",
        database="spotifyDev",
    )

    return db
