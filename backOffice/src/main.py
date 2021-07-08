import threading
import requests as req
import json
import user
from printer import printc
from time import sleep


# This will get all the users
# If the user is not in the active array than add them and make a new thread for them
def getUsers():
    r = req.get("http://localhost/api/user/getAllUsers.php")
    jsonObject = json.loads(r.text)

    try:
        for usr in jsonObject["records"]:
            if usr["userID"] not in users:
                addUser(usr)
    except Exception as e:
        printc("Failed to get users because:", "red", e, "white")


def addUser(usr):
    newUser = user.User(usr["userID"])
    users.append(usr["userID"])

    printc("Stared fetching for:",
           "green", usr["userID"], "white")

    x = threading.Thread(target=newUser.run)
    x.start()


# This holds all the active users
users = []

# This will get all users every hour
while True:
    getUsers()
    sleep(60)
