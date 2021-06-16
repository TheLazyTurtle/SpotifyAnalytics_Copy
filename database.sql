-- Use stuff
CREATE TABLE IF NOT EXISTS user (
    userID INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(45) NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(45) NOT NULL,
    isAdmin TINYINT NOT NULL DEFAULT 0,
    createdAt DATETIME NOT NULL DEFAULT current_timestamp(),
    modified TIMESTAMP NULL DEFAULT current_timestamp(),
    active TINYINT NOT NULL DEFAULT 1,
    PRIMARY KEY (userID)
);

CREATE TABLE IF NOT EXISTS spotifyData(
    userID INT NOT NULL,
    authToken VARCHAR(255) NOT NULL,
    refreshToken VARCHAR(255),
    ExpireDate INT NULL,
    PRIMARY KEY (userID)
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
    PRIMARY KEY (graphID)
);

CREATE TABLE IF NOT EXISTS filterSetting (
    graphID INT NOT NULL,
    userID INT NOT NULL,
    name VARCHAR(45) NOT NULL,
    value VARCHAR(45) NOT NULL,
    PRIMARY KEY (graphID, name, userID)
);

CREATE TABLE IF NOT EXISTS inputfield (
    graphID INT NOT NULL,
    inputFieldID INT NOT NULL,
    name VARCHAR(45) NOT NULL,
    value VARCHAR(45) NOT NULL,
    PRIMARY KEY (graphID, inputFieldID, name)
);
