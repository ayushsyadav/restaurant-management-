<?php
/**
 * Restaurant Order Management System - Cart Page
 * Displays cart items and allows checkout
 */

// Include database configuration
require_once 'includes/config.php';

// Get cart items from database if session cart exists
$cart_items = [];
$total = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $menu_ids = array_keys($_SESSION['cart']);
    $ids = implode(',', $menu_ids);
    
    $sql = "SELECT * FROM menu WHERE id IN ($ids)";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $quantity = $_SESSION['cart'][$row['id']];
            $subtotal = $row['price'] * $quantity;
            $total += $subtotal;
            
            $cart_items[] = [
                'id' => $row['id'],
                'name' => $row['item_name'],
                'price' => $row['price'],
                'quantity' => $quantity,
                'image' => $row['image'],
                'subtotal' => $subtotal
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Restaurant Order System</title>
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
                <li><a href="cart.php" class="active">🛒 Cart</a></li>
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
            <h1>Shopping Cart</h1>
            <p>Review your order and proceed to checkout</p>
        </header>

        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
            <!-- Cart Items -->
            <div class="cart-container">
                <div class="cart-items">
                    <?php if (!empty($cart_items)): ?>
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item">
                                <img src="images/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                     class="cart-item-image"
                                     onerror="this.src='images/default-food.jpg'">
                                <div class="cart-item-details">
                                    <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="cart-item-price"><?php echo formatPrice($item['price']); ?></div>
                                </div>
                                <div class="quantity-controls">
                                    <form method="post" action="update_cart.php" style="display: flex; align-items: center; gap: 0.5rem;">
                                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                               min="1" max="99" class="quantity-input" readonly>
                                        <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                                    </form>
                                    <form method="post" action="update_cart.php" style="display: inline;">
                                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                        <input type="hidden" name="action" value="remove">
                                        <button type="submit" class="quantity-btn" style="background: #e74c3c;">×</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="cart-empty">
                            <div class="cart-empty-icon">🛒</div>
                            <h2>Your cart is empty</h2>
                            <p>Looks like you haven't added any items to your cart yet.</p>
                            <a href="index.php" class="btn btn-primary" style="margin-top: 1rem;">Browse Menu</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Cart Summary -->
            <?php if (!empty($cart_items)): ?>
                <div class="cart-container">
                    <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);">Order Summary</h2>
                    
                    <div class="cart-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span class="summary-value"><?php echo formatPrice($total); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Fee:</span>
                            <span class="summary-value">FREE</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span class="summary-value">Included</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span class="summary-value"><?php echo formatPrice($total); ?></span>
                        </div>
                    </div>

                    <div style="margin-top: 2rem;">
                        <a href="checkout.php" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                            🚀 Proceed to Checkout
                        </a>
                        <a href="index.php" class="btn btn-secondary" style="width: 100%; margin-top: 1rem;">
                            ← Continue Shopping
                        </a>
                    </div>

                    <!-- Promo Code (Optional Feature) -->
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eee;">
                        <h4 style="margin-bottom: 1rem; color: var(--text-dark);">Have a promo code?</h4>
                        <form style="display: flex; gap: 0.5rem;">
                            <input type="text" placeholder="Enter promo code" class="form-control" style="flex: 1;">
                            <button type="submit" class="btn btn-secondary">Apply</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Special Offers Section -->
        <?php if (!empty($cart_items)): ?>
            <div class="form-container" style="margin-top: 3rem;">
                <h3 style="margin-bottom: 1.5rem; color: var(--text-dark);">🔥 Special Offers</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <div style="padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px;">
                        <h4>Free Delivery</h4>
                        <p style="margin: 0; opacity: 0.9;">On orders above ₹500</p>
                    </div>
                    <div style="padding: 1rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 8px;">
                        <h4>10% Off</h4>
                        <p style="margin: 0; opacity: 0.9;">Use code: FIRST10</p>
                    </div>
                    <div style="padding: 1rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 8px;">
                        <h4>Buy 2 Get 1</h4>
                        <p style="margin: 0; opacity: 0.9;">On selected items</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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
    
    <script>
        // Initialize cart with server data
        document.addEventListener('DOMContentLoaded', () => {
            // Set cart items from server for JavaScript cart manager
            const serverCartItems = <?php echo json_encode($cart_items); ?>;
            
            if (window.cartManager && serverCartItems.length > 0) {
                // Update JavaScript cart with server data
                serverCartItems.forEach(item => {
                    if (!window.cartManager.cart[item.id]) {
                        window.cartManager.cart[item.id] = {
                            name: item.name,
                            price: item.price,
                            quantity: item.quantity,
                            image: item.image
                        };
                    }
                });
                window.cartManager.updateCartUI();
            }
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
