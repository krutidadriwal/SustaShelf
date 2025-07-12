<?php
session_start();
$conn = new mysqli("localhost", "root", "", "shp");

// Always use test8 as the user
$username = 'test8';

$items_to_sell = [];

$sql = "SELECT p.product, p.sale_year FROM purchases pu
        JOIN products p ON pu.product_id = p.id
        WHERE pu.username = 'test8' AND LOWER(p.metal) = 'cobalt'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $items_to_sell[] = $row;
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Checkout Page</title>
    <link rel="stylesheet" href="../style/checkout.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li>
                    <a href="shop.php">Home</a>
                </li>
                <li>
                    <a href="shop.php">Shop</a>
                </li>
                <li>
                    <a href="cart.php">Cart</a>
                </li>
                <li>
                    <a href=
"mailto:krutidadriwal1834@gamil.com">Contact</a>
                
                       </li>
            </ul>
        </nav>
    </header>

    <!-- Fixed Chatbot Button -->
    <button id="chatbot-btn">
        💬
    </button>
    
    <section>
        <h1>Checkout for a user</h1>
        <form action="checkout-save.php" method="post">
            <h2>Billing Information</h2>
            <label for="name">Name:</label>
            <input type="text" 
                   id="name"
                   name="name" required>

            <label for="email">Email:</label>
            <input type="email" 
                   id="email" 
                   name="email" required>

            <label for="address">Address:</label>
            <input type="text" 
                   id="address" 
                   name="address" required>

            <label for="city">City:</label>
            <input type="text" 
                   id="city" 
                   name="city" required>

            <label for="state">State:</label>
            <input type="text" 
                   id="state" 
                   name="state" required>

            <label for="zip">Zip Code:</label>
            <input type="text" 
                   id="zip"
                   name="zip" required>

            <input type="submit" 
                   value="Place Order">

        </form>

        <!-- Sell Option Section -->
        <?php if (!empty($items_to_sell)) : ?>
            <div class="sell-section" style="margin-top:2em;">
                <h2>Sell Your Purchased Items</h2>
                <?php foreach ($items_to_sell as $item): ?>
                    <div class="sell-item" style="margin-bottom:1em;">
                        <span>Do you want to sell <strong><?php echo htmlspecialchars($item['product']); ?></strong> purchased in <strong><?php echo htmlspecialchars($item['sale_year']); ?></strong>?</span>
                        <button onclick="alert('Your <?php echo htmlspecialchars($item['product']); ?> will be picked during delivery.')">Yes</button>
                        <button>No</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <footer>
        <p>&LIC :: Shopping Web Application</p>
    </footer>

    
</body>

</html>