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

    <section>
        <h1>Checkout</h1>
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
    </section>

    <footer>
        <p>&LIC :: Shopping Web Application</p>
    </footer>
</body>

</html>