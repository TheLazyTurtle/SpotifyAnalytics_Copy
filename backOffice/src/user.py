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

        # Set default fetch values
        amount, time = self.getTimeAndAmount()

        while True:
            # This returns the songs objects and insert them into the db
            try:
                songs = self.fetcher.run(amount)
                if self.inserter.run(songs):
                    # If the amound added as played == the amount fetched than fetch again but than the max amount of songs to prevent any songs to get lost
                    printc("Refetching to prevent loss of songs for:",
                           "green", self.userID, "white")
                    songs = self.fetcher.run(50)
                    self.inserter.run(songs)

            except Exception as e:
                printc("Failed to get songs for:", "red",
                       self.userID, "white", e, "white")

            sleep(time)

    def getTimeAndAmount(self):
        # Get the current time and active rate of the user
        hours = self.getActiveHours()
        currentHour = self.getCurrentTime()

        amount = 50
        time = 3600

        for hour in hours:
            if hour["time"] == currentHour:
                percent = round(float(hour["percentage"]))

                if percent <= 1:
                    amount = 50
                    time = 3600
                elif percent > 1 and percent <= 3:
                    amount = 25
                    time = 1800
                elif percent > 3:
                    amount = 5
                    time = 300

        printc("Fetching " + str(amount) + " over " + str(time) +
               " seconds for:", "green", self.userID, "white")
        return amount, time

    def getActiveHours(self):
        r = req.get(apiUrl + "user/getActiveHours.php",
                    params={"userID": self.userID})
        hours = r.text
        hours = json.loads(hours)
        hours = hours['records']
        return hours

    def getCurrentTime(self):
        now = datetime.now()

        current_time = now.strftime("%H")
        return current_time

    # This will run all the tasks that need to be run for a user
    def run(self):
        # Make a thread for the fetching task
        fetcherThread = threading.Thread(target=self.fetch)
        fetcherThread.start()
