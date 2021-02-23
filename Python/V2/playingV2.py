import threading

import creds
import Fetcher
import functions as func


def getUsers():
    connection = creds.connection()

    try:
        # Gets all the active users
        cursor = connection.cursor()
        getUsers = "SELECT spotifyID from users where active = 1"
        cursor.execute(getUsers)

        func.printMsg("Got all the users", "green")

        result = cursor.fetchall()
        return result

    except Exception as e:
        func.printMsg("Couldn't get users", "red", e, "red")


# Gets all the users and makes a fetcher object for them puts it in the array of users
users = list()
for user in getUsers():
    try:
        users.append(Fetcher.Fetcher(user[0]))
    except Exception as e:
        func.printMsg("Failed to add user to user list", "red", user, "white", e, "red")

threads = list()
for user in users:
    try:
        x = threading.Thread(target=user.run)
        threads.append(x)
        x.start()
    except Exception as e:
        func.printMsg("Failed to start thread for user", "red", user, "white", e, "red")
