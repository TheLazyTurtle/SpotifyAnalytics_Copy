import unittest
import spotipy
import json

from src import fetcher


class TestFetcher(unittest.TestCase):
    # Checks if a auth token can be made from the cache file
    def test_getToken(self):
        fetch = fetcher.Fetcher("11182819693")
        self.assertIsInstance(fetch.getToken(), spotipy.Spotify)

    # Checks if a auth token can be maded when there is no cache file
    def test_getTokenFalse(self):
        fetch = fetcher.Fetcher("tester")
        self.assertFalse(fetch.getToken())

    # Checks if we can get a result
    def test_getResult(self):
        fetch = fetcher.Fetcher("11182819693")
        self.assertTrue(fetch.getResult())

    # Checks if we can't get a result
    def test_getResultFalse(self):
        fetch = fetcher.Fetcher("tester")
        self.assertFalse(fetch.getResult())

    # Tests extract song to see if it gets the correct data from the song
    def test_extractSong(self):
        fetch = fetcher.Fetcher("11182819693")
        file = open("./__tests__/data.json")
        data = json.load(file)
        data = data["items"]
        result = {
            "songID": "42oMPB2sRZfTN9vV2aN1NK",
            "url": "https://open.spotify.com/track/42oMPB2sRZfTN9vV2aN1NK",
            "name": "Let It Die",
            "img": "https://i.scdn.co/image/ab67616d0000b2735a1e3eb748b626a09ef4b63d",
            "length": 217931,
            "preview": "https://p.scdn.co/mp3-preview/9c5a56426d3349bc5d9f6b062e0da4a5cd9f744a?cid=***REMOVED***",
            "playedAt": "2021-01-07T15:10:29.429Z"
        }
        self.assertDictEqual(fetch.extractSong(data[0]), result)
        file.close()

    # Tests extract artist to see if it gets the correct data from the artist
    def test_extractArtist(self):
        fetch = fetcher.Fetcher("11182819693")
        file = open("./__tests__/data.json")
        data = json.load(file)
        data = data["items"][0]["track"]["artists"]
        result = {
            "artistID": "4u1nYxjl132D6rcMeYQ6Zz",
            "name": "Rival",
            "url": "https://open.spotify.com/artist/4u1nYxjl132D6rcMeYQ6Zz"
        }
        self.assertDictEqual(fetch.extractArtist(data[0]), result)
        file.close()

    # Tests the combination of song and artist
    def test_createSongObject(self):
        fetch = fetcher.Fetcher("11182819693")
        file = open("./__tests__/data.json")
        data = json.load(file)

        result = {
            "songID": "42oMPB2sRZfTN9vV2aN1NK",
            "url": "https://open.spotify.com/track/42oMPB2sRZfTN9vV2aN1NK",
            "name": "Let It Die",
            "img": "https://i.scdn.co/image/ab67616d0000b2735a1e3eb748b626a09ef4b63d",
            "length": 217931,
            "preview": "https://p.scdn.co/mp3-preview/9c5a56426d3349bc5d9f6b062e0da4a5cd9f744a?cid=***REMOVED***",
            "playedAt": "2021-01-07T15:10:29.429Z",
            "artists": [
                {
                    "artistID": "4u1nYxjl132D6rcMeYQ6Zz",
                    "name": "Rival",
                    "url": "https://open.spotify.com/artist/4u1nYxjl132D6rcMeYQ6Zz"
                },
                {
                    "artistID": "1hII0FUxBvpT7bnuS7TQ6q",
                    "name": "Philip Strand",
                    "url": "https://open.spotify.com/artist/1hII0FUxBvpT7bnuS7TQ6q"
                }
            ]
        }
        file.close()
        self.assertDictEqual(fetch.createSongObject(data)[0], result)
