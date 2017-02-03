-- DROP TABLE IF EXISTS RESULT;
-- DROP TABLE IF EXISTS GIST;
-- DROP TABLE IF EXISTS URL;
-- DROP TABLE IF WHITE_LIST;

CREATE TABLE RESULT
(
    url TEXT,
    description TEXT,
    created_at DATETIME
);

CREATE TABLE GIST
(
    url TEXT,
    gist TEXT,
    created_at DATETIME
);

CREATE TABLE URL
(
    url TEXT,
    html TEXT,
    created_at DATETIME
);

CREATE TABLE WHITE_LIST
(
    domain TEXT
);


-- White List Domain
INSERT INTO WHITE_LIST VALUES
(
    'twitter.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'itunes.apple.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.youtube.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'youtube.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'youtu.be'
);
INSERT INTO WHITE_LIST VALUES
(
    'm.youtube.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.instagram.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.news24.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'facebook.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.facebook.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'fb.me'
);
INSERT INTO WHITE_LIST VALUES
(
    'amazon.co.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'amazon.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.amazon.co.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.amazon.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'cas.st'
);
INSERT INTO WHITE_LIST VALUES
(
    'ameblo.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'figsoku.net'
);
INSERT INTO WHITE_LIST VALUES
(
    'vine.co'
);
INSERT INTO WHITE_LIST VALUES
(
    'twcm.me'
);
INSERT INTO WHITE_LIST VALUES
(
    'i.ask.fm'
);
INSERT INTO WHITE_LIST VALUES
(
    'l.ask.fm'
);
INSERT INTO WHITE_LIST VALUES
(
    'amzn.to'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.dmm.co.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.pixiv.net'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.swarmapp.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.marca.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'imgur.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'i.imgur.com'
);
