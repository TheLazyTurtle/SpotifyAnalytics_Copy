import threading
import fetcher
import inserter
import requests as req
import json
import creds
from time import sleep
from datetime import datetime
from printer import printc


class User():
    def __init__(self, userID, username):
        self.userID = userID
        self.username = username

    # This will fetch the songs from a user
    def fetch(self):
        # Create the fetcher and inserter for the user
        self.fetcher = fetcher.Fetcher(self.userID, self.username)
        self.inserter = inserter.Inserter(self.userID, self.username)

        while True:
            # This returns the songs objects and insert them into the db
            try:
                songs = self.fetcher.run(50)
                self.inserter.run(songs)

            except KeyboardInterrupt:
                exit()
            except Exception as e:
                printc("Failed to get songs for", "red", self.username, "white", e, "white")
                continue

            sleep(3600)

    # This will run all the tasks that need to be run for a user
    # The fetcher has its own thread because the goal is to do more with the python than just fetching. This way a user object can do more than just fetching
    def run(self):
        # Make a thread for the fetching task
        fetcherThread = threading.Thread(target=self.fetch)
        fetcherThread.start()
