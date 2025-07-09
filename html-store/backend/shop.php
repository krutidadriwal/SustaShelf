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
<html>
    <head>
        <title>SustaShopping Web Application</title>
        <link rel="stylesheet" 
                href="../style/shop.css">
    </head>
    <body>
        <header>
            <h1>Welcome <?php
            $user = $_SESSION["user"];
            echo $user["name"];
            ?> to SustaShopping Web Application</h1>
        </header>
        <nav>
            <ul>
                <li><a href="../index.html">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="logout.php">Logout</a></li>

            </ul>
        </nav>
        <main>
            <section>
                <h2>Products</h2>
                <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "shp");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch products
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                echo "<ul>";
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id    = $row["id"];
                        $name  = $row["product"];
                        $price = $row["price"];
                        $img   = $row["image"]; // path like 'images/bag.jpg'

                        echo "
                        <li>
                            <h3>$name</h3>
                            <img src='$img' alt='$name' width='150'>
                            <p><span>₹$price</span></p>
                            <form method='post' action='shop.php'>
                                <input type='hidden' name='product_id' value='$id'>
                                <label>Quantity:</label>
                                <input type='number' name='product_quantity' value='1' min='1' max='10'>
                                <button type='submit' name='add_to_cart'>Add to Cart</button>
                            </form>
                        </li>";
                    }
                } else {
                    echo "<li>No products found.</li>";
                }
                echo "</ul>";
                $conn->close();
                ?>

            </section>
        </main>
        <footer>
            <p>&LIC :: Shopping Web Application</p>
        </footer>
        <script src="shop.php"></script>
    </body>
</html>