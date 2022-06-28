<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\ArtistHasSong;
use App\Models\Played;
use App\Models\Song;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use SpotifyWebAPI;

class SystemController extends Controller
{
    private $api;
    private $logs = array();
    private $currentUsername = "";

    public function fetch($id)
    {
        if ($id != env('FETCHER_TOKEN')) {
            return response()->json([
                'data' => 'Incorrect token',
            ], 400);
        }

        $start_time = microtime(true);

        $session = $this->makeSession();
        return $session;
        if ($session == null) {
            return response()->json(['data' => 'Failed to make session'], 500);
        }

        $users = User::allActiveWithTokens();

        foreach ($users as $user) {
            $start_user_time = microtime(true);
            $user_id = $user->id;
            $this->currentUsername = $user->username;
            $this->logs[$this->currentUsername] = array();
            $this->logs['system'] = array();
            array_push($this->logs[$this->currentUsername], ['Fetcher' => 'Started fetching']);

            $tokens = $this->refreshUsersTokens($session, $user->refresh_token);

            if ($tokens == null) {
                array_push($this->logs[$this->currentUsername], ['Tokens' => 'Failed to refresh tokens']);
            } else {
                if (empty($tokens['access_token']) || empty($tokens['refresh_token'])) {
                    array_push($this->logs[$this->currentUsername], ['Tokens' => 'Tokens were incomplete fetch round skipped']);
                    continue;
                }
                array_push($this->logs[$this->currentUsername], ['Tokens' => 'Refreshed tokens']);
            }

            $this->api = new SpotifyWebAPI\SpotifyWebAPI();
            $this->api->setAccessToken($tokens['access_token']);

            $options = ['limit' => '50'];
            $tracks = $this->api->getMyRecentTracks($options);
            $tracks = json_decode(json_encode($tracks), true);

            $this->parseTracks($tracks['items'], $user_id);
            $end_user_time = microtime(true);
            array_push($this->logs[$this->currentUsername], ['execution_time' => $end_user_time - $start_user_time]);
            set_time_limit(30);
        }

        $end_time = microtime(true);
        array_push($this->logs['system'], ['execution_time' => $end_time - $start_time]);

        $current_date_time = new DateTime();
        DB::table('logs')->insert(
            [
                'log' => json_encode($this->logs),
                'created_at' => $current_date_time->format('Y-m-d H:i:s'),
                'updated_at' => $current_date_time->format('Y-m-d H:i:s')
            ]
        );
        return json_encode($this->logs, JSON_PRETTY_PRINT);
    }

    public function parseTracks($tracks, $user_id)
    {
        foreach ($tracks as $track) {
            $this->insertSong($track, $user_id);
            $this->insertAlbum($track["track"]["album"]);
            $this->insertArtists($track["track"]["artists"], $track["track"]["id"]);
        }
    }

    public function insertSong($song, $user_id)
    {
        $played_at = $song["played_at"];
        $played_at = str_replace("T", " ", $played_at);
        $played_at = str_replace("Z", "", $played_at);
        $played_at = substr($played_at, 0, -4);

        $song_info = $song["track"];

        $songObject = Song::updateOrCreate(
            [
                'song_id' => $song_info['id']
            ],
            [
                'name' => $song_info['name'],
                'length' => $song_info['duration_ms'],
                'url' => $song_info['external_urls']['spotify'],
                'img_url' => $song_info['album']['images'][0]['url'],
                'preview_url' => $song_info['preview_url'] ?? 'NULL',
                'album_id' => $song_info['album']['id'],
                'track_number' => $song_info['track_number'],
                'explicit' => $song_info['explicit']
            ]
        );

        $playedObject = Played::firstOrCreate(
            [
                'song_id' => $song_info['id'],
                'date_played' => $played_at,
                'played_by' => $user_id
            ],
            [
                'song_name' => $song_info['name']
            ]
        );

        if ($songObject->wasRecentlyCreated) {
            array_push($this->logs[$this->currentUsername], ['Added song' => ['song_id' => $song_info['id'], 'name' => $song_info['name']]]);
        }

        if ($playedObject->wasRecentlyCreated) {
            array_push($this->logs[$this->currentUsername], ['Added played' => ['song_id' => $song_info['id'], 'name' => $song_info['name']]]);
        }
    }

    public function insertAlbum($album)
    {

        $albumObject = Album::updateOrCreate(
            [
                'album_id' => $album['id']
            ],
            [
                'name' => $album['name'],
                'release_date' => $album['release_date'],
                'primary_artist_id' => $album['artists'][0]['id'],
                'url' => $album['external_urls']['spotify'],
                'img_url' => $album['images'][0]['url'],
                'type' => $album['album_type']
            ]
        );

        if ($albumObject->wasRecentlyCreated) {
            array_push($this->logs[$this->currentUsername], ['Added album' => ['album_id' => $album['id'], 'name' => $album['name']]]);
            $this->getAlbumSongs($album['id'], $album['images'][0]['url']);
        }
    }

    public function insertArtists($artists, $song_id)
    {
        foreach ($artists as $artist) {
            $artistObject = Artist::updateOrCreate(
                [
                    'artist_id' => $artist['id']
                ],
                [
                    'name' => $artist['name'],
                    'url' => $artist['external_urls']['spotify'],
                ]
            );

            if ($artistObject->wasRecentlyCreated || strtotime($artistObject->updated_at) < strtotime('-60 day')) {
                array_push($this->logs[$this->currentUsername], ['Added artist' => ['artist_id' => $artist['id'], 'name' => $artist['name']]]);

                $result = $this->api->search($artist['name'], 'artist');
                $result = json_decode(json_encode($result), true);

                $img = null;
                foreach ($result['artists']['items'] as $potentialArtist) {
                    if ($potentialArtist['id'] == $artist['id']) {
                        $img = $potentialArtist['images'][0]['url'];
                        break;
                    }
                }

                $artistObject->fill(
                    [
                        'img_url' => $img ?? 'http://www.techspot.com/images2/downloads/topdownload/2016/12/spotify-icon-18.png'
                    ]
                )->save();
            }

            ArtistHasSong::updateOrCreate(
                [
                    'artist_id' => $artist['id'],
                    'song_id' => $song_id
                ]
            );
        }
    }

    public function getAlbumSongs($album_id, $img_url)
    {
        $results = $this->api->getAlbumTracks($album_id);
        $results = json_decode(json_encode($results), true);

        foreach ($results["items"] as $song) {

            $songObject = Song::updateOrCreate(
                [
                    'song_id' => $song['id']
                ],
                [
                    'name' => $song['name'],
                    'length' => $song['duration_ms'],
                    'url' => $song['external_urls']['spotify'],
                    'img_url' => $img_url,
                    'preview_url' => $song['preview_url'] ?? 'NULL',
                    'album_id' => $album_id,
                    'track_number' => $song['track_number'],
                    'explicit' => $song['explicit']
                ]
            );

            if ($songObject->wasRecentlyCreated) {
                array_push($this->logs[$this->currentUsername], ['Added album song' => ['song_id' => $song['id'], 'name' => $song['name']]]);
                $this->insertArtists($song['artists'], $song['id']);
            }
        }
    }

    public function refreshUsersTokens($session, $refreshToken)
    {
        try {
            $session->refreshAccessToken($refreshToken);

            $tokens = array();
            $tokens['access_token'] = $session->getAccessToken();
            $tokens['refresh_token'] = $session->getRefreshToken();

            return $tokens;
        } catch (Exception $e) {
            array_push($this->logs['system'], ['Error refreshing tokens' => ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]]);
            return null;
        }
    }

    public function makeSession()
    {
        try {
            return new SpotifyWebAPI\Session(
                env('SPOTIFY_CLIENT_ID'),
                env('SPOTIFY_CLIENT_SECRET'),
                env('SPOTIFY_CALLBACK_URL')
            );
        } catch (Exception $e) {
            array_push($this->logs['system'], ['Error making session' => ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]]);
            return null;
        }
    }
}
