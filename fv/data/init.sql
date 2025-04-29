-- Drop existing tables if exist

DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;


-- create users

CREATE TABLE users (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  username       VARCHAR(50)  NOT NULL,
  password       VARCHAR(255) NOT NULL,
  role           VARCHAR(50)  NOT NULL DEFAULT 'USER',
  name           VARCHAR(50),
  surname        VARCHAR(50),
  email          VARCHAR(100),
  phone          VARCHAR(50),
  address_line1  VARCHAR(100),
  address_line2  VARCHAR(100),
  eircode        VARCHAR(20),
  created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);


-- insert sample USERS

INSERT INTO users (username, password, role, name, surname, email, phone, address_line1, address_line2, eircode)
VALUES
('john',  'john',  'ADMIN',    'John',  'Doe',   'john.doe@fruitandveggies.ie', '123-4567',  'Main St 1', 'Dublin City', 'D01ABCD'),
('steve', 'steve', 'USER',     'Steve', 'White', 'steve.white@fruitandveggies.ie', '987-6543', '2 Sunny Road', 'Galway Town', 'H91XYZW'),
('frank', 'frank', 'CUSTOMER', 'Frank', 'Black', 'frank.black@gmail.ie', '085 111 2222', 'Yeats Way 11', 'Park West, Dublin 12', 'D12B888');


-- create PRODUCTS table

CREATE TABLE products (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  product_name   VARCHAR(100) NOT NULL,
  product_price  DECIMAL(6,2) NOT NULL,
  items_in_stock INT          NOT NULL DEFAULT 0,
  product_type   VARCHAR(50)  NOT NULL,
  image_filename VARCHAR(255) DEFAULT NULL,
  unit_type      VARCHAR(50)  DEFAULT NULL,
  description    TEXT,
  created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);


-- insert fruit (10 items)

INSERT INTO products (product_name, product_price, items_in_stock, product_type, image_filename, unit_type, description)
VALUES
('Banana',       1.55,  100, 'FRUIT', 'banana.jpg',       'KG',   'Rich and creamy treat'),
('Grapes',       1.97,   50, 'FRUIT', 'grapes.jpg',       'PACK', 'Fresh grapes'),
('Kiwi',         2.15,   30, 'FRUIT', 'kiwi.jpg',         'PACK', 'Tart and juicy'),
('Apple',        1.15,   70, 'FRUIT', 'apple.jpg',        'KG',   'Crisp and sweet'),
('Orange',       1.25,   40, 'FRUIT', 'orange.jpg',       'KG',   'Classic citrus'),
('Watermelon',   4.15,   20, 'FRUIT', 'watermelon.jpg',   'UNIT', 'Big and refreshing'),
('Pineapple',    2.75,   15, 'FRUIT', 'pineapple.jpg',    'UNIT', 'Tropical juicy fruit'),
('Cherries',     3.99,   25, 'FRUIT', 'cherries.jpg',     'PACK', 'Sweet red cherries'),
('Strawberry',   3.45,   25, 'FRUIT', 'strawberry.jpg',   'PACK', 'Fresh sweet strawberries'),
('Mango',        0.99,   10, 'FRUIT', 'mango.jpg',        'UNIT', 'Tropical mango');


-- insert veggies (10 items)

INSERT INTO products (product_name, product_price, items_in_stock, product_type, image_filename, unit_type, description)
VALUES
('Celery',       1.55,   40, 'VEG',   'celery.jpg',       'PACK', 'Crisp fresh celery'),
('Tomato',       1.97,   50, 'VEG',   'tomato.jpg',       'KG',   'Ripe tomatoes'),
('Onion',        2.15,   60, 'VEG',   'onion.jpg',        'KG',   'Pungent onion'),
('Carrot',       1.15,   45, 'VEG',   'carrot.jpg',       'KG',   'Orange carrots'),
('Red Pepper',   1.25,   30, 'VEG',   'redpepper.jpg',    'KG',   'Sweet red peppers'),
('Green Cabbage',4.15,   20, 'VEG',   'greencabbage.jpg', 'UNIT', 'Crisp head of cabbage'),
('Potato',       2.75,   80, 'VEG',   'potato.jpg',       'KG',   'Hearty spuds'),
('Mushrooms',    3.99,   25, 'VEG',   'mushrooms.jpg',    'PACK', 'Versatile mushrooms'),
('Chilli',       0.85,   20, 'VEG',   'chilli.jpg',       'PACK', 'Hot chili peppers'),
('Leek',         1.45,   35, 'VEG',   'leek.jpg',         'UNIT', 'Green layered vegetable');