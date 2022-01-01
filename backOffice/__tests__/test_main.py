import unittest

from src import main


class TestMain(unittest.TestCase):
    # This will check if there are users in the system
    def test_getUsers(self):
        result = main.getUsers()
        self.assertGreater(len(result), 0, "Should be bigger than 0")
