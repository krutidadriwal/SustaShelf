<html>

<head>
    <style>
        body {
            background-color: #DEFBC2;
            font-family: Arial, sans-serif;
        }
        
        h1 {
            color: #342B2B;
            font-size: 2.5em;
            text-align: center;
            margin-top: 50px;
        }
        
        p {
            color: #342B2B;
            font-size: 1.2em;
            text-align: center;
            margin-top: 20px;
        }
        .continue-link {
            display: block;
            margin: 30px auto 0 auto;
            padding: 12px 32px;
            background-color: #7DC242;
            color: #fff;
            font-size: 1.1em;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(52,43,43,0.12);
            transition: background 0.2s;
            text-align: center;
            width: fit-content;
        }
        
    </style>
</head>


<?php
session_start();

// Basic checks
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    // If cart is empty, add faux product with product_id = 0
    $_SESSION['cart'] = [0 => 1];
}

// Get user info from session
$user = $_SESSION['user'];
$username = $user['username'];
$city = $_POST['city'];

// Connect to DB
$conn = new mysqli("localhost", "root", "", "shp");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Loop through each product in the cart
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Get product info
    $stmt = $conn->prepare("SELECT product, sale_year, warranty_years FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
    $insert = $conn->prepare("INSERT INTO purchases (username, product_id)
                              VALUES (?, ?)");
    $insert->bind_param("si", $username, $product_id);
    $insert->execute();
}
}

// Optional: Clear the cart
unset($_SESSION['cart']);

// Show confirmation
echo "<h1 class='thanks-message'>Thank you for your purchase, $username!</h1>";
echo "<a href='shop.php' class='continue-link'>Continue Shopping</a>";

?>
</html>