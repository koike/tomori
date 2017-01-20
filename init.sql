DROP TABLE IF EXISTS SIGNATURE;
DROP TABLE IF EXISTS RESULT;

CREATE TABLE SIGNATURE
(
    pattern TEXT,
    description TEXT,
    created_at DATE
);

CREATE TABLE RESULT
(
    url TEXT,
    pattern TEXT,
    description TEXT,
    is_mallicious BOOLEAN,
    created_at DATE
);

CREATE TABLE HTML
(
    url TEXT,
    html LONGTEXT,
    created_at DATE
);
