# Contributing to Restaurant Order Management System

Thank you for your interest in contributing to the Restaurant Order Management System! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

### Reporting Bugs

1. **Check existing issues** - Search for existing bug reports before creating a new one
2. **Create a detailed issue** - Include:
   - Clear description of the bug
   - Steps to reproduce
   - Expected vs actual behavior
   - Environment details (PHP version, MySQL version, browser)
   - Screenshots if applicable

### Suggesting Features

1. **Check for duplicates** - Look for existing feature requests
2. **Provide detailed description** - Explain:
   - What problem this feature solves
   - How users would benefit
   - Implementation ideas (optional)
   - Use cases and examples

### Code Contributions

#### Prerequisites
- PHP 7.4+ knowledge
- MySQL/MariaDB experience
- HTML5, CSS3, JavaScript proficiency
- Understanding of MVC patterns (helpful)
- Git and GitHub knowledge

#### Development Setup

1. **Fork the repository**
   ```bash
   git clone https://github.com/ayushsyadav/restaurant-management-
   cd restaurant-management
   ```

2. **Create a development branch**
   ```bash
   git checkout -b feature/your-feature-name
   git checkout -b fix/your-bug-fix
   ```

3. **Set up local environment**
   - Import `database_setup.sql` into your local MySQL
   - Configure `includes/config.php` with your database details
   - Set up a local web server (XAMPP, WAMP, MAMP, or Docker)

#### Coding Standards

Follow these guidelines for consistency:

**PHP Code Style:**
- Use PSR-12 coding standards
- Use meaningful variable and function names
- Add proper comments for complex logic
- Use prepared statements for all database queries
- Validate and sanitize all user inputs

**Example PHP Code:**
```php
<?php
/**
 * Function to get menu items by category
 * 
 * @param string $category The category to filter by
 * @return array Array of menu items
 */
function getMenuItemsByCategory($category) {
    global $conn;
    
    $sql = "SELECT * FROM menu WHERE category = ? AND available = 1 ORDER BY item_name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
```

**JavaScript Code Style:**
- Use ES6+ features when appropriate
- Add JSDoc comments for functions
- Handle errors gracefully
- Use meaningful variable names

**Example JavaScript Code:**
```javascript
/**
 * Add item to shopping cart
 * @param {string} itemId - The ID of the menu item
 * @param {string} itemName - The name of the menu item
 * @param {number} price - The price of the item
 * @param {number} quantity - The quantity to add (default: 1)
 */
function addToCart(itemId, itemName, price, quantity = 1) {
    try {
        // Implementation here
        console.log(`Added ${itemName} to cart`);
    } catch (error) {
        console.error('Error adding to cart:', error);
    }
}
```

**CSS Code Style:**
- Use CSS variables for colors and spacing
- Follow mobile-first responsive design
- Add comments for complex layouts
- Use semantic class names

#### Commit Guidelines

1. **Commit message format:**
   ```
   type(scope): brief description
   
   Detailed explanation (if needed)
   ```

2. **Types:**
   - `feat`: New feature
   - `fix`: Bug fix
   - `docs`: Documentation changes
   - `style`: Code formatting changes
   - `refactor`: Code refactoring
   - `test`: Adding or updating tests
   - `chore`: Maintenance tasks

3. **Examples:**
   ```
   feat(admin): add order status update functionality
   
   fix(cart): resolve quantity update issue on mobile devices
   
   docs(readme): update installation instructions for PHP 8.0
   ```

#### Pull Request Process

1. **Update documentation** - Update README.md if needed
2. **Test thoroughly** - Ensure your changes work as expected
3. **Create pull request** - Provide:
   - Clear title and description
   - Related issue number (if applicable)
   - Testing instructions
   - Screenshots for UI changes

4. **Code review** - Respond to feedback promptly

## 🏗️ Project Structure

```
restaurant-management/
├── admin/                  # Admin panel files
├── css/                    # Stylesheets
├── js/                     # JavaScript files
├── images/                 # Static images
├── includes/               # PHP includes and configuration
├── templates/              # Reusable templates (if added)
├── tests/                  # Unit tests (if added)
├── docs/                   # Additional documentation
├── index.php              # Main entry point
├── cart.php               # Shopping cart
├── checkout.php           # Checkout process
└── README.md              # Project documentation
```

## 🧪 Testing

### Manual Testing Checklist

Before submitting a pull request, test:

- [ ] All pages load without errors
- [ ] Database operations work correctly
- [ ] Form submissions are secure
- [ ] Responsive design works on mobile/tablet
- [ ] Cart functionality works properly
- [ ] Admin panel functions correctly
- [ ] Error handling works as expected

### Browser Testing

Test in at least:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## 📝 Documentation

When adding features:
1. Update README.md if needed
2. Add inline comments for complex code
3. Update database schema documentation
4. Add examples for new functionality

## 🚀 Deployment Considerations

- Ensure code works on PHP 7.4+
- Test with MySQL 5.7+ and MariaDB 10.2+
- Verify all database queries use prepared statements
- Check file permissions for uploads
- Test with different server configurations

## 🤝 Community Guidelines

### Code of Conduct

1. **Be respectful** - Treat all contributors with respect
2. **Be constructive** - Provide helpful feedback
3. **Be inclusive** - Welcome contributors of all skill levels
4. **Be patient** - Help others learn and grow

### Getting Help

- Create an issue for questions
- Join discussions in existing issues
- Check documentation first
- Search for similar problems

## 🏆 Recognition

Contributors will be:
- Listed in the README.md contributors section
- Mentioned in release notes
- Recognized for their valuable contributions

## 📧 Contact

For questions about contributing:
- Create an issue with "question" label
- Email: maintainers@restaurant-order-system.com

---

Thank you for contributing to the Restaurant Order Management System! Your contributions help make this project better for everyone.
