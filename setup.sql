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
    created_at DATETIME
);

CREATE TABLE AFRAID
(
    domain TEXT,
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
    'mobile.twitter.com'
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
    'instagram.com'
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
    's.ameblo.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'amba.to'
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
    'www.dmm.com'
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
INSERT INTO WHITE_LIST VALUES
(
    'www.showroom-live.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'play.google.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'docs.google.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'maps.google.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'google.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'google.co.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'nico.ms'
);
INSERT INTO WHITE_LIST VALUES
(
    'naver.me'
);
INSERT INTO WHITE_LIST VALUES
(
    'twitpic.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'abema.tv'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.yahoo.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.yahoo.co.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'yahoo.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'shindanmaker.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'tmblr.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'tmblr.co'
);
INSERT INTO WHITE_LIST VALUES
(
    'tumblr.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.tumblr.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'sp.mbga.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'mbga.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'r10.to'
);
INSERT INTO WHITE_LIST VALUES
(
    'a.r10.to'
);
INSERT INTO WHITE_LIST VALUES
(
    'twitch.tv'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.twitch.tv'
);
INSERT INTO WHITE_LIST VALUES
(
    'tv.naver.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'twpf.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'matome.naver.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'radiko.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'ustre.am'
);
INSERT INTO WHITE_LIST VALUES
(
    'web.live.bigo.sg'
);
INSERT INTO WHITE_LIST VALUES
(
    'vimeo.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'rover.ebay.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'blog.naver.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'm.blog.naver.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'line.me'
);
INSERT INTO WHITE_LIST VALUES
(
    'cards.twitter.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.nicovideo.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'www.tenki.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'blog.livedoor.jp'
);
INSERT INTO WHITE_LIST VALUES
(
    'github.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'news.livedoor.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'flic.kr'
);
INSERT INTO WHITE_LIST VALUES
(
    'soundcloud.com'
);
INSERT INTO WHITE_LIST VALUES
(
    'htn.to'
);
INSERT INTO WHITE_LIST VALUES
(
    'b.hatena.ne.jp'
);
