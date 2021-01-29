CREATE INDEX index_played ON played (songID, playedBy);
CREATE INDEX index_song ON song (songID, name);
CREATE INDEX index_sfa ON SongFromArtist (songID, artistID);
CREATE INDEX index_artist ON artist (artistID, name);

CREATE TABLE SongFromArtist (
    songID varchar(255) NOT NULL,
    artistID varchar(255) NOT NULL,
    primary key(songID, artistID)
);

CREATE TABLE artist (
    artistID varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    url varchar(255) NOT NULL,
    dateAdded timestamp NOT NULL DEFAULT current_timestamp(),
    addedBy varchar(255) NULL,
    primary key (artistID)
);

CREATE TABLE played (
    songID varchar(255) NOT NULL,
    datePlayed varchar(19) NOT NULL,
    playedBy varchar(255) NOT NULL,
    primary key (songID, datePlayed, playedBy)
);

CREATE TABLE song (
    songID varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    length int(11) NOT NULL,
    url varchar(255) NOT NULL,
    img varchar(255) NOT NULL,
    dateAdded timestamp NOT NULL DEFAULT current_timestamp(),
    addedBy varchar(11) NULL,
    primary key (songID)
);

CREATE TABLE user_settings (
    userID int(11) NOT NULL,
    type varchar(255) NOT NULL,
    value varchar(255) NOT NULL,
    primary key (userID, type)
);

CREATE TABLE users (
    spotifyID varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    pass varchar(255) NOT NULL,
    userID int(11) NOT NULL auto_increment(),
    email varchar(255) NOT NULL,
    spotifyAuth varchar(255) NULL,
    spotifyRefresh varchar(255) NULL,
    spotifyExpire int(255) NULL,
    active tinyint(1) NULL DEFAULT 1,
    primary key (userID);
)
