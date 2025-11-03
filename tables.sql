CREATE DATABASE fitness_center_db;
USE fitness_center_db;

CREATE TABLE users (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  password VARCHAR(255)
);

CREATE TABLE members (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  phone VARCHAR(20),
  plan VARCHAR(50),
  join_date DATE,
  fee DECIMAL(10,2),
  image VARCHAR(255),
  months INT(11) DEFAULT 1
);
