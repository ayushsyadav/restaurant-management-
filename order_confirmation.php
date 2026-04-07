<?php
/**
 * Restaurant Order Management System - Order Confirmation Page
 * Displays order details after successful checkout
 */

// Include database configuration
require_once 'includes/config.php';

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id <= 0) {
    header('Location: index.php');
    exit;
}

// Get order details
$sql = "SELECT o.*, c.name, c.phone, c.created_at as customer_since 
        FROM orders o 
        JOIN customers c ON o.customer_id = c.id 
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$order = $result->fetch_assoc();

// Get order items
$sql = "SELECT oi.*, m.item_name, m.image 
        FROM order_items oi 
        JOIN menu m ON oi.menu_id = m.id 
        WHERE oi.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

$order_items = [];
while ($row = $items_result->fetch_assoc()) {
    $order_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Restaurant Order System</title>
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
        <!-- Success Message -->
        <div class="form-container" style="text-align: center; margin-bottom: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
            <h1 style="margin-bottom: 1rem;">Order Confirmed!</h1>
            <p style="font-size: 1.1rem; opacity: 0.9;">Thank you for your order. We're preparing it with care!</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start;">
            <!-- Order Details -->
            <div class="form-container">
                <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);">Order Details</h2>
                
                <div style="background: var(--bg-light); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-light);">Order ID:</span>
                        <strong>#<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-light);">Order Date:</span>
                        <strong><?php echo date('M j, Y, g:i A', strtotime($order['order_date'])); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-light);">Status:</span>
                        <strong style="color: var(--warning-color);"><?php echo ucfirst($order['status']); ?></strong>
                    </div>
                </div>

                <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Customer Information</h3>
                <div style="background: var(--bg-light); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <div style="margin-bottom: 0.5rem;">
                        <strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?>
                    </div>
                    <div style="margin-bottom: 0.5rem;">
                        <strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?>
                    </div>
                    <div>
                        <strong>Customer Since:</strong> <?php echo date('M j, Y', strtotime($order['customer_since'])); ?>
                    </div>
                </div>

                <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Order Items</h3>
                <div style="margin-bottom: 1.5rem;">
                    <?php foreach ($order_items as $item): ?>
                        <div style="display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid #eee;">
                            <img src="images/<?php echo htmlspecialchars($item['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['item_name']); ?>" 
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; margin-right: 1rem;"
                                 onerror="this.src='images/default-food.jpg'">
                            <div style="flex: 1;">
                                <div style="font-weight: 500; margin-bottom: 0.25rem;"><?php echo htmlspecialchars($item['item_name']); ?></div>
                                <div style="color: var(--text-light); font-size: 0.9rem;">
                                    <?php echo $item['quantity']; ?> × <?php echo formatPrice($item['price']); ?>
                                </div>
                            </div>
                            <div style="font-weight: 500; color: var(--primary-color);">
                                <?php echo formatPrice($item['quantity'] * $item['price']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Order Timeline -->
                <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Order Timeline</h3>
                <div style="position: relative; padding-left: 2rem;">
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: #ddd;"></div>
                    
                    <div style="position: relative; margin-bottom: 1.5rem;">
                        <div style="position: absolute; left: -2.4rem; top: 0; width: 12px; height: 12px; background: var(--success-color); border-radius: 50%;"></div>
                        <div>
                            <strong>Order Placed</strong>
                            <div style="color: var(--text-light); font-size: 0.9rem;"><?php echo date('g:i A', strtotime($order['order_date'])); ?></div>
                        </div>
                    </div>
                    
                    <div style="position: relative; margin-bottom: 1.5rem;">
                        <div style="position: absolute; left: -2.4rem; top: 0; width: 12px; height: 12px; background: #ddd; border-radius: 50%;"></div>
                        <div>
                            <strong>Order Confirmed</strong>
                            <div style="color: var(--text-light); font-size: 0.9rem;">Pending</div>
                        </div>
                    </div>
                    
                    <div style="position: relative; margin-bottom: 1.5rem;">
                        <div style="position: absolute; left: -2.4rem; top: 0; width: 12px; height: 12px; background: #ddd; border-radius: 50%;"></div>
                        <div>
                            <strong>Preparing</strong>
                            <div style="color: var(--text-light); font-size: 0.9rem;">Estimated 15-20 mins</div>
                        </div>
                    </div>
                    
                    <div style="position: relative;">
                        <div style="position: absolute; left: -2.4rem; top: 0; width: 12px; height: 12px; background: #ddd; border-radius: 50%;"></div>
                        <div>
                            <strong>Out for Delivery</strong>
                            <div style="color: var(--text-light); font-size: 0.9rem;">Estimated 30-45 mins</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="cart-container">
                <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);">Order Summary</h2>
                
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span class="summary-value"><?php echo formatPrice($order['total_price']); ?></span>
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
                        <span>Total Paid:</span>
                        <span class="summary-value"><?php echo formatPrice($order['total_price']); ?></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="margin-top: 2rem;">
                    <a href="index.php" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                        🍽️ Order Again
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary" style="width: 100%;">
                        🖨️ Print Receipt
                    </button>
                </div>

                <!-- Contact Info -->
                <div style="margin-top: 2rem; padding: 1rem; background: var(--bg-light); border-radius: 8px;">
                    <h4 style="margin-bottom: 0.5rem; color: var(--text-dark);">Need Help?</h4>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">
                        📞 Call: +91 98765 43210<br>
                        📧 Email: support@restaurant.com<br>
                        ⏰ Available 24/7
                    </p>
                </div>

                <!-- Estimated Delivery -->
                <div style="margin-top: 1.5rem; padding: 1rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🚚</div>
                    <h4>Estimated Delivery</h4>
                    <div style="font-size: 1.2rem; font-weight: bold;">
                        <?php 
                        $delivery_time = strtotime($order['order_date']) + (35 * 60); // 35 minutes from order
                        echo date('h:i A', $delivery_time); 
                        ?>
                    </div>
                    <div style="font-size: 0.9rem; opacity: 0.9;">
                        <?php 
                        $delivery_range_min = date('h:i A', strtotime($order['order_date']) + (30 * 60));
                        $delivery_range_max = date('h:i A', strtotime($order['order_date']) + (45 * 60));
                        echo "$delivery_range_min - $delivery_range_max";
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Offers -->
        <div class="form-container" style="margin-top: 3rem; text-align: center;">
            <h3 style="margin-bottom: 1.5rem; color: var(--text-dark);">🎁 Special Offers for You</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div style="padding: 1.5rem; border: 2px dashed var(--primary-color); border-radius: 8px;">
                    <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">20% OFF Next Order</h4>
                    <p style="margin: 0; color: var(--text-light);">Use code: THANKYOU20</p>
                </div>
                <div style="padding: 1.5rem; border: 2px dashed var(--accent-color); border-radius: 8px;">
                    <h4 style="color: var(--accent-color); margin-bottom: 0.5rem;">Free Dessert</h4>
                    <p style="margin: 0; color: var(--text-light);">On orders above ₹500</p>
                </div>
                <div style="padding: 1.5rem; border: 2px dashed var(--success-color); border-radius: 8px;">
                    <h4 style="color: var(--success-color); margin-bottom: 0.5rem;">Loyalty Points</h4>
                    <p style="margin: 0; color: var(--text-light);">Earn 50 points on this order</p>
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
    <script>
        // Auto-refresh order status every 30 seconds
        setInterval(() => {
            // In a real application, you would fetch updated status from server
            console.log('Checking order status...');
        }, 30000);

        // Print receipt function
        function printReceipt() {
            window.print();
        }

        // Share order function
        function shareOrder() {
            if (navigator.share) {
                navigator.share({
                    title: 'My Restaurant Order',
                    text: `I just placed an order at Restaurant Order System! Order ID: #${String(<?php echo $order_id; ?>).padStart(6, '0')}`,
                    url: window.location.href
                });
            }
        }
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
