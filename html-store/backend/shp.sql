CREATE DATABASE shp;

USE shp;

CREATE TABLE users (
    name TEXT NOT NULL,
    username VARCHAR(18) NOT NULL PRIMARY KEY,
    city VARCHAR(20) NOT NULL,
    email VARCHAR(21) NOT NULL UNIQUE,
    password TEXT NOT NULL
);

INSERT INTO users (name, username, city, email, password)
VALUES ('Kruti', 'test8', 'mumbai', 'so@gmail.com', '1234_x');

CREATE TABLE products (
    id INT NOT NULL PRIMARY KEY,
    product TEXT NOT NULL,
    price INT NOT NULL,
    sale_year INT,
    warranty_years INT,
    image VARCHAR(255),
    metal VARCHAR(50)
);

INSERT INTO products (id, product, price, sale_year, warranty_years, image, metal) 
VALUES  
    (1, 'Smartphone', 3000, 2023, 2, '../images/smartphone.jpg', 'aluminum'), 
    (2, 'Laptop', 3000000, 2021, 4, '../images/laptop.jpg', 'aluminum'),
    (3, 'Gold Ring', 25000, 2022, 1, '../images/ring.jpg', 'gold'),
    (4, 'Silver Watch', 15000, 2020, 3, '../images/watch.jpg', 'silver'),
    (5, 'Copper Bottle', 800, 2019, 5, '../images/bottle.jpg', 'copper'),
    (6, 'Tiffin', 5000, 2018, 10, '../images/tiffin.jpg', 'steel');


CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(18),
    product_id INT,
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Sample purchase data for testing
INSERT INTO purchases (username, product_id) VALUES
('test8', 1),
('test8', 3),
('test8', 5);

-- Add some sample purchase data for testing
INSERT INTO purchases (username, product_id) VALUES
('test8', 1),
('test8', 3),
('test8', 5)
ON DUPLICATE KEY UPDATE 
    username = VALUES(username);

-- to retrieve the info needed for bhoomi's map

-- SELECT u.username, u.city, p.product, p.sale_year, p.warranty_years
-- FROM purchases pu
-- JOIN users u ON pu.username = u.username
-- JOIN products p ON pu.product_id = p.id;
