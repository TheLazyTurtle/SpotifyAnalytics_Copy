import threading
import requests as req
import json
import user
import creds
from printer import printc
from time import sleep

# This will get all the users
# If the user is not in the active array than add them and make a new thread for them
def getUsers(token):
    try:
        data = {"jwt": token}
        r = req.get(creds.apiUrl + 'user/getAllUsers.php', data)
        jsonObject = json.loads(r.text)

        for usr in jsonObject:
            if usr['userID'] not in users and not usr["username"] == "system":
                addUser(usr)
    except Exception as e:
        printc('Failed to get users because:', 'red', e, 'white')


def addUser(usr):
    newUser = user.User(usr['userID'], usr["username"])
    users.append(usr['userID'])

    printc('Stared fetching for:', 'green', usr['userID'], 'white')

    x = threading.Thread(target=newUser.run)
    x.start()

#TODO: Add some error checking
def getAuthTokens():
    data = {'username': 'system', 'password': 'test'}
    r = req.post(creds.apiUrl + 'system/login.php', data)

    jsonObject = json.loads(r.text)
    return jsonObject[0]["jwt"]


# This holds all the active users
users = []

# This will get all users every hour
while True:
    creds.authToken = getAuthTokens()
    getUsers(creds.authToken)
    sleep(3600)
