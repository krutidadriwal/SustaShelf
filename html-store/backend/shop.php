<?php
session_start();

// Start the session
// Check if the add to cart button is clicked
if (isset($_POST["add_to_cart"])) {
  
    // Get the product ID from the form
    $product_id = $_POST["product_id"];
  
    // Get the product quantity from the form
    $product_quantity = $_POST["product_quantity"];

    // Initialize the cart session variable
    // if it does not exist
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
        header("location:cart.php");
    }

    // Add the product and quantity to the cart
    $_SESSION["cart"][$product_id] = $product_quantity;
    header("location:cart.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SustaShelf - Premium Shopping Experience</title>
        <link rel="stylesheet" href="../style/shop.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="header-container">
            <h1>Welcome <?php
            $user = $_SESSION["user"];
            echo $user["name"];
            ?> to SustaShelf</h1>
        </div>
        
        <div class="navigation-container">
            <ul>
                <li><a href="../index.html">🏠 Home</a></li>
                <li><a href="shop.php">🛍️ Shop</a></li>
                <li><a href="cart.php">🛒 Cart</a></li>
                <li><a href="admin.php">⚙️ Admin</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
        </div>
        
        <div class="main-container">
            <div class="products-section">
                <h2>Premium Products</h2>
                <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "shp");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch products
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<ul>";
                    while ($row = $result->fetch_assoc()) {
                        $id    = $row["id"];
                        $name  = $row["product"];
                        $price = $row["price"];
                        $img   = $row["image"];
                        $metal = isset($row["metal"]) ? $row["metal"] : "N/A";
                        $warranty = isset($row["warranty_years"]) ? $row["warranty_years"] : "1";

                        echo "
                        <li>
                            <div class='product-image'>
                                <img src='$img' alt='$name' loading='lazy'>
                            </div>
                            <div class='product-content'>
                                <h3>$name</h3>
                                <div class='price'>$price</div>
                                <div class='product-details'>
                                    <span class='metal-badge'>Metal: " . ucfirst($metal) . "</span>
                                    <span class='warranty-badge'>$warranty Year(s) Warranty</span>
                                </div>
                                <form method='post' action='shop.php' class='product-form'>
                                    <input type='hidden' name='product_id' value='$id'>
                                    <div class='quantity-container'>
                                        <label for='quantity_$id'>Quantity:</label>
                                        <input type='number' id='quantity_$id' name='product_quantity' value='1' min='1' max='10'>
                                    </div>
                                    <button type='submit' name='add_to_cart'>🛒 Add to Cart</button>
                                </form>
                            </div>
                        </li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<div class='empty-state'>
                            <h3>No Products Available</h3>
                            <p>Check back later for amazing sustainable products!</p>
                          </div>";
                }
                $conn->close();
                ?>
            </section>
        </main>
        
        <footer>
            <p>🌱 SustaShelf - Making Retail Sustainable | Premium Shopping Experience</p>
        </footer>
    </body>
</html>