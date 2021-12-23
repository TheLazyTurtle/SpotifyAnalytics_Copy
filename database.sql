-- User stuff
CREATE TABLE IF NOT EXISTS user (
    userID VARCHAR(45) NOT NULL,
    username VARCHAR(45) NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(80) NOT NULL,
    isAdmin TINYINT NOT NULL DEFAULT 0,
    createdAt DATETIME NOT NULL DEFAULT current_timestamp(),
    modified TIMESTAMP NULL DEFAULT current_timestamp(),
    active TINYINT NOT NULL DEFAULT 1,
	fetchAmount TINYINT NULL,
    PRIMARY KEY (userID)
);

CREATE TABLE IF NOT EXISTS followers (
	follower VARCHAR(45) NOT NULL,
	following VARCHAR(45) NOT NULL,
	creationDate DATETIME NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY(follower, following)
);

CREATE TABLE IF NOT EXISTS spotifyData(
    userID varchar(45) NOT NULL,
    authToken VARCHAR(255) NOT NULL,
    refreshToken VARCHAR(255) NOT NULL,
    ExpireDate INT NULL NOT NULL,
    PRIMARY KEY (userID)
);

-- Memories stuff
CREATE TABLE IF NOT EXISTS post( 
	postID INT NOT NULL,
	userID varchar(45) NOT NULL,
	description varchar(255) NOT NULL,
	datePosted datetime DEFAULT current_timestamp(),
	img varchar(255) NOT NULL,

	PRIMARY KEY (postID)
);

CREATE TABLE IF NOT EXISTS likes (
	postID INT NOT NULL,
	userID varchar(45) NOT NULL,
	dateLiked datetime DEFAULT current_timestamp(),

	PRIMARY KEY (postID, userID)
);

CREATE TABLE IF NOT EXISTS post_has_song (
	postID INT NOT NULL,
	songID varchar(23),

	PRIMARY KEY (postID, songID)
);

-- Graph Stuff
CREATE TABLE IF NOT EXISTS graph (
    graphID INT NOT NULL,
    title VARCHAR(45) NOT NULL,
    xValueType VARCHAR(45) NOT NULL,
    api VARCHAR(45) NOT NULL,
    titleX VARCHAR(45) NOT NULL,
    titleY VARCHAR(45) NOT NULL,
    type VARCHAR(45) NOT NULL,
	containerID varchar(45) NOT NULL,
    PRIMARY KEY (graphID)
);

CREATE TABLE IF NOT EXISTS filterSetting (
    graphID INT NOT NULL,
    userID VARCHAR(25) NOT NULL,
    name VARCHAR(45) NOT NULL,
    value VARCHAR(45) NOT NULL,
    PRIMARY KEY (graphID, name, userID)
);

CREATE TABLE IF NOT EXISTS inputfield (
    graphID INT NOT NULL,
    inputFieldID INT NOT NULL,
    name VARCHAR(45) NOT NULL,
    value VARCHAR(45) NOT NULL,
	type VARCHAR(20) NOT NULL,
    PRIMARY KEY (graphID, inputFieldID, name)
);

-- Data storage
CREATE TABLE IF NOT EXISTS artist (
	artistID varchar(23) NOT NULL,
	name varchar(45) NOT NULL,
	url varchar(255) NOT NULL,
	img varchar(255) NULL,
	primary key(artistID)
);

CREATE TABLE IF NOT EXISTS artist_has_song (
	songID varchar(23) NOT NULL,
	artistID varchar(23) NOT NULL,
	primary key(songID, artistID)
);

CREATE TABLE IF NOT EXISTS played (
	songID varchar(23) NOT NULL,
	datePlayed varchar(19) NOT NULL,
	playedBy varchar(45) NOT NULL,
	songName varchar(255) NOT NULL,
	primary key(songId, datePlayed, playedBy)
);

CREATE TABLE IF NOT EXISTS song (
	songID varchar(23) NOT NULL,
	name varchar(255) NOT NULL,
	length int NOT NULL,
	url varchar(255) NOT NULL,
	img varchar(255) NOT NULL,
	preview varchar(255) NULL,
	primary key(songID)
);

CREATE TABLE IF NOT EXISTS album (
	albumID varchar(23) NOT NULL,
	name varchar(255) NOT NULL,
	releaseDate datetime NOT NULL,
	primaryArtistID varchar(23) NOT NULL,
	url varchar(255) NOT NULL,
	img varchar(255) NOT NULL,
	type varchar(255) NOT NULL,
	primary key (albumID)
);
