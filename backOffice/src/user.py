import threading
import fetcher
import inserter
import requests as req
import json
from time import sleep
from datetime import datetime
from creds import apiUrl
from printer import printc


class User():
    def __init__(self, userID):
        self.userID = userID

    # This will fetch the songs from a user
    def fetch(self):
        # Create the fetcher and inserter for the user
        self.fetcher = fetcher.Fetcher(self.userID)
        self.inserter = inserter.Inserter(self.userID, apiUrl)

        while True:
            # This returns the songs objects and insert them into the db
            try:
                songs = self.fetcher.run(50)
                self.inserter.run(songs)

            except Exception as e:
                printc("Failed to get songs for:", "red",
                       self.userID, "white", e, "white")

            sleep(3600)

    # This will run all the tasks that need to be run for a user
    def run(self):
        # Make a thread for the fetching task
        fetcherThread = threading.Thread(target=self.fetch)
        fetcherThread.start()
