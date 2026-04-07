-- Restaurant Order Management System Database Setup
-- Create database and tables with sample data

-- Create database
CREATE DATABASE IF NOT EXISTS restaurant_db;
USE restaurant_db;

-- Create customers table
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create menu table
CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255) DEFAULT 'default-food.jpg',
    description TEXT,
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    total_price DECIMAL(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);

-- Insert sample menu items
INSERT INTO menu (item_name, price, category, image, description) VALUES
('Margherita Pizza', 299.00, 'Pizza', 'pizza.jpg', 'Fresh tomatoes, mozzarella cheese, basil leaves on thin crust'),
('Pepperoni Pizza', 349.00, 'Pizza', 'pizza-pepperoni.jpg', 'Classic pepperoni with mozzarella cheese and tomato sauce'),
('Veg Burger', 149.00, 'Burger', 'veggie-burger.jpg', 'Crispy vegetable patty with lettuce, tomato, and special sauce'),
('Chicken Burger', 189.00, 'Burger', 'chicken-burger.jpg', 'Grilled chicken patty with cheese and fresh vegetables'),
('Caesar Salad', 199.00, 'Salad', 'caesar-salad.jpg', 'Romaine lettuce, parmesan cheese, croutons, caesar dressing'),
('Greek Salad', 179.00, 'Salad', 'greek-salad.jpg', 'Fresh vegetables with feta cheese and olives'),
('Pasta Carbonara', 249.00, 'Pasta', 'pasta-carbonara.jpg', 'Creamy pasta with bacon, eggs, and parmesan'),
('Pasta Alfredo', 229.00, 'Pasta', 'pasta-alfredo.jpg', 'Fettuccine pasta in rich alfredo sauce'),
('French Fries', 99.00, 'Starter', 'french-fries.jpg', 'Crispy golden potato fries with seasoning'),
('Garlic Bread', 119.00, 'Starter', 'garlic-bread.jpg', 'Toasted bread with garlic butter and herbs'),
('Coca Cola', 49.00, 'Beverage', 'cola.jpg', 'Refreshing cola drink'),
('Lemonade', 59.00, 'Beverage', 'lemonade.jpg', 'Fresh lemonade with mint'),
('Chocolate Cake', 149.00, 'Dessert', 'chocolate-cake.jpg', 'Rich chocolate cake with chocolate frosting'),
('Ice Cream Sundae', 129.00, 'Dessert', 'ice-cream.jpg', 'Vanilla ice cream with chocolate sauce and nuts'),
('Chicken Wings', 219.00, 'Starter', 'chicken-wings.jpg', 'Spicy chicken wings with dipping sauce'),
('Veg Sandwich', 139.00, 'Sandwich', 'veg-sandwich.jpg', 'Fresh vegetables sandwich with cheese');

-- Create admin user (optional - for future authentication)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@restaurant.com');

-- Create indexes for better performance
CREATE INDEX idx_menu_category ON menu(category);
CREATE INDEX idx_orders_date ON orders(order_date);
CREATE INDEX idx_customer_phone ON customers(phone);
CREATE INDEX idx_menu_available ON menu(available);

-- Display success message
SELECT 'Restaurant database setup completed successfully!' as message;
