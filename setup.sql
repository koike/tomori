DROP TABLE IF EXISTS SIGNATURE;
DROP TABLE IF EXISTS RESULT;

CREATE TABLE SIGNATURE
(
    is_reg BOOLEAN,
    pattern TEXT,
    description TEXT,
    created_at DATETIME
);

INSERT INTO SIGNATURE VALUES
(
    1,
    '/var ([a-zA-Z]{5,10}) = "iframe"/',
    'Exploit Kit',
    date('Y-m-d H:i:s')
);

INSERT INTO SIGNATURE VALUES
(
    1,
    '/position:absolute; top:-([0-9]{3,4})px/',
    'Exploit Kit',
    date('Y-m-d H:i:s')
);

INSERT INTO SIGNATURE VALUES
(
    1,
    '/style.border = "0px"/',
    'Exploit Kit',
    date('Y-m-d H:i:s')
);

INSERT INTO SIGNATURE VALUES
(
    1,
    '/frameBorder = "0"/',
    'Exploit Kit',
    date('Y-m-d H:i:s')
);

INSERT INTO SIGNATURE VALUES
(
    1,
    '/setAttribute("frameBorder", "0")/',
    'Exploit Kit',
    date('Y-m-d H:i:s')
);

INSERT INTO SIGNATURE VALUES
(
    1,
    '/http:\/\/([a-zA-Z0-9]+).([A-Z0-9]+).([A-Z0-9]+)/',
    'Exploit Kit',
    date('Y-m-d H:i:s')
);

INSERT INTO SIGNATURE VALUES
(
    0,
    'return substr_count($html, "</body>") > 1;',
    'Exploit Kit',
    date('Y-m-d H:i:s')
);

CREATE TABLE RESULT
(
    url TEXT,
    pattern TEXT,
    description TEXT,
    is_mallicious BOOLEAN,
    created_at DATETIME
);

CREATE TABLE HTML
(
    url TEXT,
    html LONGTEXT,
    created_at DATETIME
);
