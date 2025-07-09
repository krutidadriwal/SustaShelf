-- Migration script to add metal column to existing products table
-- Run this if you have an existing database without the metal column

USE shp;

-- Add metal column to products table
ALTER TABLE products ADD COLUMN metal VARCHAR(50);

-- Update existing products with metal values
UPDATE products SET metal = 'aluminum' WHERE id = 1;
UPDATE products SET metal = 'aluminum' WHERE id = 2;

-- Add some sample products with different metals
INSERT INTO products (id, product, price, sale_year, warranty_years, image, metal) 
VALUES  
    (3, 'Gold Ring', 25000, 2022, 1, '../images/ring.jpg', 'gold'),
    (4, 'Silver Watch', 15000, 2020, 3, '../images/watch.jpg', 'silver'),
    (5, 'Copper Bottle', 800, 2019, 5, '../images/bottle.jpg', 'copper'),
    (6, 'Steel Frame', 5000, 2018, 10, '../images/frame.jpg', 'steel')
ON DUPLICATE KEY UPDATE 
    metal = VALUES(metal);

-- Add some sample purchase data for testing
INSERT INTO purchases (username, product_id) VALUES
('test8', 1),
('test8', 3),
('test8', 5)
ON DUPLICATE KEY UPDATE 
    username = VALUES(username);