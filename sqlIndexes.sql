CREATE INDEX index_played ON played (songID, playedBy);
CREATE INDEX index_song ON song (songID, name);
CREATE INDEX index_sfa ON SongFromArtist (songID, artistID);
CREATE INDEX index_artist ON artist (artistID, name);

SELECT s.name AS name, count(p.songID) AS times FROM played p INNER JOIN song s ON p.songID = s.songID INNER JOIN SongFromArtist sfa ON sfa.songID = p.songID RIGHT JOIN artist a ON sfa.artistID = a.artistID WHERE p.playedBy LIKE '111%' GROUP BY s.songID HAVING times > 0 AND times < 1000 ORDER BY name ASC;

SELECT s.name AS name, count(p.songID) AS times FROM played p INNER JOIN song s ON p.songID = s.songID WHERE p.songID IN (SELECT songID FROM song WHERE songID IN (SELECT songID FROM SongFromArtist WHERE artistID IN (SELECT artistID FROM artist))) AND playedBy LIKE '111%' GROUP BY s.songID HAVING times > 0 AND times < 1000 ORDER BY name ASC;
