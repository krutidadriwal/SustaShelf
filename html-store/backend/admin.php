<?php
session_start();

// Initialize variables
$filtered_data = [];
$metal_filter = '';
$show_results = false;

// Process POST request for metal filter
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["metal_name"])) {
    $metal_filter = trim($_POST["metal_name"]);
    
    if (!empty($metal_filter)) {
        // Database connection
        $host = "localhost";
        $dbname = "shp";
        $username_db = "root";
        $password_db = "";

        try {
            $db = new PDO(
                "mysql:host=$host;dbname=$dbname",
                $username_db,
                $password_db
            );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Query to get purchases with user and product info, filtered by metal and sorted by oldest sale_year
            $sql = "SELECT u.username, p.product, p.sale_year, p.metal 
                    FROM purchases pu
                    JOIN users u ON pu.username = u.username
                    JOIN products p ON pu.product_id = p.id
                    WHERE LOWER(p.metal) = LOWER(:metal)
                    ORDER BY p.sale_year ASC";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":metal", $metal_filter);
            $stmt->execute();
            
            $filtered_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $show_results = true;
            
        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    } else {
        $error_message = "Please enter a metal name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SustaShelf - Admin Panel</title>
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SustaShelf Admin Panel</h1>
            <p>Filter Products by Highest Selling Metal</p>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="form-container">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="metal_name">Enter Highest Selling Metal Name:</label>
                        <input type="text" 
                               id="metal_name" 
                               name="metal_name" 
                               value="<?php echo htmlspecialchars($metal_filter); ?>"
                               placeholder="e.g., aluminum, gold, silver, copper, steel"
                               required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="submit-btn">Filter Products</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Error Message -->
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Results Section -->
        <?php if ($show_results): ?>
            <div class="results-section">
                <div class="results-header">
                    <h2>Products Filtered by Metal</h2>
                    <div class="metal-info">Metal: <?php echo htmlspecialchars($metal_filter); ?></div>
                </div>

                <?php if (count($filtered_data) > 0): ?>
                    <div class="record-count">
                        Found <?php echo count($filtered_data); ?> record(s) - sorted by oldest sale year first
                    </div>
                    
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Product</th>
                                <th>Sale Year</th>
                                <th>Metal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filtered_data as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product']); ?></td>
                                    <td><?php echo htmlspecialchars($row['sale_year']); ?></td>
                                    <td style="text-transform: capitalize; font-weight: 600; color: #459d72;">
                                        <?php echo htmlspecialchars($row['metal']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <h3>No products found</h3>
                        <p>No purchased products found for the metal "<?php echo htmlspecialchars($metal_filter); ?>"</p>
                        <p>Try searching for: aluminum, gold, silver, copper, or steel</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Back Link -->
        <a href="../index.html" class="back-link">← Back to Home</a>
    </div>
</body>
</html>