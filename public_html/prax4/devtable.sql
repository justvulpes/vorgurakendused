mysql --user=st2014 --password=progress -A st2014

CREATE TABLE t164036v1_news_score(
id INTEGER AUTO_INCREMENT,
username VARCHAR(50),
news_id INTEGER ,
score INTEGER DEFAULT 1,
PRIMARY KEY (id),
UNIQUE (username, news_id));

INSERT INTO t164036v1_news_score (username, news_id, score) VALUES ('rareba', 44, 5);
SELECT * FROM t164036v1_news_score;
UPDATE t164036v1_news_score SET score=0 WHERE news_id=30;
SELECT SUM(score) FROM t164036v1_news_score WHERE news_id=45;
UPDATE t164036v1_news_score SET score=1 WHERE news_id=45 AND username='rareba';
SELECT score FROM t164036v1_news_score WHERE news_id=45 AND username='rareba';
SELECT score FROM t164036v1_news_score WHERE news_id=" . $row["id"] . " AND username='" . $_SESSION["login"] . "';
INSERT INTO t164036v1_news_score (username, news_id, score) VALUES ('ragnar', 44, -1) ON DUPLICATE KEY UPDATE username='ragnar' score=-1;
SELECT username, count(username) FROM t164036v1_news_score GROUP by username ORDER by count(username) desc;

SELECT SUM(score) FROM t164036v1_news_score WHERE t164036v1_news_score.id=t164036v3_news.id AND username='root';

SELECT SUM(score) FROM t164036v1_news_score
INNER JOIN t164036v1_news_score t164036v3_news ON t164036v3_news.id = t164036v1_news_score.id
WHERE t164036v3_news.username = 'root';

SELECT news_id, SUM(score) as score FROM t164036v1_news_score GROUP by news_id ORDER BY score DESC;

SELECT news_id, title, content, added, author, t164036v1_news_score.score FROM t164036v3_news INNER JOIN t164036v1_news_score ON t164036v3_news.id=t164036v1_news_score.news_id;

SELECT * FROM (SELECT news_id, SUM(score) as score FROM t164036v1_news_score GROUP by news_id ORDER BY score DESC) INNER JOIN t164036v3_news ON t164036v3_news.id=t164036v1_news_score.news_id;

SELECT * FROM (SELECT news_id as nid, SUM(score) as scoresum FROM t164036v1_news_score GROUP by news_id ORDER BY scoresum DESC) as gold INNER JOIN t164036v3_news ON t164036v3_news.id=gold.nid;

SELECT * FROM (SELECT news_id as nid, SUM(score) as scoresum FROM t164036v1_news_score GROUP by news_id ORDER BY scoresum DESC) as gold INNER JOIN t164036v3_news ON t164036v3_news.id=gold.nid GROUP by added DESC;

-- id, news_id, added, author, content, score

CREATE TABLE t164036v1_comments(
id INTEGER AUTO_INCREMENT,
news_id INTEGER,
content VARCHAR(500) NOT NULL,
author VARCHAR(50),
added TIMESTAMP DEFAULT now(),
PRIMARY KEY (id));

SELECT news_id, COUNT(*) FROM t164036v1_comments WHERE news_id=44 GROUP BY news_id;

-- Deleting news:
-- Step 1.
DELETE from t164036v3_news WHERE id=55;
DELETE from t164036v3_news WHERE id=56;
-- ...
-- Step2.
DELETE from t164036v1_news_score WHERE news_id not in (SELECT t164036v3_news.id from t164036v3_news);
