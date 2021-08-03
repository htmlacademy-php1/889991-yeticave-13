CREATE DATABASE yeticave;

CREATE TABLE yeticave.categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  character_code VARCHAR(128) UNIQUE,
  name_category VARCHAR(128)
);
CREATE TABLE yeticave.users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL UNIQUE,
  user_name VARCHAR(128),
  user_password VARCHAR(128),
  contacts TEXT
);
CREATE TABLE yeticave.lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title VARCHAR(255),
  lot_description TEXT,
  img VARCHAR(255),
  start_price INT,
  date_finish DATE,
  step INT,
  user_id INT,
  winner_id INT,
  categori_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (winner_id) REFERENCES users(id),
  FOREIGN KEY (categori_id) REFERENCES categories(id)
);
CREATE TABLE yeticave.bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_bet TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price_bet INT,
  user_id INT,
  lot_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (lot_id) REFERENCES lots(id)
);


