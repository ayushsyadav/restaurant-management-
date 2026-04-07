<?php
/**
 * Restaurant Order Management System - Cart Update Handler
 * Handles cart item updates (increase, decrease, remove)
 */

// Include database configuration
require_once 'includes/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Validate item ID
    if ($item_id <= 0) {
        header('Location: cart.php?error=invalid_item');
        exit;
    }
    
    // Check if item exists in database
    $sql = "SELECT id FROM menu WHERE id = ? AND available = TRUE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header('Location: cart.php?error=item_not_found');
        exit;
    }
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Perform action
    switch ($action) {
        case 'increase':
            if (isset($_SESSION['cart'][$item_id])) {
                $_SESSION['cart'][$item_id]++;
            } else {
                $_SESSION['cart'][$item_id] = 1;
            }
            break;
            
        case 'decrease':
            if (isset($_SESSION['cart'][$item_id])) {
                if ($_SESSION['cart'][$item_id] > 1) {
                    $_SESSION['cart'][$item_id]--;
                } else {
                    unset($_SESSION['cart'][$item_id]);
                }
            }
            break;
            
        case 'remove':
            if (isset($_SESSION['cart'][$item_id])) {
                unset($_SESSION['cart'][$item_id]);
            }
            break;
            
        case 'update':
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
            if ($quantity > 0) {
                $_SESSION['cart'][$item_id] = min($quantity, 99); // Max 99 items
            } else {
                unset($_SESSION['cart'][$item_id]);
            }
            break;
    }
    
    // Redirect back to cart page
    header('Location: cart.php');
    exit;
}

// If not POST request, redirect to cart
header('Location: cart.php');
exit;

// Close database connection
$conn->close();
?>
