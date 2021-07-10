import mysql.connector

# my secret keys SO DON'T LEAK THEM
clientID = "CLIENTID"
clientSec = "CLIENTSECRET"

# The scope defines how much you are allowed to do
scope = "user-read-recently-played"

apiUrl = "https://APIURL/api/"


def connection():
    db = mysql.connector.connect(
        host="SQLHOST",
        user="SQLUSER",
        password="SQLPASS",
        database="SQLDB",
    )

    return db
