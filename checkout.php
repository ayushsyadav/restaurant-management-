<?php
/**
 * Restaurant Order Management System - Checkout Page
 * Handles customer information and order processing
 */

// Include database configuration
require_once 'includes/config.php';

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php?error=empty_cart');
    exit;
}

// Get cart items for display
$cart_items = [];
$total = 0;

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = sanitize($_POST['customer_name']);
    $customer_phone = sanitize($_POST['customer_phone']);
    $delivery_address = sanitize($_POST['delivery_address']);
    $special_instructions = sanitize($_POST['special_instructions']);
    
    // Validate input
    $errors = [];
    
    if (empty($customer_name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($customer_phone)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match('/^[0-9]{10}$/', $customer_phone)) {
        $errors[] = "Please enter a valid 10-digit phone number";
    }
    
    if (empty($delivery_address)) {
        $errors[] = "Delivery address is required";
    }
    
    if (empty($errors)) {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Insert customer
            $sql = "INSERT INTO customers (name, phone) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $customer_name, $customer_phone);
            $stmt->execute();
            $customer_id = $conn->insert_id;
            
            // Insert order
            $sql = "INSERT INTO orders (customer_id, total_price, status) VALUES (?, ?, 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("id", $customer_id, $total);
            $stmt->execute();
            $order_id = $conn->insert_id;
            
            // Insert order items
            foreach ($cart_items as $item) {
                $sql = "INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
                $stmt->execute();
            }
            
            // Commit transaction
            $conn->commit();
            
            // Clear cart
            unset($_SESSION['cart']);
            
            // Redirect to order confirmation
            header("Location: order_confirmation.php?order_id=$order_id");
            exit;
            
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            $errors[] = "Error processing order: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Restaurant Order System</title>
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
            <h1>Checkout</h1>
            <p>Complete your order details</p>
        </header>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Error:</strong>
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
            <!-- Checkout Form -->
            <div class="form-container">
                <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);">Delivery Information</h2>
                
                <form method="post" id="checkoutForm">
                    <div class="form-group">
                        <label for="customer_name" class="form-label">Full Name *</label>
                        <input type="text" id="customer_name" name="customer_name" 
                               class="form-control" required
                               value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>"
                               placeholder="John Doe">
                    </div>

                    <div class="form-group">
                        <label for="customer_phone" class="form-label">Phone Number *</label>
                        <input type="tel" id="customer_phone" name="customer_phone" 
                               class="form-control" required
                               value="<?php echo isset($_POST['customer_phone']) ? htmlspecialchars($_POST['customer_phone']) : ''; ?>"
                               placeholder="9876543210" maxlength="10" pattern="[0-9]{10}">
                        <small style="color: var(--text-light);">10-digit mobile number</small>
                    </div>

                    <div class="form-group">
                        <label for="delivery_address" class="form-label">Delivery Address *</label>
                        <textarea id="delivery_address" name="delivery_address" 
                                  class="form-control" required rows="3"
                                  placeholder="123 Main Street, Apt 4B, City - 123456"><?php echo isset($_POST['delivery_address']) ? htmlspecialchars($_POST['delivery_address']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="special_instructions" class="form-label">Special Instructions (Optional)</label>
                        <textarea id="special_instructions" name="special_instructions" 
                                  class="form-control" rows="2"
                                  placeholder="e.g., No onions, extra spicy, deliver at gate, etc."><?php echo isset($_POST['special_instructions']) ? htmlspecialchars($_POST['special_instructions']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" id="save_info" name="save_info" style="width: auto;">
                            <span>Save my information for next time</span>
                        </label>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            🚀 Place Order
                        </button>
                        <a href="cart.php" class="btn btn-secondary">
                            ← Back to Cart
                        </a>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="cart-container">
                <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);">Order Summary</h2>
                
                <!-- Order Items -->
                <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem;">
                    <?php foreach ($cart_items as $item): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #eee;">
                            <div style="flex: 1;">
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div style="color: var(--text-light); font-size: 0.9rem;">
                                    <?php echo $item['quantity']; ?> × <?php echo formatPrice($item['price']); ?>
                                </div>
                            </div>
                            <div style="font-weight: 500; color: var(--primary-color);">
                                <?php echo formatPrice($item['subtotal']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Price Summary -->
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span class="summary-value"><?php echo formatPrice($total); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span class="summary-value" style="color: var(--success-color);">FREE</span>
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

                <!-- Delivery Info -->
                <div style="margin-top: 2rem; padding: 1rem; background: var(--bg-light); border-radius: 8px;">
                    <h4 style="margin-bottom: 0.5rem; color: var(--text-dark);">🚚 Delivery Info</h4>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">
                        Estimated delivery: 30-45 minutes<br>
                        Free delivery on all orders
                    </p>
                </div>

                <!-- Payment Methods -->
                <div style="margin-top: 1.5rem;">
                    <h4 style="margin-bottom: 1rem; color: var(--text-dark);">💳 Payment Methods</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                            <input type="radio" name="payment" value="cod" checked style="width: auto;">
                            <span>💵 Cash on Delivery</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                            <input type="radio" name="payment" value="upi" style="width: auto;">
                            <span>📱 UPI Payment</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                            <input type="radio" name="payment" value="card" style="width: auto;">
                            <span>💳 Credit/Debit Card</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Badges -->
        <div class="form-container" style="margin-top: 3rem; text-align: center;">
            <h3 style="margin-bottom: 1.5rem; color: var(--text-dark);">Why Order With Us?</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
                <div>
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔒</div>
                    <h4>Secure Payment</h4>
                    <p style="color: var(--text-light); margin: 0;">100% secure transactions</p>
                </div>
                <div>
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚡</div>
                    <h4>Fast Delivery</h4>
                    <p style="color: var(--text-light); margin: 0;">30-45 minute delivery</p>
                </div>
                <div>
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🌟</div>
                    <h4>Quality Food</h4>
                    <p style="color: var(--text-light); margin: 0;">Fresh ingredients guaranteed</p>
                </div>
                <div>
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📞</div>
                    <h4>24/7 Support</h4>
                    <p style="color: var(--text-light); margin: 0;">Always here to help</p>
                </div>
            </div>
        </div>
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
        // Form validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const phone = document.getElementById('customer_phone').value;
            
            // Validate phone number
            if (!/^[0-9]{10}$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading"></span> Processing...';
            submitBtn.disabled = true;
            
            // Re-enable after 5 seconds in case of network issues
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });

        // Phone number formatting
        document.getElementById('customer_phone').addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 10 digits
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
