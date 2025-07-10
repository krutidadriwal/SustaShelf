<!DOCTYPE html>
<html>

<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../style/cart.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1><?php 
            session_start();
            $user = $_SESSION['user'];
            echo $user['name']; 
        ?>'s Shopping Cart</h1>
    </header>

    <nav>
        <ul>
            <li>
                <a href="../index.html">Home</a>
            </li>
            <li>
                <a href="shop.php">Shop</a>
            </li>
            <li>
                <a href=
"mailto:krutidadriwal1834@gmail.com">Contact Us</a>
            </li>
            <li>
                <a href="cart.php">Cart</a>
            </li>
        </ul>
    </nav>

    <main>
        <section>
            <table>
                <tr>
                    <th>Product Name </th>
                    <th>Quantity </th>
                    <th>Price </th>
                    <th>Total </th>
                </tr>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "shp";

                // Create connection
                $conn = 
                    new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $total = 0;

                // Loop through items in cart and display in table
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    $sql = "SELECT * FROM products WHERE id = $product_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $product = $row['product'];
                        $price = $row['price'];
                        $warranty = $row['warranty_years'];
                        $item_total = $quantity * $price;
                        $total += $item_total;

                        echo "<tr>";
                        echo "<td>$product</td>";
                        echo "<td>$quantity</td>";
                        echo "<td>$price $</td>";
                        echo "<td>$item_total $</td>";
                        echo "</tr>";
                    }
                }
                // Display total
                echo "<tr>";
                echo "<td colspan='3'>Total:</td>";
                echo "<td>$total $</td>";
                echo "</tr>";
                ?>
            </table>
            <form action="checkout.php" method="post">
                <input type="submit" 
                       value="Checkout" 
                       class="button" />
            </form>
        </section>
    </main>

    <footer>
        <p>&LIC :: Shopping Web Application</p>
    </footer>
</body>

</html>