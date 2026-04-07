<?php
/**
 * Restaurant Order Management System - Main Page
 * Displays menu items and handles cart functionality
 */

// Include database configuration
require_once 'includes/config.php';

// Get menu items from database
$sql = "SELECT * FROM menu WHERE available = TRUE ORDER BY category, item_name";
$result = $conn->query($sql);

// Get unique categories for filter
$categories = [];
if ($result->num_rows > 0) {
    $all_items = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($all_items as $item) {
        if (!in_array($item['category'], $categories)) {
            $categories[] = $item['category'];
        }
    }
    // Reset result pointer
    $result->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Order System - Menu</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">Restaurant Order</a>
            <ul class="nav-links">
                <li><a href="index.php">🍽️ Menu</a></li>
                <li><a href="cart.php">🛒 Cart</a></li>
                <li><a href="admin/">⚙️ Admin</a></li>
                <li>
                    <a href="cart.php" class="cart-icon">
                        🛒 Cart
                        <span class="cart-count" style="display: none;">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <!-- Page Header -->
        <header class="page-header">
            <h1>Our Delicious Menu</h1>
            <p>Fresh ingredients, authentic flavors, delivered with love</p>
        </header>

        <!-- Search Bar -->
        <div class="form-container" style="margin-bottom: 2rem;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <input type="text" id="searchInput" placeholder="🔍 Search menu items..." class="form-control" style="flex: 1;" onkeyup="searchMenu()">
                <button class="btn btn-primary" onclick="searchMenu()">Search</button>
            </div>
        </div>

        <!-- Category Filter -->
        <section class="category-filter">
            <div class="category-buttons">
                <button class="category-btn active" data-category="all">🍽️ All Items</button>
                <?php foreach ($categories as $category): ?>
                    <button class="category-btn" data-category="<?php echo strtolower($category); ?>">
                        <?php
                        // Add emoji based on category
                        $emoji = '';
                        switch (strtolower($category)) {
                            case 'pizza': $emoji = '🍕'; break;
                            case 'burger': $emoji = '🍔'; break;
                            case 'salad': $emoji = '🥗'; break;
                            case 'pasta': $emoji = '🍝'; break;
                            case 'starter': $emoji = '🍟'; break;
                            case 'beverage': $emoji = '🥤'; break;
                            case 'dessert': $emoji = '🍰'; break;
                            case 'sandwich': $emoji = '🥪'; break;
                            default: $emoji = '🍽️';
                        }
                        echo $emoji . ' ' . htmlspecialchars($category);
                        ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Menu Grid -->
        <section class="menu-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="menu-card fade-in" data-category="<?php echo strtolower($row['category']); ?>">
                        <div style="overflow: hidden; height: 200px;">
                            <img src="images/<?php echo htmlspecialchars($row['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['item_name']); ?>" 
                                 class="menu-card-image"
                                 onerror="this.src='images/default-food.jpg'">
                        </div>
                        <div class="menu-card-body">
                            <h3 class="menu-card-title"><?php echo htmlspecialchars($row['item_name']); ?></h3>
                            <span class="menu-card-category"><?php echo htmlspecialchars($row['category']); ?></span>
                            <?php if (!empty($row['description'])): ?>
                                <p class="menu-card-description"><?php echo htmlspecialchars($row['description']); ?></p>
                            <?php endif; ?>
                            <div class="menu-card-footer">
                                <span class="menu-card-price"><?php echo formatPrice($row['price']); ?></span>
                                <button class="add-to-cart-btn" 
                                        data-item-id="<?php echo $row['id']; ?>"
                                        data-item-name="<?php echo htmlspecialchars($row['item_name']); ?>"
                                        data-item-price="<?php echo $row['price']; ?>">
                                    🛒 Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="form-container" style="grid-column: 1 / -1; text-align: center;">
                    <h2 style="color: var(--text-light); margin-bottom: 1rem;">No menu items available</h2>
                    <p style="color: var(--text-light);">Please check back later or contact the restaurant.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Restaurant Order System. All rights reserved.</p>
            <p>📞 Call us: +91 98765 43210 | 📍 Location: Main Street, City</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/cart.js"></script>
    
    <!-- Initialize cart count from PHP session -->
    <script>
        // Set initial cart count from server if available
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            const serverCartCount = <?php echo array_sum($_SESSION['cart']); ?>;
            if (serverCartCount > 0) {
                document.addEventListener('DOMContentLoaded', () => {
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(element => {
                        element.textContent = serverCartCount;
                        element.style.display = 'flex';
                    });
                });
            }
        <?php endif; ?>
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
