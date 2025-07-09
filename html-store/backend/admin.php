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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .form-section {
            padding: 40px;
            background: #f8f9fa;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1em;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .error-message {
            background: #ff6b6b;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 40px;
            text-align: center;
        }

        .results-section {
            padding: 40px;
        }

        .results-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .results-header h2 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .metal-info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 25px;
            display: inline-block;
            font-weight: 600;
            text-transform: capitalize;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .data-table th {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 20px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .data-table td {
            padding: 18px 20px;
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.3s ease;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .data-table tr:hover {
            background-color: #e3f2fd;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-data i {
            font-size: 4em;
            margin-bottom: 20px;
            color: #ddd;
        }

        .no-data h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .back-link {
            display: inline-block;
            margin: 20px 40px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
        }

        .record-count {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
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
                                    <td style="text-transform: capitalize; font-weight: 600; color: #667eea;">
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