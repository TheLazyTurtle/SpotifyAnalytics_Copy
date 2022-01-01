import unittest

from src import caching


class TestCaching(unittest.TestCase):
    # Test if cache file exists
    def test_fileExists(self):
        cache = caching.Caching("11182819693")
        self.assertTrue(cache.fileExists())

    # Tests if cache file not exists
    def test_fileNotExists(self):
        cache = caching.Caching("Tester")
        self.assertFalse(cache.fileExists())

    # Check if the user has auth tokens
    def test_checkAuthToken(self):
        cache = caching.Caching("11182819693")
        self.assertEqual(len(cache.checkAuthToken()), 3)

    # Check if the user doesn't have auth tokens
    def test_checkAuthTokenFalse(self):
        cache = caching.Caching("tester")
        self.assertFalse(cache.checkAuthToken())
