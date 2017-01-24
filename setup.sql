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

<<<<<<< HEAD
CREATE TABLE HTML
=======
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

-- INSERT INTO SIGNATURE VALUES
-- (
--     1,
--     '/http:\/\/([a-zA-Z0-9]+).([A-Z0-9]+).([A-Z0-9]+)/',
--     'Exploit Kit',
--     date('Y-m-d H:i:s')
-- );

INSERT INTO SIGNATURE VALUES
(
    0,
    'return substr_count($html, "</body>") > 1;',
    'Exploit Kit',
    date('Y-m-d H:i:s')
);

CREATE TABLE RESULT
>>>>>>> 385b8f0478622db43f9c3879313ffb4454633c76
(
    url TEXT,
    html TEXT,
    created_at DATETIME
);

CREATE TABLE URL
(
    url TEXT,
<<<<<<< HEAD
=======
    html TEXT,
>>>>>>> 385b8f0478622db43f9c3879313ffb4454633c76
    created_at DATETIME
);
