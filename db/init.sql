CREATE TABLE songs (
  id INTEGER NOT NULL UNIQUE,
  song_name TEXT NOT NULL,
  album INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (1, "Yonkers", 1);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (2, "Awkward", 2);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (3, "SMUCKERS", 3);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (4, "Glitter", 4);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (5, "A BOY IS A GUN", 5);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (6, "CORSO", 6);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (7, "BEST INTEREST", 0);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (8, "2SEATER", 3);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (9, "SHE", 3);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (10, "EARFQUAKE", 5);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (11, "Garden Shed", 4);

INSERT INTO
  songs (id, song_name, album)
VALUES
  (12, "Who Dat Boy", 4);

CREATE TABLE tags (
  id INTEGER NOT NULL UNIQUE,
  genre INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

INSERT INTO
  tags(id, genre)
VALUES
  (1, 1);

INSERT INTO
  tags(id, genre)
VALUES
  (2, 2);

INSERT INTO
  tags(id, genre)
VALUES
  (3, 3);

INSERT INTO
  tags(id, genre)
VALUES
  (4, 4);

CREATE TABLE songs_tags (
  id INTEGER NOT NULL UNIQUE,
  songs_id INTEGER NOT NULL,
  tags_id INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT),
  FOREIGN KEY (songs_id) REFERENCES songs(id),
  FOREIGN KEY (tags_id) REFERENCES tags(id)
);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (1, 1, 4);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (2, 2, 2);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (3, 3, 1);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (4, 4, 2);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (5, 5, 2);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (6, 6, 1);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (7, 7, 1);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (8, 8, 2);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (9, 9, 3);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (10, 10, 3);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (11, 11, 2);

INSERT INTO
  songs_tags(id, songs_id, tags_id)
VALUES
  (12, 12, 1);

--login tables
CREATE TABLE users (
  id INTEGER NOT NULL UNIQUE,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

-- password: monkey
INSERT INTO
  users (id, username, password)
VALUES
  (
    1,
    'sd625',
    '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.' -- monkey
  );

CREATE TABLE sessions (
  id INTEGER NOT NULL UNIQUE,
  user_id INTEGER NOT NULL,
  session TEXT NOT NULL UNIQUE,
  last_login TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY(user_id) REFERENCES users(id)
);
