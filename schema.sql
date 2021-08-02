CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

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
  user_name INT,
  winner VARCHAR(128),
  categori INT,
  FOREIGN KEY (user_name) REFERENCES users(id),
  FOREIGN KEY (categori) REFERENCES categories(id)
);
CREATE TABLE yeticave.bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_bet TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price_bet INT,
  user_name INT,
  lot INT,
  FOREIGN KEY (user_name) REFERENCES users(id),
  FOREIGN KEY (lot) REFERENCES lots(id)
);


