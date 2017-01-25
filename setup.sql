DROP TABLE IF EXISTS RESULT;
DROP TABLE IF EXISTS HTML;
DROP TABLE IF EXISTS URL;

CREATE TABLE RESULT
(
    url TEXT,
    rate REAL,
    description TEXT,
    created_at DATETIME
);


CREATE TABLE HTML
(
    url TEXT,
    html TEXT,
    created_at DATETIME
);

CREATE TABLE URL
(
    url TEXT,
    html TEXT,
    created_at DATETIME
);
