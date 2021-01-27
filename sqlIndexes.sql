CREATE INDEX index_played ON played (songID, playedBy);
CREATE INDEX index_song ON song (songID, name);
CREATE INDEX index_sfa ON SongFromArtist (songID, artistID);
CREATE INDEX index_artist ON artist (artistID, name);
