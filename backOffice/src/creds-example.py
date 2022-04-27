# my secret keys SO DON'T LEAK THEM
clientID = "***REMOVED***"
clientSec = "***REMOVED***"

# The scope defines how much you are allowed to do
scope = "user-read-recently-played"

apiUrl = "http://192.168.2.198/api/"

def init():
    global authToken
    authToken = ""
