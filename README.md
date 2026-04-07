# 🍽️ Restaurant Order Management System

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/Status-Production%20Ready-green.svg)](https://github.com)

A modern, full-stack web application for managing restaurant orders with a beautiful responsive interface. Built with PHP, MySQL, HTML5, CSS3, and JavaScript. Perfect for small to medium-sized restaurants looking to digitize their ordering system.

## ✨ Features

### 🛒 Customer Side
- **🍽️ Dynamic Menu Display**: Browse menu items by category with beautiful card layouts
- **🛒 Shopping Cart**: Add/remove items, update quantities with real-time calculations
- **🔍 Search & Filter**: Find items quickly by name or category
- **📱 Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **🔒 Secure Checkout**: Complete order process with customer information validation
- **📧 Order Confirmation**: Track order status and view detailed order information
- **🎨 Modern UI**: Beautiful animations, hover effects, and professional design

### ⚙️ Admin Side
- **📊 Dashboard**: Overview of orders, revenue, and system statistics
- **🍽️ Menu Management**: Add, edit, delete menu items with image upload support
- **📋 Order Management**: View all orders, update status, filter by date/status
- **👥 Customer Management**: View customer details and order history
- **🔄 Real-time Updates**: Track order status from pending to completion
- **📈 Analytics**: Popular items, revenue tracking, and order statistics
- **🎛️ Modern Admin Interface**: Clean, intuitive admin panel with sidebar navigation

## 🛠️ Technology Stack

### Frontend
- **HTML5**: Semantic markup for better accessibility
- **CSS3**: Modern responsive design with animations and transitions
- **JavaScript**: Dynamic cart management and interactive features
- **Responsive Design**: Mobile-first approach with CSS Grid and Flexbox

### Backend
- **PHP**: Server-side logic and database operations
- **MySQL**: Relational database for storing menu, orders, and customer data
- **mysqli**: Secure database connectivity with prepared statements

### Design Features
- **Modern UI**: Clean, professional restaurant theme
- **Animations**: Smooth transitions and hover effects
- **Color Scheme**: Restaurant-appropriate color palette
- **Typography**: Clean, readable fonts
- **Icons**: Emoji icons for universal understanding

## � Quick Start

### 🎯 One-Click Setup (Recommended)

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/restaurant-management.git
   cd restaurant-management
   ```

2. **Import Database**
   ```bash
   mysql -u root -p restaurant_db < database_setup.sql
   ```

3. **Configure Database**
   Edit `includes/config.php` with your database credentials:
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = 'your_password';
   $database = 'restaurant_db';
   ```

4. **Start Using!**
   - **Customer Interface**: `http://localhost/restaurant-management/`
   - **Admin Panel**: `http://localhost/restaurant-management/admin/`

### 📋 Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher  
- Web server (Apache/Nginx)
- PHP extensions: `mysqli`, `gd` (for image processing)

---

## �📁 Project Structure

```
restaurant-management/
├── 📂 admin/                  # Admin panel files
│   ├── 📄 index.php          # Admin dashboard
│   ├── 📄 menu.php           # Menu management
│   ├── 📄 orders.php         # Order management
│   └── 📄 customers.php      # Customer management
├── 📂 css/
│   └── 📄 style.css          # Main stylesheet
├── 📂 js/
│   └── 📄 cart.js            # Cart functionality
├── 📂 images/                # Product and UI images
├── 📂 includes/
│   └── 📄 config.php         # Database configuration
├── 📄 index.php              # Main menu page
├── 📄 cart.php               # Shopping cart
├── 📄 checkout.php           # Checkout process
├── 📄 order_confirmation.php # Order confirmation
├── 📄 update_cart.php        # Cart update handler
├── 📄 process_order.php      # AJAX order processing
├── 📄 database_setup.sql     # Database structure and sample data
├── 📄 README.md              # This file
├── 📄 CONTRIBUTING.md        # Contributing guidelines
├── 📄 LICENSE                # MIT License
└── 📄 .gitignore             # Git ignore file
```

## 🗄️ Database Schema

### Tables
1. **customers**: Customer information
   - id, name, phone, created_at

2. **menu**: Menu items
   - id, item_name, price, category, image, description, available, created_at

3. **orders**: Order information
   - id, customer_id, total_price, order_date, status

4. **order_items**: Order line items
   - id, order_id, menu_id, quantity, price

5. **admin_users**: Admin authentication (optional)
   - id, username, password, email, created_at

## 🚀 Installation Guide

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- PHP extensions: mysqli, gd (for image processing)

### Step 1: Database Setup
1. Create a MySQL database named `restaurant_db`
2. Import the `database_setup.sql` file:
   ```sql
   mysql -u root -p restaurant_db < database_setup.sql
   ```

### Step 2: Configuration
1. Update database credentials in `includes/config.php`:
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = 'your_password';
   $database = 'restaurant_db';
   ```

### Step 3: File Permissions
Set appropriate permissions for the `images/` directory:
```bash
chmod 755 images/
chmod 644 images/*
```

### Step 4: Web Server Configuration
Place the project files in your web root:
- Apache: `/var/www/html/restaurant-management/`
- Nginx: `/usr/share/nginx/html/restaurant-management/`

### Step 5: Access the Application
- **Customer Interface**: `http://localhost/restaurant-management/`
- **Admin Panel**: `http://localhost/restaurant-management/admin/`

## 📱 Usage Guide

### For Customers
1. Browse the menu on the main page
2. Add items to cart using the "Add to Cart" button
3. View cart and update quantities
4. Proceed to checkout with customer details
5. Receive order confirmation with tracking

### For Admins
1. Access admin panel at `/admin/`
2. View dashboard statistics
3. Manage menu items (add/edit/delete)
4. Process orders and update status
5. View customer information and order history

## 🎨 Customization

### Adding New Categories
1. Update the `$all_categories` array in `admin/menu.php`
2. Add category-specific icons in `index.php` if needed

### Customizing Colors
Edit CSS variables in `css/style.css`:
```css
:root {
    --primary-color: #e74c3c;    /* Red */
    --secondary-color: #2c3e50;  /* Dark blue */
    --accent-color: #f39c12;     /* Orange */
    /* ... other variables */
}
```

### Adding New Features
- Follow the existing code structure and naming conventions
- Use prepared statements for database operations
- Implement proper error handling and validation
- Maintain responsive design principles

## 🔒 Security Features

- **SQL Injection Prevention**: All database queries use prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Form tokens for sensitive operations
- **File Upload Security**: Image validation and secure file handling
- **Session Management**: Secure session configuration

## 🚀 Performance Optimizations

- **Database Indexing**: Optimized queries with proper indexes
- **Image Optimization**: Compressed images for faster loading
- **CSS/JS Minification**: Optimized assets (in production)
- **Caching**: Browser caching for static assets
- **Lazy Loading**: Images load as needed

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config.php`
   - Ensure MySQL server is running
   - Verify database exists and user has permissions

2. **Images Not Displaying**
   - Check file permissions in `images/` directory
   - Verify image paths in database
   - Ensure GD extension is enabled

3. **Cart Not Working**
   - Check JavaScript console for errors
   - Ensure session is properly configured
   - Verify `js/cart.js` is loading correctly

4. **Admin Panel Access Issues**
   - Check file permissions in `admin/` directory
   - Verify PHP error reporting settings
   - Ensure proper URL rewriting if using

### Debug Mode
To enable debug mode, add this to `config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📈 Future Enhancements

### Planned Features
- [ ] Payment Gateway Integration (PayPal, Stripe)
- [ ] Email Notifications for Orders
- [ ] SMS Alerts for Order Status
- [ ] Advanced Analytics Dashboard
- [ ] Multi-language Support
- [ ] Delivery Tracking System
- [ ] Customer Reviews and Ratings
- [ ] Loyalty Program Integration
- [ ] Mobile App (React Native)
- [ ] API for Third-party Integration

### Technical Improvements
- [ ] RESTful API Development
- [ ] Docker Containerization
- [ ] Redis Caching Implementation
- [ ] Automated Testing Suite
- [ ] CI/CD Pipeline Setup
- [ ] Load Balancing Configuration
- [ ] Database Optimization
- [ ] Security Audit Implementation

## 📸 Screenshots

### 🛒 Customer Interface
- **Menu Display**: Beautiful card-based menu layout
- **Shopping Cart**: Real-time cart management
- **Checkout Process**: Secure and user-friendly checkout
- **Order Tracking**: Live order status updates

### ⚙️ Admin Panel  
- **Dashboard**: Comprehensive overview with statistics
- **Menu Management**: Easy-to-use menu item editor
- **Order Management**: Advanced order filtering and management
- **Analytics**: Business insights and reports

*(Add screenshots here when deploying to GitHub)*

---

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for details.

### 🎯 How to Contribute
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### � Development Guidelines
- Follow PSR-12 coding standards
- Use prepared statements for all database queries
- Test on mobile and desktop browsers
- Update documentation for new features

---

## � Troubleshooting

### Common Issues

| Issue | Solution |
|-------|----------|
| **Database Connection Error** | Check `config.php` credentials and ensure MySQL is running |
| **Images Not Displaying** | Verify `images/` directory permissions (755) |
| **Cart Not Working** | Check browser console for JavaScript errors |
| **Admin Panel Access** | Ensure proper file permissions and PHP error reporting |

### � Debug Mode
Enable debugging by adding to `config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## 📈 Roadmap

### 🚀 Upcoming Features
- [ ] 💳 Payment Gateway Integration (PayPal, Stripe)
- [ ] 📧 Email/SMS Notifications
- [ ] 📱 Mobile App (React Native)
- [ ] 🌍 Multi-language Support
- [ ] 🍕 Online Payment Processing
- [ ] 🚚 Delivery Tracking System
- [ ] ⭐ Customer Reviews & Ratings
- [ ] 🎯 Loyalty Program
- [ ] 📊 Advanced Analytics Dashboard
- [ ] 🔐 Two-Factor Authentication

### 🛠️ Technical Improvements
- [ ] RESTful API Development
- [ ] Docker Containerization
- [ ] Redis Caching
- [ ] Automated Testing Suite
- [ ] CI/CD Pipeline

---

## 📞 Support & Community

- **📧 Email**: support@restaurant-order-system.com
- **💬 Issues**: [GitHub Issues](https://github.com/YOUR_USERNAME/restaurant-management/issues)
- **📖 Documentation**: [Wiki](https://github.com/YOUR_USERNAME/restaurant-management/wiki)
- **🏆 Contributing**: See [CONTRIBUTING.md](CONTRIBUTING.md)

---

## 🏆 Show Your Support

⭐ If this project helped you, please give it a star on GitHub!

🐛 Found a bug? [Report it here](https://github.com/YOUR_USERNAME/restaurant-management/issues/new)

💡 Have an idea? [Request a feature](https://github.com/YOUR_USERNAME/restaurant-management/issues/new?template=feature_request.md)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Contributors

Thanks to all the people who contribute to this project!

<a href="https://github.com/YOUR_USERNAME/restaurant-management/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=YOUR_USERNAME/restaurant-management" />
</a>

---

**🍽️ Made with ❤️ for restaurants worldwide!**
