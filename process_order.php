<?php
/**
 * Restaurant Order Management System - Order Processing
 * Handles AJAX order submissions from checkout
 */

// Include database configuration
require_once 'includes/config.php';

// Set content type for JSON response
header('Content-Type: application/json');

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get JSON data
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit;
}

// Validate required fields
$required_fields = ['customer_name', 'customer_phone', 'items', 'total'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

$customer_name = sanitize($data['customer_name']);
$customer_phone = sanitize($data['customer_phone']);
$items = $data['items'];
$total = floatval($data['total']);

// Validate phone number
if (!preg_match('/^[0-9]{10}$/', $customer_phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid phone number']);
    exit;
}

// Validate items
if (empty($items) || !is_array($items)) {
    echo json_encode(['success' => false, 'message' => 'No items in order']);
    exit;
}

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
    foreach ($items as $menu_id => $item_data) {
        $quantity = intval($item_data['quantity']);
        $price = floatval($item_data['price']);
        
        if ($quantity > 0 && $price > 0) {
            $sql = "INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiid", $order_id, $menu_id, $quantity, $price);
            $stmt->execute();
        }
    }
    
    // Commit transaction
    $conn->commit();
    
    // Clear cart session
    unset($_SESSION['cart']);
    
    // Return success response
    echo json_encode([
        'success' => true, 
        'order_id' => $order_id,
        'message' => 'Order placed successfully!'
    ]);
    
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    
    // Return error response
    echo json_encode([
        'success' => false, 
        'message' => 'Error processing order: ' . $e->getMessage()
    ]);
}

// Close database connection
$conn->close();
?>
