<?php

require_once("dbconn.php");

/**
 * Helper function to safely execute a prepared statement or return dummy data
 */
function safeQuery($query, $params = [], $fetchMode = 'all', $dummyData = []) {
    global $conn;
    
    if (!$conn instanceof PDO) {
        // Return dummy data if no connection
        return $dummyData;
    }
    
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        
        if ($fetchMode === 'all') {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($fetchMode === 'one') {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($fetchMode === 'column') {
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        return [];
    } catch (PDOException $e) {
        // In production: log error instead of displaying
        // error_log("Database error: " . $e->getMessage());
        return $dummyData;
    }
}

// ────────────────────────────────────────────────
// CATEGORY & PRODUCT FUNCTIONS (mostly dummy for now)
// ────────────────────────────────────────────────

function getCategories() {
    // Real version (uncomment when DB is ready):
    /*
    return safeQuery("SELECT * FROM categories ORDER BY category_name");
    */
    
    // Dummy data
    return [
        ['category_id' => 1, 'category_name' => 'Essentials'],
        ['category_id' => 2, 'category_name' => 'Accessories'],
    ];
}

function getProducts() {
    // Real version:
    /*
    return safeQuery("SELECT * FROM products ORDER BY product_id DESC");
    */
    
    // Dummy
    return [
        [
            'product_id' => 1,
            'product_name' => 'Sample Product 1',
            'product_price' => 29.99,
            'product_image' => 'assets/images/mockup.webp',
            'product_description' => 'This is a sample product.',
            'category_id' => 1
        ],
        [
            'product_id' => 2,
            'product_name' => 'Sample Product 2',
            'product_price' => 39.99,
            'product_image' => 'assets/images/young-model-fashion-shoot.jpg',
            'product_description' => 'Another sample product.',
            'category_id' => 1
        ]
    ];
}

function getSearchProducts($product_id) {
    // Real:
    /*
    return safeQuery(
        "SELECT * FROM products WHERE product_id = ?",
        [$product_id],
        'one',
        null
    ) ?: [];
    */
    
    // Dummy
    return [
        'product_id' => $product_id,
        'product_name' => 'Searched Product',
        'product_price' => 25.99,
        'product_image' => 'assets/images/hero-img5.webp',
        'product_description' => 'Searched product description.',
        'category_id' => 1
    ];
}

function getProductsByCategory($category_id) {
    // Real:
    /*
    return safeQuery(
        "SELECT * FROM products WHERE category_id = ?",
        [$category_id]
    );
    */
    
    // Dummy
    return [
        [
            'product_id' => 1,
            'product_name' => 'Category Product 1',
            'product_price' => 19.99,
            'product_image' => 'assets/images/dressSketch.webp',
            'product_description' => 'Product in category.',
            'category_id' => $category_id
        ]
    ];
}

function getTopProducts() {
    // Real version (this was the crashing function):
    /*
    return safeQuery(
        "SELECT p.*, SUM(oi.quantity) AS total_sold
         FROM order_items oi
         JOIN products p ON oi.product_id = p.product_id
         GROUP BY p.product_id, p.product_name
         ORDER BY total_sold DESC
         LIMIT 4"
    );
    */
    
    // Dummy
    return [
        [
            'product_id' => 1,
            'product_name' => 'Top Product 1',
            'product_price' => 49.99,
            'product_image' => 'assets/images/recentWork.jpg',
            'total_sold' => 100
        ],
        [
            'product_id' => 2,
            'product_name' => 'Top Product 2',
            'product_price' => 59.99,
            'product_image' => 'assets/images/recentWork2.jpg',
            'total_sold' => 80
        ]
    ];
}

// ────────────────────────────────────────────────
// USER FUNCTIONS
// ────────────────────────────────────────────────

function getUsers() {
    // Real:
    /*
    return safeQuery("SELECT * FROM users");
    */
    
    // Dummy
    return [
        [
            'user_id' => 1,
            'user_name' => 'Admin User',
            'user_email' => 'admin@example.com',
            'user_type' => 'admin'
        ]
    ];
}

function getUsersDesc() {
    return safeQuery(
        "SELECT * FROM users ORDER BY created_at DESC"
    );
}

function getSearchUser($user_id) {
    return safeQuery(
        "SELECT * FROM users WHERE user_id = ?",
        [$user_id],
        'one'
    ) ?: [];
}

function getUserType($user_email) {
    return safeQuery(
        "SELECT * FROM users WHERE user_email = ?",
        [$user_email],
        'one'
    ) ?: [];
}

// ────────────────────────────────────────────────
// REVIEWS
// ────────────────────────────────────────────────

function getReviews() {
    // Limited version for frontend
    /*
    return safeQuery(
        "SELECT r.review_id, r.rating, r.comment, r.review_date,
                u.user_id, u.user_name, u.user_email, u.user_type, u.user_profile_image
         FROM reviews r
         JOIN users u ON r.user_id = u.user_id
         ORDER BY r.review_date DESC
         LIMIT 10"
    );
    */
    
    // Dummy
    return [
        [
            'review_id' => 1, 'rating' => 5, 'comment' => 'Great product!',
            'review_date' => '2023-01-01', 'user_id' => 1, 'user_name' => 'John Doe',
            'user_email' => 'john@example.com', 'user_type' => 'user',
            'user_profile_image' => 'uploads/profile_pictures/default_pf.jpg'
        ],
        // ... more
    ];
}

function getAllReviews() {
    return safeQuery(
        "SELECT r.review_id, r.rating, r.comment, r.review_date,
                u.user_id, u.user_name, u.user_email, u.user_type, u.user_profile_image
         FROM reviews r
         JOIN users u ON r.user_id = u.user_id
         ORDER BY r.review_date DESC"
    );
}

function getReviewsByUserId($user_id) {
    return safeQuery(
        "SELECT r.review_id, r.rating, r.comment, r.review_date,
                u.user_id, u.user_name, u.user_email, u.user_profile_image
         FROM reviews r
         JOIN users u ON r.user_id = u.user_id
         WHERE r.user_id = ?
         ORDER BY r.review_date DESC",
        [$user_id]
    );
}

// ────────────────────────────────────────────────
// ORDERS, DISCOUNTS, WISHLIST
// ────────────────────────────────────────────────

function getDiscount($discount_code) {
    return safeQuery(
        "SELECT * FROM discounts WHERE discount_code = ?",
        [$discount_code],
        'one'
    ) ?: [];
}

function getDiscounts() {
    return safeQuery("SELECT * FROM discounts");
}

function getOrderDetails($order_id) {
    return safeQuery(
        "SELECT o.*, oi.product_id, oi.product_name, oi.quantity, oi.price,
                u.user_name, u.user_email, d.discount_code, d.discount_percent
         FROM orders o
         JOIN order_items oi ON o.order_id = oi.order_id
         JOIN users u ON o.user_id = u.user_id
         LEFT JOIN discounts d ON o.discount_id = d.discount_id
         WHERE o.order_id = ?",
        [$order_id]
    );
}

function getOrders() {
    return safeQuery(
        "SELECT o.*, u.user_name, d.discount_code, d.discount_percent
         FROM orders o
         JOIN users u ON o.user_id = u.user_id
         LEFT JOIN discounts d ON o.discount_id = d.discount_id
         ORDER BY o.order_date DESC"
    );
}

function getOrderItems() {
    return safeQuery("SELECT * FROM order_items ORDER BY order_id DESC");
}

function getOrdersByUserId($user_id) {
    return safeQuery(
        "SELECT o.*, u.user_name, d.discount_code, d.discount_percent
         FROM orders o
         JOIN users u ON o.user_id = u.user_id
         LEFT JOIN discounts d ON o.discount_id = d.discount_id
         WHERE o.user_id = ?
         ORDER BY o.order_date DESC",
        [$user_id]
    );
}

function getOrderItemsByUserId($user_id) {
    return safeQuery(
        "SELECT oi.*
         FROM order_items oi
         JOIN orders o ON oi.order_id = o.order_id
         WHERE o.user_id = ?
         ORDER BY o.order_date DESC",
        [$user_id]
    );
}

function getWishList($user_id) {
    return safeQuery(
        "SELECT product_id FROM wishlist WHERE user_id = ?",
        [$user_id],
        'column'
    );
}

function getWishedProduct($user_id) {
    return safeQuery(
        "SELECT p.*
         FROM wishlist w
         JOIN products p ON w.product_id = p.product_id
         WHERE w.user_id = ?",
        [$user_id]
    );
}

// ────────────────────────────────────────────────
// OTHER
// ────────────────────────────────────────────────

function getMessages() {
    return safeQuery("SELECT * FROM messages");
}

function validatePassword($password) {
    return preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
}