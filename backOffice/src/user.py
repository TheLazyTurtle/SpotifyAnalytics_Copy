import threading
import fetcher
import inserter
from time import sleep


class User():
    def __init__(self, userID):
        self.userID = userID

    # This will fetch the songs from a user
    # TODO: Add error handling and printing
    def fetch(self):
        self.fetcher = fetcher.Fetcher(self.userID)
        self.inserter = inserter.Inserter(self.userID, "http://localhost/api/")

        while True:
            # This returns the songs objects and insert them into the db
            songs = self.fetcher.run()
            self.inserter.run(songs)

            # TODO: Implement GH issue #43
            sleep(300)

    # This will run all the tasks that need to be run for a user
    def run(self):
        # Make a thread for the fetching task
        fetcherThread = threading.Thread(target=self.fetch)
        fetcherThread.start()
