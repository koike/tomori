DROP TABLE IF EXISTS RESULT;
DROP TABLE IF EXISTS GIST;
DROP TABLE IF EXISTS URL;

CREATE TABLE RESULT
(
    url TEXT,
    rate REAL,
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
